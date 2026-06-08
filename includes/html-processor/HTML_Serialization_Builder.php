<?php

/**
 *
 * This file was presented in:
 * "HTML API Practicum: A Deep Dive"
 * WordCamp Europe 2026
 *
 * Presenter:
 * Dennis Snell
 * https://github.com/dmsnell
 */

/**
 * HTML_Serialization_Builder class.
 *
 * This class can be used to perform structural changes to an
 * HTML document while maintaining some but not all safety
 * protections. Namely, proper nesting structure of HTML is
 * maintained, but HTML updates could still leak out of the
 * containing parent node. For example, this allows inserting
 * an A element inside an open A element, which would close
 * the containing A element.
 *
 * Modifications may be requested for a document _once_ after
 * matching a token. Due to the way the modifications are
 * applied, it's not possible to set the inner HTML for a
 * node more than once, or append more than one HTML chunk.
 * 
 */
class HTML_Serialization_Builder extends WP_HTML_Processor {
	private $output;
	private $last_token = null;

	private static function is_virtual_token( WP_HTML_Processor $processor ): bool {
		static $is_virtual = null;

		if ( null === $is_virtual ) {
			$is_virtual = new ReflectionMethod( WP_HTML_Processor::class, 'is_virtual' );
		}

		return $is_virtual->invoke( $processor );
	}

	public function next_token(): bool {
		if ( isset( $this->last_token ) ) {
			$this->output    .= $this->last_token;
			$this->last_token = null;
		}

		$did_match = parent::next_token();
		$this->last_token = $this->serialize_token();

		return $did_match;
	}

	public function build() {
		if ( isset( $this->last_token ) ) {
			$this->output    .= $this->last_token;
			$this->last_token = null;
		}

		return $this->output;
	}

	public function append( $html ) {
		if ( ! isset( $this->last_token ) ) {
			return false;
		}

		$this->output    .= $this->last_token;
		$this->output    .= WP_HTML_Processor::normalize( $html );
		$this->last_token = null;
	}

	public function prepend( $html ) {
		if ( ! isset( $this->last_token ) ) {
			return false;
		}

		$this->output    .= WP_HTML_Processor::normalize( $html );
		$this->output    .= $this->last_token;
		$this->last_token = null;
	}

	public function set_inner_html( $html ) {
		if ( $this->is_tag_closer() || ! isset( $this->last_token ) ) {
			return false;
		}

		$prelude = implode( '', array_map( fn ( $name ) => "<{$name}>", $this->get_breadcrumbs() ) );
		$inner_p = WP_HTML_Processor::create_full_parser( "{$prelude}{$html}" );
		$inner_html = '';
		while ( $inner_p->next_token() && $inner_p->get_current_depth() < count( $this->get_breadcrumbs() ) ) {
			// Skip past the prelude
			continue;
		}
		while ( $inner_p->next_token() && $inner_p->get_current_depth() >= count( $this->get_breadcrumbs()) ) {
			$inner_html .= $inner_p->serialize_token();
		}
		if ( $inner_p->is_tag_closer() ) {
			$inner_p->next_token();
		}
		$has_more_tokens = ! self::is_virtual_token( $inner_p ) && $inner_p->next_token();

		$this->output    .= $this->last_token;
		$this->output    .= $has_more_tokens ? '' : $inner_html;
		$this->last_token = null;

		$depth = $this->get_current_depth();
		while ( $this->get_current_depth() >= $depth && parent::next_token() ) {
			$this->last_token = null;
			if ( $has_more_tokens ) {
				$this->output .= $this->serialize_token();
			}
			continue;
		}

		$this->output    .= $this->serialize_token();
		$this->last_token = null;
		return true;
	}

	public function set_outer_html( $html ) {
		if ( $this->is_tag_closer() || ! isset( $this->last_token ) ) {
			return false;
		}

		$selected_depth = $this->get_current_depth();
		$prelude = implode( '', array_map( fn ( $name ) => "<{$name}>", array_slice( $this->get_breadcrumbs(), 0, -1 ) ) );
		$inner_p = WP_HTML_Processor::create_full_parser( "{$prelude}{$html}" );
		$inner_html = '';
		while ( $inner_p->next_token() && $inner_p->get_current_depth() < count( $this->get_breadcrumbs() ) - 1 ) {
			// Skip past the prelude
			continue;
		}
		while ( $inner_p->next_token() && $inner_p->get_current_depth() >= count( $this->get_breadcrumbs() ) - 1 ) {
			$inner_html .= $inner_p->serialize_token();
		}
		if ( $inner_p->is_tag_closer() ) {
			$inner_p->next_token();
		}
		$has_more_tokens = ! self::is_virtual_token( $inner_p ) && $inner_p->next_token();

		if ( $has_more_tokens ) {
			$this->output .= $this->last_token;
		} else {
			$this->output .= $inner_html;
		}
		$this->last_token = null;

		if ( $this->expects_closer() ) {
			while ( parent::next_token() ) {
				$this->last_token = null;
				if ( $has_more_tokens ) {
					$this->output .= $this->serialize_token();
				}

				if ( $this->get_current_depth() < $selected_depth ) {
					break;
				}
			}
		}

		return true;
	}

	public function wrap( $wrapping_tag ) {
		if ( $this->is_tag_closer() || ! isset( $this->last_token ) ) {
			return false;
		}

		$wrapper = WP_HTML_Processor::create_fragment( $wrapping_tag );
		if (
			false === $wrapper->next_token() ||
			'#tag' !== $wrapper->get_token_type() ||
			! $wrapper->expects_closer()
		) {
			return false;
		}

		$this->output    .= $wrapper->serialize_token();
		$this->output    .= $this->serialize_token();
		$this->last_token = null;

		if ( $this->expects_closer() ) {
			$depth = $this->get_current_depth();
			while ( parent::next_token() ) {
				$this->output    .= $this->serialize_token();
				$this->last_token = null;

				if ( $this->get_current_depth() < $depth ) {
					break;
				}
			}
		}

		$this->output    .= '</' . strtolower( $wrapper->get_tag() ) . '>';
		$this->last_token = null;
		return true;
	}

	/**
	 * @return bool
	 */
	public function unwrap() {
		if ( $this->is_tag_closer() || ! isset( $this->last_token ) ) {
			return false;
		}

		$this->last_token = null;
		$depth            = $this->get_current_depth();
		while ( parent::next_token() ) {
			if ( $this->get_current_depth() < $depth ) {
				break;
			}

			$this->output    .= $this->serialize_token();
			$this->last_token = null;
		}

		$this->last_token = null;
		return true;
	}
}

function go( $html ) {
	$processor = HTML_Serialization_Builder::create_fragment( $html );

	while ( $processor->next_token() ) {
		if ( 'HEAD' === $processor->get_tag() && $processor->is_tag_closer() ) {
			$processor->prepend( '<script></script>' );
		}

		if ( 'H1' === $processor->get_tag() ) {
			$processor->set_inner_html( 'You can’t always win.' );
		}

		if ( 'P' === $processor->get_tag() ) {
			$processor->set_outer_html( '<span>You can&apos;t<em> </script><!--' );
		}

		if ( 'SPAN' === $processor->get_tag() ) {
			$processor->set_outer_html( "<data id=inner><span>You can't<em> </data></script><!--" );
		}

		if (
			'#text' === $processor->get_token_type() &&
			str_contains( $processor->get_modifiable_text(), 'wrap' )
		) {
			$processor->wrap( '<span class="wrapped-text">' );
		}

		if ( 'OUTPUT' === $processor->get_tag() ) {
			$processor->unwrap();
		}


	}

	return $processor->build();
}

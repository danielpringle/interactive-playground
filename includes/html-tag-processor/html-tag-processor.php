<?php
/**
 * WP_HTML_Tag_Processor demo shortcode utilities.
 *
 * This file focuses on safe, attribute-level HTML transformations using
 * WP_HTML_Tag_Processor.
 *
 * Modes:
 * - default: Adds a demo class to paragraph tags.
 * - classes: Adds utility classes to headings and paragraphs.
 * - a11y: Adds missing accessibility attributes (alt, aria-label).
 * - performance: Adds image loading/decoding and external link safety attrs.
 * - sanitize: Removes inline style attributes from selected tags.
 *
 * Examples:
 * 1. [html_tag_processor_demo mode="classes"]<div><h2>Title</h2><p>Text</p></div>[/html_tag_processor_demo]
 * 2. [html_tag_processor_demo mode="a11y"]<div><img src="/wp-content/plugins/10-interactive-playground/assets/WordPress-logotype-simplified.png"><button>Buy</button></div>[/html_tag_processor_demo]
 * 3. [html_tag_processor_demo mode="performance"]<div><img src="/wp-content/plugins/10-interactive-playground/assets/WordPress-logotype-simplified.png"><a href="https://danielpringle.co.uk">Link</a></div>[/html_tag_processor_demo]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wp_iap_process_tag_processor_mode' ) ) {
	/**
	 * Process HTML using a selected WP_HTML_Tag_Processor mode.
	 *
	 * @param string $html Raw HTML input.
	 * @param string $mode Processing mode.
	 * @return string
	 */
	function wp_iap_process_tag_processor_mode( string $html, string $mode ): string {
		$mode      = strtolower( trim( $mode ) );
		$processor = new \WP_HTML_Tag_Processor( $html );

		while ( $processor->next_tag() ) {
			$tag = $processor->get_tag();

			switch ( $mode ) {
				case 'classes':
					if ( 'P' === $tag ) {
						$processor->add_class( 'demo-paragraph' );
					}
					if ( 'H2' === $tag || 'H3' === $tag ) {
						$processor->add_class( 'demo-heading' );
					}
					break;

				case 'a11y':
					if ( 'IMG' === $tag && ! $processor->get_attribute( 'alt' ) ) {
						$processor->set_attribute( 'alt', 'Demo image' );
					}
					if ( 'BUTTON' === $tag && ! $processor->get_attribute( 'aria-label' ) ) {
						$processor->set_attribute( 'aria-label', 'Action button' );
					}
					if ( 'A' === $tag && ! $processor->get_attribute( 'aria-label' ) ) {
						$processor->set_attribute( 'aria-label', 'Link' );
					}
					break;

				case 'performance':
					if ( 'IMG' === $tag ) {
						if ( ! $processor->get_attribute( 'loading' ) ) {
							$processor->set_attribute( 'loading', 'lazy' );
						}
						if ( ! $processor->get_attribute( 'decoding' ) ) {
							$processor->set_attribute( 'decoding', 'async' );
						}
					}
					if ( 'A' === $tag ) {
						$href = $processor->get_attribute( 'href' );
						if ( is_string( $href ) && str_starts_with( $href, 'http' ) ) {
							$processor->set_attribute( 'rel', 'noopener noreferrer' );
							$processor->set_attribute( 'target', '_blank' );
						}
					}
					break;

				case 'sanitize':
					if ( in_array( $tag, array( 'P', 'DIV', 'SPAN' ), true ) ) {
						$processor->remove_attribute( 'style' );
					}
					break;

				default:
					if ( 'P' === $tag ) {
						$processor->add_class( 'demo-default' );
					}
					break;
			}
		}

		return $processor->get_updated_html();
	}
}

if ( ! function_exists( 'wp_iap_render_tag_processor_shortcode' ) ) {
	/**
	 * Render the tag processor demo shortcode.
	 *
	 * @param array       $atts Shortcode attributes.
	 * @param string|null $content Enclosed shortcode content.
	 * @return string
	 */
	function wp_iap_render_tag_processor_shortcode( array $atts, ?string $content = null ): string {
		$atts = shortcode_atts(
			array(
				'mode' => 'default',
			),
			$atts,
			'html_tag_processor_demo'
		);

		$default_html = '<div><h2>Section</h2><p>Body text</p><img src="/wp-content/plugins/10-interactive-playground/assets/WordPress-logotype-simplified.png"><a href="https://example.com">External</a><button>Buy</button></div>';

		$input = ( is_string( $content ) && '' !== trim( $content ) )
			? do_shortcode( shortcode_unautop( $content ) )
			: $default_html;

		$output = wp_iap_process_tag_processor_mode( $input, (string) $atts['mode'] );

		$html  = '<section class="html-tag-processor-demo">';
		$html .= '<h2>WP_HTML_Tag_Processor Demo (' . esc_html( (string) $atts['mode'] ) . ')</h2>';
		$html .= '<p>Modes: default, classes, a11y, performance, sanitize</p>';
		$html .= '<h3>Input</h3><pre><code>' . esc_html( $input ) . '</code></pre>';
		$html .= '<h3>Output</h3><pre><code>' . esc_html( $output ) . '</code></pre>';
		$html .= '<h3>Rendered Output</h3><div class="demo-render">' . wp_kses_post( $output ) . '</div>';
		$html .= '</section>';

		return $html;
	}
}

add_shortcode( 'html_tag_processor_demo', 'wp_iap_render_tag_processor_shortcode' );

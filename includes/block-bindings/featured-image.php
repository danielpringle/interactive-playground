<?php
/**
 * Block Binding Source: Featured Image
 *
 * Registers a block binding source for the post's featured image (URL and alt text).
 *
 * @package DPUK_Block_Bindings
 */

namespace InteractivePlayground\Block_Bindings;

if ( ! function_exists( __NAMESPACE__ . '\\register_featured_image_binding' ) ) {
    /**
     * Register the featured image block binding source.
     */
    function register_featured_image_binding() {
        register_block_bindings_source( 'fliteragency/post-featured-image', array(
            'label'              => esc_html__( 'Featured Image', 'dpuk-block-bindings' ),
            'get_value_callback' => __NAMESPACE__ . '\featured_image_binding_callback',
        ) );
    }
    add_action( 'init', __NAMESPACE__ . '\register_featured_image_binding' );
}

if ( ! function_exists( __NAMESPACE__ . '\featured_image_binding_callback' ) ) {
    /**
     * Callback for the featured image binding source.
     *
     * @param array $args Arguments for the binding (expects 'key' => 'url' or 'alt').
     * @return string|null
     */
    function featured_image_binding_callback( $args ) {
        if ( ! isset( $args['key'] ) ) {
            return null;
        }
        if ( ! has_post_thumbnail() ) {
            return null;
        }
        $id  = get_post_thumbnail_id();
        $url = get_the_post_thumbnail_url();
        $alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
        switch ( $args['key'] ) {
            case 'url':
                return esc_url( $url );
            case 'alt':
                return esc_attr( $alt );
            default:
                return null;
        }
    }
}

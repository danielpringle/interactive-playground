<?php
/**
 * Block Binding Source: Copyright
 *
 * Registers a block binding source for displaying copyright information.
 *
 * @package DPUK_Block_Bindings
 */

namespace InteractivePlayground\Block_Bindings;

if ( ! function_exists( __NAMESPACE__ . '\register_copyright_binding' ) ) {
    /**
     * Register the copyright block binding source.
     */
    function register_copyright_binding() {
        register_block_bindings_source( 'nachalo-scaffold/display-copyright', array(
            'label'              => esc_html__( 'Copyright', 'dpuk-block-bindings' ),
            'get_value_callback' => __NAMESPACE__ . '\copyright_binding_callback',
        ) );
    }
    add_action( 'init', __NAMESPACE__ . '\register_copyright_binding' );
}

if ( ! function_exists( __NAMESPACE__ . '\copyright_binding_callback' ) ) {
    /**
     * Callback for the copyright binding source.
     *
     * @param array $args Arguments for the binding (expects 'key').
     * @return string|null
     */
    function copyright_binding_callback( $args ) {
        if ( ! isset( $args['key'] ) ) {
            return null;
        }
        return '© ' . gmdate( 'Y' ) . ' All rights reserved.';
    }
}
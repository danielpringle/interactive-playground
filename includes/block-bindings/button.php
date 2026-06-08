<?php
/**
 * Company Email Block Binding
 *
 * Registers a custom block binding source for the company email address, allowing blocks (such as Button) to bind their URL attribute to the company email (as a mailto: link) stored in options via ACF.
 *
 * @package DPUK\InteractivePlayground
 */

namespace DPUK\InteractivePlayground\Block_Bindings;

if ( ! function_exists( __NAMESPACE__ . '\register_company_email_binding' ) ) {
    /**
     * Register the company email block binding source.
     *
     * This makes the "Company Email Address" binding available for block attributes (e.g., Button URL),
     * returning a mailto: link using the company email stored in options.
     *
     * @return void
     */
    function register_company_email_binding() {
        register_block_bindings_source( 'dpuk/company-email-binding', array(
            'label'              => esc_html__( 'Company Email Address', 'interactive-playground' ),
            'get_value_callback' => __NAMESPACE__ . '\company_email_callback',
        ) );
    }
    add_action( 'init', __NAMESPACE__ . '\register_company_email_binding' );
}

if ( ! function_exists( __NAMESPACE__ . '\company_email_callback' ) ) {
    /**
     * Callback for the company email binding source.
     *
     * Returns a mailto: link using the company email address stored in options (set via ACF).
     *
     * @param array $args Arguments for the binding (not used).
     * @return string|null The mailto: link for the company email, or null if not set.
     */
    function company_email_callback( $args ) {
        $email = get_option( 'options_dpuk_acf_company_email' );
        return $email ? 'mailto:' . sanitize_email($email) : null;
    }
}
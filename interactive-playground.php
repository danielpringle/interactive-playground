<?php
/**
 * WP Interactivity API Playground
 *
 * @package           DPUK\InteractivePlayground
 * @author            Daniel Pringle
 *
 * @wordpress-plugin
 * Plugin Name:       Interactive Playground
 * Plugin URI:        https://danielpringle.co.uk
 * Description:       Experimental WordPress frontend architecture lab for exploring the Interactivity API, shared state systems, and modern block interaction patterns.
 * Version:           0.0.1
 * Requires at least: 6.9
 * Requires PHP:      8.2
 * Author:            Daniel Pringle
 * Author URI:        https://danielpringle.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       interactive-playground
 */

namespace DPUK\InteractivePlayground;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_IAP_VERSION', '0.0.1' );
define( 'WP_IAP_DIR', __DIR__ );
define( 'WP_IAP_BLOCKS', WP_IAP_DIR . '/build/blocks/' );
define( 'WP_IAP_MANIFEST_FILE', __DIR__ . '/build/blocks-manifest.php' );

// Load plugin JSON
add_filter('acf/settings/load_json', function ($paths) {

    $paths[] = plugin_dir_path(__FILE__) . 'acf-json';

    return $paths;

});

// Save this specific group to plugin
add_filter('acf/settings/save_json/key=group_6900b25ca1973', function ($path) {

    return plugin_dir_path(__FILE__) . 'acf-json';

});


add_action('acf/init', function() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( array(
            'page_title'    => 'Interactive Playground Settings',
            'menu_title'    => 'Playground Settings',
            'menu_slug'     => 'interactive-playground-settings',
            'capability'    => 'manage_options',
            'redirect'      => false,
        ) );
    }
});

/**
 * Loads global helper functions for the plugin.
 *
 * This file contains procedural functions used throughout the plugin that are not part of the main class.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/html-processor/html-processor.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/html-tag-processor/html-tag-processor.php';

/**
 * Autoload all block binding sources from inc/block-bindings/
 *
 * This keeps block binding registrations modular and organized.
 */
$block_bindings_dir = plugin_dir_path( __FILE__ ) . 'includes/block-bindings/';
if ( is_dir( $block_bindings_dir ) ) {
	foreach ( glob( $block_bindings_dir . '*.php' ) as $file ) {
		require_once $file;
	}
}


/**
 * The core plugin class that is used to define the hooks that initialise the various components.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin.php';

/**
 * Returns the main instance of Filter BlockLibrary.
 *
 * @since  1.0.0
 * @return \DPUK\BlockKit\Plugin
 */
function wp_iap() {
	return \DPUK\InteractivePlayground\Plugin::instance();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
wp_iap()->setup();
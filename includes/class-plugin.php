<?php
/**
 * Plugin class.
 *
 * @package DPUK\InteractivePlayground
 */

namespace DPUK\InteractivePlayground;

/**
 * The core plugin class, responsible for loading all dependencies, defining
 * the plugin version, and registering the hooks that define the plugin.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    DPUK\InteractivePlayground
 */
final class Plugin {

	/**
	 * Holds the singleton instance of this class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin
	 */
	protected static $instance = false;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Setup flag to ensure only run once.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      boolean
	 */
	protected $is_setup = false;

	/**
	 * Define the core functionality of the platform as initialized by hooks.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		if ( defined( 'WP_IAP_VERSION' ) ) {
			$this->version = WP_IAP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-block-kit';
	}

	/**
	 * Return the Block Library instance.
	 *
	 * @since    1.0.0
	 * @return   Plugin
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new Plugin();
		}

		return self::$instance;
	}

	/**
	 * Setup the initial hooks to fully load the plugin.
	 *
	 * @since 1.0.0
	 */
	public function setup() {

		// Don't double setup
		if ( $this->is_setup ) {
			return;
		}
		$this->is_setup = true;

		add_action( 'init', array( $this, 'register_blocks' ) );
		add_filter( 'block_categories_all', array( $this, 'register_block_categories' ), 20, 2 );
	}

	/**
	 * Automatically registers all blocks that are located within the build directory
	 *
	 * @since 1.0.0
	 */
	public function register_blocks() {

		wp_register_block_types_from_metadata_collection(
			WP_IAP_BLOCKS,
			WP_IAP_MANIFEST_FILE
		);
	}


	/**
	 * Registers a custom block category for this plugin's blocks.
	 *
	 * Adds a "Theme Tools" category to the block inserter, making it easier to find plugin blocks.
	 *
	 * @param array                   $block_categories Existing block categories.
	 * @param WP_Block_Editor_Context $editor_context Editor context (unused).
	 * @return array Modified block categories with the custom category prepended.
	 */
	public function register_block_categories( $block_categories, $editor_context ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
			// Check if a category with slug 'dpuk' exists
			foreach ( $block_categories as $category ) {
				if ( isset( $category['slug'] ) && 'dpuk' === $category['slug'] ) {
					// 'dpuk' category exists, return as is
					return $block_categories;
				}
			}

			// If not found, add custom category
			$block_categories[] = array(
				'slug'  => 'dpuk',
				'title' => __( 'DPUK', 'interactive-playground' ),
				'icon'  => 'layout',
			);

			return $block_categories;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

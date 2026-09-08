/**
 * Custom Webpack configuration for DPUK Theme Tools plugin.
 *
 * This config extends the default @wordpress/scripts webpack config, which is optimized for block development.
 *
 * Key points:
 * - Retains all default block build behavior from @wordpress/scripts.
 * - Adds a custom entry for main theme JS (src/js/main.js) to bundle additional frontend scripts.
 * - (Optional) You can add more entries, e.g., for admin/editor overrides, by uncommenting or adding lines.
 * - The output config is extended to always clean the output directory before each build.
 *
 * Usage:
 * - All block assets in src/blocks/* are handled automatically by the default config.
 * - Any additional JS/CSS you want to bundle (not tied to a block) should be added as a new entry here.
 *
 * Notes:
 * - Do not override entry unless you need to add non-block assets.
 * - If you only build blocks, you can use the default config with no changes.
 * - If you add new entries, ensure the source files exist, or webpack will error.
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');

module.exports = {
  // Spread all default config options for block development
  ...defaultConfig,

  // Extend the entry points: keep all block entries, add custom ones
  entry: {
    // Default block entries (returns an object, not a function)
    ...defaultConfig.entry(), 

    // Global frontend JavaScript for plugin-level behaviour.
    main: resolve(process.cwd(), 'src/js/main.js'),

    // Global frontend stylesheet compiled to build/style.css.
    style: resolve(process.cwd(), 'src/sass/style.scss'),

    // Shared block editor stylesheet for reusable editor UI/components.
    editor: resolve(process.cwd(), 'src/sass/editor/index.scss'),

    // Block editor script for registering core block overrides, styles, and variations.
    'block-overrides': resolve(process.cwd(), 'src/js/block-overrides/block-overrides.js'),

    	// React UI for the Activity CPT editor.
    'settings': resolve(
      process.cwd(),
      'src/js/settings/settings.js'
    ),
  },

  // Extend output config: clean output dir before build
  output: {
    ...defaultConfig.output,
    clean: true, // Clean the output directory before emit
  },
};
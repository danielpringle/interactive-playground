

import { registerBlockVariation } from "@wordpress/blocks";
import { __ } from '@wordpress/i18n';

/**
 * Block variation: Copyright
 *
 * Registers a `core/paragraph` variation that binds the paragraph content to
 * a dynamic data source which outputs the Copyright. The binding uses the
 * custom source `nachalo-scaffold/display-copyright` with `args.key = 'year'`.
 *
 * Attributes
 * - className: Adds `copyright` class to the block for targeting/styling.
 * - metadata.name: Human-readable label shown in the UI.
 * - metadata.bindings.content: Binds paragraph content to the Copyright.
 *
 * Visibility
 * - isActive: Active when `metadata.bindings.content.args.key` is set.
 * - scope: Available in inserter and as a transform target.
 */
registerBlockVariation("core/paragraph", {
    name: "dpuk-binding-copyright",
    title: "Copyright Binding",
    description: __( 'Display copyright data', 'nachalo-scaffold' ),
    icon: "info",
    attributes: {
        className: "copyright",
        metadata: {
            name: "Copyright Binding",
            bindings: {
                content: { 
                    source: 'nachalo-scaffold/display-copyright',
                    args: {
                        key: 'year',
                    },
                },
            },
        },
    },
    isActive: [ 'metadata.bindings.content.args.key' ],
    scope: [ 'inserter', 'transform' ],
});

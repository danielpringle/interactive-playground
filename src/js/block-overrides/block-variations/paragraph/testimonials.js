import { registerBlockVariation } from "@wordpress/blocks";
import { __ } from '@wordpress/i18n';

const testimonialVariations = [
    {
        name: "testimonial-name",
        title: "Testimonial Name",
        description: __("Displays the testimonial's name (ACF)", "nachalo-scaffold"),
        icon: "id",
        attributes: {
            className: "testimonial-name",
            metadata: {
                name: "Testimonial Name",
                bindings: {
                    content: {
                        source: "acf/field",
                        args: { key: "name" }
                    }
                }
            }
        },
        isActive: [ 'metadata.bindings.content.args.key' ],
        scope: [ 'inserter', 'transform' ],
    },
    {
    name: "testimonial-date",
    title: "Testimonial Date",
    description: __("Displays the testimonial's date (ACF)", "nachalo-scaffold"),
    icon: "id",
    attributes: {
        className: "testimonial-date",
        metadata: {
            name: "Testimonial Date",
            bindings: {
                content: {
                    source: "acf/field",
                    args: { key: "date" }
                }
            }
        }
    },
    isActive: [ 'metadata.bindings.content.args.key' ],
    scope: [ 'inserter', 'transform' ],
    },
    // Add more variations here...
];

testimonialVariations.forEach(variation => {
    registerBlockVariation("core/paragraph", variation);
});
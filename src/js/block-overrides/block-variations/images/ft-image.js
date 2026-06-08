import { registerBlockVariation } from "@wordpress/blocks";
import { __ } from '@wordpress/i18n';

/**
 * Block Variation: ACF Image Bind
 *
 * Registers a variation for core/image that displays an image from a specific ACF custom field (dpuk_acf_block_bindings_image).
 * The image URL is dynamically set from the value of this field for the current post, allowing editors to display any image
 * stored in that custom field.
 */
registerBlockVariation("core/image", {
    name: "acf-binding-image",
    title: "ACF Image Bind",
    description: __( 'A wide image', 'textdomain' ),
    icon: "book-alt",
    className: "dpuk-acf-block-bindings-img",
    attributes: {
        width: "150px",
        height: "150px",
        scale: "cover",
        style: {
            border: {
                radius: "0"
            }
        },
        metadata: {
            name: "ACF IMG",
            bindings: {
                url: { 
                    source: "acf/field", 
                    args: { 
                        key: "dpuk_acf_block_bindings_image" 
                    } 
                },
            },
        },
    },
    isActive: [ 'attributes.metadata.bindings.url.args.key' ],
});


/**
 * Block Variation: Display Post Featured Image
 *
 * Registers a variation for core/image that displays the post's featured image using a custom block binding source.
 * The image URL and alt text are dynamically set from the post's featured image and its alt meta, making this block
 * always reflect the current post's featured image.
 */
wp.domReady(() => {
    wp.blocks.registerBlockVariation("core/image", {
      name: "display-post-featured-image",
      title: "Display Post Featured Image",
      description: 'Displays the featured image in the image block',
      icon: "format-image",
      attributes: {
        className: "is-display-featured-image",
        metadata: {
            bindings: {
                url: {
                    source: 'fliteragency/post-featured-image',
                    args: {
                        key: 'url',
                    },
                },
                 alt: {
                    source: 'fliteragency/post-featured-image',
                    args: {
                        key: 'alt',
                    },
                },
            },
        },
      },
      isActive: [ 'attributes.metadata.bindings.url.args.key' ],
   }) 
});
<?php

$the_content = $content ?? '';
$padding_block_start = $attributes['paddingBlockStart'] ?? '';
$padding_block_end   = $attributes['paddingBlockEnd'] ?? '';
// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'hero-pulse-block',
        'data-hero-pulse' => '',
	)
);

$hero_styles = array();

if ( $padding_block_start ) {
    $hero_styles[] = 'padding-block-start:' . $padding_block_start;
}

if ( $padding_block_end ) {
    $hero_styles[] = 'padding-block-end:' . $padding_block_end;
}

$hero_style_attribute = $hero_styles ? implode( ';', $hero_styles ) . ';' : '';

?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
    <section class="hero" style="<?php echo esc_attr( $hero_style_attribute ); ?>">
        <canvas></canvas>
        <div class="content">
            <?php echo wp_kses_post( $the_content ); ?>
        </div>
    </section>
</div>
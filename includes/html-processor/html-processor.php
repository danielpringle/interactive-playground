<?php

/**
 * HTML Serialization Builder for experimental HTML API transformations.
 *
 * Extends WP_HTML_Processor with helper methods to perform structural
 * transformations (append, prepend, wrap, unwrap, set inner/outer HTML)
 * while traversing tokens and rebuilding output.
 *
 * Note:
 * - This is an experimental utility for testing and demos.
 * - It preserves parser-driven structure, but does not guarantee full
 *   safety against all malformed or adversarial HTML mutations.
 *
 * Attribution:
 * Techniques from the "HTML API Practicum:
 * A Deep Dive" session at WordCamp Europe 2026.
 * https://github.com/dmsnell
 *
 * @see https://europe.wordcamp.org/2026/session/html-api-practicum-a-deep-dive/
 */

require_once __DIR__ . '/HTML_Serialization_Builder.php';

add_shortcode( 'html_api_demo', function () {
    // Test 1: Default demo with H1
    $input = '<div><h1>Section</h1><p>Body</p></div>';

    // Test 2: SPAN tag (triggers set_outer_html)
    // $input = '<div><span>This is a span</span></div>';

    // Test 3: Text containing "wrap" (triggers wrap)
    // $input = '<div><p>This text should wrap nicely</p></div>';

    // Test 4: OUTPUT tag (triggers unwrap)
    // $input = '<div><output>Remove me</output></div>';

    // Test 5: Multiple H1 tags
    // $input = '<div><h1>First</h1><h1>Second</h1></div>';

    // Test 6: Nested SPAN tags
    // $input = '<div><span>Outer <span>Inner</span></span></div>';

    // Test 7: Multiple transformations (wrap + span)
    // $input = '<div><p>This will wrap and has a <span>span inside</span></p></div>';

    // Test 8: Complex structure
    // $input = '<head><body><h1>Title</h1><output><p>wrap text here</p></output><span>content</span></body></head>';

    // Test 9: Malformed HTML (testing parser robustness)
    // $input = '<div><h1>Section</h3><p>Body</p></div>';

    // Test 10: Multiple text nodes with wrap keyword
    // $input = '<div><p>wrap one</p><p>wrap two</p></div>';

    // Active default
    // $input = '<div><h1>Section</h1><p>Body</p></div>';

    // Test 11: P tag (triggers set_outer_html on P)
    // $input = '<div><p>Paragraph content</p></div>';

    // Test 12: HEAD tag (triggers prepend)
    // $input = '<head></head><body><p>Content</p></body>';

    $output = go( $input );

    $html  = '<section class="html-api-demo">';
    $html .= '<h2>HTML API Demo</h2>';
    $html .= '<h3>Input</h3><pre><code>' . esc_html( $input ) . '</code></pre>';
    $html .= '<h3>Output</h3><pre><code>' . esc_html( $output ) . '</code></pre>';
    $html .= '<h3>Rendered Output</h3><div class="demo-render">' . $output . '</div>';
    $html .= '</section>';

    return $html;
} );
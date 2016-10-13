<?php

/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package human
 */
get_header ();
$template = 'Single Post';
$post_type = ucwords ( str_replace ( '_', ' ', get_post_type () ) );

//code_tester();


if ( ! empty ( do_shortcode ( '[human_template name="Single ' . $post_type . '"]' ) ) ) {
            $template = 'Single ' . $post_type;
}
if ( get_post_meta ( get_the_ID (), 'human_template' ) && ! empty ( get_post_meta ( get_the_ID (), 'human_template' )[ 0 ] ) ) {
            $template = get_post_meta ( get_the_ID (), 'human_template' )[ 0 ];
}

//print_r ( $template . '<hr>' );
$content = human_template ( array (
            "name" => $template ) );



echo $content;
get_footer ();
?>



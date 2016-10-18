<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package human
 */
get_header ();

$template = 'Page';


if ( get_post_meta ( get_the_ID (), 'human_template' ) && ! empty ( get_post_meta ( get_the_ID (), 'human_template' )[ 0 ] ) ) {
            $template = get_post_meta ( get_the_ID (), 'human_template' )[ 0 ];
}
?>



<?php echo do_shortcode ( '[human_template name="' . $template . '"]' ); ?>


<?php get_footer (); ?>
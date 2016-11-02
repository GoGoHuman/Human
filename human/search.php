<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package human
 */
get_header ();

$template = 'Search';
if ( get_post_meta ( get_the_ID (), 'human_template' ) && ! empty ( get_post_meta ( get_the_ID (), 'human_template' )[ 0 ] ) ) {
            $template = get_post_meta ( get_the_ID (), 'human_template' )[ 0 ];
}
?>
<hr><hr>
<?php echo do_shortcode ( '[human_template name="' . $template . '"]' ) ?>
<?php get_footer (); ?>


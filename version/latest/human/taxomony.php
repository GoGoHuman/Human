<?php

/**
 * The template for displaying taxomony pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package human
 */
get_header ();

$template = 'Archive';
if ( is_category () ) {
            $cat = get_query_var ( 'cat' );
            $yourcat = get_category ( $cat );
            // $template = $yourcat->slug;
}
if ( get_post_meta ( get_the_ID (), 'human_template' ) && ! empty ( get_post_meta ( get_the_ID (), 'human_template' )[ 0 ] ) ) {
            $template = get_post_meta ( get_the_ID (), 'human_template' )[ 0 ];
}
?>



<?php echo do_shortcode ( '[human_template name="' . $template . '"]' ) ?>
<?php get_footer (); ?>
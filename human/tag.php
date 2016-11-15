<?php

/**
 * The template for displaying tag pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package human
 */
get_header ();

$template = 'Tag';
if ( is_tag () ) {
            $tag = ' ' . ucwords ( get_query_var ( 'tag' ) );
}
$post_type = ucwords ( get_post_type () );
if ( ! empty ( do_shortcode ( '[human_template name="Tag' . $tag . '"]' ) ) ) {
            $template = 'Tag' . $tag;
}
?>



<?php echo do_shortcode ( '[human_template name="' . $template . '"]' ) ?>
<?php get_footer (); ?>
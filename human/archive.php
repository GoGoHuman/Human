<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package human
 */
get_header ();

$template = 'Archive';
$post_type = ucwords ( get_post_type () );
if (! empty ( do_shortcode ( '[human_template name="Archive ' . $post_type . '"]' ) )) {
	$template = 'Archive ' . $post_type;
}
?>



<?php echo do_shortcode('[human_template name="'.$template.'"]')?>
<?php get_footer(); ?>

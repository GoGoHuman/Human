<?php
/**
 * The template for Human Templates.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package human Templates
 */
?>
get_header(); ?>

		<?php echo do_shortcode('[human_template name="Archive"]');?>

		

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php

/**
 * The template for Human Templates.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package human Templates
 */
$pageid = get_the_ID();
$page_title = get_the_title($pageid);

get_header();

//print_r($template . '<hr>');
?>

<?php echo do_shortcode('[human_template name="' . $page_title . '"]') ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

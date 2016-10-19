<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package human
 */

get_header();

$template = '404';

if(get_post_meta(get_the_ID(),'human_template') && !empty(get_post_meta(get_the_ID(),'human_template'))){
    $template = get_post_meta(get_the_ID(),'human_template');
}

?>



<?php echo do_shortcode('[human_template name="'.$template.'"]') ?>
<?php get_footer(); ?>

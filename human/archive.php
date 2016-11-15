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



$term = get_queried_object ();
//print_r ( $term->label );
$post_type = $term->label;

if ( ! empty ( $post_type ) ) {
            if ( ! empty ( do_shortcode ( '[human_template name="Archive ' . $post_type . '"]' ) ) ) {
                        $template = 'Archive ' . $post_type;
            }
}
//print_r ( '[human_template name="Archive ' . $post_type . '"]' );
?>



<?php echo do_shortcode ( '[human_template name="' . $template . '"]' ) ?>
<?php get_footer (); ?>

<?php

/*
 * Template Name: Home
 *
 *  @package human
 */

get_header ();

$template = 'Home';

if ( get_post_meta ( get_the_ID (), 'human_template' ) && ! empty ( get_post_meta ( get_the_ID (), 'human_template' ) ) ) {
            $template = get_post_meta ( get_the_ID (), 'human_template' );
}

print_r ( $template );
?>



<?php echo do_shortcode ( '[human_template name="' . $template . '"]' ) ?>

<?php get_footer (); ?>

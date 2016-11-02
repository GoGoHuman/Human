
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
$end = '';
if ( is_account_page () ) {
            /* echo '<div class="vc_row wpb_row vc_inner vc_row-fluid section vc_row-o-equal-height vc_row-flex">'
              . '<div class="wpb_column vc_column_container vc_col-sm-3">'
              . '<div class="vc_column-inner ">'
              . '<div class="wpb_wrapper">
              MY ACCOUNT MENU
              </div>
              </div>
              </div><div class="wpb_column vc_column_container vc_col-sm-9">'
              . '<div class="vc_column-inner ">'
              . '<div class="wpb_wrapper">';
              $end = '</div></div></div></div>'; */
}
?>


<?php

woocommerce_content ();
//echo $end;
?>

<?php get_sidebar (); ?>
<?php get_footer (); ?>
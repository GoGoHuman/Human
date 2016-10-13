<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package human
 */
$pageid = get_the_ID ();
$page_title = get_the_title ( $pageid );
?>
</div>
<footer id="mastfoot" class="site-footer" role="footer">
          <div class="footer_inner">
                  <?php
                  $HUMAN_DEFAULT_FOOTER = get_option ( 'HUMAN_DEFAULT_FOOTER' );
                  if ( isset ( get_option ( 'PAGE_DEFAULT_FOOTER' )[ $pageid ] ) ) {
                              if ( get_option ( 'PAGE_DEFAULT_FOOTER' )[ $pageid ][ 'footer' ] === "default" ) {

                                          $footer_content = get_post ( $HUMAN_DEFAULT_FOOTER );
                              }
                              else {

                                          $footer_content = get_post ( get_option ( 'PAGE_DEFAULT_FOOTER' )[ $pageid ][ 'footer' ] );
                              }
                  }
                  else {

                              $footer_content = get_post ( $HUMAN_DEFAULT_FOOTER );
                  }
                  if ( strpos ( $page_title, 'Footer-' ) !== false ) {

                  }
                  else {
                              if ( $HUMAN_DEFAULT_FOOTER ) {
                                          echo do_shortcode ( $footer_content->post_content );
                              }
                              else {
                                          echo do_shortcode ( '[human_template name="Footer" type="human_widgets"]' );
                              }
                  }
                  ?>
          </div>
</footer><!-- #masthead -->

<div class="mega-menu-top-wrapper">
        <?php
        if ( ! wp_is_mobile () ) {
                    echo human_template ( array (
                                'name' => 'Mega Menu Horistonal',
                                'type' => 'human_widgets' ) );
        }
        ?>
</div>
<div class="mega-menu-content-wrapper">
<?php
if ( ! wp_is_mobile () ) {
            echo human_template ( array (
                        'name' => 'Mega Menu Vertical',
                        'type' => 'human_widgets' ) );
}
?>
</div>

</div><!-- #page -->
<?php wp_footer (); ?>

</body>
</html>



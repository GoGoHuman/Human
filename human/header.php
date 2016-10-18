<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package human
 */
$pageid = get_the_ID ();
//echo $pageid;
?>

<!DOCTYPE html>
<html <?php language_attributes (); ?>>
          <head>
                    <meta charset="utf-8" id="charset">
                    <meta name="viewport" id="viewported" content="width=device-width, initial-scale=1.0">
                    <link rel="profile" href="http://gmpg.org/xfn/11">
                    <link rel="pingback" href="<?php bloginfo ( 'pingback_url' ); ?>">
                    <?php
                    wp_head ();
                    $human_analytics = get_option ( 'HUMAN_ANALYTICS' );
                    if ( $human_analytics && ! empty ( $human_analytics ) ) {
                                ?>
                                <script>
                                            ( function ( i, s, o, g, r, a, m ) {
                                                      i['GoogleAnalyticsObject'] = r;
                                                      i[r] = i[r] || function () {
                                                                ( i[r].q = i[r].q || [ ] ).push ( arguments )
                                                      }, i[r].l = 1 * new Date ();
                                                      a = s.createElement ( o ),
                                                                m = s.getElementsByTagName ( o )[0];
                                                      a.async = 1;
                                                      a.src = g;
                                                      m.parentNode.insertBefore ( a, m )
                                            } ) ( window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga' );
                                            ga ( 'create', '<?php echo $human_analytics; ?>', 'auto' );
                                            ga ( 'send', 'pageview' );
                                </script>
                    <?php } ?>
          </head>

          <body <?php body_class ( 'human-id-' . $pageid ); ?>>


                    <div id="page" class="hfeed site human-page" style="">


                              <header id="masthead" class="site-header" role="banner">
                                        <div class="header_inner">
                                                <?php
                                                $HUMAN_DEFAULT_HEADER = get_option ( 'HUMAN_DEFAULT_HEADER' );
                                                $attr[ "name" ] = "Header " . ucwords ( get_post_type () );
                                                if ( human_widget ( $attr ) !== null ) {
                                                            // echo '<hr>';
                                                            print(do_shortcode ( '[human_widget name="Header ' . ucwords ( get_post_type () ) . '"]' ) );
                                                }
                                                elseif ( $HUMAN_DEFAULT_HEADER && isset ( get_option ( 'PAGE_DEFAULT_HEADER' )[ $pageid ][ 'header' ] ) ) {
                                                            if ( get_option ( 'PAGE_DEFAULT_HEADER' )[ $pageid ][ 'header' ] === "default" ) {

                                                                        $header_content = get_post ( $HUMAN_DEFAULT_HEADER );
                                                            }
                                                            else {

                                                                        $header_content = get_post ( get_option ( 'PAGE_DEFAULT_HEADER' )[ $pageid ][ 'header' ] );
                                                            }

                                                            print( do_shortcode ( $header_content->post_content ) );
                                                }
                                                else {

                                                            if ( $HUMAN_DEFAULT_HEADER && ! empty ( $HUMAN_DEFAULT_HEADER ) ) {
                                                                        $header_content = get_post ( $HUMAN_DEFAULT_HEADER );
                                                                        //   print_r ( $header_content );
                                                                        print( do_shortcode ( $header_content->post_content ) );

                                                                        //  echo ucwords ( get_post_type () );
                                                                        // echo $HUMAN_DEFAULT_HEADER;
                                                            }
                                                            else {
                                                                        print(do_shortcode ( '[human_template name="Header" type="human_widgets"]' ) );
                                                            }
                                                }
                                                //echo do_shortcode('[human_widget name="Header ' . ucwords(get_post_type()) . '"]')
                                                ?>
                                        </div>
                              </header><!-- #masthead -->
                              <?php
//  $mobile_bar = do_shortcode('[human_template name="Mobile Bar Header" type="human_widgets"]');
//   echo  str_replace('<div class="mega-menu-toggle"></div>', $mobile_bar,do_shortcode('[maxmegamenu location=header-extra-menu]'));
                              ?>

                              <div class="content-wrapper" id="main">

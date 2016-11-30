<?php
/**
 * @package: HUMAN SOCIAL AUTH
 * @subpackage: HUMAN
 * @author: SergeDirect
 * @param: HUMAN SOCIAL AUTH ADMINISTRATION 
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly

global $wpdb;
?>



<div class="wrap human_wrapper" id="human_setting_wrapper">

          <form method="post">
                  <?php
                  if ( isset ( $_POST[ 'network' ] ) ) {
                              $networks = $_POST[ 'network' ];
                              $human_auths = get_option ( 'human_auths' );
                              for ( $i = 0; $i < count ( $networks ); $i ++ ) {
                                          $a = '';
                                          $s = '';
                                          $network = esc_html ( $networks[ $i ] );
                                          if ( isset ( $_POST[ 'app_id_' . $network ] ) ) {
                                                      $a = esc_html ( $_POST[ 'app_id_' . $network ] );
                                                      $s = esc_html ( $_POST[ 'app_secret_' . $network ] );
                                          }
                                          $human_auths[ $network ][ 'appid' ] = $a;
                                          $human_auths[ $network ][ 'secret' ] = $s;
                              }
                              update_option ( 'human_auths', $human_auths );
                  }
                  $networks = array (
                              'Facebook',
                              'LinkedIn',
                              'Google',
                              'Twitter' );
                  $app = '';
                  foreach ( $networks as $network ) {
                              $s = '';
                              $a = '';
                              if ( isset ( get_option ( 'human_auths' )[ $network ][ 'appid' ] ) ) {
                                          $a = get_option ( 'human_auths' )[ $network ][ 'appid' ];
                              }
                              if ( isset ( get_option ( 'human_auths' )[ $network ][ 'secret' ] ) ) {
                                          $s = get_option ( 'human_auths' )[ $network ][ 'secret' ];
                              }
                              $app .= '<h3>' . ucwords ( $network ) . ' Auth Details</h3>';
                              $app .= '<input type="hidden" name="network[]" value="' . $network . '">';
                              $app .= '<input type="text" placeholder="App ID" name="app_id_' . $network . '" value="' . $a . '">';
                              $app .= '<input type="text" placeholder="App Secret" name="app_secret_' . $network . '" value="' . $s . '"><hr>';
                  }
                  echo $app;
                  ?>
                    <button type="submit" class="page-title-action">Save</button>
          </form>
</div>
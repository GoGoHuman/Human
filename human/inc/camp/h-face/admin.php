<?php
/**
 * @package: HUMAN
 * @subpackage: HUMAN ADMIN
 * @author: SergeDirect
 * @param: HUMAN ADMIN 
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly

global $wpdb;


//$HUMAN_ADMIN_BRAIN = new HUMAN_ADMIN_BRAIN();
//echo $HUMAN_ADMIN_BRAIN->post();
?>



<div class="wrap human_wrapper" id="human_setting_wrapper">
          <form method="post">
                    <input type="hidden" value="1" name="human_options">
                    <h3>Set Default Header</h3>
                    <select name="HUMAN_DEFAULT_HEADER">
                              <option value="">--select default header--</option>
                              <?php
                              $hastreet = $hapostcode = $hacountry = $hphone1 = $hphone2 = '';
                              if ( isset ( $_POST[ 'human-address-street' ] ) ) {
                                          $hastreet = $_POST[ 'human-address-street' ];
                                          update_option ( 'human-option-hastreet', $hastreet );
                              }
                              $hastreet = stripcslashes ( get_option ( 'human-option-hastreet' ) );
                              if ( isset ( $_POST[ 'human-address-postcode' ] ) ) {
                                          $hapostcode = $_POST[ 'human-address-postcode' ];
                                          update_option ( 'human-option-hapostcode', $hapostcode );
                              }

                              $hapostcode = stripcslashes ( get_option ( 'human-option-hapostcode' ) );
                              if ( isset ( $_POST[ 'human-address-country' ] ) ) {
                                          $hacountry = $_POST[ 'human-address-country' ];
                                          update_option ( 'human-option-hacountry', $hacountry );
                              }
                              $hacountry = stripcslashes ( get_option ( 'human-option-hacountry' ) );
                              if ( isset ( $_POST[ 'human-phone-one' ] ) ) {
                                          $hphone1 = $_POST[ 'human-phone-one' ];
                                          update_option ( 'human-option-hphone1', $hphone1 );
                              }
                              $hphone1 = stripcslashes ( get_option ( 'human-option-hphone1' ) );
                              if ( isset ( $_POST[ 'human-phone-two' ] ) ) {
                                          $hphone2 = $_POST[ 'human-phone-two' ];
                                          update_option ( 'human-option-hphone2', $hphone2 );
                              }
                              $hphone2 = stripcslashes ( get_option ( 'human-option-hphone2' ) );

                              if ( isset ( $_POST[ 'logo' ] ) ) {
                                          update_option ( 'human-option-logo', urlencode ( $_POST[ 'logo' ] ) );
                              }
                              $logo = urldecode ( get_option ( 'human-option-logo' ) );


                              global $table_prefix;

                              $search_query = 'SELECT ID,post_title FROM ' . $table_prefix . 'posts
                         WHERE post_type = "human_widgets" AND post_status="publish"
                         AND post_title LIKE %s';

                              $like = '%Header%';
                              $results = $wpdb->get_results ( $wpdb->prepare ( $search_query, $like ), ARRAY_A );

                              $HUMAN_DEFAULT_HEADER = '';

                              if ( get_option ( 'HUMAN_DEFAULT_HEADER' ) ) {
                                          $HUMAN_DEFAULT_HEADER = get_option ( 'HUMAN_DEFAULT_HEADER' );
                              }
                              echo $HUMAN_DEFAULT_HEADER;

                              foreach ( $results as $key => $array ) {
                                          $HUMAN_DEFAULT_HEADER_SELECTED = '';
                                          if ( $HUMAN_DEFAULT_HEADER === $array[ 'ID' ] ) {
                                                      $HUMAN_DEFAULT_HEADER_SELECTED = 'selected';
                                          }
                                          echo '<option value="' . $array[ 'ID' ] . '" ' . $HUMAN_DEFAULT_HEADER_SELECTED . '>' . $array[ 'post_title' ] . '</option>';
                              }
                              ?>
                    </select>

                    <h3>Set Default Footer</h3>
                    <select name="HUMAN_DEFAULT_FOOTER">
                              <option value="">--select default footer--</option>
                              <?php
                              $search_query = 'SELECT ID,post_title FROM ' . $table_prefix . 'posts
                            WHERE post_type = "human_widgets" AND post_status="publish"
                         AND post_title LIKE %s';

                              $like = '%Footer%';
                              $results = $wpdb->get_results ( $wpdb->prepare ( $search_query, $like ), ARRAY_A );

                              $HUMAN_DEFAULT_FOOTER = '';

                              if ( get_option ( 'HUMAN_DEFAULT_FOOTER' ) ) {
                                          $HUMAN_DEFAULT_FOOTER = get_option ( 'HUMAN_DEFAULT_FOOTER' );
                              }
                              echo $HUMAN_DEFAULT_FOOTER;
                              foreach ( $results as $key => $array ) {
                                          $HUMAN_DEFAULT_FOOTER_SELECTED = '';
                                          if ( $HUMAN_DEFAULT_FOOTER === $array[ 'ID' ] ) {
                                                      $HUMAN_DEFAULT_FOOTER_SELECTED = 'selected';
                                          }
                                          echo '<option value="' . $array[ 'ID' ] . '" ' . $HUMAN_DEFAULT_FOOTER_SELECTED . '>' . $array[ 'post_title' ] . '</option>';
                              }
                              ?>
                    </select>
                    <h3>Google Analytics</h3>
                    <?php
                    if ( isset ( $_POST[ 'HUMAN_ANALYTICS' ] ) ) {
                                update_option ( 'HUMAN_ANALYTICS', esc_html ( $_POST[ 'HUMAN_ANALYTICS' ] ) );
                    }
                    if ( get_option ( 'HUMAN_ANALYTICS' ) ) {
                                $analytics = get_option ( 'HUMAN_ANALYTICS' );
                    }
                    else {
                                $analytics = '';
                    }
                    ?>
                    <h4>Copy / paste your Google analytics tracking ID:</h4>
                    <input name="HUMAN_ANALYTICS" placeholder="e.g. UA-74715244-1" value="<?php echo $analytics; ?>">
                    <p>Business Address</p>
                    <input type="text" name="human-address-street" placeholder="e.g. 15 Gentle's Entry" value="<?php echo $hastreet; ?>"><br>
                    <input type="text" name="human-address-postcode" placeholder="e.g. EH8 8PD" value="<?php echo $hapostcode; ?>"><br>
                    <input type="text" name="human-address-country" placeholder="e.g. United Kingdom" value="<?php echo $hacountry; ?>">
                    <hr>
                    <p>Business Phones</p>
                    <input type="text" name="human-phone-one" placeholder="e.g. (+44) 131 557 2171" value="<?php echo $hphone1; ?>"><br>
                    <input type="text" name="human-phone-two" placeholder="e.g. (+44) 131 557 2171" value="<?php echo $hphone2; ?>">
                    <h3>General Logo</h3>
                    <div class="human-controls human-media-wrapper" id="media-logo">

                              <?php
                              $logo_style = '';
                              if ( ! empty ( $logo ) ) {
                                          $logo_style = 'background:url(\'' . $logo . '\');color:transparent';
                              }
                              ?>
                              <a class="human-media" style="<?php echo $logo_style; ?>">upload </a>
                              <span class="fa fa-close human-del-media"></span>
                              <input type="hidden" class="human_media_holder human_meta_field" meta_title="logo"  meta_desc="logo"  meta_id="logo" meta_type="image" name="logo" id="logo" value="<?php echo $logo; ?>">
                    </div>

                    <button type="submit">Update</button>
          </form>
</div>
<?php
/**
 * @package: human
 * @subpackage: human CSS GEN
 * @author: SergeDirect
 * @param: human CSS GEN admin
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly
if ( ! is_admin () ) {
            exit;
}
global $wpdb;
if ( isset ( $_POST[ 'reset_css' ] ) ) {
            $css_overriten = human_reset_css ();
}
if ( isset ( $_POST[ 'reset_css_url' ] ) ) {
            $from = $_POST[ 'from' ];
            $to = $_POST[ 'to' ];
            $new_urls = str_replace ( $from, $to, (file_get_contents ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css" ) ) );
            $new_html_css = str_replace ( $from, $to, (file_get_contents ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css.html" ) ) );


            $myfile = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css", "w" );
            fwrite ( $myfile, $new_urls );

            fclose ( $myfile );
            $myfile = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css.html", "w" );
            fwrite ( $myfile, $new_html_css );

            fclose ( $myfile );

            global $fonts_face;

            $new_urls = str_replace ( $from, $to, $fonts_face );


            $myfile = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human-fonts.css", "w" );

            fwrite ( $myfile, $new_urls );

            fclose ( $myfile );

            update_option ( 'HUMAN-STYLE-GENS-FONTS', $new_urls );
            update_option ( 'STYLE-GENS', str_replace ( $from, $to, get_option ( 'STYLE-GENS' ) ) );
            update_option ( 'STYLE-GENS-MINIFIED', str_replace ( $from, $to, get_option ( 'STYLE-GENS-MINIFIED' ) ) );
            update_option ( 'HUMAN_CUSTOM_CSS', str_replace ( $from, $to, get_option ( 'HUMAN_CUSTOM_CSS' ) ) );
            $css__urls_overriten = true;
}
?>
<div class="wrap human_wrapper" id="human_setting_wrapper">

          <form method="post">
                  <?php
                  if ( isset ( $css__urls_overriten ) ) {
                              echo '<h4 style="color:blue">* Settings updated</h4>';
                  }
                  ?>
                    <h3>Reset CSS urls</h3>
                    <p><b>Leave fields empty to rebuild fonts</b></p>
                    From Url (* with slash on the end)<br>
                    <input type="url" placeholder="e.g. http://human.camp/" name="from" style="min-width:300px">
                    <br>
                    To Url (* with slash on the end)<br>
                    <input type="url" placeholder="e.g. https://human.camp" name="to" style="min-width:300px">
                    <br><br>
                    <input type="hidden" name="reset_css_url_check" value="1">
                    <input type="checkbox" name="reset_css_url" value="1"> - Select here if you really want to override this.
                    <?php
                    if ( isset ( $_POST[ 'reset_css_url_check' ] ) ) {
                                if ( ! isset ( $_POST[ 'reset_css_url' ] ) ) {
                                            echo '<br><span style="color:red">Please check here if you really want to do this</span>';
                                }
                    }
                    ?>
                    <p><b><i>Warning this will reset any custom css image/file urls.</i></b></p>
                    <button type = "submit">Reset CSS Urls</button>
          </form>
</div>

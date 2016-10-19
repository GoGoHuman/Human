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
                    <h3>Save</h3>
                    <button type="submit">Update</button>
          </form>
</div>
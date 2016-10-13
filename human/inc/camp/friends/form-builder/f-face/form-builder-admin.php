<?php
/**
 * @package: human
 * @subpackage: human post-types
 * @author: SergeDirect
 * @param: human post-types admin
 */
if ( defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly

if ( ! is_admin () ) {
            exit;
} // exit if accessed from front end 
global $wpdb;
$cpt = '';
if ( isset ( $_POST[ 'human_mailchimp_api' ] ) ) {
            $cpt = esc_html ( trim ( $_POST[ 'human_mailchimp_api' ] ) );
            update_option ( 'human_mailchimp_api', $cpt );
}
if ( get_option ( 'human_mailchimp_api' ) ) {
            $cpt = get_option ( 'human_mailchimp_api' );
}
?>



<div class="wrap human_wrapper" id="human_setting_wrapper">

          <form method="post">
                    <h3>Enter Mailchimp API here</h3>
                    <p>&nbsp; </p>
                    <input type="text" name="human_mailchimp_api" value="<?php echo get_option ( 'human_mailchimp_api' ); ?>">
                    <button type="submit" class="page-title-action">Save</button>
          </form>
          <h4>Lists:</h4>
          <table class="table">
                    <tr><td>List Name</td><td>List ID</td></tr>
                    <?php
                    $lists = human_mailchimp_lists ();
                    foreach ( $lists as $k => $v ) {
                                if ( is_array ( $v ) ) {
                                            // var_dump($v);
                                            foreach ( $v[ 0 ][ 'data' ] as $key => $val ) {
                                                        foreach ( $val as $ks => $vs ) {

                                                                    // print_r($vs);
                                                                    //  echo $vs['email'];
                                                        }
                                            }
                                }
                                else {
                                            echo '<tr><td>' . $k . '</td><td>' . $v . '</td></tr>';
                                }
                    }
                    ?>

          </table>
</div>

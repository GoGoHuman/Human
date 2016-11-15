<?php
/**
 * @package: human
 * @subpackage: human post-types
 * @author: SergeDirect
 * @param: human post-types admin
 */
if (!defined('ABSPATH')) {
            exit;
} // Exit if accessed directly
if (!is_admin()) {
            exit;
}
global $wpdb;
$cpt = '';
if (isset($_POST['human_custom_post_types'])) {
            $cpt = esc_html(trim($_POST['human_custom_post_types']));
            update_option('human_custom_post_types', $cpt);

            $redirect_link = url_protocol() . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $r = '
            <script async>

                            location.assign("' . explode('?', $redirect_link)[0] . '?page=human-post-types-settings");

            </script>';
            echo $r;
}
if (get_option('human_custom_post_types')) {
            $cpt = get_option('human_custom_post_types');
}
?>



<div class="wrap human_wrapper" id="human_setting_wrapper">

            <form method="post">
                        <h3>Custom Post Types</h3>
                        <p>Enter url friendly , coma separated Post Types e.g. news,products,services </p>
                        <input type="text" name="human_custom_post_types" value="<?php echo $cpt; ?>">
                        <button type="submit" class="page-title-action">Save</button>
            </form>

</div>

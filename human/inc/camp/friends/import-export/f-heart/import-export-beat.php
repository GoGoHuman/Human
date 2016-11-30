<?php

/**
 *  Import Export for backup, clone and push to the HumanMarket place
 * @package Human
 * @subpackage Import Export
 * @author Sergei Pavlov
 * @author URI: https://linkedin.com/in/sergedirect
 */
if (!defined('ABSPATH')) {
            exit;
} // Exit if accessed directly
add_action('wp_ajax_human_backup', 'human_backup');

//add_action ( 'wp_ajax_nopriv_cssGenAjax', 'cssGenAjax' );
require('import-export-cron.php');

function human_backup() {
            check_ajax_referer('ajax-human-nonce', 'nonce');

            if (true) {
                        if (isset($_POST['start_backup'])) {
                                    $data = [];
                                    $data['mysqldump_path'] = $_POST['mysqldump_path'];
                                    $data['template_name'] = $_POST['template_name'];

                                    update_option('human-backup', $data);

                                    if (!wp_next_scheduled('human_backup_hook')) {
                                                $time = time() + 5;
                                                wp_schedule_event($time, 'hourly', 'human_backup_hook');
                                    }
                                    wp_send_json_success($data['template_name'] . ' Baby Backup Started');
                        } elseif (isset($_POST['check_backup'])) {
                                    $msgs = get_option('human-bp-messages');
                                    $msgs = implode('<br>', $msgs);
                                    if (wp_next_scheduled('human_backup_hook')) {

                                                wp_send_json_success($msgs);
                                    } else {
                                                update_option('human-bp-messages', '');
                                                $baby = get_option('human-last-backup-name');
                                                update_option('human-last-backup-name', '');
                                                wp_send_json_success(array(
                                                    'msg' => $msgs,
                                                    'template' => $baby,
                                                    'cleared' => 1));
                                    }
                        }
            }
}

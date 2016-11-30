<?php

//print_r ( date ( "d F Y H:i:s", wp_next_scheduled ( 'human_backup_hook' ) ) );
add_action('human_backup_hook', 'human_trigger_backup');

//wp_clear_scheduled_hook ( 'human_backup_hook' );


function human_trigger_backup() {

            $data = get_option('human-backup');
            if (isset($data['mysqldump_path'])) {
                        update_option('human-mysqldump-path', esc_html($data['mysqldump_path']));
                        //    update_option ( 'human-backup', 'started' );
            }
            if (!isset($data['template_name'])) {

                        die('backup already started');
            }

            $template_name = str_replace(array(
                ' ',
                ','), '-', $data['template_name']);

            $bp_msgs = [];
            $bp_msgs[] = 'Backup started...';
            update_option('human-bp-messages', $bp_msgs);

            require_once(HUMAN_BASE_PATH . 'friends/backup/f-heart/lib/human-zip.php');

            $bp_msgs[] = 'Requesting Files...';
            update_option('human-bp-messages', $bp_msgs);
            $b = CreateWPFullBackupZip($template_name);

            $bp_msgs[] = 'Generating backup zip...';
            update_option('human-bp-messages', $bp_msgs);
            if (isset($b)) {
                        if (is_array($b)) {


                                    $template_created = $template_name;

                                    wp_clear_scheduled_hook('human_backup_hook');
                                    update_option('human-backup', '2');
                                    update_option('human-last-backup-name', $template_created);
                                    die();
                        } else {
                                    $e = 'Not an array';
                        }
            } else {
                        $e = 'Not Isset';
            }

            $bp_msgs[] = '' . $template_created . ' Opps something went wrong there on line ' . __LINE__ . ' in ' . __FILE__;

            update_option('human-bp-messages', $bp_msgs);

            wp_clear_scheduled_hook('human_backup_hook');
            die();
}

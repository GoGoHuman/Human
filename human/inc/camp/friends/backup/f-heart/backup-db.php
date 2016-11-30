<?php

/*
 *  @package Backup DB
 *  @author Sergei Pavlov <itpal24@gmail.com>
 *
 */

if ( ! defined ( 'ABSPATH' ) || ! is_admin () ) {
            exit;
} // Exit if accessed directly

function backup_tables ( $dest, $exclude = null ) {

            require_once('lib/BackupClass.php');
            $human_bu = new wpdbBackup();
            update_option ( 'test-dest', $dest );
            $human_bu->perform_backup ( $dest );
}

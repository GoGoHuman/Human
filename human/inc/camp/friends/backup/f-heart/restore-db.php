<?php

/*
 *  @package Restore DB
 *  @author Sergei Pavlov <itpal24@gmail.com>
 *
 */

if ( ! defined ( 'ABSPATH' ) || ! is_admin () ) {
            exit;
} // Exit if accessed directly

function restore_db ( $db_path ) {

            require_once('lib/BackupClass.php');

//Database Configurations Array
            $config = array (
                        'host' => DB_HOST,
                        'login' => DB_USER,
                        'password' => DB_PASSWORD,
                        'database_name' => DB_NAME );

            try {
                        $dbBackupObj = new DbBackup ( $config );
                        return $dbBackupObj->executeRestore ( $db_path );
            }
            catch ( Exception $e ) {
                        echo $e->getMessage ();
            }
}

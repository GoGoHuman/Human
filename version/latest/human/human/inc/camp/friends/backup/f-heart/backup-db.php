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
            if ( ! isset ( $exclude ) ) {
                        global $table_prefix;
                        $exclude = array (
                                    $table_prefix . 'users',
                                    $table_prefix . 'user_meta' );
            }
//Database Configurations Array
            $config = array (
                        'host' => DB_HOST,
                        'login' => DB_USER,
                        'password' => DB_PASSWORD,
                        'database_name' => DB_NAME );
            try {
                        $dbBackupObj = new DbBackup ( $config );
                        $dbBackupObj->setBackupDirectory ( $dest ); //CustomFolderName
                        $dbBackupObj->setDumpType ( 1 ); //To disable the single table files dumping (1 Dump file for the whole database)
                        if ( isset ( $exclude ) && ! empty ( $exclude ) ) {
                                    $dbBackupObj->excludeTable ( $exclude ); //Exclude few tables from your backup execution
                        }
                        // $dbBackupObj->addDumpOption ( '-f' ); //Add few custom options to your backup execution
                        //$dbBackupObj->enableS3Support($amazonConfig);//Transfer your backup files to Amazon S3 Storage
                        return $dbBackupObj->executeBackup (); //Start the actual backup process using the user specified settings and options
            }
            catch ( Exception $e ) {
                        return 'There was an error creating Database Backup' . $e->getMessage ();
            }
}

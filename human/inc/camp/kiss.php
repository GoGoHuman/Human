<?php

/**
 * @package Human
 * @subpackage Kiss - Connector
 * @author Sergedirect
 * @author URI: http://human.camp
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly


if ( is_multisite () ) {
            add_action ( 'admin_notices', 'human_wpc_msnotice' );
}

function human_wpc_msnotice () {
            echo '<div class="error"><span style="color:red;"><b>Human theme is incompatible with Wordpress Multisite installations. <br>Human team work hard to resolve this issue in future release.</b></span></div>';
}

define ( 'HUMAN_NAME', basename ( basename ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) );
define ( 'HUMAN_NAMED', ucwords ( HUMAN_NAME ) ); // Capitalise first letter for public theme name display


define ( 'HUMAN_PARENT_URL', str_replace ( '-child', '', get_template_directory_uri () ) );
define ( 'HUMAN_PARENT_PATH', str_replace ( '-child', '', get_template_directory () ) );
define ( 'HUMAN_BASE_URL', HUMAN_PARENT_URL . '/inc/camp/' );
define ( 'HUMAN_BASE_PATH', HUMAN_PARENT_PATH . '/inc/camp/' );

define ( 'HUMAN_CHILD_URL', get_template_directory_uri () . '-child' );
define ( 'HUMAN_CHILD_PATH', get_template_directory () . '-child' );
define ( 'HUMAN_CHILD_BASE_URL', HUMAN_CHILD_URL . '/inc/camp/' );
define ( 'HUMAN_CHILD_BASE_PATH', HUMAN_CHILD_PATH . '/inc/camp/' );


if ( is_admin () ) {
            require HUMAN_BASE_PATH . 'h-heart/admin.class.php';
}


require HUMAN_BASE_PATH . 'h-heart/init.php';
// Load MAIN HUMAN Classes


define ( 'HUMAN_DER_SEP', DIRECTORY_SEPARATOR );
define ( 'HUMAN_FRIENDS_PATH', HUMAN_BASE_PATH . 'friends' );
define ( 'HUMAN_FRIENDS_URL', HUMAN_BASE_URL . 'friends' );
define ( 'HUMAN_CHILD_FRIENDS_PATH', HUMAN_CHILD_BASE_PATH . 'friends' );
define ( 'HUMAN_CHILD_FRIENDS_URL', HUMAN_CHILD_BASE_URL . 'friends' );

$friend_styles = [ ];
$friend_scripts = [ ];
$friend_load = [ ];

function human_icon () {
            return site_url () . "/wp-content/themes/human/assets/images/icons/post-details.png";
}

/**
 * Preload all friends heart beats
 * @array settings pages
 */
foreach ( new DirectoryIterator ( HUMAN_FRIENDS_PATH ) as $file ) {


            if ( $file->isDot () )
                        continue;

            if ( $file->isDir () ) {

                        $friend_names[] = $file->getFilename ();
                        $friend_name = $file->getFilename ();

                        if ( is_file ( HUMAN_FRIENDS_PATH . '/' . $friend_name . '/f-heart/' . $friend_name . '-beat.php' ) !== false ) {

                                    if ( is_file ( HUMAN_CHILD_FRIENDS_PATH . '/' . $friend_name . '/f-heart/' . $friend_name . '-beat.php' ) === false ) {
                                                require HUMAN_FRIENDS_PATH . '/' . $friend_name . '/f-heart/' . $friend_name . '-beat.php';
                                    }
                                    else {
                                                require HUMAN_CHILD_FRIENDS_PATH . '/' . $friend_name . '/f-heart/' . $friend_name . '-beat.php';
                                    }
                        }
                        if ( is_file ( HUMAN_FRIENDS_PATH . '/' . $friend_name . '/f-face/' . $friend_name . '-admin.php' ) !== false ) {
                                    $friend_load[ $friend_name ] = 1;
                        }
                        if ( is_file ( HUMAN_FRIENDS_PATH . '/' . $friend_name . '/f-character/mood/' . $friend_name . '-global.css' ) !== false ) {
                                    $friend_styles[ $friend_name ][ 'style' ] = 1;
                        }
                        if ( is_file ( HUMAN_FRIENDS_PATH . '/' . $friend_name . '/f-character/temper/' . $friend_name . '-global.js' ) !== false ) {
                                    $friend_scripts[ $friend_name ][ 'scripts' ] = 1;
                        }
            }
}

function human_register_scripts () {
            wp_deregister_style ( 'rs-plugin-settings' );
            wp_register_script ( 'human-js', HUMAN_BASE_URL . 'h-character/temper/global.js', array (
                        'jquery' ) );
            wp_register_style ( "font-awesome", HUMAN_BASE_URL . "h-helpers/font-awesome/css/font-awesome.min.css" );
            wp_register_style ( "human-bootstrap", HUMAN_BASE_URL . "h-helpers/bootstrap/css/bootstrap.min.css" );
}

add_action ( 'init', 'human_register_scripts' );

function human_visitor_enqueue () {
            global $friend_styles, $friend_scripts;

// wp_enqueue_style("human-font-awesome");
// wp_enqueue_style("human-bootstrap");
            //wp_register_script('multi-select', HUMAN_BASE_URL.'/h-helpers/multi-select/jquery.tokenize.css');
            foreach ( $friend_styles as $friend_name => $val ) {

                        if ( is_file ( HUMAN_BASE_URL . 'friends/' . $friend_name . '/f-character/mood/' . $friend_name . '-global.css' ) ) {
                                    wp_enqueue_style ( 'human-' . $friend_name . '-css', HUMAN_BASE_URL . 'friends/' . $friend_name . '/f-character/mood/' . $friend_name . '-global.css', array (
                                                'human-bootstrap' ) );
                        }
            }

            wp_enqueue_style ( 'human-css', HUMAN_BASE_URL . 'h-character/mood/global.css', array (
                        'js_composer_front' ) );
            wp_register_style ( 'human-parent-css', HUMAN_PARENT_URL . '/style.css', array (
                        'human-css' ) );
            wp_enqueue_style ( 'human-parent-css' );
            wp_enqueue_style ( 'human-child-css', HUMAN_CHILD_URL . '/style.css', array (
                        'human-parent-css' ) );
// wp_enqueue_script('human-navigation', HUMAN_BASE_URL . 'h-helpers/nav/navigation.js', array('jquery'), '20120206', true);
// wp_enqueue_script('human-skip-link-focus-fix', HUMAN_BASE_URL . 'h-helpers/nav/skip-link-focus-fix.js', array('jquery'), '20130115', true);


            wp_dequeue_script ( 'jquery' );
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'human-js' );
            wp_localize_script ( 'human-js', 'humanAjax', array (
                        'ajaxurl' => admin_url ( 'admin-ajax.php' ),
                        'siteurl' => site_url (),
                        'thisUrl' => get_permalink ( get_the_ID () ),
                        'nonce' => wp_create_nonce ( 'ajax-human-nonce' ),
                        'human_name' => HUMAN_NAME
                        )
            );

            foreach ( $friend_scripts as $friend_name => $val ) {

                        wp_enqueue_script ( 'human-' . $friend_name . '-js', HUMAN_BASE_URL . 'friends/' . $friend_name . '/f-character/temper/' . $friend_name . '-global.js', array (
                                    'human-js' ) );
            }
}

add_action ( "wp_enqueue_scripts", "human_visitor_enqueue" );

/**
 * @param Preload friend's settings
 */
function human_menu () {

            global $friend_load;

            add_menu_page ( HUMAN_NAMED, HUMAN_NAMED, "manage_options", HUMAN_NAME . "-settings", "human_admin_settings", null, 28.786568 );


            add_submenu_page ( HUMAN_NAME . "-settings", "Settings", "Settings", 'manage_options', HUMAN_NAME . "-settings", null, 1 );


            $nav_class_active = '';

            if ( isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] === HUMAN_NAME . '-settings' ) {
                        $nav_class_active = ' nav-tab-active';
            }
            else {
                        $nav_class_active = '';
            }

            $human_tabs = '<a href="?page=' . HUMAN_NAME . '-settings"  class="nav-tab' . $nav_class_active . '">Main Settings</a>';

            foreach ( $friend_load as $friend_name => $val ) {

                        if ( isset ( $_GET[ 'page' ] ) && $_GET[ 'page' ] === 'human-' . $friend_name . '-settings' ) {

                                    $nav_class_active = ' nav-tab-active';
                        }
                        else {
                                    $nav_class_active = '';
                        }
                        $human_tabs .= '<a href="?page=' .
                                    'human-' . $friend_name . '-settings" class="nav-tab' . $nav_class_active . '">' . strtoupper ( str_replace ( array (
                                                '_',
                                                '-' ), ' ', $friend_name ) ) . '</a>';
                        $friend_named = ucwords ( str_replace ( array (
                                    "_",
                                    "-" ), " ", $friend_name ) );
                        $friend_name_settings = 'human-' . $friend_name . '-settings';
                        add_submenu_page (
                                    HUMAN_NAME . '-settings', $friend_named, $friend_named, 'manage_options', $friend_name_settings, function() use ($friend_name) {
                                    human_admin_settings ( $friend_name );
                        }
                        );
            }



            define ( 'HUMAN_TABS', $human_tabs );
}

if ( is_admin () && current_user_can ( 'edit_pages' ) ) {
            add_action ( 'admin_menu', 'human_menu' );
}

function human_admin_settings ( $friend_name ) {

//print_r(unserialize(HUMAN_FRIENDS));echo '<hr>';

            if ( empty ( $friend_name ) ) {


                        echo human_admin_header ();
                        require HUMAN_BASE_PATH . 'h-face/admin.php';
            }
            else {

                        echo human_admin_header ();
                        require HUMAN_BASE_PATH . 'friends/' . $friend_name . '/f-face/' . $friend_name . '-admin.php';
            }
}

require HUMAN_BASE_PATH . 'h-face/global.php';

function human_enqueue_stranger ( $handle, $source, $after = '' ) {

            if ( wp_script_is ( $handle, 'enqueued' ) ) {
                        return;
            }
            else {
                        wp_register_script ( $handle, $source, array (
                                    $after ) );
                        wp_enqueue_script ( $handle );
            }
}

function human_admin_scripts () {

            wp_enqueue_style ( 'human-admin-style', HUMAN_BASE_URL . '/h-character/mood/admin.css', array () );
            wp_enqueue_script ( 'human-admin-script', HUMAN_BASE_URL . '/h-character/temper/admin.js', array (
                        'jquery' ) );
            wp_localize_script ( 'human-admin-script', 'humanAjax', array (
                        'ajaxurl' => admin_url ( 'admin-ajax.php' ),
                        'siteurl' => site_url (),
                        'thisUrl' => get_permalink ( get_the_ID () ),
                        'nonce' => wp_create_nonce ( 'ajax-human-nonce' ),
                        'human_name' => HUMAN_NAME
                        )
            );
}

add_action ( 'admin_enqueue_scripts', 'human_admin_scripts' );


if ( current_user_can ( 'edit_posts' ) ) {

            function addTinyMCELinkClasses ( $wp ) {
                        $wp .= ',' . HUMAN_BASE_URL . '/character/mood/tinymce.css';
                        return $wp;
            }

            if ( function_exists ( 'add_filter' ) ) {
                        //     add_filter ( 'mce_css', 'addTinyMCELinkClasses' );
            }
}
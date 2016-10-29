<?php

/**
 * @package Human
 * @subpackage CSS Genrator
 * @author Sergei Pavlov <itpal24@gmail.com>
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly



$HumanCssGen = new HumanCssGen();

$HumanCssGen->init ();

class HumanCssGen {

            public function init () {

                        add_action (
                                    'wp_enqueue_scripts', array (
                                    &$this,
                                    'scripts' )
                        );
            }

            public function scripts () {
                        wp_dequeue_style ( 'font-awesome' );
                        wp_enqueue_style ( 'font-awesome' );
                        wp_dequeue_style ( 'open-sans-css' );
                        $absurl = explode ( '/wp-content', HUMAN_BASE_URL )[ 0 ] . '/';
                        wp_enqueue_style ( 'human-fonts-css', $absurl . '/wp-content/human-fonts.css', array (), '123' );
                        wp_dequeue_style ( 'human-parent-css' );
                        wp_enqueue_style ( 'human-parent-css' );

                        wp_enqueue_style ( 'human-dynamic-css', $absurl . 'wp-content/human.css', array (
                                    'human-parent-css' ), '123' );

                        wp_enqueue_style ( 'human-custom-css', $absurl . 'wp-content/human-custom.css', array (
                                    'human-dynamic-css' ), '123' );

                        wp_enqueue_style ( 'human-child-css', HUMAN_CHILD_URL . '/style.css', array (
                                    'human-custom-css' ) );
            }

            public function get () {

            }

}

//add_action('wp_ajax_dynamic_css', 'dynamic_css');
//add_action('wp_ajax_nopriv_dynamic_css', 'dynamic_css');

function dynamic_css () {

            header ( 'Content-type: text/css' );


            if ( get_option ( 'STYLE-GENS-MINIFIED' ) ) {
                        echo html_entity_decode ( stripslashes ( get_option ( 'STYLE-GENS-MINIFIED' ) ) );
            }
            die ();
}

function human_cssgen_tag_helper () {
            wp_enqueue_script ( 'css-combinations', HUMAN_BASE_URL . 'friends/css-gen/f-character/temper/combinations.js', array (
                        'jquery',
                        'vc_inline_iframe_js' ), '001', 'in_footer' );

            wp_enqueue_script ( 'css-gen-tag-helper', HUMAN_BASE_URL . 'friends/css-gen/f-character/temper/css-gen-tag-helper.js', array (
                        'jquery',
                        'css-combinations' ), '001', 'in_footer' );
}

add_action ( 'wp_enqueue_scripts', 'human_cssgen_tag_helper' );
/* gets the data from a URL */

function human_get_url_data ( $url ) {
            $ch = curl_init ();
            $timeout = 5;
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
            $data = curl_exec ( $ch );
            curl_close ( $ch );
            return $data;
}

add_action ( 'wp_ajax_cssGenAjax', 'cssGenAjax' );
add_action ( 'wp_ajax_nopriv_cssGenAjax', 'cssGenAjax' );

function cssGenAjax () {
            check_ajax_referer ( 'ajax-human-nonce', 'nonce' );

            if ( true ) {

                        if ( isset ( $_POST[ 'new_section' ] ) && ! empty ( $_POST[ 'new_section' ] ) ) {
                                    $new_section = sanitize_text_field ( $_POST[ 'new_section' ] );
                                    $css_folder = 'global';
                                    if ( isset ( $_POST[ 'section_folder' ] ) && ! empty ( $_POST[ 'section_folder' ] ) ) {
                                                $css_folder = sanitize_text_field ( $_POST[ 'section_folder' ] );
                                    }
                                    if ( ! isset ( get_option ( 'human_css_sections' )[ $new_section ] ) ) {
                                                $sections = get_option ( 'human_css_sections' );
                                                $sections[ $css_folder ][ $new_section ] = $new_section;

                                                update_option ( 'human_css_sections', $sections );
                                                $rep = $new_section;
                                    }
                                    else {
                                                $rep = 'exist';
                                    }
                                    $response = array (
                                                'new_section' => '' . $rep . '',
                                                'css_folder' => '' . $css_folder . ''
                                    );
                        }
                        elseif ( isset ( $_POST[ 'new_section' ] ) && empty ( $_POST[ 'new_section' ] ) ) {
                                    $rep = 'empty';
                                    $response = array (
                                                'new_section' => '' . $rep . ''
                                    );
                        }
                        elseif ( isset ( $_POST[ 'style_gens_minified' ] ) ) {


                                    $style_gens_mini = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css", "w" );
                                    $style_gens_minified = trim ( html_entity_decode ( stripslashes ( $_POST[ 'style_gens_minified' ] ) ) );
                                    fwrite ( $style_gens_mini, $style_gens_minified );
                                    fclose ( $style_gens_mini );

                                    $style_gens = trim ( html_entity_decode ( stripslashes ( base64_decode ( $_POST[ 'style_gens' ] ) ) ) );
                                    $style_gens_html = explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css.html";
                                    $style_gens_htm = fopen ( $style_gens_html, "w" );
                                    fwrite ( $style_gens_htm, $style_gens );
                                    fclose ( $style_gens_htm );


                                    $style_custom = explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human-custom.css";
                                    $style_cstm = fopen ( $style_custom, "w" );
                                    fwrite ( $style_cstm, $_POST[ 'custom_css' ] );
                                    fclose ( $style_cstm );
                                    update_option ( 'HUMAN_CUSTOM_CSS', $_POST[ 'custom_css' ] );
                                    $human_fonts = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human-fonts.css", "w+" );
                                    global $fonts_face;
                                    fwrite ( $human_fonts, $fonts_face );
                                    fclose ( $human_fonts );

                                    $response = array (
                                                'saved' => 'saving'
                                    );
                        }
                        elseif ( isset ( $_POST[ 'del_section' ] ) ) {

                                    $sections = get_option ( 'human_css_sections' );

                                    if ( ! empty ( $_POST[ 'del_folder' ] ) ) {
                                                $delete_folder = $_POST[ 'del_folder' ];
                                    }
                                    else {
                                                $delete_folder = 'global';
                                    }
                                    unset ( $sections[ $delete_folder ][ $_POST[ 'del_section' ] ] );
                                    // $sections = array_filter($sections);
                                    update_option ( 'human_css_sections', $sections );

                                    $response = array (
                                                'del_section' => 'Section ' . $_POST[ 'del_section' ] . ' - deleted!' );
                        }
                        elseif ( isset ( $_POST[ 'rename_folder' ] ) ) {

                                    $sections = get_option ( 'human_css_sections' );
                                    $sections[ $_POST[ 'new_name' ] ] = $sections[ $_POST[ 'rename_folder' ] ];
                                    unset ( $sections[ $_POST[ 'rename_folder' ] ] );

                                    $response = array (
                                                'folder_renamed' => 'Folder Renamed to ' . $_POST[ 'new_name' ] . '!' );
                        }
                        elseif ( isset ( $_POST[ 'css_url' ] ) ) {

                                    $response = array (
                                                'url' => file_get_contents ( $_POST[ 'css_url' ] ) );
                        }
                        elseif ( isset ( $_POST[ 'newcolor' ] ) ) {
                                    $colors = [ ];
                                    $colors = get_option ( 'human-color-palette' );
                                    $colors[ $_POST[ 'newcolor' ] ] = $_POST[ 'newcolor' ];
                                    update_option ( 'human-color-palette', $colors );
                                    $response = human_palette_colors ();
                        }
                        elseif ( isset ( $_POST[ 'delete_color' ] ) ) {
                                    $colors = get_option ( 'human-color-palette' );
                                    unset ( $colors[ $_POST[ 'delete_color' ] ] );
                                    update_option ( 'human-color-palette', $colors );
                                    $response = human_palette_colors ();
                        }
                        else {
                                    $response = array (
                                                'error' => 'Data Error.' );
                        }
            }
            else {
                        $response ( array (
                                    'error' => 'Wrong Nonce.' ) );
            }

            wp_send_json_success ( $response );
}

function human_palette_colors () {
            // delete_option ( 'human-color-palette' );
            if ( get_option ( 'human-color-palette' ) ) {
                        $colors = get_option ( 'human-color-palette' );
                        $palette = '';
                        foreach ( $colors as $k => $v ) {
                                    $palette .= '<div class="palette-holder-wrapper" style="float:left"><span class="palette-holder" data-color="' . $v . '" style="width:20px;height:20px;background:' . $v . ';cursor:pointer;display:block"></span><span style="font-size: 9px!important;
    position: relative;
    top: -30px;
    right: -1px;" class="fa fa-times delete-palette"  data-color="' . $v . '"></span>&nbsp;</div>';
                        }
                        $response = array (
                                    'colors' => $palette . '<div style="clear:both"></div>' );
            }
            else {
                        $response = [ ];
            }
            return $response;
}

require HUMAN_BASE_PATH . 'friends/css-gen/f-face/css-gen-admin-user.php';

function load_css_gen () {

            echo '<link href="' . HUMAN_BASE_URL . 'friends/css-gen/f-character/mood/css-gen-admin.css" type="text/css" rel="stylesheet">';

            echo '<div style="display:none;" class="css-draggable human-compose-mode-css-gen css-gradient">';
            global $css_font_list;
            $css_builder = css_builder ( $css_font_list );
            echo $css_builder . '</div>';

            echo '
                      <div class="human-loading-gif-wrapper"> <div class="human-loading-gif"></div></div>';
}

function load_css_gen_scripts () {

            wp_dequeue_script ( 'jquery' );
            wp_enqueue_media ();
            wp_enqueue_script ( 'media-upload' );

            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'css-gen-helpers', HUMAN_BASE_URL . 'friends/css-gen/f-character/temper/helpers.js', array (
                        'jquery' ) );
            wp_enqueue_script ( 'jquery-ui' );

            wp_enqueue_script ( 'jquery-ui-draggable' );
            wp_enqueue_style ( 'wp-color-picker' );

            wp_enqueue_script ( 'jquery-ui-autocomplete' );
            wp_enqueue_script ( 'iris', admin_url ( 'js/iris.min.js' ), array (
                        'jquery-ui-draggable',
                        'jquery-ui-slider',
                        'jquery-touch-punch' ), false, 1 );
            wp_enqueue_script ( 'wp-color-picker', admin_url ( 'js/color-picker.min.js' ), array (
                        'iris' ), false, 1 );
            $colorpicker_l10n = array (
                        'clear' => __ ( 'Clear' ),
                        'defaultString' => __ ( 'Default' ),
                        'pick' => __ ( 'Select Color' ) );
            // wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );


            wp_enqueue_script ( 'css-gen-select-tag', HUMAN_BASE_URL . 'friends/css-gen/f-character/temper/select.tag.js', array (
                        'jquery-ui-autocomplete' ) );

            wp_enqueue_script ( 'css-gen-script', HUMAN_BASE_URL . 'friends/css-gen/f-character/temper/script.js', array (
                        'css-gen-select-tag' ) );

            wp_enqueue_script ( 'css-gen-update-css', HUMAN_BASE_URL . 'friends/css-gen/f-character/temper/update.css.js', array (
                        'css-gen-script' ) );
}

if ( isset ( $_GET[ 'vc_action' ] ) && $_GET[ 'vc_action' ] === 'vc_inline' && current_user_can ( 'administrator' ) ) {
            //   load_css_gen();
            add_action ( 'admin_footer', 'load_css_gen' );
            add_action ( 'admin_enqueue_scripts', 'load_css_gen_scripts', 99 );
}

function human_reset_css ( $template = null ) {

            if ( isset ( $template ) ) {
                        $path = $template;
                        $default_css = file_get_contents ( $path . '/css-mins.html' );
                        $default_gen_mins = $default_css;
                        $default_gens = file_get_contents ( $path . '/html_css.html' );
                        $default_custom_css = file_get_contents ( $path . '/custom_css.html' );
                        $default_fonts_css = file_get_contents ( $path . '/custom_fonts.html' );
                        $override_templates = true;
            }
            else {
                        $default_css = file_get_contents ( HUMAN_BASE_PATH . 'friends/css-gen/f-face/templates/css-gen-min.html' );
                        $default_fonts_css = file_get_contents ( HUMAN_BASE_PATH . 'friends/css-gen/human-fonts.css' );
                        $default_gens = file_get_contents ( HUMAN_BASE_PATH . 'friends/css-gen/f-face/templates/css-gen.html' );
                        $default_gen_mins = file_get_contents ( HUMAN_BASE_PATH . 'friends/css-gen/f-face/templates/css-gen-min.html' );

                        $default_custom_css = file_get_contents ( HUMAN_BASE_PATH . 'friends/css-gen/f-face/templates/custom-css.html' );
            }
            $myfile = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css", "w" );

            fwrite ( $myfile, $default_css );
            fclose ( $myfile );

            global $fonts_face;


            $myfile = fopen ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human-fonts.css", "w" );
//$txt = "John Doe\n";
            fwrite ( $myfile, $default_fonts_css );

            fclose ( $myfile );

            if ( isset ( $override_templates ) ) {
                        human_setup_options ( $template );
            }
            return true;
}

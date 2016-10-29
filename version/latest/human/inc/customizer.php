<?php
/**
 * foodbiz Theme Customizer.
 *
 * @package foodbiz
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function human_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'human_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function human_customize_preview_js() {
}
add_action( 'customize_preview_init', 'human_customize_preview_js' );

add_action( 'customize_register', 'my_customize_register' );

function my_customize_register($wp_customize) {
         //include_once get_template_directory().'/inc/scrum/classes/tinymce.class.php';
}
<?php

/**
 * @package   	human
 * @subpackage 	functions
 * @since     	1.0
 * @author    	Sergei Pavlov <itpal24@gmail.com>
 * @copyright 	Copyright (c) 2015, Humancamp Ltd.
 * @link      	https://human.camp
 * @license   	PHP Files: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *               Media, JS & CSS files https://human.camp/licence
 */
/**
 * Human scripts and defaults
 */
include_once get_template_directory () . '/inc/camp/kiss.php';

function human_get_posts ( $attr ) {

            global $HUMAN_POSTS;
            $ids = $attr;
            return $HUMAN_POSTS->human_get_posts ( $ids );
}

/**
 * HUMAN functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package HUMAN
 */
if ( ! function_exists ( 'human_setup' ) ) :

            /**
             * Sets up theme defaults and registers support for various WordPress features.
             *
             * Note that this function is hooked into the after_setup_theme hook, which
             * runs before the init hook. The init hook is too late for some features, such
             * as indicating support for post thumbnails.
             */
            function human_setup () {
                        /*
                         * Make theme available for translation.
                         * Translations can be filed in the /languages/ directory.
                         * If you're building a theme based on human, use a find and replace
                         * to change 'human' to the name of your theme in all the template files.
                         */
                        load_theme_textdomain ( 'human', get_template_directory () . '/languages' );

                        // Add default posts and comments RSS feed links to head.
                        add_theme_support ( 'automatic-feed-links' );

                        /*
                         * Let WordPress manage the document title.
                         * By adding theme support, we declare that this theme does not use a
                         * hard-coded <title> tag in the document head, and expect WordPress to
                         * provide it for us.
                         */
                        add_theme_support ( 'title-tag' );

                        /*
                         * Enable support for Post Thumbnails on posts and pages.
                         *
                         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
                         */
                        add_theme_support ( 'post-thumbnails' );

                        // This theme uses wp_nav_menu() in one location.
                        register_nav_menus ( array (
                                    'primary' => esc_html__ ( 'Primary Menu', 'human' ),
                        ) );

                        /*
                         * Switch default core markup for search form, comment form, and comments
                         * to output valid HTML5.
                         */
                        add_theme_support ( 'html5', array (
                                    'search-form',
                                    'comment-form',
                                    'comment-list',
                                    'gallery',
                                    'caption',
                        ) );

                        /*
                         * Enable support for Post Formats.
                         * See https://developer.wordpress.org/themes/functionality/post-formats/
                         */
                        add_theme_support ( 'post-formats', array (
                                    'aside',
                                    'image',
                                    'video',
                                    'quote',
                                    'link',
                        ) );

                        // Set up the WordPress core custom background feature.
                        add_theme_support ( 'custom-background', apply_filters ( 'human_custom_background_args', array (
                                    'default-color' => 'ffffff',
                                    'default-image' => '',
                        ) ) );
            }

endif; // human_setup
add_action ( 'after_setup_theme', 'human_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function human_content_width () {

            $GLOBALS[ 'content_width' ] = apply_filters ( 'human_content_width', 1024 );
}

add_action ( 'after_setup_theme', 'human_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
/**
 * Custom template tags for this theme.
 */
require get_template_directory () . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory () . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory () . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory () . '/inc/jetpack.php';



<?php

$cpts = [ ];
if ( get_option ( 'human_custom_post_types' ) && ! empty ( get_option ( 'human_custom_post_types' ) ) ) {
            $cpts = explode ( ',', get_option ( 'human_custom_post_types' ) );
}

add_action ( 'admin_menu', 'human_custom_post_parent' );

function human_custom_post_parent () {
            global $cpts;
            $i = 1;
            foreach ( $cpts as $cpt ) {

                        $clean_name = ucwords ( str_replace ( '_', ' ', $cpt ) );
                        $i ++;
                        add_menu_page ( $clean_name, $clean_name, "manage_options", "edit.php?post_type=" . $cpt, "", "", 1.786568 . $cpt );
            }
}

function human_bar_links () {
            global $wp_admin_bar;
            global $post;
            if ( ! is_super_admin () || ! is_admin_bar_showing () ) {
                        return;
            }
            global $cpts;
            $c_posts = array_unique ( array_merge ( array (
                        'human_templates',
                        'human_loops',
                        'human_forms',
                        'human_widgets' ), $cpts ) );
            // print_r($c_posts);
            // print_r($post->post_type);
            if ( $post ) {
                        if ( in_array ( $post->post_type, $c_posts ) ) {
                                    if ( is_single () ) {
                                                $wp_admin_bar->add_menu ( array (
                                                            'id' => 'edit',
                                                            'parent' => false,
                                                            'title' => __ ( 'Edit ', 'human' ),
                                                            'href' => get_edit_post_link ( $post->id )
                                                ) );
                                    }
                        }
            }
}

add_action ( 'wp_before_admin_bar_render', 'human_bar_links' );

add_action ( 'after_switch_theme', 'human_setup_options' );

function human_switch_theme_options () {
            human_setup_options ( $import = 0 );
}

function human_setup_options ( $import = 0 ) {

            global $wpdb, $cpts;
            update_option ( 'revslider-valid-notice', 'false' );
            $roles = get_option ( 'wp_user_roles' );

            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types' ] = 'custom';
            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/post' ] = 1;
            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/page' ] = 1;
            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/human_templates' ] = 1;
            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/human_loops' ] = 1;
            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/human_forms' ] = 1;
            $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/human_widgets' ] = 1;
            if ( isset ( $import ) && strpos ( $import, 'human' ) > 0 ) {

                        $path = $import . '/';
            }
            else {

                        $path = HUMAN_FRIENDS_PATH . '/templates/f-face/';
            }

            $human_forms_path = $path . 'human-forms';
            $human_loops_path = $path . 'human-loops';
            $human_widgets_path = $path . 'human-widgets';
            $human_templates_path = $path . 'human-templates';
            $page_path = $path . 'page';
            foreach ( $cpts as $k => $c ) {

                        $roles[ 'administrator' ][ 'capabilities' ][ 'vc_access_rules_post_types/' . $c ] = 1;
            }
            update_option ( 'wp_user_roles', $roles );
            $human_templates = [ ];
            $human_templates[ 'human_forms' ] = '';
            $human_templates[ 'human_loops' ] = '';
            $human_templates[ 'human_templates' ] = '';
            $human_templates[ 'human_forms' ] = '';
            $human_templates[ 'page' ] = '';
            foreach ( new DirectoryIterator ( $human_forms_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $human_templates[ 'human_forms' ][] = $file->getFilename ();
            }
            foreach ( new DirectoryIterator ( $human_loops_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $human_templates[ 'human_loops' ][] = $file->getFilename ();
            }
            foreach ( new DirectoryIterator ( $human_templates_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $human_templates[ 'human_templates' ][] = $file->getFilename ();
            }
            foreach ( new DirectoryIterator ( $human_widgets_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $human_templates[ 'human_widgets' ][] = $file->getFilename ();
            }
            foreach ( new DirectoryIterator ( $page_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $human_templates[ 'page' ][] = $file->getFilename ();
            }

            function wp_exist_post_by_title ( $title, $post_type ) {
                        global $wpdb, $table_prefix;
                        $sql = "SELECT ID FROM " . $table_prefix . "posts WHERE post_title = '" . $title . "' && post_type = '" . $post_type . "'";
                        $return = $wpdb->get_var ( $sql );

                        if ( ! empty ( $return ) ) {
                                    return $return;
                        }
                        else {
                                    return false;
                        }
            }

            function insert_human_templates ( $template_type, $human_templates, $import = null ) {

//print_r($human_templates);

                        foreach ( $human_templates as $key => $template ) {
                                    $template = str_replace ( array (
                                                '.html',
                                                '_' ), array (
                                                '',
                                                ' ' ), $template );
                                    $template_slug = str_replace ( ' ', '_', $template );
                                    $content_file = HUMAN_BASE_PATH . 'friends/templates/f-face/' . str_replace ( '_', '-', $template_type ) . '/' . $template_slug . '.html';
                                    if ( isset ( $import ) && strpos ( $import, 'human' ) > 0 ) {
                                                $content_file = $import . '/' . str_replace ( '-', '_', $template_type ) . '/' . $template_slug . '.html';
                                    }
                                    $content = file_get_contents ( $content_file );

                                    $human_type = $template_type;

                                    $post = array (
                                                'post_title' => $template,
                                                'post_content' => $content,
                                                'post_status' => 'publish',
                                                'post_author' => 0,
                                                'post_type' => $human_type
                                    );
                                    // usage
                                    $post_exists = wp_exist_post_by_title ( $template, $human_type );
                                    if ( ! $post_exists ) {
                                                wp_insert_post ( $post );
                                    }
                                    else {

                                    }
                        }
            }

            if ( ! empty ( $human_templates ) ) {

                        foreach ( $human_templates as $k => $v ) {
                                    if ( ! empty ( $k ) && ! empty ( $v ) ) {
                                                if ( isset ( $import ) ) {
                                                            insert_human_templates ( $k, $v, $import );
                                                }
                                                else {

                                                            insert_human_templates ( $k, $v );
                                                }
                                    }
                        }
            }
            if ( ! get_option ( 'STYLE-GENS' ) ) {
                        human_reset_css ();
            }
            return true;
}

add_action ( 'init', 'human_register_templates', 11 );

function human_register_templates () {
            register_post_type ( 'human_templates', array (
                        'labels' => array (
                                    'name' => __ ( 'H - Templates' ),
                                    'singular_name' => __ ( 'human_templates' ),
                        ),
                        'public' => true,
                        'exclude_from_search' => true,
                        'has_archive' => false,
                        'rewrite' => array (
                                    'slug' => 'human_templates' ),
                        'show_in_menu' => false,
                        //  'publicly_queryable' => false,
                        )
            );
            register_post_type ( 'human_loops', array (
                        'labels' => array (
                                    'name' => __ ( 'H - Loops' ),
                                    'singular_name' => __ ( 'human_loops' ),
                        ),
                        'public' => true,
                        'exclude_from_search' => true,
                        'has_archive' => false,
                        'rewrite' => array (
                                    'slug' => 'human_loops' ),
                        'show_in_menu' => false,
                        //    'publicly_queryable' => false,
                        )
            );
            register_post_type ( 'human_forms', array (
                        'labels' => array (
                                    'name' => __ ( 'H - Forms' ),
                                    'singular_name' => __ ( 'human_forms' ),
                                    'show_in_menu' => false
                        ),
                        'public' => true,
                        'exclude_from_search' => true,
                        'has_archive' => false,
                        'rewrite' => array (
                                    'slug' => 'human_forms' ),
                        'show_in_menu' => false,
                        //  'publicly_queryable' => false,
                        )
            );
            register_post_type ( 'human_widgets', array (
                        'labels' => array (
                                    'name' => __ ( 'Common Tempaltes' ),
                                    'singular_name' => __ ( 'human_widgets' ),
                                    'show_in_menu' => false
                        ),
                        'public' => true,
                        'publicly_queryable' => false,
                        'exclude_from_search' => true,
                        'rewrite' => array (
                                    'slug' => 'human_widgets' ),
                        'show_in_menu' => false,
                        //    'publicly_queryable' => false,
                        'supports' => array (
                                    'title',
                                    'page-attributes',
                                    'custom-fields',
                                    'editor',
                                    'thumbnail',
                                    'excerpt' )
                        )
            );
}

function human_remove_metabox () {
            remove_meta_box ( 'wpseo_meta', 'human_widgets', 'normal' );
            remove_meta_box ( 'wpseo_meta', 'human_templates', 'normal' );
            remove_meta_box ( 'wpseo_meta', 'human_forms', 'normal' );
            remove_meta_box ( 'wpseo_meta', 'human_loops', 'normal' );
}

//add_action( 'add_meta_boxes', 'human_remove_metabox', 101 );

function human_enqueue_template_scripts ( $hook ) {
            if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
                        return;
            }
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'templates-admin-js', HUMAN_BASE_URL . 'friends/templates/f-character/temper/templates-admin.js', array (
                        'jquery' ) );
}

add_action ( 'admin_enqueue_scripts', 'human_enqueue_template_scripts' );

function human_admin_menus () {
            global $cpts;
            foreach ( $cpts as $k => $c ) {
                        $clean_name = ucwords ( str_replace ( '_', ' ', $c ) );
                        register_post_type ( $c, array (
                                    'labels' => array (
                                                'name' => $clean_name,
                                                'singular_name' => $clean_name,
                                                'show_in_menu' => true,
                                                'add_new' => __ ( 'Add New' ),
                                                'add_new_item' => __ ( 'Add New' ),
                                                'search_items' => $clean_name
                                    ),
                                    'taxonomies' => array (
                                                'category' ),
                                    'public' => true,
                                    'has_archive' => true,
                                    'rewrite' => array (
                                                'slug' => $c ),
                                    'show_in_menu' => false,
                                    'show_ui' => true
                                    )
                        );
                        //add_submenu_page ( "Custom-Posts", $clean_name, $clean_name, 'manage_options', 'edit.php?post_type=' . $c, 0 );
                        add_post_type_support ( $c, array (
                                    'title',
                                    'excerpt',
                                    'author',
                                    'thumbnail',
                                    'excerpt',
                                    'trackbacks',
                                    'custom-fields',
                                    'comments',
                                    'revisions',
                                    'page-attributes',
                                    'post-formats' ) );
            }
            //flush_rewrite_rules();

            add_submenu_page ( HUMAN_NAME . "-settings", "Human Templates", "Human Templates", 'manage_options', 'edit.php?post_type=human_templates', 0 );
            add_submenu_page ( HUMAN_NAME . "-settings", "Common Templates", "Human Widgets", 'manage_options', 'edit.php?post_type=human_widgets', 0 );

            add_submenu_page ( HUMAN_NAME . "-settings", "Human Forms", "Human Forms", 'manage_options', 'edit.php?post_type=human_forms', 0 );
            add_submenu_page ( HUMAN_NAME . "-settings", "Human Loops", "Human Loops", 'manage_options', 'edit.php?post_type=human_loops', 0 );
}

if ( function_exists ( 'add_submenu_page' ) === true ) {
            add_action ( 'init', 'human_admin_menus', 0 );
}

function human_template ( $attr, $content = null ) {
            //print_r($attr);
            global $wpdb;
            $name = $attr[ 'name' ];
            $res = '';
            $end = '';

            $current_post_id = get_the_ID ();
            if ( isset ( $attr[ 'type' ] ) ) {
                        $type = $attr[ 'type' ];
            }
            else {
                        $type = 'human_templates';
            }
            if ( in_array ( 'comment', explode ( ' ', strtolower ( $name ) ) ) && comments_open ( $current_post_id ) !== true ) {
                        return;
            }
            //   print_r ( explode ( '@', strtolower ( $name ) ) );
            if ( isset ( $attr[ 'hide_for_users' ] ) && is_user_logged_in () === true ) {
                        return " ";
            }
            if ( isset ( $attr[ 'show_for_users' ] ) && is_user_logged_in () !== true ) {
                        return " ";
            }
            if ( isset ( $attr[ 'hide_for_mobiles' ] ) && wp_is_mobile () === true ) {
                        return " ";
            }
            if ( isset ( $attr[ 'show_for_mobiles' ] ) && wp_is_mobile () === false ) {
                        return " ";
            }
            if ( strpos ( $name, 'Content' ) !== false ) {
                        // $schema = get_post_meta ( $id, 'human_page_schema' );
                        //  $res .= '<article class="article-content" itemscope="" itemtype="http://schema.org/'.$schema.'">';
                        // $end .= '</article>';
            }
            global $table_prefix;
            $result = $wpdb->get_var ( 'SELECT post_content FROM ' . $table_prefix . 'posts
                                                              WHERE post_type = "' . $type . '"
                                                              AND post_status ="publish" AND post_title = "' . $name . '"' );
            // $post = get_post_field($name, 'OBJECT',$type);
            //$content = apply_filters('the_content', );
            if ( empty ( $result ) ) {
                        return null;
            }
            else {
                        // print_r($result);
                        //  print_r ( new_post_human_meta_filter ( get_post_field ( 'post_content' ), $result, 'x' ) . $result . '<hr>' );

                        $content = do_shortcode ( $result );

                        return $content; // do_shortcode($res.$content.$end);
            }
}

add_shortcode ( 'human_template', 'human_template' );

add_shortcode ( 'human_widget', 'human_widget' );

function human_widget ( $attr ) {
            $res = '[human_template name="' . $attr[ 'name' ] . '" type="human_widgets"]';
            $ct = do_shortcode ( $res );
            if ( $ct ) {
                        $class = strtolower ( str_replace ( ' ', '-', $attr[ 'name' ] ) );
                        return '<div class="human-' . $class . '">' . $ct . '</div>';
            }
}

function human_template_names ( $type = 'human_templates', $like = null ) {
            global $wpdb, $table_prefix;
            if ( isset ( $like ) ) {
                        $search_query = 'SELECT post_title FROM ' . $table_prefix . 'posts
                              WHERE post_type = "' . $type . '"  AND post_status="publish"
                         AND post_title LIKE %s';

                        $like = '%' . $like . '%';
                        return $wpdb->get_results ( $wpdb->prepare ( $search_query, $like ), ARRAY_A );
            }
            return $wpdb->get_results ( 'SELECT post_title FROM ' . $table_prefix . 'posts
                                                              WHERE post_type = "' . $type . '"
                                                              AND post_status ="publish"', ARRAY_A );
}

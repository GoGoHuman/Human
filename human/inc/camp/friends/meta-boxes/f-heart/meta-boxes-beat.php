<?php

add_shortcode ( 'human_templates_content_meta', 'human_templates_content_meta' );

function human_templates_content_meta ( $attr, $content = null ) {
            if ( is_array ( get_post_meta ( $attr[ 'meta_post_id' ], 'human_meta_boxes' ) ) && ! isset ( get_post_meta ( $attr[ 'meta_post_id' ], 'human_meta_boxes' )[ 0 ][ $attr[ 'meta_id' ] ][ 'value' ] ) ) {
                        return '';
            }
            if ( ! isset ( get_post_meta ( $attr[ 'meta_post_id' ], 'human_meta_boxes' )[ 0 ][ $attr[ 'meta_id' ] ][ 'value' ] ) ) {
                        return;
            }
            global $post;
            $value = get_post_meta ( get_the_ID (), $attr[ 'meta_id' ], true );

            $wrap = '<p>';
            $end_wrap = '</p>';
            if ( isset ( $attr[ 'meta_meta_wrapper' ] ) ) {
                        $wrap = '<' . $attr[ 'meta_meta_wrapper' ] . '>';
                        $end_wrap = '</' . $attr[ 'meta_meta_wrapper' ] . '>';
            }
            if ( $attr[ 'meta_type' ] !== 'image' ) {
                        $values = '<div class="human-meta-content" meta-post-id="' . $attr[ 'meta_id' ] . '" meta-post-type=' . $attr[ 'meta_type' ] . '>' . $wrap . $value . $end_wrap . '</div>';
            }
            else {
                        $values = '<div class="human-image" meta-post-id="' . $attr[ 'meta_id' ] . '" meta-post-type=' . $attr[ 'meta_type' ] . '><img src="' . $value . '" alt="" class="meta-image-' . $attr[ 'meta_id' ] . '"></div>';
            }
            $res = ' ' . do_shortcode ( $values );
            return trim ( $res );
}

add_shortcode ( 'human_content_meta', 'human_content_meta' );

function human_content_meta ( $attr ) {
            if ( isset ( $attr[ 'wck' ] ) ) {
                        if ( isset ( get_post_meta ( get_the_ID (), $attr[ 'wck' ], true )[ 0 ] ) ) {
                                    $testimonial_entry = get_post_meta ( get_the_ID (), $attr[ 'wck' ], true )[ 0 ];
                                    //  print_r ( $attr[ 'meta_id' ] );
                                    if ( ! empty ( $testimonial_entry[ $attr[ 'meta_id' ] ] ) ) {
                                                return $testimonial_entry[ $attr[ 'meta_id' ] ];
                                    }
                        }
            }
            else {
                        return get_post_meta ( get_the_ID (), $attr[ 'meta_id' ], true )[ 0 ];
            }
}

if ( is_admin () ) {

            add_action ( 'vc_before_init', 'human_meta_integrate' );

            function human_meta_integrate () {
                        vc_map ( array (
                                    "name" => __ ( "Human Content Meta", "human" ),
                                    "base" => "human_meta_loops",
                                    "class" => "human_meta_loops",
                                    "category" => __ ( "Human Loops", "human" ),
                                    "icon" => human_icon (),
                                    "params" => array (
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human_meta_loops",
                                                            "heading" => __ ( "Meta ID", "human" ),
                                                            "param_name" => "meta_id",
                                                            "value" => "",
                                                            "description" => __ ( "e.g. 'description'", "human" )
                                                ) )
                        ) );
                        vc_map ( array (
                                    "name" => __ ( "Human Content Meta", "human" ),
                                    "base" => "human_content_meta",
                                    "class" => "human_content_meta",
                                    "category" => __ ( "Human Content", "human" ),
                                    "icon" => human_icon (),
                                    "params" => array (
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human_meta_post",
                                                            "heading" => __ ( "Meta ID", "human" ),
                                                            "param_name" => "meta_id",
                                                            "value" => "",
                                                            "description" => __ ( "e.g. 'descripiton'", "human" )
                                                ),
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human_meta_post",
                                                            "heading" => __ ( "WCK Meta Group Name", "human" ),
                                                            "param_name" => "wck",
                                                            "value" => "",
                                                            "description" => __ ( "e.g. 'postdescription'", "human" )
                                                ) )
                        ) );
                        vc_map ( array (
                                    "name" => __ ( "Human Templates Content Meta", "human" ),
                                    "base" => "human_templates_content_meta",
                                    "class" => "human_templates_content_meta",
                                    "category" => __ ( "Human Content", "human" ),
                                    "icon" => human_icon (),
                                    "params" => array (
                                                array (
                                                            "type" => "dropdown",
                                                            "holder" => "div",
                                                            "class" => "human_meta_type",
                                                            "heading" => __ ( "Meta Box Type", "human" ),
                                                            "param_name" => "meta_type",
                                                            "value" => array (
                                                                        __ ( "--- Select ---", "human" ) => "",
                                                                        __ ( "Text Field", "human" ) => "text",
                                                                        __ ( "Editor", "human" ) => "wysiwyg",
                                                                        __ ( "Image", "human" ) => "image"
                                                            ),
                                                            "description" => __ ( "Choose Meta Box Content Type", "human" )
                                                ),
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human_meta_id",
                                                            "heading" => __ ( "MetaBox unique ID", "human" ),
                                                            "param_name" => "meta_id",
                                                            "value" => "",
                                                            "description" => __ ( "e.g. 'logo-top' -> must be unique per this page", "human" )
                                                ),
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human_meta_title",
                                                            "heading" => __ ( "MetaBox title", "human" ),
                                                            "param_name" => "meta_title",
                                                            "value" => "",
                                                            "description" => __ ( "e.g. 'Logo'", "human" )
                                                ),
                                                array (
                                                            "type" => "textarea",
                                                            "holder" => "div",
                                                            "class" => "human_meta_desc",
                                                            "heading" => __ ( "MetaBox Description", "human" ),
                                                            "param_name" => "meta_desc",
                                                            "value" => "",
                                                            "description" => __ ( "Meta field description, shown before field in custom field panel", "human" )
                                                ),
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human_meta_wrapper",
                                                            "heading" => __ ( "Wrapping tag for heading fields e.g. h1,h2,h3", "human" ),
                                                            "param_name" => "meta_meta_wrapper",
                                                            "value" => "p",
                                                            "description" => __ ( "This field is optional, should be used for heading tags only", "human" )
                                                ),
                                                array (
                                                            "type" => "textfield",
                                                            "holder" => "div",
                                                            "class" => "human-hidden-meta",
                                                            "heading" => __ ( "Post ID", "human" ),
                                                            "param_name" => "meta_post_id",
                                                            "value" => __ ( "1", "human" ),
                                                            "description" => __ ( "Don't edit this ! ", "human" )
                                                ),
                                    )
                        ) );
            }

            function human_get_defaults ( $post ) {
                        $post_details_array = array (
                                    'post_excerpt',
                                    'post_status'
                        );
                        $defaults = [ ];
                        foreach ( $post_details_array as $v ) {
                                    $defaults[ $v ] = $post->$v;
                        }

                        //  $defaults[  'guid' ] = get_permalink ( $post->ID );
                        return $defaults;
            }

            if ( isset ( $_GET[ 'post_type' ] ) && is_admin () ) {
                        if ( $_GET[ 'post_type' ] === 'human_templates' || $_GET[ 'post_type' ] === 'human_widgets' || $_GET[ 'post_type' ] === 'human_forms' || $_GET[ 'post_type' ] === 'human_loops' ) {
                                    add_action ( 'admin_enqueue_scripts', 'human_meta_boxes_scripts' );
                        }
            }

            function human_meta_boxes_scripts () {

                        wp_enqueue_script ( 'meta-boxes-inteface', HUMAN_BASE_URL . '/friends/meta-boxes/f-character/temper/meta-boxes-interface.js', array (
                                    'jquery',
                                    'human-admin-script',
                                    'quicktags' ) );
                        wp_enqueue_script ( 'load-post-boxes', HUMAN_BASE_URL . '/friends/meta-boxes/f-character/temper/load-post-boxes.js', array (
                                    'meta-boxes-inteface' ) );

                        wp_enqueue_media ();
            }

            function human_get_wp_metas ( $post_id ) {
                        $post_thumbnail_id = get_post_thumbnail_id ( $post_id );
                        $post_thumbnail_url = wp_get_attachment_url ( $post_thumbnail_id );
                        return array (
                                    'meta_title' => get_post_meta ( $post_id, '_yoast_wpseo_title' ),
                                    'meta_description' => get_post_meta ( $post_id, '_yoast_wpseo_metadesc' ),
                                    'featured_image' => $post_thumbnail_url
                        );
            }

            function human_populate_frame ( $post_id ) {

                        $post = get_post ( $post_id );
                        $meta_value = get_post_meta ( $post_id, 'human_meta_boxes' );
                        if ( isset ( $meta_value[ 0 ][ 'x' ] ) ) {
                                    foreach ( $meta_value[ 0 ] as $k => $v ) {
                                                if ( $v[ 'type' ] == 'wysiwyg' ) {
                                                            $editor_id = $post_id . '_post_id-' . $k;
                                                            $meta_value[ 0 ][ $k ] = 'editor;';
                                                            $meta_value[ 0 ][ 'editor' ][ $k ] = 'editor';
                                                            wp_editor ( $v[ 'value' ], $editor_id, array (
                                                                        'textarea_name' => $editor_id,
                                                                        'media_buttons' => true,
                                                                        'tinymce' => true,
                                                                        'textarea_rows' => 10,
                                                                        'wpautop' => false ) );
                                                }
                                                else {

                                                            $meta_value[ 0 ][ 'no-wysiwyg' ][ $k ] = $v[ 'type' ];
                                                }
                                    }
                        }
                        else {

                        }
                        $defaults = human_get_defaults ( $post );
                        $human_get_metas = [ ];
                        $human_get_metas[ $post->post_type ][] = array (
                                    'title' => $post->post_title,
                                    'post_id' => $post_id,
                                    'metas' => $meta_value,
                                    'defaults' => $defaults,
                                    'wp_metas' => human_get_wp_metas ( $post_id ) );
                        return $human_get_metas;
            }

            function human_get_links ( $post_types = null ) {
                        global $wpdb, $table_prefix;
                        $metas = $wpdb->get_results ( 'SELECT post_id, meta_key, meta_value FROM ' . $table_prefix . 'postmeta WHERE meta_key = "human_meta_boxes"', ARRAY_A );
                        $human_get_metas = [ ];
                        if ( ! empty ( $metas ) ) {
                                    foreach ( $metas as $meta ) {


                                                $pid = $meta[ 'post_id' ];
                                                $template = get_post ( $pid );
                                                if ( isset ( $post_types ) ) {
                                                            $post_type = $post_types;
                                                }
                                                elseif ( $template->post_title === 'Page' ) {
                                                            $post_type = 'page';
                                                }
                                                elseif ( $template->post_title === 'Single Post' ) {
                                                            $post_type = 'post';
                                                }
                                                else {
                                                            $post_type = strtolower ( str_replace ( 'Single ', '', $template->post_title ) );
                                                }

                                                $args = $wpdb->get_results ( 'SELECT ID, post_title FROM ' . $table_prefix . 'posts WHERE post_type = "' . $post_type . '"', ARRAY_A );


                                                foreach ( $args as $post ) {
                                                            if ( $post[ 'post_title' ] !== 'Auto Draft' ) {
                                                                        $human_get_metas[ $post_type ][] = array (
                                                                                    'title' => $post[ 'post_title' ],
                                                                                    'post_id' => $post[ 'ID' ]
                                                                        );
                                                            }
                                                }
                                    }
                                    // print_r ( $human_get_metas );
                                    //  echo '<hr>';
                                    return $human_get_metas;
                        }
                        else {

                        }
            }

//print_r ( human_get_links () );

            /*
             *  Get all human_metas and associated posts data
             *  $param human_get_metas
             * $return human_get_metas
             */

            function human_get_metas ( $post_id = null ) {
                        global $wpdb, $table_prefix;
                        $human_get_metas = [ ];
                        if ( isset ( $post_id ) && get_post_meta ( $post_id, 'human_meta_boxes' ) !== null ) {
                                    return get_post_meta ( $post_id, 'human_meta_boxes' );
                        }
                        elseif ( isset ( $post_id ) && get_post_meta ( $post_id, 'human_meta_boxes' ) == null ) {
                                    $human_editible_links[ 0 ] = array (
                                                'metas' => 'no metas' );
                        }
                        else {
                                    $human_editible_links = human_get_links ();
                        }
                        return $human_editible_links;
            }

            function human_new_meta_post ( $post_id = 2, $post_type = 'page' ) {
                        if ( isset ( $_POST[ 'post_id' ] ) ) {
                                    if ( is_numeric ( $_POST[ 'post_id' ] ) ) {

                                                $post_id = $_POST[ 'post_id' ];
                                    }
                        }


                        $new_post = human_duplicate_post_as_draft ( $post_id );
                        $new_post_id = $new_post[ 'post_id' ];
                        $new_content = str_replace ( 'meta_post_id = "' . $post_id . '"', 'meta_post_id = "' . $new_post_id . '"', $new_post[ 'post_content' ] );

                        $new_post_query = array (
                                    'ID' => $new_post_id,
                                    'post_content' => $new_content
                        );

// Update the post into the database
                        wp_update_post ( $new_post_query );
                        $post = get_post ( $new_post_id );
                        $res[ $post->post_type ] = array (
                                    'post_type' => $post->post_type,
                                    'title' => $post->post_title,
                                    'post_id' => $new_post_id,
                                    'metas' => human_get_metas ( $new_post_id ),
                                    'defaults' => human_get_defaults ( $post ) );
                        return $res;
            }

            function humanGetImageIdByUrl ( $url ) {
                        global $wpdb;
                        $image = $wpdb->get_col ( $wpdb->prepare ( "SELECT ID FROM $wpdb->posts WHERE guid = '%s';
", $url ) );

                        if ( ! empty ( $image ) ) {
                                    return $image[ 0 ];
                        }

                        return $url;
            }

            function human_insert_attachment ( $img, $parent_post_id ) {


                        $filename = str_replace ( '\\', '/', ABSPATH . 'wp-content' . explode ( 'wp-content', $img )[ 1 ] );

                        $filetype = wp_check_filetype ( basename ( $filename ), null );



                        $attach_id = humanGetImageIdByUrl ( $img );

                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                        $attach_data = wp_generate_attachment_metadata ( $attach_id, $filename );

                        wp_update_attachment_metadata ( $parent_post_id, $attach_data );

                        set_post_thumbnail ( $parent_post_id, $attach_id );
                        return $attach_id;
            }

            add_action ( 'wp_ajax_human_new_post', 'human_new_post' );

            function human_new_post () {

                        check_ajax_referer ( 'ajax-human-nonce', 'nonce' );

                        if ( true ) {
                                    if ( isset ( $_POST[ 'old_post' ] ) && isset ( $_POST[ 'post_id' ] ) && is_numeric ( $_POST[ 'post_id' ] ) ) {
                                                $res = human_populate_frame ( $_POST[ 'post_id' ] );
                                    }
                                    else {
                                                $res = human_new_meta_post ();
                                    }
                                    wp_send_json_success ( $res );
                        }
                        else {
                                    $r = 'Wrong nonce!';
                        }
                        wp_send_json_error ( $r );
            }

            require_once( 'meta-box-generator.php' );

            add_action ( 'wp_ajax_meta_boxes_ajax', 'meta_boxes_ajax' );

//add_action ( 'wp_ajax_nopriv_meta_boxes_ajax', 'meta_boxes_ajax' );

            function meta_boxes_ajax () {

                        check_ajax_referer ( 'ajax-human-nonce', 'nonce' );

                        if ( true ) {
                                    $r = array (
                                                'Unknown Error',
                                                $_POST );
                                    if ( isset ( $_POST[ 'type' ] ) && $_POST[ 'type' ] === 'populate_frame' && isset ( $_POST[ 'post_id' ] ) ) {


                                                $post_details = human_get_metas ();
                                                if ( ! empty ( $post_details ) ) {

                                                            wp_send_json_success ( $post_details );
                                                }
                                                else {
                                                            $r = $post_details . 'Meta-boxes do not exist';
                                                }
                                    }
                                    elseif ( isset ( $_POST[ 'save_data' ] ) ) {
                                                $old_boxes = get_post_meta ( $_POST[ 'post_id' ], "human_meta_boxes" );
                                                if ( isset ( $_POST[ 'data' ] ) ) {
                                                            $boxes = $_POST[ 'data' ];
                                                            $metas = [ ];
                                                            $old_value = [ ];
                                                            foreach ( $boxes as $key => $value ) {
                                                                        if ( isset ( $value[ 'id' ] ) ) {
                                                                                    if ( ! isset ( $value[ 'value' ] ) ) {
                                                                                                if ( isset ( $old_boxes[ 0 ][ $value[ 'id' ] ][ 'value' ] ) ) {
                                                                                                            $value[ 'value' ] = $old_boxes[ 0 ][ $value[ 'id' ] ][ 'value' ];
                                                                                                            $old_value[] = $value[ 'value' ];
                                                                                                }
                                                                                                else {
                                                                                                            $value[ 'value' ] = '';
                                                                                                }
                                                                                    }
                                                                                    $metas[ $value[ 'id' ] ] = $value;
                                                                        }
                                                                        else {
//$metas = $value[  'id' ];
                                                                        }
                                                            }
                                                            update_post_meta ( $_POST[ 'post_id' ], 'human_meta_boxes', $metas );
                                                            if ( isset ( $_POST[ 'wp_metas' ] ) ) {
                                                                        $wp_metas = [ ];
                                                                        $wp_metas = $_POST[ 'wp_metas' ];
                                                                        $f_image = $featured_image = [ ];
                                                                        foreach ( $wp_metas as $key => $wp_meta ) {
                                                                                    $meta_id = $wp_meta[ 'id' ];
                                                                                    if ( $meta_id === 'meta_title' ) {
                                                                                                $meta_id = '_yoast_wpseo_title';
                                                                                    }
                                                                                    elseif ( $meta_id === 'meta_description' ) {
                                                                                                $meta_id = '_yoast_wpseo_metadesc';
                                                                                    }
                                                                                    elseif ( $meta_id === 'featured_image' ) {

                                                                                                $f_image[ 1 ] = 'is_image';
                                                                                    }
                                                                                    if ( isset ( $f_image[ 1 ] ) ) {
                                                                                                $featured_image[] = human_insert_attachment ( $wp_meta[ 'val' ], $_POST[ 'post_id' ] );
                                                                                    }
                                                                                    else {
                                                                                                update_post_meta ( $_POST[ 'post_id' ], $meta_id, $wp_meta[ 'val' ] );
                                                                                    }
                                                                        }
                                                            }

                                                            if ( isset ( $_POST[ 'wp_defaults' ] ) ) {
                                                                        $wp_defaults = [ ];
                                                                        $wp_defaults = $_POST[ 'wp_defaults' ];
                                                                        $post_arg = [ ];
                                                                        foreach ( $wp_defaults as $key => $wp_default ) {
                                                                                    if ( ! empty ( $wp_default[ 'id' ] ) && ! empty ( $wp_default[ 'val' ] ) ) {
                                                                                                $post_arg[ $wp_default[ 'id' ] ] = $wp_default[ 'val' ];
                                                                                    }
                                                                        }
                                                                        $post_arg[ 'ID' ] = $_POST[ 'post_id' ];
                                                                        if ( isset ( $post_arg[ 'post_title' ] ) ) {
                                                                                    $nguid = strtolower ( str_replace ( ' ', '-', $post_arg[ 'post_title' ] ) );
                                                                                    $post_arg[ 'post_name' ] = $nguid;
                                                                        }
                                                                        wp_update_post ( $post_arg );
                                                            }

                                                            wp_send_json_success ( array (
                                                                        0 => get_post_meta ( $_POST[ 'post_id' ], 'human_meta_boxes' ),
                                                                        1 => $featured_image,
                                                                        3 => $post_arg ) );
                                                }
                                                else {
                                                            delete_post_meta ( $_POST[ 'post_id' ], 'human_meta_boxes' );
                                                            $r = 'No Meta Data submitted';
                                                }
                                    }
                                    else {
                                                $r = array (
                                                            'Human meta boxes is not saved',
                                                            $_POST );
                                    }
                        }
                        else {
                                    $r = 'wrong nounce';
                        }

                        wp_send_json_error ( $r );
            }

}
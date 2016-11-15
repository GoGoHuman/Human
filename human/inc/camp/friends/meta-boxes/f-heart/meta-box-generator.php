<?php

function human_meta_fields ( $post_id ) {
            $post_type = get_post_type ( $post_id );
            if ( $post_type === 'page' ) {
                        $human_template_title = 'Page';
            }
            else {
                        $human_template_title = 'Single ' . str_replace ( '_', ' ', ucwords ( $post_type ) );
            }

            global $wpdb, $table_prefix, $fields;
            $meta_id = $wpdb->get_var ( "SELECT ID FROM " . $table_prefix . "posts WHERE post_title='$human_template_title' AND post_type = 'human_templates' AND post_status='publish'" );

            if ( isset ( get_post_meta ( $meta_id, 'human_meta_boxes' )[ 0 ] ) ) {
                        $human_meta_boxes = get_post_meta ( $meta_id, 'human_meta_boxes' )[ 0 ];
                        $fields = [ ];
                        foreach ( $human_meta_boxes as $key => $metas ) {
                                    $fields[] = array (
                                                'id' => $key,
                                                'name' => $metas[ 'title' ],
                                                'type' => $metas[ 'type' ],
                                                'desc' => $metas[ 'desc' ],
                                                'value' => get_post_meta ( $post_id, $key )
                                    );
                        }
                        return $fields;
            }
}

function human_hide_editor () {
            $post_type = get_post_type ( $_GET[ 'post' ] );
            remove_post_type_support ( $post_type, 'editor' );
}

if ( isset ( $_GET[ 'post' ] ) && is_numeric ( $_GET[ 'post' ] ) && get_post_type ( $_GET[ 'post' ] ) !== null && ! in_array ( get_post_type ( $_GET[ 'post' ] ), array (
                        'human_templates',
                        'human_widgets',
                        'human_forms',
                        'human_loops' ) ) ) {
            if ( ! empty ( human_meta_fields ( $_GET[ 'post' ] ) ) ) {

                        add_action ( 'admin_init', 'human_hide_editor' );

                        require_once( 'Custom-Meta-Boxes/custom-meta-boxes.php' );

                        add_filter ( 'cmb_meta_boxes', 'cmb_human_metaboxes' );

                        function cmb_human_metaboxes ( array $meta_boxes ) {
                                    if ( isset ( $_GET[ 'post' ] ) ) {
                                                $post_type = get_post_type ( $_GET[ 'post' ] );
                                                $fields = human_meta_fields ( $_GET[ 'post' ] );

                                                $meta_boxes[] = array (
                                                            'title' => 'Human Content Meta',
                                                            'pages' => $post_type,
                                                            'context' => 'normal',
                                                            'priority' => 'high',
                                                            'fields' => $fields, // an array of fields - see individual field
                                                            'desc' => 'Human Content Meta fields',
                                                );

                                                return $meta_boxes;
                                    }
                        }

            }
}

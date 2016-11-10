<?php

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */

function human_duplicate_post_as_draft ( $ajax = null ) {

            global $wpdb;
            if ( ! ( isset ( $_GET[ 'post' ] ) || isset ( $_POST[ 'post' ] ) || isset ( $ajax ) || ( isset ( $_REQUEST[ 'action' ] ) && 'human_duplicate_post_as_draft' == $_REQUEST[ 'action' ]) ) ) {
                        wp_die ( 'No post to duplicate has been supplied!' );
            }


            /*
             * get the original post id
             */
            if ( isset ( $ajax ) ) {
                        $post_id = $ajax;
                        $post_ajax = true;
            }
            else {
                        $post_id = (isset ( $_GET[ 'post' ] ) ? absint ( $_GET[ 'post' ] ) : absint ( $_POST[ 'post' ] ));
            }
            /*
             * and all the original post data then
             */
            $post = get_post ( $post_id );

            /*
             * if you don't want current user to be the new post author,
             * then change next couple of lines to this: $new_post_author = $post->post_author;
             */
            $current_user = wp_get_current_user ();
            $new_post_author = $current_user->ID;

            /*
             * if post data exists, create the post duplicate
             */
            if ( isset ( $post ) && $post != null ) {

                        /*
                         * new post data array
                         */
                        $post_title = $post->post_title . ' - copy';
                        $args = array (
                                    'comment_status' => $post->comment_status,
                                    'ping_status' => $post->ping_status,
                                    'post_author' => $new_post_author,
                                    'post_content' => $post->post_content,
                                    'post_excerpt' => $post->post_excerpt,
                                    'post_name' => $post->post_name,
                                    'post_parent' => $post->post_parent,
                                    'post_password' => $post->post_password,
                                    'post_status' => 'draft',
                                    'post_title' => $post_title,
                                    'post_type' => $post->post_type,
                                    'to_ping' => $post->to_ping,
                                    'menu_order' => $post->menu_order
                        );

                        /*
                         * insert the post by wp_insert_post() function
                         */
                        $new_post_id = wp_insert_post ( $args );

                        /*
                         * get all current post terms ad set them to the new post draft
                         */
                        $taxonomies = get_object_taxonomies ( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
                        foreach ( $taxonomies as $taxonomy ) {
                                    $post_terms = wp_get_object_terms ( $post_id, $taxonomy, array (
                                                'fields' => 'slugs' ) );
                                    wp_set_object_terms ( $new_post_id, $post_terms, $taxonomy, false );
                        }

                        /*
                         * duplicate all post meta just in two SQL queries
                         */
                        $post_meta_infos = $wpdb->get_results ( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
                        if ( count ( $post_meta_infos ) != 0 ) {
                                    $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                                    foreach ( $post_meta_infos as $meta_info ) {
                                                $meta_key = $meta_info->meta_key;
                                                $meta_value = addslashes ( $meta_info->meta_value );
                                                $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                                    }
                                    $sql_query.= implode ( " UNION ALL ", $sql_query_sel );
                                    $wpdb->query ( $sql_query );
                        }
                        /*
                         *  if we use this function through ajax
                         */
                        if ( isset ( $ajax ) ) {
                                    return array (
                                                'post_id' => $new_post_id,
                                                'post_content' => $post->post_content,
                                                'post_title' => $post->post_title );
                        }
                        else {
                                    /*
                                     * finally, redirect to the edit post screen for the new draft
                                     */
                                    wp_redirect ( admin_url ( 'edit.php?post_type=' . $_GET[ 'post_type' ] ) );
                                    exit;
                        }
            }
            else {
                        wp_die ( 'Post creation failed, could not find original post: ' . $post_id );
            }
}

add_action ( 'admin_action_human_duplicate_post_as_draft', 'human_duplicate_post_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */

function human_duplicate_post_link ( $actions, $post ) {

            if ( current_user_can ( 'edit_posts' ) ) {
                        $post_type = 'post';
                        if ( isset ( $_GET[ 'post_type' ] ) ) {
                                    $post_type = $_GET[ 'post_type' ];
                        }
                        $actions[ 'duplicate' ] = '<a href="?post_type=' . $post_type . '&action=human_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
            }
            return $actions;
}

add_filter ( 'post_row_actions', 'human_duplicate_post_link', 10, 2 );
add_filter ( 'page_row_actions', 'human_duplicate_post_link', 10, 2 );



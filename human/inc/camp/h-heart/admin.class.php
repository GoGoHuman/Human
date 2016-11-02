<?php
/**
 * @package: HUMAN
 * @subpackage: HUMAN_ADMIN
 * @author: Sergei Pavlov <itpal24@gmail.com>
 * @param: HUMAN_ADMIN
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly



$HUMAN_ADMIN_BRAIN = new HUMAN_ADMIN_BRAIN();
$HUMAN_ADMIN_BRAIN->init ();

class HUMAN_ADMIN_BRAIN {

            public function init () {

                        $this->post ();
                        add_action ( 'save_post', array (
                                    $this,
                                    'human_save_meta_box_data' ) );

                        if ( human_is_edit_page () ) {
                                    add_action ( 'add_meta_boxes', array (
                                                $this,
                                                'add_meta_box' ), 1, 1 );
                        }
            }

            public function human_page_attr ( $post ) {
                        global $wpdb;

                        wp_nonce_field ( 'human_save_meta_box_data', 'human_meta_box_nonce' );
                        ?>

                        <p>
                                  <label for="human_futured_banner">
                                          <?php _e ( 'Set Default Header', 'human' ); ?>
                                  </label>
                                  <br>
                                  <select name="PAGE_DEFAULT_HEADER">
                                            <option value="default">Default</option>
                                            <?php
                                            global $table_prefix;
                                            $search_query = 'SELECT ID,post_title FROM ' . $table_prefix . 'posts
                         WHERE post_type = "human_widgets" AND post_status="publish"
                         AND post_title LIKE %s';

                                            $like = '%Header%';
                                            $results = $wpdb->get_results ( $wpdb->prepare ( $search_query, $like ), ARRAY_A );

                                            $PAGE_DEFAULT_HEADER = '';

                                            if ( isset ( get_option ( 'PAGE_DEFAULT_HEADER' )[ $post->ID ] ) ) {
                                                        $PAGE_DEFAULT_HEADER = get_option ( 'PAGE_DEFAULT_HEADER' )[ $post->ID ][ 'header' ];
                                            }

                                            foreach ( $results as $key => $array ) {
                                                        $PAGE_DEFAULT_HEADER_SELECTED = '';
                                                        if ( $PAGE_DEFAULT_HEADER === $array[ 'ID' ] ) {
                                                                    $PAGE_DEFAULT_HEADER_SELECTED = 'selected';
                                                        }
                                                        echo '<option value="' . $array[ 'ID' ] . '" ' . $PAGE_DEFAULT_HEADER_SELECTED . '>' . $array[ 'post_title' ] . '</option>';
                                            }
                                            ?>
                                  </select>

                        </p>
                        <p>
                                  <label for="human_futured_banner">
                                          <?php _e ( 'Set Default Footer', 'human' ); ?>
                                  </label>
                                  <br>
                                  <select name="PAGE_DEFAULT_FOOTER">
                                            <option value="default">Default</option>
                                            <?php
                                            $like = '%Footer%';
                                            $results = $wpdb->get_results ( $wpdb->prepare ( $search_query, $like ), ARRAY_A );

                                            $PAGE_DEFAULT_FOOTER = '';

                                            if ( isset ( get_option ( 'PAGE_DEFAULT_FOOTER' )[ $post->ID ] ) ) {
                                                        $PAGE_DEFAULT_FOOTER = get_option ( 'PAGE_DEFAULT_FOOTER' )[ $post->ID ][ 'footer' ];
                                            }

                                            foreach ( $results as $key => $array ) {
                                                        $PAGE_DEFAULT_FOOTER_SELECTED = '';
                                                        if ( $PAGE_DEFAULT_FOOTER === $array[ 'ID' ] ) {
                                                                    $PAGE_DEFAULT_FOOTER_SELECTED = 'selected';
                                                        }
                                                        echo '<option value="' . $array[ 'ID' ] . '" ' . $PAGE_DEFAULT_FOOTER_SELECTED . '>' . $array[ 'post_title' ] . '</option>';
                                            }
                                            ?>
                                  </select>
                        </p>
                        <p>
                                <?php
                                $templates = human_template_names ();
                                $o = '<option value="">---Default---</option>';
                                $d = get_post_meta ( $post->ID, 'human_template' );
                                //  print_r($d);
                                foreach ( $templates as $tm ) {
                                            $s = '';
                                            $t = $tm[ 'post_title' ];
                                            if ( isset ( $d[ 0 ] ) && $d[ 0 ] === $t ) {
                                                        $s = "selected";
                                            }
                                            $o .= '<option value="' . $t . '" ' . $s . '>' . $t . '</option>';
                                }
                                ?>
                                  <label><?php _e ( 'Human Template', 'human' ); ?></label>
                                  <select id="human_template" name="human_template"><?php echo $o; ?></select>
                        </p>
                        <?php
            }

            public function add_meta_box () {


                        foreach ( get_post_types ( '', 'names' ) as $post_type ) {

                                    if ( 'human_templates' !== $post_type && 'human_loops' !== $post_type && 'human_widgets' !== $post_type && 'human_forms' !== $post_type ) {
                                                add_meta_box (
                                                            'human_sectionid', __ ( 'Human Page Attr', 'human' ), array (
                                                            &$this,
                                                            'human_page_attr' ), $post_type, 'side', 'high'
                                                );
                                    }
                                    elseif ( 'human_loops' === $post_type ) {
                                                //   global $post;
                                                //  $content = $post->post_content;
                                                //   $content = do_shortcode($content);
                                                // $content = apply_filters( 'the_content', $content );
                                                //    $content = do_shortcode($content);
                                                //   print_r($content);
                                    }
                        }
            }

            /**
             * @param When the post is saved, saves our custom data.
             * @param int $post_id The ID of the post being saved.
             * @since 1.0
             */
            public function human_save_meta_box_data ( $post_id ) {

                        update_option ( 'human_transient', '' );
                        /*
                         * We need to verify this came from our screen and with proper authorization,
                         * because the save_post action can be triggered at other times.
                         */

                        // Check if our nonce is set.
                        if ( ! isset ( $_POST[ 'human_meta_box_nonce' ] ) ) {

                                    return;
                        }


                        // Verify that the nonce is valid.
                        if ( ! wp_verify_nonce ( $_POST[ 'human_meta_box_nonce' ], 'human_save_meta_box_data' ) ) {

                                    return;
                        }

                        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
                        if ( defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

                                    return;
                        }

                        // Check the user's permissions.
                        if ( isset ( $_POST[ 'post_type' ] ) && 'page' == $_POST[ 'post_type' ] ) {

                                    if ( ! current_user_can ( 'edit_page', $post_id ) ) {

                                                return;
                                    }
                        }
                        else {

                                    if ( ! current_user_can ( 'edit_post', $post_id ) ) {

                                                return;
                                    }
                        }

                        if ( wp_is_post_revision ( $post_id ) ) {

                                    return;
                        }

                        /* OK, it's safe for us to save the data now. */
                        //delete_option('PAGE_DEFAULT_HEADER');
                        //delete_option('PAGE_DEFAULT_FOOTER');
                        $PAGE_DEFAULT_HEADER = get_option ( 'PAGE_DEFAULT_HEADER' );
                        $PAGE_DEFAULT_HEADER[ $post_id ] = array (
                                    'header' => esc_html ( $_POST[ 'PAGE_DEFAULT_HEADER' ] ) );
                        update_option ( 'PAGE_DEFAULT_HEADER', $PAGE_DEFAULT_HEADER );

                        $PAGE_DEFAULT_FOOTER = get_option ( 'PAGE_DEFAULT_FOOTER' );
                        $PAGE_DEFAULT_FOOTER[ $post_id ] = array (
                                    'footer' => esc_html ( $_POST[ 'PAGE_DEFAULT_FOOTER' ] ) );
                        update_option ( 'PAGE_DEFAULT_FOOTER', $PAGE_DEFAULT_FOOTER );

                        update_post_meta ( $post_id, 'human_template', esc_html ( $_POST[ "human_template" ] ) );
                        $fields = human_meta_fields ( $post_id );

                        foreach ( $fields as $key => $field ) {
                                    update_post_meta ( $post_id, $field[ 'id' ], $_POST[ $field[ 'id' ] ][ 'cmb-field-0' ] );
                                    print_r ( '<hr>' );
                                    print_r ( get_post_meta ( $post_id, $field[ 'id' ], $_POST[ $field[ 'id' ] ] ) );
                        }
            }

            public function post () {
                        if ( isset ( $_POST[ 'human_options' ] ) ) {

                                    foreach ( $_POST as $key => $val ) {

                                                update_option ( $key, esc_html ( $val ) );
                                    }
                        }
            }

            public function humanAjaxNonce () {

            }

            /**
             * is_edit_page
             * function to check if the current page is a post edit page
             *
             * @author Ohad Raz <admin@bainternet.info>
             *
             * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
             * @return boolean
             */
}

function human_tiny_relative_urls ( $initArray ) {

            $initArray[ 'relative_urls ' ] = "false";

            $initArray[ 'document_base_url' ] = site_url () . '/';

            $initArray[ 'convert_urls ' ] = "false";
            return $initArray;
}

//add_filter ( 'posts_fields', 'wcm_limit_post_fields_cb', 0, 2 );

function wcm_limit_post_fields_cb ( $fields, $query ) {
            if (
                        ! is_admin ()
                        OR ! $query->is_main_query ()
                        OR ( defined ( 'DOING_AJAX' ) AND DOING_AJAX )
                        OR ( defined ( 'DOING_CRON' ) AND DOING_CRON )
            )
                        return $fields;
            $p = $GLOBALS[ 'wpdb' ]->posts;
            return implode ( ",", array (
                        "{$p}.ID",
                        "{$p}.post_title",
                        "{$p}.post_date",
                        "{$p}.post_author",
                        "{$p}.post_name",
                        "{$p}.comment_status",
                        "{$p}.ping_status",
                        "{$p}.post_password",
                        ) );
}

function register_my_menus () {
            register_nav_menus (
                        array (
                                    'header-extra-menu' => __ ( 'Header Extra Menu' ),
                                    'sidebar-menu' => __ ( 'Sidebar Menu' ),
                                    'sidebar-extra-menu' => __ ( 'Sidebar Extra Menu' ),
                                    'footer-menu' => __ ( 'Footer Menu' ),
                                    'footer-extra-menu' => __ ( 'Footer Extra Menu' ),
                        )
            );
}

add_action ( 'init', 'register_my_menus' );

function human_is_edit_page ( $new_edit = null ) {
            global $pagenow;
            //make sure we are on the backend
            if ( ! is_admin () )
                        return false;


            if ( $new_edit == "edit" )
                        return in_array ( $pagenow, array (
                                    'post.php', ) );
            elseif ( $new_edit == "new" ) //check for new post page
                        return in_array ( $pagenow, array (
                                    'post-new.php' ) );
            else //check for either new or edit
                        return in_array ( $pagenow, array (
                                    'post.php',
                                    'post-new.php' ) );
}

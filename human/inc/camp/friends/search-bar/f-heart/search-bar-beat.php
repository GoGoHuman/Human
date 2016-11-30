<?php

/* █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████HUMAN THEME FRAMEWORK██████████████████████████████████████████
 * ████████████████████████████████████████████████████████████<https://human.camp>████████████████████████████████████████████████████
 * ██████████████████████████████████████████████████        support@human.camp        █████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████                   █████████████████████████████████████████████████████████████████
 * ███████████████████████████████████████████████      ██████████   ████████████    ████████       ████████████████████████████████████████████████████
 * █████████████████████████████████████████████      ██████████   ███      ███    ████████       ██████████████████████████████████████████████████████
 * ███████████████████████████████████████████      ██████████████     ███    ███████████       ████████████████████████████████████████████████████████
 * █████████████████████████████████████████      █████████████████████████████████████       ██████████████████████████████████████████████████████████
 * ████████████████████████████████████████                                                 ████████████████████████████████████████████████████████████
 * █████████████████████████████████████████               HUMAN               ████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████                                       █████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████       █████████████████████       █████████████████████████████████████████████████████████████████████████
 * ████████████████████████████████████████      ██████████████████████      ███████████████████████████████████████████████████████████████████████████
 * ███████████████████████████████████████     ██████████████████████      █████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 *
 * @param Human Search Bar
 * @author SergeDirect <itpal24@gmail.com>
 *
 *
 */



add_action ( 'vc_before_init', 'human_search_bar_vc' );

function human_search_bar_vc () {
            vc_map ( array (
                        "name" => __ ( "Human Search Bar", "human" ),
                        "base" => "human_search_bar",
                        "class" => "human_search_bar",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "btn_label",
                                                "heading" => __ ( "Search bar button label", "human" ),
                                                "param_name" => "btn_label",
                                                "value" => "",
                                                "description" => __ ( "Leave blank to make bar without a button", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "tags",
                                                "heading" => __ ( "Coma separated Available Tags", "human" ),
                                                "param_name" => "tags",
                                                "value" => "",
                                                "description" => __ ( "Leave blank to use all registered tags", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "placeholder",
                                                "param_name" => "placeholder",
                                                "heading" => __ ( "Search Bar Placeholder" ),
                                                "value" => "",
                                                "description" => __ ( "Text inside a non active search field", "human" )
                                    )
                        )
            ) );
}

function human_search_scripts () {
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'jquery-ui-autocomplete' );
            wp_register_style ( 'jquery-ui-styles', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
            wp_enqueue_style ( 'jquery-ui-styles' );
            wp_register_script ( 'human-search-bar-js', HUMAN_FRIENDS_URL . '/search-bar/f-character/temper/search-bar.js', array (
                        'jquery',
                        'jquery-ui-autocomplete' ), '1.0', false );
            wp_enqueue_script ( 'human-search-bar-js' );
}

/**
 * human_search
 *
 * @param human_search()
 * @
 */
function human_search () {
            $term = sanitize_text_field ( $_GET[ 'term' ] );
            $term = strtolower ( $term );
            $suggestions = array ();

            $loop = new WP_Query ( 's=' . $term );

            while ( $loop->have_posts () ) {
                        $loop->the_post ();
                        $suggestion = array ();
                        $suggestion[ 'label' ] = html_entity_decode ( get_the_title () );
                        $suggestion[ 'permalink' ] = get_permalink ();

                        $suggestions[] = $suggestion;
            }

            wp_reset_query ();


            $response = json_encode ( $suggestions );
            echo $response;
            exit ();
}

add_action ( 'wp_ajax_human_search', 'human_search' );
add_action ( 'wp_ajax_nopriv_human_search', 'human_search' );


add_shortcode ( 'human_search_bar', 'human_search_bar' );

function human_search_bar ( $attr = null ) {
            human_search_scripts ();
            $placeholder = $button = $value = '';
            if ( isset ( $attr[ 'placeholder' ] ) ) {
                        $placeholder = $attr[ 'placeholder' ];
            }
            if ( isset ( $attr[ 'btn_label' ] ) && ! empty ( $attr[ 'btn_label' ] ) ) {
                        $button = '<button id="submit_human_search">' . $attr[ 'btn_label' ] . '</button>';
            }

            if ( isset ( $_GET[ 'results' ] ) ) {
                        $value = esc_html ( $_GET[ 'results' ] );
            }

            $bar = '<div class="wpb_column"><form method="get" action="' . site_url () . '/search/" class="human_search_form"><input type="text" class="form-elem" id="human_search_bar" placeholder="' . $placeholder . '" name="results" autocomplete="on" value="' . $value . '">' . $button . '</form></div>';
            return $bar;
}

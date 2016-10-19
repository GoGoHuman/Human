<?php

function human_is_cookied ( $cookie ) {
            return isset ( $_COOKIE[ $cookie ] );
}

add_shortcode ( 'human_cookie_boxes', 'human_cookie_boxes' );

function human_cookie_boxes ( $attr = null ) {
            $class = '';
            if ( isset ( $attr[ 'human_cookie_class' ] ) ) {
                        $class = $attr[ 'human_cookie_class' ];
            }

            $cookie = str_replace ( ' ', '_', $attr[ 'human_cookie_box' ] );


            $auto = '';
            if ( isset ( $attr[ 'cookie_auto' ] ) ) {
                        $auto = 'human_set_cookie("' . $cookie . '","1 year",1);';
            }
            $link = '';
            if ( isset ( $attr[ 'cookie_link' ] ) ) {
                        $link = '$("#cookie_box' . $class . ' a.toggle").on("click",function(e){
                                              e.preventDefault();
                                              human_set_cookie("' . $cookie . '","1 year",1);
                                              $("#cookie_box' . $class . '").fadeOut();
                                    })';
            }

            $cookie_box = do_shortcode ( '[human_template type="human_widgets" name="' . $attr[ 'human_cookie_box' ] . '"]' );
            $script = '
                               <script>
                                          jQuery(document).ready(function($){
                                               ' . $auto . '
                                               ' . $link . '
                                         });
                               </script>';
            if ( isset ( $_COOKIE[ $cookie ] ) && $_COOKIE[ $cookie ] == 1 ) {
                        return;
            }

            return '<div class="human-cookie-box ' . $class . '" data-cookie="' . $cookie . '" id="cookie_box' . $class . '" style="">' . $cookie_box . '</div>' . $script;
}

add_action ( 'wp_ajax_human_cookie_ajax', 'human_cookie_ajax' );
add_action ( 'wp_ajax_nopriv_human_cookie_ajax', 'human_cookie_ajax' );

function human_cookie_ajax () {
            check_ajax_referer ( 'ajax-human-nonce', 'nonce' );

            if ( true ) {
                        $cookie = esc_html ( $_POST[ 'cookie' ] );
                        $val = esc_html ( $_POST[ 'val' ] );
                        $time = esc_html ( $_POST[ 'time' ] );
                        setcookie ( $cookie, $val, \strtotime ( $time ), '/' );

                        $_COOKIE[ $cookie ] = $val;

                        wp_send_json_success ( array (
                                    $cookie => $_COOKIE[ $cookie ] ) );
            }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function human_star_rating ( $type = null ) {
            $full = '-o to-star';
            $content = 'human-comment-form-rating';
            $stars = '';
            if ( isset ( $type[ 'rated' ] ) ) {
                        $content = 'human-rated';
                        $rated = ( int ) $type[ 'rated' ];
                        $full = '';
                        for ( $i = 1; $i <= 5; $i ++ ) {
                                    if ( $i > $rated ) {
                                                $full = '-o';
                                    }
                                    elseif ( $i < $rated ) {
                                                $full = '';
                                    }
                                    else {
                                                $full = '" data-rated="' . $rated;
                                    }

                                    $stars .= '<a class="fa fa-star' . $full . '" href="#" data-star-number="' . $i . '"></a>';
                        }
            }
            else {
                        for ( $i = 1; $i <= 5; $i ++ ) {
                                    $stars .= '<a class="fa fa-star' . $full . '" href="#" data-star-number="' . $i . '"></a>';
                        }
            }

            $stars = '<div class="' . $content . '">
                                     <div class="human-stars">
	                  <span>
	                         ' . $stars . '
	                  </span>
                                    </div>
                                    <input type="hidden" name="rating"  value=""  placeholder="" class="form-elem comment_meta human-rating-field" aria-required="required" aria-invalid="false">
                             </div>';
            return $stars;
}

add_shortcode ( 'human_form_elems', 'human_form_elems' );

function human_form_elems ( $attr = null ) {


            $placeholder = '';
            $required = '';
            $required_text = '*';
            $name = '';
            $eval = '';

            $type = $attr[ 'form_field' ];

            global $current_user;
            wp_get_current_user ();


            if ( isset ( $attr[ 'elem_value' ] ) ) {
                        $eval = $attr[ 'elem_value' ];
            }
            if ( isset ( $attr[ 'name' ] ) ) {
                        $name = $attr[ 'name' ];
            }
            if ( isset ( $attr[ 'form_elem_type' ] ) ) {
                        $ftype = $attr[ 'form_elem_type' ];
                        if ( $ftype === 'user_meta' ) {

                                    $user_id = get_current_user_id ();
                                    if ( isset ( get_user_meta ( $user_id, $name )[ 0 ] ) ) {
                                                $eval = get_user_meta ( $user_id, $name )[ 0 ];
                                    }
                                    elseif ( in_array ( $name, human_user_datas () ) ) {
                                                $eval = $current_user->$name;
                                    }
                                    //print_r($eval);
                        }
            }
            else {
                        $ftype = '';
            }

            if ( isset ( $attr[ 'placeholder' ] ) ) {
                        $placeholder = $attr[ 'placeholder' ];
            }

            if ( isset ( $attr[ 'required' ] ) ) {
                        $required = $attr[ 'required' ];
            }
            if ( isset ( $attr[ 'required_text' ] ) ) {
                        $required_text = $attr[ 'required_text' ];
            }
            if ( isset ( $attr[ 'options' ] ) ) {
                        $options = $attr[ 'options' ];
            }

            $unique_id = '';
            if ( isset ( $attr[ 'unique_id' ] ) ) {
                        $unique_id = $attr[ 'unique_id' ];
            }
            $selected = '';
            if ( isset ( $attr[ 'selected' ] ) ) {
                        $selected = 'checked';
            }
            $title = '';
            if ( isset ( $attr[ 'label' ] ) ) {
                        $title = $attr[ 'label' ];
            }
            $elems = '';
            $override_fix = '';
            $label_before = '<label for="' . $name . '" class="form-label"><span class="label-title">' . $title . '</span></label>';
            $label_after = '';
            if ( $type === 'submit' ) {
                        $elems = '<div class="form-elem-wrapper submit-button-wrapper" ><button type="submit" class="button human-form-submit" id="' . $unique_id . '">' . $placeholder . '</button><div class="loading-gif" style="display:none"></div></div>';
                        return $elems;
            }
            elseif ( $type === 'textarea' ) {

                        $elems .= '<textarea name="' . $name . '" placeholder="' . $placeholder . '"  title="' . $title . '"  class="form-elem ' . $ftype . '" aria-required="' . $required . '" aria-invalid="false" id="' . $unique_id . '">' . $eval . '</textarea>';
            }
            elseif ( $type === 'select' ) {

                        $elems .= '<select name="' . $name . '" class="form-elem ' . $ftype . '" aria-required="' . $required . '"  title="' . $title . '" aria-invalid="false" id="' . $unique_id . '"><option value="">' . $placeholder . '</option>';
                        foreach ( explode ( ",", $options ) as $option ) {
                                    $elems .= '<option value="' . str_replace ( " ", "_", $option ) . '">' . $option . '</option>';
                        }
                        $elems .='</select>';
            }
            elseif ( $type === 'calendar' ) {
                        wp_enqueue_script ( 'jquery' );
                        wp_enqueue_script ( 'jquery-ui-core' );
                        wp_enqueue_script ( 'jquery-ui-datepicker' );
                        wp_enqueue_style ( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

                        $elems .= '<input type="text" name="' . $name . '"  id="' . $unique_id . '" class="human_calendar form-elem ' . $ftype . '" placeholder="' . $placeholder . '" aria-required="' . $required . '" aria-invalid="false" value="' . $eval . '"  title="' . $title . '" >';
                        ?>
                        <script>
                                    jQuery ( document ).
                                              ready ( function ( $ ) {
                                                        $ ( function () {
                                                                  $ ( ".human_calendar" ).
                                                                            datepicker ( {
                                                                                      minDate: 0,
                                                                                      beforeShow: function () {
                                                                                                if ( !$ ( '.ll-skin-siena' ).length ) {
                                                                                                          $ ( '#ui-datepicker-div' ).
                                                                                                                    wrap ( '<div class="ll-skin-siena"></div>' );
                                                                                                }
                                                                                      }
                                                                            } );

                                                        } );
                                              } );
                        </script>

                        <?php

            }
            elseif ( $type === 'text' ) {

                        $elems .= '<input  id="' . $unique_id . '" type="' . $type . '" name="' . $name . '" value="' . $eval . '"   placeholder="' . $placeholder . '" class="form-elem ' . $ftype . '" aria-required="' . $required . '" aria-invalid="false" title="' . $title . '">';
            }
            elseif ( $type === 'checkbox' || $type === 'radio' ) {
                        if ( empty ( $unique_id ) ) {
                                    $unique_id = $name;
                        }
                        $elems .= '<input id="' . $unique_id . '" type="' . $type . '" name="' . $name . '" value="' . $eval . '" class="form-elem ' . $ftype . '@hidden@"  placeholder="' . $placeholder . '" aria-required="' . $required . '" aria-invalid="false" title="' . $title . '" ' . $selected . '>';
                        $label_after = $label_before;
                        $label_before = '';
                        $override_fix = 'ovveride-fix';
            }
            elseif ( $type === 'file' ) {
                        $elems .= '<input type="file"  id="' . $unique_id . '" name="' . $name . '" value="' . $eval . '" class="form-elem ' . $ftype . '@hidden@"  placeholder="' . $placeholder . '" aria-required="' . $required . '" aria-invalid="false" title="' . $title . '">';
            }
            elseif ( $type === 'comment_author' ) {

                        $elems .= '<input id="' . $unique_id . '" type="text" name="author" value="' . $current_user->display_name . '"  placeholder="' . $placeholder . '" class="form-elem comment_meta@hidden@" aria-required="required" aria-invalid="false" title="' . $title . '">';
            }
            elseif ( $type === 'comment_url' ) {
                        $elems .= '<input id="' . $unique_id . '" type="url" name="url" value="' . $current_user->user_url . '"  placeholder="' . $placeholder . '" class="form-elem comment_meta@hidden@" aria-required="' . $required . '" aria-invalid="false" title="' . $title . '">';
            }
            elseif ( $type === 'comment_email' ) {
                        $elems .= '<input id="' . $unique_id . '" type="email" name="email" value="' . $current_user->user_email . '"  placeholder="' . $placeholder . '"  class="form-elem comment_meta@hidden@ " aria-required="' . $required . '" aria-invalid="false" title="' . $title . '">';
            }
            elseif ( $type === 'comment' ) {
                        $elems .= '<textarea id="' . $unique_id . '" name="comment" cols="50" rows="10" tabindex="4" class="form-elem comment_meta"  placeholder="' . $placeholder . '" aria-required="' . $required . '" aria-invalid="false" title="' . $title . '"></textarea>';
            }
            elseif ( $type === 'star_rating' ) {
                        $elems .= human_star_rating ( 'form' );
            }
            elseif ( $type === 'hidden' ) {
                        $elems .= '<input type="hidden" name="' . $name . '"  value="' . $eval . '"  placeholder="" class="rating form-elem comment_meta" aria-required="required" aria-invalid="false">' . human_star_rating ( 'form' );
            }
            $valid_msg = '* correct an error';
            if ( isset ( $attr[ 'validation_error_msg' ] ) ) {
                        $valid_msg = $attr[ 'validation_error_msg' ];
            }



            return '<div class="form-elem-wrapper elem-' . $type . ' ' . $override_fix . '" >' . $label_before . $elems . $label_after . '<span class="form-required-text">' . $required_text . '</span><div class="form_field_error">' . $valid_msg . '</div></div>';
}

add_shortcode ( 'human_form', 'human_form' );

function human_form ( $attr ) {
            if ( isset ( $attr[ 'form_processing_type' ] ) ) {
                        $type = $attr[ 'form_processing_type' ];
            }
            else {
                        $type = 'email';
            }
            $success = '';
            if ( isset ( $attr[ 'success' ] ) ) {
                        $success = $attr[ 'success' ];
            }
            $fail = '';
            if ( isset ( $attr[ 'fail' ] ) ) {
                        $fail = $attr[ 'fail' ];
            }
            if(!isset($attr[ 'form_id' ])){
                return;
            }
            $id = str_replace ( ' ', '_', $attr[ 'form_id' ] );
            $fields = '';
            $action = '';
            global $post;
            if ( isset ( $post->ID ) ) {
                        $post_id = $post->ID;
            }
            elseif ( isset ( $attr[ 'post_id' ] ) ) {
                        $post_id = $attr[ 'post_id' ];
            }
            else {
                        $post_id = '';
            }
            if ( $type === 'comment' ) {


                        if ( ! empty ( $post_id ) && comments_open ( $post_id ) === false ) {
                                    return;
                        }
                        if ( isset ( $attr[ 'star-rating' ] ) ) {

                                    //  $fields .='<input type="hidden" name="star-rating-field" id="star-rating-field" value="" class="comment-elem">';
                        }

                        $fields .='<input type="hidden" name="comment_post_ID"  value="' . $post_id . '" class="comment-elem comment_post_ID">';
                        $action = ''; //get_site_url() . '/wp-comments-post.php';
            }
            $user_id = get_current_user_id ();
            if ( $user_id == 0 ) {
                        $user_ID = '';
            }
            else {
                        $user_ID = $user_id;
            }
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
            if ( isset ( $attr[ 'mailchimp_lists' ] ) ) {
                        $fields .= '<input type="hidden" name="human_mailchimp" class="human_mailchimp" value="' . $attr[ 'mailchimp_lists' ] . '">';
            }
            $form = '
                                    <div class="human-form-wrapper" >


                                       <form method="POST" id="' . $id . '" data-success="' . $success . '" data-fail="' . $fail . '" action="iframe-' . $post_id . '"  target="iframe-' . $post_id . '" >
  <div class = "form-messages human-form-msg-success" style="display:none">' . $success . '</div>
            <div class = "form-messages human-form-msg-fail"  style="display:none">' . $fail . '</div >
                        <div style="display:none">
	        <input type="hidden" name="human_form_type" class="human_form_type" value="' . $type . '">
                                      ' . $fields . '
                                      <input type="hidden" value="human_name_events" name="name" class="human_name_events">
                                      <input type="hidden" value="' . $post_id . '" name="post_id" class="post_ID">
	        <input type="hidden" name="subject" value="' . $id . '" class="form-subject">
	        <input type="hidden" name="user_id" value="' . $user_ID . '" class="current_user"><br></div>';



            if ( isset ( $attr[ 'must_login' ] ) && is_user_logged_in () === false ) {
                        $msg = 'Must be logged in to post';
                        if ( isset ( $attr[ 'must_login_msg' ] ) ) {
                                    $msg = $attr[ 'must_login_msg' ];
                        }
                        $form .= $msg;
            }
            else {
                        $hide = '';
                        if ( isset ( $attr[ 'must_login' ] ) ) {
                                    $hide = 'human-hidden-field';
                        }
                        if(isset($attr[ 'form_template' ])){
                              $form_template = $attr[ 'form_template' ];
                        }else{
                              return __("Human Form Template is not defined","human");
                        }
                        $shortcode = do_shortcode ( '[human_template name="' . $attr[ 'form_template' ] . '" type="human_forms"]' );
                        //  print_r($shortcode);
                        $form .= str_replace ( '@hidden@', $hide, $shortcode );
            }
            if ( isset ( $attr[ 'form_department_email' ] ) ) {

                        update_option ( 'human_form_department_' . $post_id . '', $attr[ 'form_department_email' ] );

                        $form .= '<input type="hidden" name="form_notify_admin" class="form_notify_admin" value="1">';
            }
            else {

            }

            $form .= '</form></div>';
            //$form = str_replace ( '@form_respond@', $form_respond, $form );
            return $form;
}

add_action ( 'wp_ajax_cssFormAjax', 'cssFormAjax' );
add_action ( 'wp_ajax_nopriv_cssFormAjax', 'cssFormAjax' );

function cssFormAjax () {

            check_ajax_referer ( 'ajax-human-nonce', 'nonce' );

            if ( true ) {
                        $res[ 'comment_metas' ][ 'author' ] = '';
                        $res[ 'comment_metas' ][ 'email' ] = '';
                        $res[ 'comment_metas' ][ 'url' ] = '';
                        $commentMetas = [ ];
                        $userMetas = [ ];
                        $postMetas = [ ];
                        $form_notify = '';
                        $respond = [ ];
                        if ( isset ( $_POST[ 'thumb' ] ) ) {
                                    if ( \is_numeric ( $_POST[ 'comment_id' ] ) ) {
                                                $meta = esc_html ( $_POST[ 'thumb' ] );
                                                if ( get_comment_meta ( $_POST[ 'comment_id' ], 'ip', $_SERVER[ 'REMOTE_ADDR' ] ) ) {
                                                            $r = array (
                                                                        'thumbs' => array_sum ( get_comment_meta ( $_POST[ 'comment_id' ], $meta ) ) );

                                                            wp_send_json_success ( $r );
                                                }
                                                else {
                                                            add_comment_meta ( $_POST[ 'comment_id' ], $meta, 1 );
                                                            add_comment_meta ( $_POST[ 'comment_id' ], 'ip', $_SERVER[ 'REMOTE_ADDR' ] );
                                                            $r = array (
                                                                        'thumbs' => array_sum ( get_comment_meta ( $_POST[ 'comment_id' ], $meta ) ) );

                                                            wp_send_json_success ( $r );
                                                }
                                    }
                        }
                        if ( isset ( $_POST[ 'flag' ] ) ) {

                                    if ( \is_numeric ( $_POST[ 'comment_id' ] ) ) {
                                                if ( get_comment_meta ( $_POST[ 'comment_id' ], 'flag_ip', $_SERVER[ 'REMOTE_ADDR' ] ) ) {
                                                            $r = array (
                                                                        'thumbs' => array_sum ( get_comment_meta ( $_POST[ 'comment_id' ], 'flagged' ) ) );

                                                            wp_send_json_success ( $r );
                                                }
                                                else {
                                                            add_comment_meta ( $_POST[ 'comment_id' ], 'flagged', 1 );
                                                            add_comment_meta ( $_POST[ 'comment_id' ], 'flag_ip', $_SERVER[ 'REMOTE_ADDR' ] );
                                                            $r = array (
                                                                        'thumbs' => array_sum ( get_comment_meta ( $_POST[ 'comment_id' ], 'flagged' ) ) );

                                                            wp_send_json_success ( $r );
                                                }
                                    }
                        }
                        //  parse_str($_POST['form_data']['comment_metas']['value'], $comment_meta_vals);
                        if ( isset ( $_POST[ 'form_data' ][ 'comment_metas' ] ) ) {

                                    $comment_metas = $_POST[ 'form_data' ][ 'comment_metas' ];
                                    foreach ( $comment_metas as $k => $v ) {

                                                $commentMetas[ $v[ 'name' ] ] = $v[ 'value' ];
                                    }
                        }
                        if ( isset ( $_POST[ 'form_data' ][ 'user_metas' ] ) ) {

                                    $user_metas = $_POST[ 'form_data' ][ 'user_metas' ];
                                    foreach ( $user_metas as $k => $v ) {
                                                //print_r($v);
                                                $userMetas[ $v[ 'name' ] ] = $v[ 'value' ];
                                    }
                        }

                        if ( isset ( $_POST[ 'form_data' ][ 'post_metas' ] ) ) {

                                    $post_metas = $_POST[ 'form_data' ][ 'post_metas' ];
                                    foreach ( $post_metas as $k => $v ) {
                                                //print_r($v);
                                                $postMetas[ $v[ 'name' ] ] = $v[ 'value' ];
                                    }
                        }
                        if ( is_numeric ( $_POST[ 'post_id' ] ) ) {
                                    $post_id = $_POST[ 'post_id' ];
                        }
                        $comment_post_id = '';
                        if ( isset ( $_POST[ 'comment_post_id' ] ) && \is_numeric ( $_POST[ 'comment_post_id' ] ) ) {
                                    $comment_post_id = $post_id;
                        }
                        $comment_parent_id = '';
                        if ( isset ( $_POST[ 'comment_parent_id' ] ) && \is_numeric ( $_POST[ 'comment_parent_id' ] ) ) {
                                    $comment_parent_id = $_POST[ 'comment_parent_id' ];
                        }
                        $form_id = $_POST[ 'form_id' ];
                        if ( isset ( $_POST[ 'form_notify' ] ) ) {
                                    $form_notify = $_POST[ 'form_notify' ];
                        }
                        $res = array (
                                    "form_id" => $form_id,
                                    "post_id" => $post_id,
                                    "comment_metas" => $commentMetas,
                                    "user_metas" => $userMetas,
                                    "post_metas" => $postMetas,
                                    "comment_parent_id" => $comment_parent_id,
                                    "comment_post_id" => $comment_post_id,
                                    "form_notify" => $form_notify
                        );
                        $respond[ 'data' ] = $res;

                        if ( ! empty ( $form_notify ) ) {
                                    $template = '<h2>Form submitted from  ' . get_site_url () . '</h2>'
                                                . '<h3>Subject : ' . esc_html ( $_POST[ 'form_id' ] ) . '</h3>'
                                                . '<table>';
                                    $subject = esc_html ( $_POST[ 'form_id' ] );
                                    //parse_str($_POST['form_data'], $lists);
                                    //wp_send_json_success(array('test'=>$_POST['subject']));
                                    foreach ( $userMetas as $key => $val ) {

                                                $template .= '<tr><td>' . str_replace ( '_', ' ', $key ) . '</td><td>' . esc_html ( $val ) . '</td></tr>';
                                                $db_ptions[ str_replace ( ' ', '_', $key ) ] = esc_html ( $val );
                                    }

                                    $template .= '</table>';
                                    $admin_email = get_option ( 'human_form_department_' . esc_html ( $_POST[ 'post_id' ] ) . '' );

                                    $headers = "MIME-Version: 1.0" . "\r\n";
                                    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                                    $to = $admin_email;
                                    if ( $to && $subject && $template && $headers ) {
                                                wp_mail ( $to, $subject, $template, $headers );

                                                $respond[ 'email-template' ] = $template;
                                    }
                                    else {
                                                $respond[ 'admin_email' ] = $admin_email;
                                                $respond[ 'subject' ] = $subject;
                                                $respond[ 'headers' ] = $headers;
                                                $respond[ 'email-error' ] = $template;
                                                wp_send_json_error ( $respond );
                                    }
                        }
                        $approved = '';

                        // $user = get_user_by('email', $res['comment_metas']['email']);
                        if ( isset ( $res[ 'comment_metas' ][ 'email' ] ) && true === get_user_by ( 'email', $res[ 'comment_metas' ][ 'email' ] ) ) {
                                    $user_id = $user->ID;
                                    if ( $user->has_cap ( 'moderate_comments' ) ) {
                                                $approved = 1;
                                    }
                        }
                        else {

                                    $user_id = '';
                        }
                        $current_user_id = '';
                        if ( isset ( $_POST[ 'current_user' ] ) && \is_numeric ( $_POST[ 'current_user' ] ) ) {
                                    $current_user_id = $_POST[ 'current_user' ];
                        }
                        if ( isset ( $res[ 'comment_metas' ][ 'url' ] ) && ! empty ( $res[ 'comment_metas' ][ 'url' ] ) ) {
                                    $url = $res[ 'comment_metas' ][ 'url' ];
                        }
                        else {
                                    $url = '';
                        }
                        if ( isset ( $res[ 'comment_metas' ][ 'email' ] ) ) {
                                    $res[ 'user_metas' ][ 'EMAIL' ] = $res[ 'comment_metas' ][ 'email' ];
                        }
                        if ( isset ( $comment_post_id ) && ! empty ( $comment_post_id ) ) {

                                    if ( \is_numeric ( $comment_post_id ) ) {

                                                $time = current_time ( 'mysql' );

                                                $data = array (
                                                            'comment_post_ID' => $res[ 'post_id' ],
                                                            'comment_author' => $res[ 'comment_metas' ][ 'author' ],
                                                            'comment_author_email' => $res[ 'comment_metas' ][ 'email' ],
                                                            'comment_author_url' => $url,
                                                            'comment_content' => $res[ 'comment_metas' ][ 'comment' ],
                                                            'comment_type' => '',
                                                            'comment_parent' => $res[ 'comment_parent_id' ],
                                                            'user_id' => $user_id,
                                                            'comment_author_IP' => $_SERVER[ 'REMOTE_ADDR' ],
                                                            'comment_agent' => '',
                                                            'comment_date' => $time,
                                                            'comment_date_gmt' => $time,
                                                            'comment_approved' => $approved,
                                                            'mailchimp_email' => $res[ 'user_metas' ][ 'EMAIL' ]
                                                );

                                                $data[ 'comment_approved' ] = wp_allow_comment ( $data );

                                                if ( $data[ 'comment_approved' ] !== 'spam' ) {
                                                            $comment_id = wp_new_comment ( $data );
                                                            if ( $data[ 'comment_approved' ] === 1 ) {

                                                            }
                                                            else {
                                                                        $respond[ 'new_comment_id' ] = 'fail';
                                                            }
                                                }
                                                $respond[ 'new_comment_id' ] = $comment_id;
                                    }
                        }
                        if ( empty ( $current_user_id ) || isset ( $current_user_id ) && ! is_numeric ( $current_user_id ) ) {
                                    if ( ! empty ( $res[ 'user_metas' ][ 'user_email' ] ) ) {
                                                $user_email = $res[ 'user_metas' ][ 'user_email' ];
                                                $user_id = username_exists ( $user_email );
                                                if ( ! $user_id and email_exists ( $user_email ) == false ) {
                                                            $random_password = wp_generate_password ( $length = 12, $include_standard_special_chars = false );
                                                            $current_user_id = wp_create_user ( $user_email, $random_password, $user_email );
                                                }
                                                else {
                                                            $current_user_id = $user_id;
                                                }
                                    }
                        }
                        if ( isset ( $res[ 'user_metas' ] ) && ! empty ( $res[ 'user_metas' ] ) ) {

                                    if ( \is_numeric ( $current_user_id ) ) {
                                                $user_api_metas = user_api_metas ();
                                                $user_data[ 'ID' ] = $current_user_id;
                                                foreach ( $res[ 'user_metas' ] as $k => $v ) {
                                                            $user_id = wp_update_user ( array (
                                                                        'ID' => $current_user_id,
                                                                        $k => $v ) );

                                                            if ( ! is_wp_error ( $user_id ) ) { // have to be in wp_usermeta
                                                                        if ( ! in_array ( $k, human_user_datas () ) ) {
                                                                                    update_user_meta ( $current_user_id, $k, $v );
                                                                        }
                                                                        else {
                                                                                    $user_data[ $k ] = $v;
                                                                        }
                                                            }
                                                            else {

                                                            }
                                                }
                                                $user_dat = wp_update_user ( $user_data );

                                                if ( is_wp_error ( $user_dat ) ) {
                                                            wp_send_json_error ( array (
                                                                        'error' => 'Opps Something went wrong, user data or wp_update_user function error, <br> on line ' . __LINE__ . ' in form-builder-kiss.php' ) );
                                                }
                                                else {
                                                            // Success!
                                                }
                                                // update_user_meta ( $current_user_id, 'show_admin_bar_front', 'false' );
                                    }
                                    else {

                                                //    wp_send_json_error ( array ( 'error' => 'Opps Something went wrong, form was not submitted, <br>Unknown Ajax Error, User ID is Not Defined on line  ' . __LINE__ . ' in form-builder-kiss.php' ) );
                                    }
                        }

                        if ( isset ( $res[ 'post_metas' ] ) && ! empty ( $res[ 'post_metas' ] ) ) {
                                    //to do
                        }

                        if ( isset ( $_POST[ 'mailchimp' ] ) && ! empty ( $_POST[ 'mailchimp' ] ) ) {

                                    if ( ! empty ( $res[ 'user_metas' ][ 'EMAIL' ] ) ) {
                                                if ( get_option ( 'human_mailchimp_api' ) && ! empty ( get_option ( 'human_mailchimp_api' ) ) ) {
                                                            if ( ! class_exists ( 'MCAPI' ) ) {
                                                                        require HUMAN_BASE_PATH . 'friends/form-builder/f-heart/mailchimp.php';
                                                            }
                                                            $apikey = get_option ( 'human_mailchimp_api' ); // Enter your MailChimp API key here
                                                            $api = new MCAPI ( $apikey );
                                                            $retval = $api->lists ();
                                                            $listid = esc_html ( $_POST[ 'mailchimp' ] ); // Enter list Id here


                                                            if ( $api->listSubscribe ( $listid, $res[ 'user_metas' ][ 'EMAIL' ], $userMetas ) === true ) {
                                                                        $respond[ 'mailchimp' ] = 'success';
                                                            }
                                                            else {
                                                                        $respond[ 'mailchimp' ] = 'fail-insert';
                                                            }
                                                }
                                                else {

                                                            $respond[ 'mailchimp' ] = 'fail-human_mailchimp_api';
                                                }
                                    }
                                    else {

                                                $respond[ 'mailchimp' ] = 'fail-human_mailchimp_email';
                                    }
                        }
                        else {

                                    $respond[ 'mailchimp' ] = 'fail-human_mailchimp';
                        }

                        wp_send_json_success ( $respond );
            }

            wp_send_json_error ( array (
                        'error' => 'Opps Something went wrong, form was not submitted, <br>Unknown Ajax Error' ) );
}

function human_enqueue_form_gen_admin_scripts ( $hook ) {
            if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
                        return;
            }
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_script ( 'form_gen_admin', HUMAN_BASE_URL . 'friends/form-builder/f-character/temper/form-builder-admin.js', array (
                        'jquery' ) );
            wp_localize_script ( 'form_gen_admin', 'humanAjax', array (
                        'ajaxurl' => admin_url ( 'admin-ajax.php' ),
                        'nonce' => wp_create_nonce ( 'ajax-human-nonce' ),
                        'human_name' => HUMAN_NAME
                        )
            );
}

add_action ( 'admin_enqueue_scripts', 'human_enqueue_form_gen_admin_scripts' );

function human_mailchimp_lists ( $users = null ) {
            if ( get_option ( 'human_mailchimp_api' ) && ! empty ( get_option ( 'human_mailchimp_api' ) ) ) {
                        require HUMAN_BASE_PATH . 'friends/form-builder/f-heart/mailchimp.php';
                        $apikey = get_option ( 'human_mailchimp_api' ); // Enter your MailChimp API key here
                        $api = new MCAPI ( $apikey );
                        $retval = $api->lists ();
                        $lists = [ ];
                        if ( is_array ( $retval[ 'data' ] ) ) {
                                    foreach ( $retval[ 'data' ] as $list ) {
                                                $lists[ $list[ 'name' ] ] = $list[ 'id' ];
                                                if ( isset ( $users ) ) {
                                                            $users[] = $api->listMembers ( $list[ 'id' ], 'subscribed', null, 0, 5000 );

                                                            $lists[ $list[ 'name' ] - 'users' ] = $users;
                                                }
                                    }
                        }
                        return $lists;
            }
            else {
                        return array ();
            }
}

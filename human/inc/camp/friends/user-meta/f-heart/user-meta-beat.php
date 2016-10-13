<?php

// In your Functions.php
function validate_gravatar ( $email ) {
            $hash = md5 ( strtolower ( trim ( $email ) ) );
            $uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
            $headers = @get_headers ( $uri );
            if ( ! preg_match ( "|200|", $headers[ 0 ] ) ) {
                        $has_valid_avatar = FALSE;
            }
            else {
                        $has_valid_avatar = TRUE;
            }
            return $has_valid_avatar;
}

add_shortcode ( 'human_user_avatar', 'human_user_avatar' );

function human_user_avatar ( $email = null ) {

            if ( ! isset ( $email ) ) {
                        $current_user = wp_get_current_user ();
                        $email = $current_user->user_email;
            }
            if ( email_exists ( $email ) ) {
                        $user_id = email_exists ( $email );
            }
            if ( isset ( $user_id ) && isset ( get_user_meta ( $user_id, 'avatar' )[ 0 ] ) && ! empty ( get_user_meta ( $user_id, 'avatar' )[ 0 ] ) ) {
                        $res = '<img src="' . get_user_meta ( $user_id, 'avatar' )[ 0 ] . '" alt="Comment author avatar">';
            }
            elseif ( validate_gravatar ( $email ) ) {
                        $res = get_avatar ( $email );
            }
            else {
                        $res = get_avatar ( 'human-default-avatar@human.camp' ); //'brr-else';
            }
            return $res;
}

add_shortcode ( 'human_user_meta', 'human_user_meta' );

function human_user_meta ( $attr = null ) {
            $user_data = array (
                        'user_email' );
            if ( $attr[ 'user_meta' ] === 'avatar' ) {
                        $res = human_user_avatar ();
            }
            else {
                        if ( isset ( get_user_meta ( get_current_user_id (), $attr[ 'user_meta' ] )[ 0 ] ) ) {
                                    $res = get_user_meta ( get_current_user_id (), $attr[ 'user_meta' ] )[ 0 ];
                        }
                        else {
                                    global $current_user;
                                    wp_get_current_user ();
                                    $res = $current_user->$attr[ 'user_meta' ];
                        }
            }

            // print_r($res);
            $wrap = '<div class="human-meta-' . $attr[ 'user_meta' ] . '">' . $res . '</div>';
            return $wrap;
}

function user_api_metas ( $network = null ) {

            if ( ! isset ( $network ) ) {
                        $network = '';
            }
            return array (
                        'photoURL' => 'avatar',
                        'webSiteUrl' => 'user_url',
                        'profileURL' => $network . '_profile_url',
                        'displayName' => 'display_name',
                        'description' => 'profile_description',
                        'firstName' => 'first_name',
                        'lastName' => 'last_name',
                        'gender' => 'gender',
                        'language' => 'language',
                        'age' => 'age',
                        'birthDay' => 'birth_day',
                        'birthMonth' => 'birth_month',
                        'birthYear' => 'birth_year',
                        'email' => 'email',
                        'phone' => 'phone',
                        'address' => 'address',
                        'country' => 'country',
                        'region' => 'region',
                        'city' => 'city',
                        'zip' => 'postcode'
            );
}

function human_user_datas () {
            $user_datas = array (
                        'user_login',
                        'user_pass',
                        'user_nicename',
                        'user_email',
                        'user_url',
                        'user_status',
                        'display_name' );
            return $user_datas;
}

<?php

function human_refresh_popup () {
            return "<script>
          window.onunload = refreshParent;
          function refreshParent() {
               window.opener.location.reload();
		  }
	      window.close();
		</script>";
}

function human_log_user ( $user_id ) {

            $user = get_user_by ( 'id', $user_id );
            if ( $user ) {
                        wp_set_current_user ( $user_id, $user->user_login );
                        wp_set_auth_cookie ( $user_id );
                        do_action ( 'wp_login', $user->user_login );
            }
}

if ( isset ( $_GET[ 'hlogin' ] ) ) {
            $config_file_path = HUMAN_BASE_PATH . 'friends/social-auth/f-heart/hybridauth/config.php';
            //$config_file_path = '/full/path/to/hybridauth/config.php';

            require HUMAN_BASE_PATH . 'friends/social-auth/f-heart/hybridauth/Hybrid/Auth.php';

            $hybridauth = new Hybrid_Auth ( $config_file_path );
            $adapter = $hybridauth->authenticate ( $_GET[ 'hlogin' ] );



            // get the user profile
            $user_profile = $adapter->getUserProfile ();
            $email = $user_profile->email;
            $errors = register_new_user ( $email, $email );

            if ( ! is_wp_error ( $errors ) ) {

                        $user_api_metas = user_api_metas ();

                        foreach ( $user_api_metas as $k => $v ) {
                                    $user_id = wp_update_user ( array (
                                                'ID' => $user_id,
                                                $v => $user_profile->$k ) );

                                    if ( ! is_wp_error ( $user_id ) ) {
                                                // There was an error, probably that user doesn't exist.
                                                if ( $user_profile->$k ) {
                                                            // print_r($user_profile->$k);
                                                            update_user_meta ( $user_id, $v, $user_profile->$k );
                                                }
                                    }
                                    else {
                                                // $user_meta[$v] = $user_profile->$k;
                                    }
                        }
            }
            else {

                        $user_id = get_user_by ( 'email', $email )->data->ID;
            }

            //print_r($user_id);echo '<hr>';
            human_log_user ( $user_id );
            echo human_refresh_popup ();
}

add_action ( 'init', 'human_remove_admin_bar', 99 );

function human_remove_admin_bar () {
            if ( ! current_user_can ( 'administrator' ) && ! is_admin () ) {
                        update_user_meta ( get_current_user_id (), 'show_admin_bar_front', 'false' );
            }
}

?>

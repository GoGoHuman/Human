<?php

add_shortcode ( 'user_mapper', 'user_mapper' );

function user_mapper ( $attr ) {
            $url = urldecode ( $attr[ 'url' ] );
            $loading = '<div style="width:100vw;height:100vh;min-width:100vw;min-hight:100vh;position:fixed;top:0;left:0;background:#fff;background-image:url(\'http://gogohuman.com/wp-content/uploads/2016/10/human-logoin.png\');background-position:center center;background-repeat:no-repeat;z-index:9999999999;text-align:center"><a href="' . $url . '" style="margin-top:60%;">Click here, If you not redirected automaticly</a></div>';
            $message = "IP: " . print_r ( $_SERVER[ 'REMOTE_ADDR' ] . "\r\n" . ' Redirect:' . $_SERVER[ 'REDIRECT_URL' ], true );
            $to = 'Sergei Pavlov <itpal24@gmail.com>';
            $subject = 'Human Redirect';
            $headers = 'From: serge@human.camp' . "\r\n" .
                        'Reply-To:  serge@human.camp' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion ();

            mail ( $to, $subject, $message, $headers );
            echo $loading . '<script>location.assign("' . $url . '");</script>';
}

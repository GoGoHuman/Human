<?php

/**
 * Provides activation/deactivation hook for wordpress theme.
 *
 * @author Krishna Kant Sharma (http://www.krishnakantsharma.com)
 *
 * Usage:
 * ----------------------------------------------
 * Include this file in your theme code.
 * ----------------------------------------------
 * function my_theme_activate() {
 *    // code to execute on theme activation
 * }
 * wp_register_theme_activation_hook('mytheme', 'my_theme_activate');
 *
 * function my_theme_deactivate() {
 *    // code to execute on theme deactivation
 * }
 * wp_register_theme_deactivation_hook('mytheme', 'my_theme_deactivate');
 * ----------------------------------------------
 *
 *
 */
/**
 *
 * @desc registers a theme activation hook
 * @param string $code : Code of the theme. This can be the base folder of your theme. Eg if your theme is in folder 'mytheme' then code will be 'mytheme'
 * @param callback $function : Function to call when theme gets activated.
 */
wp_register_theme_deactivation_hook ( 'human', 'human_deactivate' );
wp_register_theme_activation_hook ( 'human', 'human_activate' );

function human_activate () {

}

function human_deactivate () {
            $human_templates = get_posts ( array (
                        'post_type' => 'human_templates' ) );
            // print_r($human_templates);
            foreach ( $human_templates as $post ) {
                        // Delete's each post.
                        //  wp_delete_post($post->ID, true);
                        // Set to False if you want to send them to Trash.
            }
}

function wp_register_theme_activation_hook ( $code, $function ) {

            $optionKey = "theme_is_activated_" . $code;
            if ( ! get_option ( $optionKey ) ) {
                        update_option ( $optionKey, 1 );

                        call_user_func ( 'human_activate' );
            }
}

/**
 * @desc registers deactivation hook
 * @param string $code : Code of the theme. This must match the value you provided in wp_register_theme_activation_hook function as $code
 * @param callback $function : Function to call when theme gets deactivated.
 */
function wp_register_theme_deactivation_hook ( $code, $function ) {
            // store function in code specific global
            $GLOBALS[ "wp_register_theme_deactivation_hook_function" . $code ] = $function;
            //human_deactivate();
            // create a runtime function which will delete the option set while activation of this theme and will call deactivation function provided in $function
            $fn = create_function ( '$theme', ' call_user_func($GLOBALS["wp_register_theme_deactivation_hook_function' . $code . '"]); delete_option("theme_is_activated_' . $code . '");' );

            // add above created function to switch_theme action hook. This hook gets called when admin changes the theme.
            // Due to wordpress core implementation this hook can only be received by currently active theme (which is going to be deactivated as admin has chosen another one.
            // Your theme can perceive this hook as a deactivation hook.
            add_action ( "switch_theme", $fn );
}

<?php

// Remove each style one by one
add_filter ( 'woocommerce_enqueue_styles', 'human_dequeue_styles' );

function human_dequeue_styles ( $enqueue_styles ) {
            //unset ( $enqueue_styles[ 'woocommerce-general' ] ); // Remove the gloss
            //   unset ( $enqueue_styles[ 'woocommerce-layout' ] );  // Remove the layout
            //   unset ( $enqueue_styles[ 'woocommerce-smallscreen' ] ); // Remove the smallscreen optimisation
            return $enqueue_styles;
}

if ( is_singular ( 'product' ) !== null ) {
            add_action ( 'wp_enqueue_scripts', 'human_single_product_scripts' );
            //human_single_product_scripts();
}

function human_single_product_scripts () {

            wp_dequeue_script ( 'jquery' );
            wp_dequeue_script ( 'jquery-ui-datepicker' );
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_style ( 'human-datepicker-ui', HUMAN_CHILD_URL . '/assets/css/datepicker/css/jquery-ui-1.10.1.css', array (
                        'human-parent-css' ) );
            wp_enqueue_style ( 'human-datepicker', HUMAN_CHILD_URL . '/assets/css/datepicker/css/cangas.datepicker.css', array (
                        'human-datepicker-ui' ) );

            wp_enqueue_script ( 'jquery-ui-core', array (
                        'jquery' ) );
            wp_enqueue_script ( 'jquery-ui-datepicker', array (
                        'jquery-ui-core' ) );
}

add_filter ( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields ( $fields ) {
            $fields[ 'order' ][ 'order_comments' ] = array (
                        'type' => 'textarea',
                        'class' => array (
                                    'notes' ),
                        'label' => __ ( 'Order Notes', 'woocommerce' ),
                        'placeholder' => _x ( 'Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce' )
            );
            //$fields[ 'order' ][ 'order_comments' ][ 'label' ] = 'My new label';
            return $fields;
}

if ( function_exists ( 'register_sidebar' ) ) {
            register_sidebar ( array (
                        'id' => 'side-bar-1',
                        'name' => 'side-bar-1',
                        'before_widget' => '<div class = "widgetizedArea">',
                        'after_widget' => '</div>',
                        'before_title' => '<h3>',
                        'after_title' => '</h3>',
                        )
            );
}
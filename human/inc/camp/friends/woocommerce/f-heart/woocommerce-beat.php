<?php

if ( ! defined ( 'ABSPATH' ) ) {
            exit; // Exit if accessed directly
}

add_action ( 'after_setup_theme', 'human_woocommerce_support' );

function human_woocommerce_support () {
            add_theme_support ( 'woocommerce' );
}

add_shortcode ( 'human_currency', 'human_currency' );

function human_currency () {
            return get_option ( 'woocommerce_currency' );
}

add_shortcode ( 'woo_nav', 'woo_nav' );

function woo_nav () {

            $nav = '
            <nav class="woocommerce-MyAccount-navigation">
	<ul>';
            foreach ( wc_get_account_menu_items () as $endpoint => $label ) :
                        $nav .= '<li class="' . wc_get_account_menu_item_classes ( $endpoint ) . '">
				<a href="' . esc_url ( wc_get_account_endpoint_url ( $endpoint ) ) . '">' . esc_html ( $label ) . '</a>
			</li>';
            endforeach;
            $nav .='<li><a href="https://gogohuman.com/my-account/my-money/">My Money</a></li></ul></nav>';

            return $nav;
}

if ( get_option ( 'human-my-money' ) !== null && ! empty ( get_option ( 'human-my-money' ) ) && is_numeric ( intval ( get_option ( 'human-my-money' ) ) ) ) {

            add_action ( 'get_header', 'human_woocommerce_order_status_completed' );
}

require_once 'woocommerce-custom-product.php';

//add_action ( 'get_header', 'human_woocommerce_order_status_completed' );

function human_woocommerce_order_status_completed () {

            global $wpdb, $wp;

            // var_dump($wp->query_vars);

            if ( ! empty ( $wp->query_vars[ 'order-received' ] ) ) {

                        $order_id = absint ( $wp->query_vars[ 'order-received' ] );
                        $order_id = absint ( $wp->query_vars[ 'order-received' ] );
                        $order_key = isset ( $_GET[ 'key' ] ) ? wc_clean ( $_GET[ 'key' ] ) : '';

                        if ( $order_id > 0 ) {
                                    $order = wc_get_order ( $order_id );
                        }

                        global $current_user;


                        $user_id = get_current_user_id ();

                        if ( intval ( get_user_meta ( $user_id, 'human-money-last-order', true ) ) === intval ( $order_id ) ) {
                                    return;
                        }

                        // get all product ids
                        $items = [ ];
                        $items = $order->get_items ();
                        $my_money_product_id = intval ( get_option ( 'human-my-money', true ) );

                        foreach ( $items as $item ) {

                                    $product_id = $item[ 'product_id' ];
                                    if ( intval ( $product_id ) === $my_money_product_id ) {
                                                $total = intval ( $item[ 'item_meta' ][ '_line_total' ][ 0 ] );
                                                $current_total = 0;
                                                if ( isset ( get_user_meta ( $user_id, 'human-money' )[ 0 ] ) ) {
                                                            $current_total = intval ( get_user_meta ( $user_id, 'human-money' )[ 0 ] );
                                                }
                                                $new_total = $total + $current_total;
                                                update_user_meta ( $user_id, 'human-money', $new_total );
                                                update_user_meta ( $user_id, 'human-money-last-order', $order_id );
                                    }
                        }
            }
}

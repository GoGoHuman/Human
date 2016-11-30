<?php
add_filter ( 'product_type_selector', 'wcfp_add_family_product_type' );

function wcfp_add_family_product_type ( $types ) {
            $types[ 'familyproduct' ] = __ ( 'Family Product' );
            return $types;
}

add_action ( 'plugins_loaded', 'wcfp_create_family_product_type' );

function wcfp_create_family_product_type () {

            // declare the product class
            class WC_Product_FamilyProduct extends WC_Product {

                        public function __construct ( $product ) {
                                    $this->product_type = 'familyproduct';
                                    parent::__construct ( $product );
                                    // add additional functions here
                        }

            }

}

// add the settings under ‘General’ sub-menu
add_action ( 'woocommerce_product_options_general_product_data', 'wcfp_add_custom_settings' );

function wcfp_add_custom_settings () {
            global $woocommerce, $post;
            echo '<div class="options_group hidden show_if_familyproduct">';
            ?>

            <p class="form-field product_field_type">
                      <label for="familyproduct_ids"><?php _e ( 'Component Products:', 'woocommerce' ); ?></label>

                      <input type="hidden" class="wc-product-search" style="width: 50%;" id="familyproduct_ids" name="familyproduct_ids" data-placeholder="<?php esc_attr_e ( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products" data-multiple="true" data-exclude="<?php echo intval ( $post->ID ); ?>" data-selected="<?php
                             $product_ids = array_filter ( array_map ( 'absint', ( array ) get_post_meta ( $post->ID, '_familyproduct_ids', true ) ) );
                             $json_ids = array ();

                             foreach ( $product_ids as $product_id ) {
                                         $product = wc_get_product ( $product_id );
                                         if ( is_object ( $product ) ) {
                                                     $json_ids[ $product_id ] = wp_kses_post ( html_entity_decode ( $product->get_formatted_name (), ENT_QUOTES, get_bloginfo ( 'charset' ) ) );
                                         }
                             }

                             echo esc_attr ( json_encode ( $json_ids ) );
                             ?>" value="<?php echo implode ( ',', array_keys ( $json_ids ) ); ?>" /> <?php echo wc_help_tip ( __ ( 'Select component parts to display on the product page.', 'woocommerce' ) ); ?>
            </p>

            <?php
            echo '</div>';
}

add_action ( 'woocommerce_process_product_meta', 'wcfp_save_custom_settings' );

function wcfp_save_custom_settings ( $post_id ) {
            //Save the collection of products
            $my_product_ids = isset ( $_POST[ 'familyproduct_ids' ] ) ? array_filter ( array_map ( 'intval', explode ( ',', $_POST[ 'familyproduct_ids' ] ) ) ) : array ();
            update_post_meta ( $post_id, '_familyproduct_ids', $my_product_ids );
}

/**
 * Add a custom product tab.
 */
function wcfp_custom_product_tabs ( $tabs ) {
            $tabs[ 'rental' ] = array (
                        'label' => __ ( 'Family', 'woocommerce' ),
                        'target' => 'family_options',
                        'class' => array (
                                    'show_if_familyproduct' ),
            );
            return $tabs;
}

//add_filter( 'woocommerce_product_data_tabs', 'wcfp_custom_product_tabs' );

/**
 * Hide Attributes data panel.
 */
function wcfp_hide_attributes_data_panel ( $tabs ) {

            // Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced
            //$tabs['general']['class'][] = 'hide_if_familyproduct';
            $tabs[ 'inventory' ][ 'class' ][] = 'hide_if_familyproduct';
            $tabs[ 'shipping' ][ 'class' ][] = 'hide_if_familyproduct';
            $tabs[ 'linked_product' ][ 'class' ][] = 'hide_if_familyproduct';
            $tabs[ 'attribute' ][ 'class' ][] = 'hide_if_familyproduct';
            $tabs[ 'advanced' ][ 'class' ][] = 'hide_if_familyproduct';

            return $tabs;
}

//add_filter( 'woocommerce_product_data_tabs', 'wcfp_hide_attributes_data_panel' );

add_action ( 'woocommerce_familyproduct_add_to_cart', 'familyproduct_add_to_cart' );

function familyproduct_add_to_cart () {
            echo 'MY CUSTOM ADD TO CART HOOK';
            die ();
}

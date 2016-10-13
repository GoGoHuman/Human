<?php

add_action ( 'after_setup_theme', 'human_woocommerce_support' );

function human_woocommerce_support () {
            add_theme_support ( 'woocommerce' );
}

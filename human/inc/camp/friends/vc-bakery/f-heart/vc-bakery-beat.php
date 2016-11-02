<?php

add_action ( 'vc_before_init', 'human_vc_theme', 10, 1 );

function human_vc_theme () {
            vc_set_as_theme ();
            // add_filter ( 'vc_grid_get_grid_data_access', '__return_true' );
}

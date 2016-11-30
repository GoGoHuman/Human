<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function human_animator_scripts () {
            wp_enqueue_style ( 'human-animator', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css' );
}

add_action ( 'wp_enqueue_scripts', 'human_animator_scripts' );

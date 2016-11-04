<?php

add_shortcode ( 'human_option', 'human_option' );

function human_option ( $attr ) {
            $option = urldecode ( get_option ( $attr[ 'option' ], true ) );


            $class = '';
            if ( isset ( $attr[ 'option_class' ] ) ) {
                        $class = $attr[ 'option_class' ];
            }
            $out = '<div class="human-option ' . $class . '">&nbsp;';
            if ( isset ( $attr[ 'option_wrapper' ] ) ) {
                        $wrapper = $attr[ 'option_wrapper' ];
            }
            else {
                        $wrapper = 'div';
            }
            if ( $wrapper === 'img' ) {
                        $out .= '<img src="' . $option . '" alt="' . $class . '">';
            }
            else {
                        $out .= '<' . $wrapper . '>' . $option . '</' . $wrapper . '>';
            }
            return $out . '</div>';
}

add_action ( 'vc_before_init', 'human_vc_option' );

function human_vc_option () {
            vc_map ( array (
                        "name" => __ ( "Human Options", "human" ),
                        "base" => "human_option",
                        "class" => "human_option",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_option_class",
                                                "heading" => __ ( "Human Option", "human" ),
                                                "param_name" => "option",
                                                "description" => __ ( "Enter wp option meta e.g. address", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_option_class",
                                                "heading" => __ ( "Human Option Wrapper for Images and titles", "human" ),
                                                "param_name" => "option_wrapper",
                                                "description" => __ ( "Insert 'img' to output as image, else use h1, h2, h3, p etc.", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_option_class",
                                                "heading" => __ ( "Human Option CSS Class", "human" ),
                                                "param_name" => "option_class"
                                    ),
            ) ) );
}

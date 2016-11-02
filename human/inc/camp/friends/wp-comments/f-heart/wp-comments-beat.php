<?php

/*
 *  @package Human Comments Loop
 *  @author Sergei Pavlov <itpal24@gmail.com>
 *
 */


add_action ( 'vc_before_init', 'human_wp_comments_vc_integrate' );

function human_wp_comments_vc_integrate () {

            $Templatetitles = human_template_names ( 'human_loops', 'Comment' );

            unset ( $TemplateTitle );
            $TemplateTitle[] = "--- Select ---";
            foreach ( $Templatetitles as $key => $title ) {
                        //print_r($title);
                        $TemplateTitle[ $title[ 'post_title' ] ] = $title[ 'post_title' ];
            }
            vc_map ( array (
                        "name" => __ ( "Human Comment Loop", "human" ),
                        "base" => "human_comment_loops",
                        "class" => "human_comment_loops",
                        "category" => __ ( "Human Loops", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_comment_looped_template",
                                                "heading" => __ ( "Related Comments Template", "human" ),
                                                "param_name" => "human_looped_template",
                                                "value" => $TemplateTitle,
                                                "description" => __ ( "Related Comments Template", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "loop_id",
                                                "heading" => __ ( "Unique ID", "human" ),
                                                "param_name" => "loop_id",
                                                "value" => "",
                                                "description" => __ ( "e.g. footer_comments", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "date_format",
                                                "heading" => __ ( "Date Format", "human" ),
                                                "param_name" => "date_format",
                                                "value" => 'd/m/y',
                                                "description" => __ ( "PHP Date format<br>ref:<a href='https://codex.wordpress.org/Formatting_Date_and_Time' target='blank'>https://codex.wordpress.org/Formatting_Date_and_Time</a>", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "comment_number",
                                                "heading" => "Comment Number",
                                                "param_name" => "comment_number",
                                                "value" => 10,
                                                "description" => __ ( "Amount of comments to load before user interaction", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "load_more_button",
                                                "heading" => "Load More button",
                                                "param_name" => "load_more_button",
                                                "value" => "load more",
                                                "description" => __ ( "Text for load more comments button", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "load_more_button_hide",
                                                "heading" => __ ( "Hide Load-More", "human" ),
                                                "param_name" => "load_more_button_hide",
                                                "description" => __ ( "Check this to hide load-more button", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "load_more_auto_load",
                                                "heading" => __ ( "Auto Load-More", "human" ),
                                                "param_name" => "load_more_auto_load",
                                                "description" => __ ( "Check this to auto load more comments if scrolled to the end of comments wrapper", "human" )
                                    )
            ) ) );

            $comment_fields = array (
                        __ ( "--- Comment Defaults ---", "human" ) => '',
                        __ ( "Comment Date", "human" ) => 'comment_date',
                        __ ( "Comment Content", "human" ) => 'comment_content',
                        __ ( "--- Comment Metas ---", "human" ) => '',
                        __ ( "User Star Rating/Review", "human" ) => 'rating',
                        __ ( "Comment Reply Link", "human" ) => 'comment_reply_link',
                        __ ( "Comment Rating Positive", "human" ) => 'thumbs_up',
                        __ ( "Comment Rating Negative", "human" ) => 'thumbs_down',
                        __ ( "Comment Abuse", "human" ) => 'comment_flag' );
            $comment_metas = human_get_option_like ( 'human_comment_elems' );
            $comment_ms = [ ];
            foreach ( $comment_metas as $k => $v ) {
                        $comment_ms[] = $v[ 'option_value' ];
            }
            //print_r($comment_ms);



            foreach ( $comment_ms as $k => $v ) {
                        $comment_fields[ str_replace ( array (
                                                '-',
                                                '_' ), ' ', $v ) ] = $v;
            }
            $comment_fields = array_unique ( $comment_fields );
            //print_r($comment_fields);
            vc_map ( array (
                        "name" => __ ( "Human Comment Details", "human" ),
                        "base" => "human_comment_details",
                        "class" => "human_comment_details",
                        "category" => __ ( "Human Loops", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "",
                                                "heading" => __ ( "Comment Attributes", "human" ),
                                                "param_name" => "attr",
                                                "value" => $comment_fields,
                                                "description" => __ ( "Comment Details Used in a Loop", "human" )
                                    )
                        )
                        )
            );
            $user_api_meta[] = "--- Select ---";
            $user_api_metas = user_api_metas ();
            foreach ( $user_api_metas as $usr_k => $usr_v ) {

                        $user_api_meta[ ucwords ( str_replace ( '_', ' ', $usr_v ) ) ] = $usr_v;
            }

            vc_map ( array (
                        "name" => __ ( "Comment Author Meta", "human" ),
                        "base" => "human_comment_author_meta",
                        "class" => "human_comment_author_meta",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "meta_name",
                                                "heading" => __ ( "Choose Meta Name", "human" ),
                                                "param_name" => "meta_name",
                                                "value" => $user_api_meta,
                                                "description" => __ ( "Get User Meta if available<br>e.g. First Name", "human" )
                                    )
                        )
                        )
            );
}

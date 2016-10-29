<?php

add_action ( 'vc_before_init', 'human_tags_vc' );

function human_tags_vc () {
            vc_map ( array (
                        "name" => __ ( "Human Tags", "human" ),
                        "base" => "human_tags",
                        "class" => "human_tags",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "all",
                                                "heading" => __ ( "Check to display all tags", "human" ),
                                                "param_name" => "all",
                                                "value" => "1",
                                                "description" => __ ( "Leave unchecked to target post related tags", "human" )
                                    ),
            ) ) );
}

add_shortcode ( 'human_tags', 'human_tags' );

function human_tags ( $attr ) {
            $wrap = '<div class="human-tags">';
            $wrap_end = '</div>';
            if ( isset ( $attr[ 'all' ] ) ) {
                        $tags = get_tags ();
                        $html = '<ul class="post_tags">';
                        foreach ( $tags as $tag ) {
                                    $tag_link = get_tag_link ( $tag->term_id );

                                    $html .= "<li><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
                                    $html .= "{$tag->name}</a></li>";
                        }
                        $html .= '</ul>';
                        return $wrap . $html . $wrap_end;
            }
            elseif ( get_the_tag_list () ) {
                        return $wrap . get_the_tag_list ( '<ul><li>', '</li><li>', '</li></ul>' ) . $wrap_end;
            }
}

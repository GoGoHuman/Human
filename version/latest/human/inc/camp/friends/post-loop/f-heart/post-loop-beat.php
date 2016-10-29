<?php

/*
 *  @package Human Post Loop
 *  @author Sergei Pavlov <itpal24@gmail.com>
 *
 */


add_action ( 'vc_before_init', 'human_post_loop_vc_integrate' );

function human_post_loop_vc_integrate () {

            $Templatetitles = human_template_names ( 'human_loops', 'Related Posts Template' );
            $TemplateTitle[] = "--- Select ---";
            foreach ( $Templatetitles as $key => $title ) {
                        //print_r($title);
                        $TemplateTitle[ $title[ 'post_title' ] ] = $title[ 'post_title' ];
            }
            vc_map ( array (
                        "name" => __ ( "Human Posts", "human" ),
                        "base" => "human_posts_loop",
                        "class" => "human_posts_loop",
                        "category" => __ ( "Human Loops", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_looped_template",
                                                "heading" => __ ( "Related Posts Template", "human" ),
                                                "param_name" => "human_looped_template",
                                                "value" => $TemplateTitle,
                                                "description" => __ ( "Related Posts Template", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_excerpt_length",
                                                "heading" => __ ( "Excerpt Length", "human" ),
                                                "param_name" => "excerpt",
                                                "value" => 160,
                                                "description" => __ ( "Excerpt Length", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "post_ids",
                                                "heading" => __ ( "Order by post/page IDs", "human" ),
                                                "param_name" => "post_ids",
                                                "value" => "",
                                                "description" => __ ( "e.g. 2,6,12,53; !!! attention - only works when post_type is set to post or page", "human" )
                                    ), /*
                                      array (
                                      "type" => "textfield",
                                      "holder" => "div",
                                      "class" => "loop_id",
                                      "heading" => __ ( "Unique ID", "human" ),
                                      "param_name" => "loop_id",
                                      "value" => "",
                                      "description" => __ ( "e.g. right-sidebar-posts", "human" )
                                      ), */
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "cat_name",
                                                "heading" => __ ( "Related Posts by Category Name", "human" ),
                                                "param_name" => "cat_name",
                                                "value" => 'Current Category',
                                                "description" => __ ( "Note: you can find category and tag ids in url when you click on edit specific category in wp-admin->category or tag", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "post_children",
                                                "heading" => __ ( "Load child posts/pages of current", "human" ),
                                                "param_name" => "post_children",
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "post_type",
                                                "heading" => __ ( "Related Posts by Post_type", "human" ),
                                                "param_name" => "post_type",
                                                "value" => '',
                                                "description" => __ ( "You can change it any custom type you've created or 'page'", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "tag_name",
                                                "heading" => __ ( "Related Posts by Tag", "human" ),
                                                "param_name" => "tag_name",
                                                "value" => '',
                                                "description" => __ ( "Tag Slug - leave empty if is tag page", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_date_format",
                                                "heading" => __ ( "Related Posts Date Format", "human" ),
                                                "param_name" => "date",
                                                "value" => "d/m/y",
                                                "description" => __ ( "Related Posts PHP Date Format", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_thumb_size",
                                                "heading" => __ ( "Related Posts Thumbnail Size", "human" ),
                                                "param_name" => "thumb_size",
                                                "value" => 'thumbnail',
                                                "description" => __ ( "full,medium,thumbnail or numeric in pixels size/size or 0 to disable e.g. 160/160", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "post_number",
                                                "heading" => "Post Number",
                                                "param_name" => "post_number",
                                                "value" => 5,
                                                "description" => __ ( "Amount of posts to load before user interaction", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "load_more_button",
                                                "heading" => "Load More Button",
                                                "param_name" => "load_more_button",
                                                "value" => "load more",
                                                "description" => __ ( "Text for load more posts/products/custom-posts button", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "permalink_text",
                                                "heading" => __ ( "Permalink Text", "human" ),
                                                "param_name" => "permalink_text",
                                                "description" => __ ( "Text for read more button/link usualy after short content on each listed item", "human" )
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
                                                "description" => __ ( "Check this to auto load more posts/products/custom-posts if scrolled to the end of comments wrapper", "human" )
                                    ),
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "post_order",
                                                "heading" => __ ( "Post Order (Newer is default)", "human" ),
                                                "param_name" => "post_order",
                                                "value" => array (
                                                            "--- choose ---" => "",
                                                            "Older" => "ASC",
                                                            "Newer" => "DESC"
                                                ),
                                                "description" => __ ( "Choose filter to give users awasome ability to reorder posts", "human" ) ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "inline",
                                                "heading" => __ ( "Inline Rows", "human" ),
                                                "param_name" => "inline",
                                                "description" => __ ( "Check this to output looped content inline, good for mega menus", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "extra_class",
                                                "heading" => __ ( "CSS class", "human" ),
                                                "param_name" => "extra_class",
                                                "value" => "",
                                                "description" => __ ( "Additional CSS class if needed", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "human-search",
                                                "heading" => __ ( "human-search", "human" ),
                                                "param_name" => "human_search",
                                                "description" => __ ( "Check this to make this list searchable", "human" )
                                    )
                        )
                        )
            );

            vc_map ( array (
                        "name" => __ ( "Human Posts Details", "human" ),
                        "base" => "human_post_details",
                        "class" => "human_post_details",
                        "category" => __ ( "Human Loops", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "",
                                                "heading" => __ ( "Post Attributes", "human" ),
                                                "param_name" => "attr",
                                                "value" => array (
                                                            __ ( "--- Select ---", "human" ) => '',
                                                            __ ( "Post Title", "human" ) => 'Title',
                                                            __ ( "Post Date", "human" ) => 'Date',
                                                            __ ( "Post Thumb", "human" ) => 'Thumb',
                                                            __ ( "Post Author", "human" ) => 'Author',
                                                            __ ( "Post Excerpt", "human" ) => 'Excerpt',
                                                            __ ( "Post Content", "human" ) => 'Content',
                                                            __ ( "Post Permalink", "human" ) => 'Permalink',
                                                            __ ( "Post Yoast Title" ) => 'Yoast title',
                                                            __ ( "Post Yoast Description" ) => 'Yoast descripition' ),
                                                "description" => __ ( "Post Details Used in a Loop", "human" )
                                    )
                        )
                        )
            );

            vc_map ( array (
                        "name" => __ ( "Posts Filters", "human" ),
                        "base" => "human_posts_filters",
                        "class" => "human_posts_filters",
                        "category" => __ ( "Human Loops", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_posts_filter_type",
                                                "heading" => __ ( "Filter Type", "human" ),
                                                "param_name" => "filter_type",
                                                "value" => array (
                                                            "--- choose ---" => "",
                                                            "Older" => "ASC",
                                                            "Newer" => "DESC"
                                                ),
                                                "description" => __ ( "Choose filter to give users awasome ability to reorder posts", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_posts_filter_name",
                                                "heading" => __ ( "Filter Type Label", "human" ),
                                                "param_name" => "filter_name",
                                                "value" => __ ( "older", "human" ),
                                                "description" => __ ( "Give filter button own label e.g. older or new or newer or leave blank and add icons using Human Css Builder", "human" )
                                    ),
            ) ) );
}

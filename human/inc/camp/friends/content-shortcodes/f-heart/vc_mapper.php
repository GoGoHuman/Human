<?php

if ( ! is_admin () ) {
            exit;
}


add_action ( 'vc_before_init', 'human_vc_integrate' );

function human_vc_integrate () {

            $human_template_names = human_template_names ( 'human_widgets' );
            $human_templates_name[] = "--- Select ---";
            foreach ( $human_template_names as $key => $human_templates_n ) {
                        $human_templates_name[ $human_templates_n[ 'post_title' ] ] = $human_templates_n[ 'post_title' ];
            }
            vc_map ( array (
                        "name" => __ ( "Human Widgets", "human" ),
                        "base" => "human_widget",
                        "class" => "human_widget",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_widget_class",
                                                "heading" => __ ( "Human Widget", "human" ),
                                                "param_name" => "name",
                                                "value" => $human_templates_name,
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "hide_for_users",
                                                "heading" => __ ( "Hide for Logged in Users", "human" ),
                                                "param_name" => "hide_for_users",
                                                "description" => __ ( "Check if you don't want to load this template for logged in users", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "show_for_users",
                                                "heading" => __ ( "Show for Logged in Users Only", "human" ),
                                                "param_name" => "show_for_users",
                                                "description" => __ ( "Check if you want to load this template for logged in users only<br>e.g. Profile info or comment form", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "hide_for_mobiles",
                                                "heading" => __ ( "Hide for Mobiles", "human" ),
                                                "param_name" => "hide_for_mobiles",
                                                "description" => __ ( "Check if you don't want to load this template for Mobile Devices<br> Good for data-hungry sliders", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "show_for_mobiles",
                                                "heading" => __ ( "Show for Mobiles Only", "human" ),
                                                "param_name" => "show_for_mobiles",
                                                "description" => __ ( "Check if you want to load this template for Mobile Devices Only<br> You can create sliders for mobiles only that are not that heavy, and load them as a custom template", "human" )
                                    )
                        )
                        )
            );
            unset ( $human_templates_name );
            $human_template_names = human_template_names ();
            $human_templates_name[] = "--- Select ---";
            foreach ( $human_template_names as $key => $human_templates_n ) {
                        $human_templates_name[ $human_templates_n[ 'post_title' ] ] = $human_templates_n[ 'post_title' ];
            }
            vc_map ( array (
                        "name" => __ ( "Human Templates", "human" ),
                        "base" => "human_template",
                        "class" => "human_templates",
                        "category" => __ ( "Human Templates", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_templates",
                                                "heading" => __ ( "Human Template", "human" ),
                                                "param_name" => "name",
                                                "value" => $human_templates_name,
                                                "description" => __ ( "Human - Templates", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "hide_for_users",
                                                "heading" => __ ( "Hide for Logged in Users", "human" ),
                                                "param_name" => "hide_for_users",
                                                "description" => __ ( "Check if you don't want to load this template for logged in users", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "show_for_users",
                                                "heading" => __ ( "Show for Logged in Users Only", "human" ),
                                                "param_name" => "show_for_users",
                                                "description" => __ ( "Check if you want to load this template for logged in users only<br>e.g. Profile info or comment form", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "hide_for_mobiles",
                                                "heading" => __ ( "Hide for Mobiles", "human" ),
                                                "param_name" => "hide_for_mobiles",
                                                "description" => __ ( "Check if you don't want to load this template for Mobile Devices<br> Good for data-hungry sliders", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "show_for_mobiles",
                                                "heading" => __ ( "Show for Mobiles Only", "human" ),
                                                "param_name" => "show_for_mobiles",
                                                "description" => __ ( "Check if you want to load this template for Mobile Devices Only<br> You can create sliders for mobiles only that are not that heavy, and load them as a custom template", "human" )
                                    )
                        )
                        )
            );

            vc_map ( array (
                        "name" => __ ( "Human Form Fields", "human" ),
                        "base" => "human_form_elems",
                        "class" => "human_form_elems",
                        "category" => __ ( "Human Forms", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "form_field",
                                                "heading" => __ ( "Form Field", "human" ),
                                                "param_name" => "form_field",
                                                "value" => array (
                                                            __ ( "--- Select ---", "human" ) => "",
                                                            __ ( "Text Field", "human" ) => "text",
                                                            __ ( "Dropdown", "human" ) => "select",
                                                            __ ( "Large Text Area", "human" ) => "textarea",
                                                            __ ( "Calendar", "human" ) => "calendar",
                                                            __ ( "Checkbox", "human" ) => "checkbox",
                                                            __ ( "Radio Button", "human" ) => "radio",
                                                            __ ( "Submit Button", "human" ) => "submit",
                                                            __ ( "File Upload", "human" ) => "file",
                                                            __ ( "--- Comment Fields ---", "human" ) => "",
                                                            __ ( "Author", "human" ) => "comment_author",
                                                            __ ( "Email", "human" ) => "comment_email",
                                                            __ ( "Url", "human" ) => "comment_url",
                                                            __ ( "Comment area", "human" ) => "comment",
                                                            __ ( "Hidden Field", "human" ) => "hidden",
                                                            __ ( "Rating Field", "human" ) => "star_rating"
                                                ),
                                                "description" => __ ( "Choose any form field", "human" )
                                    ),
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "form_elem_type",
                                                "heading" => __ ( "Element Meta Type (Only for custom fields, ignore default comment and rating elements)", "human" ),
                                                "param_name" => "form_elem_type",
                                                "value" => array (
                                                            __ ( "---select---", "human" ) => "",
                                                            "user_meta" => "user_meta",
                                                            "post_meta" => "post_meta",
                                                            "comment_meta" => "comment_meta" ),
                                                "description" => __ ( "Select WP meta type for this field e.g. user_meta will be saved in DataBase as user_meta and can be accesses via get_user_meta(\$user_id,'field_name') php function", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "elem_name",
                                                "heading" => __ ( "Element Name ( name='' )", "human" ),
                                                "param_name" => "name",
                                                "description" => __ ( "Element url friendly name for server processing", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "elem_placeholder",
                                                "heading" => __ ( "Element Placeholder", "human" ),
                                                "param_name" => "placeholder",
                                                "description" => __ ( "Placeholders used as a temporary label inside element ", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "elem_value",
                                                "heading" => __ ( "Element Default Value", "human" ),
                                                "param_name" => "elem_value",
                                                "description" => __ ( "You can set default value for father processing", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "elem_unique_id",
                                                "heading" => __ ( "Element Unique ID", "human" ),
                                                "param_name" => "unique_id",
                                                "description" => __ ( "Set unique ID for this element, useful for checkboxes and radio buttons", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "elem_title",
                                                "heading" => __ ( "Element Label", "human" ),
                                                "param_name" => "label",
                                                "description" => __ ( "Use this for label value and to improve readability for screen reading devices", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "dropdown_options",
                                                "heading" => __ ( "Dropdown Options", "human" ),
                                                "param_name" => "options",
                                                "description" => __ ( "Add coma separated values e.g. option1,option2 etc.<br>Use this for dropdown elements only ", "human" )
                                    ),
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "elem_required",
                                                "heading" => __ ( "Validation (*required)", "human" ),
                                                "param_name" => "required",
                                                "value" => array (
                                                            __ ( "Not required", "human" ) => "",
                                                            __ ( "Required", "human" ) => "required" ),
                                                "description" => __ ( "Select required to enable Human form validation", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "elem_required_text",
                                                "heading" => __ ( "Required Text", "human" ),
                                                "param_name" => "required_text",
                                                "value" => __ ( "*", "human" ),
                                                "description" => __ ( "", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "validation_error_msg",
                                                "heading" => __ ( "Validation Error Message", "human" ),
                                                "param_name" => "validation_error_msg",
                                                "value" => __ ( "* correct an error", "human" ),
                                                "description" => __ ( "e.g. 'corrent an error'", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "selected",
                                                "heading" => __ ( "Check this to auto select", "human" ),
                                                "param_name" => "selected",
                                                "description" => __ ( "Good for checkboxes and radio buttons", "human" )
                                    )
            ) ) );
            vc_map ( array (
                        "name" => __ ( "Human Links to Categories", "human" ),
                        "base" => "human_get_categories",
                        "class" => "human_get_categories",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "content_type",
                                                "heading" => __ ( "Human Links to Categories", "human" ),
                                                "param_name" => "content_type",
                                                "value" => array (
                                                            __ ( "Links to All Categoreis", "human" ) => "the_category",
                                                            __ ( "Child Categories", "human" ) => "children",
                                                ),
                                                "heading" => __ ( "Human Links to Categories", "human" ),
                                    ), ) ) );

            vc_map ( array (
                        "name" => __ ( "Human User Meta", "human" ),
                        "base" => "human_user_meta",
                        "class" => "human_user_meta",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_user_meta_field",
                                                "heading" => __ ( "User - Meta", "human" ),
                                                "param_name" => "user_meta",
                                                "description" => __ ( "e.g. first_name, last_name, nick_name, avatar
                                                           <br>Full explanation at ", "human" ) . "<a href='https://human.camp/academy/contents/user-meta'>https://human.camp/academy/contents/user-meta</a>",
                                    )
                        ),
            ) );

            vc_map ( array (
                        "name" => __ ( "Human Post Contents", "human" ),
                        "base" => "human_post_contents",
                        "class" => "human_post_contents",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_post_content_type",
                                                "heading" => __ ( "Choose Content Type", "human" ),
                                                "param_name" => "content_type",
                                                "value" => array (
                                                            __ ( "--- Select ---", "human" ) => "",
                                                            __ ( "Title", "human" ) => "Title",
                                                            __ ( "Date", "human" ) => "Date",
                                                            __ ( "Author", "human" ) => "Author",
                                                            __ ( "Featured Image", "human" ) => "Image",
                                                            __ ( "Content", "human" ) => "Content",
                                                            __ ( "Post Excerpt", "human" ) => 'Excerpt',
                                                            __ ( "Links to related categories", "human" ) => "the_category",
                                                            __ ( "Rating", "human" ) => "Rating"
                                                ),
                                                "description" => __ ( "Choose Post Content Type", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "image_size'",
                                                "heading" => __ ( "Featured Image Size", "human" ),
                                                "param_name" => "image_size",
                                                "value" => "",
                                                "description" => __ ( "e.g. full, medium, thumbnail or in pixels e.g. 120/120 <br> ref: https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "date_format'",
                                                "heading" => __ ( "Date Format", "human" ),
                                                "param_name" => "date_format",
                                                "value" => "",
                                                "description" => __ ( "PHP date format <br> Default: d/m/y<br>ref: https://codex.wordpress.org/Formatting_Date_and_Time", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "author_names'",
                                                "heading" => __ ( "Author Details", "human" ),
                                                "param_name" => "author_names",
                                                "value" => "",
                                                "description" => __ ( "e.g. user_nicename,first_name,last_name <br> Coma separated without whitespace ref: https://codex.wordpress.org/Function_Reference/the_author_meta", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "label_content'",
                                                "heading" => __ ( "Label Content", "human" ),
                                                "param_name" => "label_content",
                                                "value" => ""
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "label_wrapper'",
                                                "heading" => __ ( "Label Wrapper", "human" ),
                                                "param_name" => "label_wrapper",
                                                "value" => "",
                                                "description" => __ ( "e.g. h2 or h3", "human" )
                                    ),
                        )
                        )
            );
            unset ( $TemplateTitle );
            vc_map (
                        array (
                                    "name" => __ ( "Social Login", "human" ),
                                    "base" => "wordpress_social_login",
                                    "class" => "wordpress_social_login",
                                    "category" => __ ( "Human Content" ),
                                    "icon" => human_icon (),
                        )
            );




            $formtitles = human_template_names ( 'human_forms' );
            $formtitle[] = "--- Select ---";
            foreach ( $formtitles as $key => $title ) {
                        $formtitle[ $title[ 'post_title' ] ] = $title[ 'post_title' ];
            }


            vc_map ( array (
                        "name" => __ ( "Human Archive Title", "human" ),
                        "base" => "human_archive_titles",
                        "class" => "human_archive_title",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_archive_class",
                                                "heading" => __ ( "Human Archive Title Class", "human" ),
                                                "param_name" => "archive_class",
                                                "value" => "",
                                                "description" => __ ( "Additional CSS class name", "human" )
                                    ) )
            ) );
            vc_map ( array (
                        "name" => __ ( "Human Separator", "human" ),
                        "base" => "human_separators",
                        "class" => "human_separator",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_separator_class",
                                                "heading" => __ ( "CSS Class", "human" ),
                                                "param_name" => "separator_css_class",
                                                "value" => "",
                                                "description" => __ ( "Additional CSS class", "human" )
                                    ) )
            ) );
            $cookieboxes = human_template_names ( 'human_widgets', 'Cookie Box' );
            $cookiebox[ "--- Select ---" ] = "";
            foreach ( $cookieboxes as $key => $title ) {
                        $cookiebox[ $title[ 'post_title' ] ] = $title[ 'post_title' ];
            }
            vc_map ( array (
                        "name" => __ ( "Cookie Box", "human" ),
                        "base" => "human_cookie_boxes",
                        "class" => "human_cookies",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_cookie_box",
                                                "heading" => __ ( "Cookie boxes", "human" ),
                                                "param_name" => "human_cookie_box",
                                                "value" => $cookiebox,
                                                "description" => __ ( "Select cookie box template", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_cookie_class",
                                                "heading" => __ ( "CSS Class", "human" ),
                                                "param_name" => "human_cookie_class",
                                                "value" => "",
                                                "description" => __ ( "Additional CSS class", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "human_cookie_auto",
                                                "heading" => __ ( "Automaticly Set Cookie", "human" ),
                                                "param_name" => "cookie_auto",
                                                "value" => "",
                                                "description" => __ ( "Check this box to automaticly set cookie after page load", "human" )
                                    ),
                                    array (
                                                "type" => "checkbox",
                                                "holder" => "div",
                                                "class" => "human_cookie_link",
                                                "heading" => __ ( "Set Cookie and hide box after link click", "human" ),
                                                "param_name" => "cookie_link",
                                                "value" => "",
                                                "description" => __ ( "Note: toggle link must have class 'toggle'", "human" )
                                    ),
                        )
            ) );

            $mailchimp_lists[ '---Select---' ] = '';
            $mailchimp_lists = array_merge ( $mailchimp_lists, human_mailchimp_lists () );
            vc_map ( array (
                        "name" => __ ( "Human Forms", "human" ),
                        "base" => "human_form",
                        "class" => "human_form",
                        "category" => __ ( "Human Forms", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "form_template",
                                                "heading" => __ ( "Form Template", "human" ),
                                                "param_name" => "form_template",
                                                "value" => $formtitle,
                                                "description" => __ ( "Form Template", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "form_name",
                                                "heading" => __ ( "Form Name", "human" ),
                                                "param_name" => "form_id",
                                                "value" => "",
                                                "description" => __ ( "Url friendly form name e.g. contact_form", "human" )
                                    ),
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "form_processing_type",
                                                "heading" => __ ( "Form Type", "human" ),
                                                "param_name" => "form_processing_type",
                                                "value" => array (
                                                            __ ( "--- Choose Process Method ---", "human" ) => "",
                                                            __ ( "Comment", "human" ) => "comment",
                                                            __ ( "User Meta", "human" ) => "user_meta"
                                                ),
                                                "description" => __ ( "Available Params: comment, user_meta, leave empty for email processing", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "form_success",
                                                "heading" => __ ( "Form Sucess Message", "human" ),
                                                "param_name" => "success",
                                                "value" => "",
                                                "description" => __ ( "e.g. Thank you for you interest, will be in touch with you shortly", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "form_fail",
                                                "heading" => __ ( "Form Fail Message", "human" ),
                                                "param_name" => "fail",
                                                "value" => "",
                                                "description" => __ ( "e.g. Sorry there was an error submitting this form", "human" )
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "form_department_email",
                                                "heading" => __ ( "Admin Notification Email", "human" ),
                                                "param_name" => "form_department_email",
                                                "value" => "",
                                                "description" => __ ( "Department email e.g. support@", "human" ) . explode ( '//', get_site_url () )[ 1 ] . " " . __ ( " Leave blank to disable", "human" )
                                    ),
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_mailchimp",
                                                "heading" => __ ( "MailChimp Lists" ),
                                                "param_name" => "mailchimp_lists",
                                                "value" => $mailchimp_lists,
                                                "description" => __ ( "Integrate this form with existing MailChimp list", "human" )
                                    )
            ) ) );
}

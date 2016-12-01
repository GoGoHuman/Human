<?php

function human_comment_looper($key, $val, $attr, $user_metas_simple, $archive_layout, $comment_form_metas, $date_format, $post_id, $user_api_meta, $human_comment_form_metas, $human_looped_template, $user, $comment_user) {
            $string = '';
            global $wpdb;
            foreach ($user_metas_simple as $usr_meta) {
                        if (isset($user->$usr_meta)) {
                                    if ($usr_meta == 'avatar') {
                                                $metas[] = human_user_avatar($user->user_email);
                                    } elseif (!empty($user->$usr_meta)) {
                                                $metas[] = $user->$usr_meta;
                                    } else {

                                                $metas[] = '&nbsp;';
                                    }
                        } elseif (is_array($comment_user)) {
                                    if ($usr_meta == 'avatar') {
                                                $metas[] = human_user_avatar($comment_user['comment_user']);
                                    } elseif ($usr_meta === 'display_name') {
                                                $q = get_comment_author($comment_user['comment_id']);
                                                $metas[] = $q;
                                    } else {

                                                $metas[] = '&nbsp;';
                                    }
                        } elseif ($usr_meta == 'avatar') {
                                    $metas[] = human_user_avatar($user->user_email);
                        } else {
                                    $metas[] = '&nbsp;';
                        }
            }


            foreach ($comment_form_metas as $c_meta) {

                        $comments_args = array(
                                    'parent' => $val->comment_ID,
                                    'post_id' => $post_id,
                        );

                        $itemPropWrap = array('<div class="comment-wrapper" id="' . $val->comment_ID . '">');
                        //$all_comments=get_comments($comments_args);
                        if (isset($val->$c_meta) && !empty($val->$c_meta) && $c_meta !== 'thumbs_up' && $c_meta !== 'thumbs_down' && $c_meta != 'comment_flag') {
                                    if ($c_meta === 'comment_date') {
                                                $comment_form_metas_to[] = '<time itemprop="datePublished" datetime="' . date('c', strtotime($val->$c_meta)) . '">' . date($date_format, strtotime($val->$c_meta)) . '</time>';
                                    } elseif ($c_meta === 'comment_content') {
                                                $comment_form_metas_to[] = '<div itemprop="description">' . $val->$c_meta . '</div>';
                                    } else {
                                                $comment_form_metas_to[] = $val->$c_meta;
                                    }
                        } else {

                                    if (get_comment_meta($val->comment_ID, $c_meta)) {

                                                if ($c_meta === 'rating') {

                                                            $rating = get_comment_meta($val->comment_ID, 'rating')[0];
                                                            $comment_form_metas_to[] = '
                                                                                                         <div itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating" class="human-star-rating" title="' . __("Rated", "human") . ' ' . $rating . ' ' . __("out of", "human") . ' 5">' . human_star_rating(array('rated' => $rating)) . '<div itemProp="ratingValue" class="itemProp">' . $rating . '</div></div>';

                                                            $itemPropWrap[0] = '<div itemprop="review" itemscope="" itemtype="http://schema.org/Review"  id="' . $val->comment_ID . '"  class="comment-wrapper">';
                                                } elseif ($c_meta === 'thumbs_up') {

                                                            $thumbs_up = array_sum(get_comment_meta($val->comment_ID, 'thumbs_up'));
                                                            $comment_form_metas_to[] = '<div class="comment_thumbs"><i class="fa fa-thumbs-o-up comment_thumb_up" data-comment-id="' . $val->comment_ID . '" data-thumb="thumbs_up"></i><div class="comment_thumb_up_holder" id="thumbs_up' . $val->comment_ID . '">' . $thumbs_up . '</div></div>';
                                                } elseif ($c_meta === 'thumbs_down') {

                                                            $thumbs_down = array_sum(get_comment_meta($val->comment_ID, 'thumbs_down'));
                                                            $comment_form_metas_to[] = '<div class="comment_thumbs"><i class="fa fa-thumbs-o-down comment_thumb_down" data-comment-id="' . $val->comment_ID . '" data-thumb="thumbs_down"></i><div class="comment_thumb_down_holder" id="thumbs_down' . $val->comment_ID . '">' . $thumbs_down . '</div></div>';
                                                } elseif ($c_meta === 'comment_flag') {

                                                            $comment_form_metas_to[] = '<div class="comment_flag"><i class="fa fa-flag-o comment_flag_holder" id="flag' . $val->comment_ID . '" data-comment-id="' . $val->comment_ID . '"></i></div>';
                                                } else {
                                                            $comment_form_metas_to[] = implode(get_comment_meta($val->comment_ID, $c_meta));
                                                }
                                    } elseif ($c_meta === 'comment_reply_link') {
                                                //print_r($c_meta);
                                                if (isset($attr['extras'])) {
                                                            $link_text = $attr['extras'];
                                                } else {
                                                            $link_text = 'reply';
                                                }
                                                $comment_form_metas_to[] = '<div class="comment-reply-link-wrapper"><a href="#" data-comment-id="' . $val->comment_ID . '">' . $link_text . '</a></div>';
                                    } elseif ($c_meta === 'thumbs_up') {
                                                $thumbs_up = array_sum(get_comment_meta($val->comment_ID, 'thumbs_up'));
                                                $comment_form_metas_to[] = '<div class="comment_thumbs"><i class="fa fa-thumbs-o-up comment_thumb_up" data-comment-id="' . $val->comment_ID . '" data-thumb="thumbs_up"></i><div class="comment_thumb_up_holder" id="thumbs_up' . $val->comment_ID . '">' . $thumbs_up . '</div></div>';
                                    } elseif ($c_meta === 'thumbs_down') {

                                                $thumbs_down = array_sum(get_comment_meta($val->comment_ID, 'thumbs_down'));
                                                $comment_form_metas_to[] = '<div class="comment_thumbs"><i class="fa fa-thumbs-o-down comment_thumb_down" data-comment-id="' . $val->comment_ID . '" data-thumb="thumbs_down"></i><div class="comment_thumb_down_holder" id="thumbs_down' . $val->comment_ID . '">' . $thumbs_down . '</div></div>';
                                    } elseif ($c_meta === 'comment_flag') {


                                                $comment_form_metas_to[] = '<div class="comment_flag"><i class="fa fa-flag-o comment_flag_holder" id="flag' . $val->comment_ID . '" data-comment-id="' . $val->comment_ID . '"></i></div>';
                                    } else {
                                                $comment_form_metas_to[] = '&nbsp;';
                                    }
                        }
            }
            $comment_parent_ID = $val->comment_ID;
            $child_class = 'comment-reply ';
            $children = '';
            $children_strings = '';
            $children = $wpdb->get_results("SELECT comment_ID FROM " . $wpdb->prefix . "comments WHERE comment_parent = '" . $comment_parent_ID . "'", ARRAY_N);
            $children_strings = [];
            if (!empty($children)) {
                        $j = 0;
                        $children = array_reverse($children);
                        if (isset($attr['level'])) {
                                    $level = $attr['level'] + 1;
                                    $expand = '';
                        } else {
                                    $level = 0;
                                    $expand = '<div class="bottom-expand text-center"><a class="expand-reply-comments">expand <span class="fa fa-caret-down"></span></a></div>';
                        }
                        $levels = 'level-' . $level;
                        foreach ($children[0] as $key => $child) {
                                    $j++;
                                    $children_strings[$j] = '<div class="' . $child_class . ' ' . $levels . '">' . $expand . '' . str_replace('@comment_id@', $child, do_shortcode('[human_comment_loops human_looped_template="' . $human_looped_template . '" loop_id="' . $attr['loop_id'] . '" post_id="' . $post_id . '" date_format="' . $date_format . '" comment_number="" comment_parent="' . $comment_parent_ID . '" comment_reply="true" comment_order="DESC" level="' . $level . '"]')) . '</div>';
                        }
                        $children_strings = array_reverse($children_strings);
                        //  $string .= '<br>----'.$comment_parent_ID.'---<hr>';
            } else {
                        //  $string .= '<hr>'.$children.'<hr>';
            }
            $comment_children_wrap = '';
            if (isset($attr['comment_parent'])) {
                        $comment_children_wrap = ''; //'@comment_children@';
                        $comment_children_wrap .=str_replace('@comment_id@', $comment_parent_ID, implode($children_strings));
            } else {
                        $comment_children_wrap = '';
                        if (is_array($children_strings)) {
                                    //print_r(array_reverse(array_unique(array_merge($children_strings))));
                                    //print_r($children_strings);
                                    $comment_children_wrap .= str_replace('@comment_id@', $comment_parent_ID, implode(array_unique(array_merge($children_strings))));
                        }
            }
            /*
              print_r($user_api_meta);
              print_r($metas);
              print('<hr>');
              print_r($human_comment_form_metas);
              print_r($comment_form_metas_to);
              print('<hr>');
             */
            $archive_layout1 = str_replace($user_api_meta, $metas, $itemPropWrap[0] . $archive_layout . '</div>' . $comment_children_wrap);
            $archive_layout2 = str_replace($human_comment_form_metas, $comment_form_metas_to, $archive_layout1);

            $string .=str_replace('@comment_id@', $comment_parent_ID, $archive_layout2);
            return $string;
}

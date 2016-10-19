<?php
add_shortcode ( 'human_archive_titles', 'human_archive_titles' );

function human_archive_titles ( $attr ) {
            $class = '';
            if ( isset ( $attr[ 'archive_class' ] ) ) {
                        $class = $attr[ 'archive_class' ];
            }

            return '<div class="' . $class . '" ><h1>' . str_replace ( '_', ' ', get_post_type () ) . '</h1></div>';
}

add_filter ( 'human_post_contents', 'do_shortcode' );

function human_primary_menu ( $attr ) {
            return do_shortcode ( '[maxmegamenu location=' . $attr . ']' );
}

add_shortcode ( 'human_post_contents', 'human_post_contents' );

function human_post_contents ( $attr = null ) {
            if ( isset ( $attr ) && isset ( $attr[ 'content_type' ] ) ) {
                        $id = get_the_ID ();
                        $type = $attr[ 'content_type' ];
                        if ( $type === 'Title' ) {
                                    return '<div  class="post-title"><h1 itemprop="name">' . get_the_title ( $id ) . '</h1></div>';
                        }
                        elseif ( $type === 'Date' ) {
                                    if ( isset ( $attr[ 'date_format' ] ) ) {
                                                $format = $attr[ 'date_format' ];
                                    }
                                    else {
                                                $format = 'd/m/y';
                                    }
                                    return '<time class="post-date" datetime="' . date ( DATE_ATOM, get_the_date ( $id, 'd-m-y h-m-s ' ) ) . '">' . get_the_date ( $id, $format ) . '</time>';
                        }
                        elseif ( $type === 'Permalink' ) {
                                    $adon_class = '';
                                    if ( isset ( $attr[ 'adon_class' ] ) ) {
                                                $adon_class = $attr[ 'adon_class' ];
                                    }
                                    $link_text = '';
                                    if ( isset ( $attr[ 'permalink_text' ] ) ) {
                                                $link_text = $attr[ 'permalink_text' ];
                                    }
                                    return '<a href="' . get_permalink ( $id ) . '" class="' . $adon_class . '">' . $link_text . '</a>';
                        }
                        elseif ( $type === 'Image' ) {

                                    if ( isset ( $attr[ 'image_size' ] ) ) {
                                                if ( strpos ( $attr[ 'image_size' ], '/' ) ) {
                                                            $size = explode ( '/', $attr[ 'image_size' ] );
                                                }
                                                else {
                                                            $size = $attr[ 'image_size' ];
                                                }
                                    }
                                    else {
                                                $size = 'full';
                                    }
                                    if ( isset ( $attr[ 'align' ] ) ) {
                                                $align = $attr[ 'align' ];
                                    }
                                    else {
                                                $align = 'center';
                                    }
                                    return '<div class="post-featured-img" style="">' . get_the_post_thumbnail ( $id, $size ) . '</div>';
                        }
                        elseif ( $type === 'Content' ) {
                                    $content_post = get_post ( $id );
                                    $content = '';
                                    if ( isset ( $content_post->post_content ) ) {
                                                $content = $content_post->post_content;
                                                $content = apply_filters ( 'the_content', $content );
                                                $content = str_replace ( ']]>', ']]&gt;', $content );
                                    }
                                    return '<div class="post-content">' . $content . '</div>';
                        }
                        elseif ( $type === 'Author' ) {
                                    $author_id = get_post_field ( 'post_author', $id );
                                    if ( isset ( $attr[ 'author_names' ] ) ) {
                                                $names = explode ( ',', trim ( $attr[ 'author_names' ] ) );
                                                $author = '';
                                                foreach ( $names as $key => $val ) {
                                                            $author .= '<span class="post-author-meta">' . the_author_meta ( $val, $author_id ) . '</span>';
                                                }
                                                return '<div class="post-author" style="">' . $author . '</div>';
                                    }
                        }
                        elseif ( $type === 'Rating' ) {
                                    $count = 0;
                                    $extra = '';
                                    $comments = get_approved_comments ( get_the_ID () );
                                    $i = 1;
                                    $total = 0;
                                    $total_rating = 0;
                                    foreach ( $comments as $key => $v ) {
                                                if ( get_comment_meta ( $v->comment_ID, 'rating' ) ) {
                                                            $i ++;
                                                            $rating = get_comment_meta ( $v->comment_ID, 'rating' )[ 0 ];
                                                            $total +=$i;
                                                            $total_rating += $rating;
                                                            $all[] = $rating;
                                                }
                                    }
                                    $total_count = count ( $all );
                                    if ( ! empty ( $total_rating ) ) {
                                                $count = round ( ((5 * $total_count / 100) - ($total_rating / 100)) * 100, 1 );
                                    }
                                    $star = '';
                                    for ( $j = 1; $j < 6; $j ++ ) {
                                                $d = $j - 0.5;
                                                if ( $count >= $j ) {
                                                            $star .= '<span class="fa fa-star"></span>';
                                                }
                                                elseif ( $count < $j && $count >= $d ) {
                                                            $star .= '<span class="fa fa-star-half "></span>';
                                                }
                                                else {

                                                            $star .= '<span class="fa fa-star-o"></span>';
                                                }
                                    }
                                    $extra = ' <div class="human-post-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="human-star-rating" title="' . __ ( 'Rated ', 'human' ) . $count . __ ( ' out of 5', 'human' ) . '
                                                                        <span style="width:' . $count . 'px">
				<strong itemprop="ratingValue" class="rating">' . $count . '</strong>
                                                                                                <span itemprop="bestRating">' . max ( $all ) . '</span>
			                        <span itemprop="ratingCount" class="rating">' . $total_count . '</span>
			</span>
                                                                        <span class="human-stars">' . $star . '</span>
		</div>

	       </div>';
                                    return $extra;
                        }
            }
}

add_shortcode ( 'human_get_categories', 'human_get_categories' );

function human_get_categories ( $attr = null ) {
            if ( isset ( $attr[ 'content_type' ] ) ) {
                        if ( $attr[ 'content_type' ] === 'children' ) {

                                    $this_cat = get_query_var ( 'cat' ); // get the category of this category archive page
                                    return '<div class="category_links"><ul class="">' . wp_list_categories ( 'child_of=' . $this_cat . '&echo=0&title_li' ) . '</ul></div>'; // list child categories
                        }
                        else {

                                    return '<div class="category_links"><ul>' . the_category () . '</ul></div>';
                        }
            }
}

function human_get_post_date ( $attr = null ) {

            if ( isset ( $attr[ 'id' ] ) ) {
                        $id = $attr[ 'id' ];
            }
            else {
                        $id = get_the_ID ();
            }
            if ( isset ( $attr[ 'format' ] ) ) {
                        $format = $attr[ 'format' ];
            }
            else {
                        $format = 'd/m/y';
            }
            return '<time class="post-date" itemprop="date"  datetime="' . date ( DATE_ATOM, strtotime ( get_the_date ( $format, $id ) ) ) . '">'
                        . get_the_date ( $format, $id ) . '</time>';
}

add_shortcode ( 'human_get_comments', 'human_get_comments' );

function human_get_comments () {

//echo do_shortcode('[comment comment_file ="/comments.php"]');
}

add_shortcode ( 'human_separator', 'human_separator' );

function human_separator ( $attr = null ) {
            $class = '';
            if ( isset ( $attr[ 'css_class' ] ) ) {
                        $class = $attr[ 'css_class' ];
            }
            return '<div class="human_separator ' . $class . '">&nbsp;</div>';
}

function human_get_user_meta ( $type, $user_id = null ) {
            if ( ! isset ( $user_id ) ) {
                        $user_id = wp_get_current_user ()->data->ID;
            }
            return get_user_meta ( $user_id, $type );
}

add_shortcode ( 'human_comment_author_meta', 'human_comment_author_meta' );

function human_comment_author_meta ( $meta_name ) {
            $meta = strtolower ( $meta_name[ 'meta_name' ] );
            $item_prop = '';
            if ( $meta === 'display_name' ) {
                        $item_prop = 'itemprop="author"';
            }
            elseif ( $meta === 'avatar' ) {
                        $item_prop = 'itemprop="image"';
            }
            return '<div class="' . $meta . '" ' . $item_prop . '>@author_meta_' . $meta_name[ 'meta_name' ] . '@</div>';
}

add_shortcode ( 'human_comment_details', 'human_comment_details' );

function human_comment_details ( $attr ) {
// print_r($attr['attr'].'<hr>');
            return '<div class="' . strtolower ( $attr[ 'attr' ] ) . '">@human_get_' . strtolower ( $attr[ 'attr' ] ) . '@</div>';
}

function human_get_post_image ( $attr = null ) {


            $id = get_the_ID ();

            if ( isset ( $attr[ 'size' ] ) ) {
                        $size = explode ( ',', $attr[ 'size' ] );
            }
            else {
                        $size = 'full';
            }
            if ( isset ( $attr[ 'align' ] ) ) {
                        $align = $attr[ 'align' ];
            }
            else {
                        $align = 'center';
            }
            return '<div class="post-featured-img" style="background:url(\'' . get_the_post_thumbnail_url ( $id, $size ) . '\')"></div>';
}

add_shortcode ( 'human_post_details', 'human_post_details' );

function human_post_details ( $attr ) {
//  print_r($attr['attr'].'<hr>');
            $type = strtolower ( $attr[ 'attr' ] );

            $count = 0;
            $extra = '';
            if ( $type === 'rating' ) {
                        $comments = get_approved_comments ( get_the_ID () );
                        $i = 1;
                        $total = 0;
                        $total_rating = 0;
                        foreach ( $comments as $key => $v ) {
                                    if ( get_comment_meta ( $v[ 'comment_ID' ], 'rating' ) ) {
                                                $i ++;
                                                $rating = get_comment_meta ( $v[ 'comment_ID' ], 'rating' );
                                                $total +=$i;
                                                $total_rating += $rating;
                                                $all[] = $rating;
                                    }
                        }
                        if ( ! empty ( $total_rating ) ) {
                                    $count = ($total * 5) / 100 * $total_rating;
                        }
                        $extra = ' <div class="human-post-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="human-star-rating" title="' . __ ( 'Rated ', 'human' ) . $count . __ ( ' out of 5', 'human' ) . '
                                                                        <span style="width:' . $count . 'px">
				<strong itemprop="ratingValue" class="rating"></strong>
                                                                                                <span itemprop="bestRating">' . max ( $count ) . '</span>
			                        <span itemprop="ratingCount" class="rating">' . count ( $all ) . '</span>
			</span>
		</div>

	       </div>';
            }
            return '<div class="' . strtolower ( $attr[ 'attr' ] ) . '">' . $extra . '@human_get_' . strtolower ( $attr[ 'attr' ] ) . '@</div>';
}

function human_get_option_like ( $like ) {
            global $wpdb, $table_prefix;
            $search_query = 'SELECT option_value FROM ' . $table_prefix . 'options
                              WHERE option_name LIKE %s';

            $like = '%' . $like . '%';
            return $wpdb->get_results ( $wpdb->prepare ( $search_query, $like ), ARRAY_A );
}

function sortArrayByArray ( array $array, array $orderArray ) {
            $ordered = array ();
            foreach ( $orderArray as $key ) {
                        if ( array_key_exists ( $key, $array ) ) {
                                    $ordered[ $key ] = $array[ $key ];
                                    unset ( $array[ $key ] );
                        }
            }
            return $ordered + $array;
}

function human_rewrite_urls ( $content, $curl ) {

            $url = urlencode_deep ( $curl );
            $humanUrl = '';
            if ( strpos ( $content, '@human-url' ) !== false ) {
                        $humanUrl = explode ( '@', explode ( '@human-url-', $content )[ 1 ] )[ 0 ];
            }

            $facebook = '//www.facebook.com/sharer/sharer.php?u=' . $url . '" class="human-social-buttons"';
            $twitter = '//twitter.com/intent/tweet?url=' . $url . '" class="human-social-buttons"';
            $gplus = '//plus.google.com/share?url=' . $url . '" class="human-social-buttons"';
            $replace = array (
                        '@facebook-share@"',
                        '@twitter-share@"',
                        '@googleplus-share@"',
                        '@permalink@',
                        '@human-url-' . $humanUrl . '@' );
            $to = array (
                        $facebook,
                        $twitter,
                        $gplus,
                        $curl,
                        get_site_url () . '/' . $humanUrl );
            return str_replace ( $replace, $to, $content );
}

add_shortcode ( 'human_posts_filters', 'human_posts_filters' );

function human_posts_filters ( $attr ) {
            if ( $attr[ 'filter_type' ] === 'ASC' ) {
                        $label = 'older';
            }
            else {

                        $label = 'latest';
            }
            if ( isset ( $attr[ 'filter_name' ] ) ) {
                        $label = $attr[ 'filter_name' ];
            }
            return '<a href="?post-order=' . $attr[ 'filter_type' ] . '">' . $label . '</a>';
}

// Add a shortcode
add_shortcode ( 'human_posts_loop', 'human_posts_loop' );
add_shortcode ( 'human_do_shortcode', 'human_do_shortcode' );

function human_do_shortcode ( $shortcode ) {

            ob_start ();
            ?> <?php echo do_shortcode ( $shortcode ); ?>.'<?php
            return ob_get_clean ();
}

// Enable shortcodes in text widgets
add_filter ( 'human_posts_loop', 'do_shortcode' );

function human_posts_loop ( $attr = null ) {
// the query

            $catname = '';
            $string = '';

            $tagname = '';


            if ( isset ( $attr[ 'cat_name' ] ) ) {

                        $catname = $attr[ 'cat_name' ];
            }
            else {
                        if ( is_category () ) {
                                    $catname = get_category ( get_query_var ( 'cat' ) )->slug;
                        }
                        else {
                                    $catname = '';
                        }
            }

            $extraclass = '';
            if ( isset ( $attr[ 'extra_class' ] ) ) {
                        $extraclass = $attr[ 'extra_class' ] . ' ';
            }
            if ( isset ( $attr[ 'tag_name' ] ) ) {

                        $tagname = $attr[ 'tag_name' ];
            }
            else {
                        if ( is_tag () ) {
                                    $tagname = get_tag ( get_query_var ( 'tag' ) )->slug;
                        }
                        else {
                                    $tagname = '';
                        }
            }
            $post_id = get_the_id ();

            if ( isset ( $attr[ 'post_id' ] ) ) {
                        $post_id = $attr[ 'post_id' ];
            }
            elseif ( is_category () ) {
                        $post_id = 'category_' . $catname;
            }
            elseif ( is_archive () ) {

                        $post_id = 'archive_' . get_post_type ();
            }
            elseif ( is_tag () ) {
                        $post_id = 'tag_' . $tagname;
            }

//print_r ( $post_id );

            $paged = 1;
            if ( isset ( $attr[ 'paged' ] ) ) {
                        $paged = $attr[ 'paged' ];
            }
            $pages = $paged + 1;
            $post_number = 5;
            if ( isset ( $attr[ 'post_number' ] ) ) {
                        $post_number = $attr[ 'post_number' ];
            }

//print_r($attr);

            $post_order = 'DESC';
            if ( isset ( $attr[ 'post_order' ] ) ) {
                        $post_order = $attr[ 'post_order' ];
            }
            if ( isset ( $_GET[ 'post-order' ] ) ) {
                        $post_order = esc_html ( $_GET[ 'post-order' ] );
            }
            $post_type = 'post';
            if ( isset ( $attr[ 'post_type' ] ) ) {
                        $post_type = $attr[ 'post_type' ];
            }
            $post_parent = '';
            if ( isset ( $attr[ 'post_children' ] ) ) {
                        if ( ! is_archive () ) {
                                    $post_parent = $post_id;
                        }
                        else {
                                    $post_type = get_post_type ();
                        }
            }

            $l_query = '';
            if ( isset ( $attr[ 'post_ids' ] ) ) {

                        $l_query = array (
                                    'order' => $post_order,
                                    'post__in' => explode ( ',', $attr[ 'post_ids' ] ),
                                    'orderby' => 'post__in',
                                    'post_type' => $post_type );
            }
            else {

                        $l_query = array (
                                    'order' => $post_order,
                                    'post_type' => $post_type,
                                    'category_name' => $catname,
                                    'paged' => $paged,
                                    'posts_per_page' => $post_number,
                                    'post_parent' => $post_parent );
            }

            $the_query = new WP_Query ( $l_query );


            $total_posts = $the_query->found_posts;


            if ( isset ( $attr[ 'thumb_size' ] ) ) {
                        if ( is_numeric ( $attr[ 'thumb_size' ] ) && $attr[ 'thumb_size' ] == 0 ) {
                                    $thumbsize = 0;

// print_r ( $attr[ 'thumb_size' ] . '<hr>' );
                        }
                        elseif ( strpos ( $attr[ 'thumb_size' ], '/' ) !== false ) {
                                    $thumbsize = explode ( '/', $attr[ 'thumb_size' ] );
                        }
                        else {
                                    $thumbsize = $attr[ 'thumb_size' ];
                        }
            }
            else {
                        $thumbsize = 'full';
            }
            if ( isset ( $attr[ 'excerpt' ] ) ) {
                        $excerpt = $attr[ 'excerpt' ];
            }
            else {
                        $excerpt = '160';
            }

            if ( isset ( $attr[ 'date' ] ) ) {
                        $date = $attr[ 'date' ];
            }
            else {
                        $date = 'd/m/Y';
            }
            if ( isset ( $attr[ 'permalink_text' ] ) ) {
                        update_option ( '_permalink_text', $attr[ 'permalink_text' ] );
            }

            $permalink_text = get_option ( '_permalink_text' );

            if ( isset ( $attr[ 'thumb_type' ] ) ) {
                        $thumb_type = $attr[ 'thumb_type' ];
            }
            else {
                        $thumb_type = 'image';
            }
            $human_looped_template = 'Related Posts Template';
            $archive_layout = '';
            if ( ! isset ( $attr[ 'loop_id' ] ) ) {
                        $attr[ 'loop_id' ] = 'no_loop_id' . get_the_ID ();
            }
            if ( isset ( $_GET[ 'clear-cache' ] ) ) {
                        delete_option ( 'human_transient' );
            }
            if ( isset ( $attr[ 'human_looped_template' ] ) ) {

                        if ( ! isset ( get_option ( 'human_transient' )[ 'human_featured_posts' ][ $attr[ 'human_looped_template' ] ] ) ) {
//echo '<hr>';
                                    $shortcode = '[human_template name="' . $attr[ 'human_looped_template' ] . '" type="human_loops"]';
                                    $archive_layout = do_shortcode ( $shortcode );
                                    $human_looped_template = $attr[ 'human_looped_template' ];
                                    $transidients = [ ];
                                    if ( get_option ( 'human_transient' ) ) {
                                                $transidients = get_option ( 'human_transient' );
                                    }
// print_r ( $attr[ 'human_looped_template' ] );
                                    $transidients[ 'human_featured_posts' ][ $attr[ 'human_looped_template' ] ] = $archive_layout;
                                    update_option ( 'human_transient', $transidients );
                        }
                        else {
                                    $archive_layout = get_option ( 'human_transient' )[ 'human_featured_posts' ][ $attr[ 'human_looped_template' ] ];
                        }
            }
            else {
                        exit;
            }
            $inline = '';
            if ( isset ( $attr[ 'inline' ] ) ) {
                        $inline = 'inline';
            }

            $human_current_url = get_the_permalink ();
//print_r ( $the_query );

            if ( $the_query->have_posts () ) {


                        while ( $the_query->have_posts () ) {
                                    $the_query->the_post ();

                                    $url = get_the_permalink ();

                                    $human_active_item = '';
                                    if ( $human_current_url === $url ) {
                                                $human_active_item = 'human_active_item';
                                    }
                                    if ( has_post_thumbnail () ) {
                                                if ( $thumbsize !== 0 ) {
                                                            $thumb_id = get_post_thumbnail_id ();
                                                            $thumb_url_array = wp_get_attachment_image_src ( $thumb_id, $thumbsize, true );
                                                            $thumb_url = $thumb_url_array[ 0 ];

//  print_r ( $attr[ 'thumb_size' ] );
//print_r ( $thumb_url_array );
                                                            if ( $thumb_type === 'image' ) {

                                                                        $img = '<div class="human-recent-thumb"><img src="' . $thumb_url . '" alt=""></div>';
                                                            }
                                                            else {

                                                                        $img = '<div class="human-recent-thumb" style="background:url(\'' . $thumb_url . '\')"></div>';
                                                            }

//- See more at: https://arjunphp.com/how-to-get-the-post-thumbnail-url-in-wordpress/#sthash.Zn6RIgFx.dpuf
                                                            $ready_thumb = '<div class="post-featured h-recent-post-inner"><a href="' . get_the_permalink () . '" rel="bookmark">
                                                   ' . $img . '</a></div>';
                                                }
                                                else {
// print_r ( $attr[ 'thumb_size' ] . '<hr>' );
                                                            $ready_thumb = '';
                                                }
                                    }
                                    else {
                                                $ready_thumb = '<div class="post-featured h-recent-post-inner"><a href="' . $url . '" rel="bookmark">
                                                    <div class="human-recent-thumb no-image"><img class="" src="/wp-content/plugins/js_composer/assets/vc/no_image.png" alt=""></div></a></div>';
                                    }
                                    if ( get_the_author () ) {
                                                $ready_author = '<div class="post-author h-recent-post-inner">' . get_the_author () . '</div>';
                                    }
                                    else {
                                                $ready_author = '<div class="post-author h-recent-post-inner">&nbsp;</div>';
                                    }
                                    $ready_date = human_get_post_date ( array (
                                                'id' => get_the_id (),
                                                'format' => $date ) );

                                    $ready_content = '<div class="content-inside-loop">' . get_the_content () . '</div>';
                                    $ready_title = '<div class="h-recent-post-inner"><h3 class="post-title"><a href="' . get_the_permalink () . '" rel="bookmark">' . get_the_title () . '</a></h3></div>';

                                    $ready_excerpt = '<div class="post-excerpt h-recent-post-inner"><p>' . substr ( get_the_excerpt (), 0, $excerpt ) . '<a href="' . $url . '">...</a></p></div>';

                                    $ready_permalink = '<a class="human-post-permalink" href="' . $url . '">' . $permalink_text . '</a>';
                                    $human_get_yoast_description = $human_get_yoast_title = 0;
                                    if ( isset ( get_post_meta ( get_the_id (), '_yoast_wpseo_metadesc' )[ 0 ] ) ) {
                                                $human_get_yoast_description = '<div class="yoast-description">' . get_post_meta ( get_the_id (), '_yoast_wpseo_metadesc' )[ 0 ] . '</div>';
                                    }
                                    if ( isset ( get_post_meta ( get_the_id (), '_yoast_wpseo_title' )[ 0 ] ) ) {
                                                $human_get_yoast_title = '<div class="yoast-title">' . get_post_meta ( get_the_id (), '_yoast_wpseo_title' )[ 0 ] . '</div>';
                                    }
                                    $ready_rating = '<span class="human-stars"></span>';
                                    $tags = array (
                                                '@human_get_title@',
                                                '@human_get_author@',
                                                '@human_get_excerpt@',
                                                '@human_get_permalink@',
                                                '@human_get_date@',
                                                '@human_get_thumb@',
                                                '@human_get_rating@',
                                                '@human_get_content@',
                                                '@human_get_yoast title@',
                                                '@human_get_yoast descripition@',
                                    );
                                    $replace_tags = array (
                                                $ready_title,
                                                $ready_author,
                                                $ready_excerpt,
                                                $ready_permalink,
                                                $ready_date,
                                                $ready_thumb,
                                                $ready_rating,
                                                $ready_content,
                                                $human_get_yoast_title,
                                                $human_get_yoast_description
                                    );
                                    $string .= '<div class="related_post_wrapper ' . $human_active_item . ' ' . $inline . ' ">';
                                    $string .= str_replace ( $tags, $replace_tags, $archive_layout );
                                    $string .='</div>';
                        }
            }
            else {
// no posts found
//echo 'No psts found';
            }
            $load_more = ' load more ';
            if ( isset ( $attr[ 'load_more_button' ] ) ) {
                        $load_more = $attr[ 'load_more_button' ];
            }
            $shortcode = 'human_posts_loop,post_order=' . $post_order . ',post_number=' . $post_number . ',paged=' . $pages . ',cat_name=' . $catname . ',excerpt=' . $excerpt . ',date=' . $date . ',post_type=' . $post_type . ',thumb_size=' . $thumbsize . ',human_looped_template=' . $human_looped_template . ',post_id=' . $post_id . ',data_extra=1,post_parent=' . $post_parent . ',inline=' . $inline . ',loop_id=' . $attr[ 'loop_id' ];

            if ( isset ( $attr[ 'data_extra' ] ) ) {
                        $load_more_btn = '<span id="data-extra" data-extra=\'' . $shortcode . '\'></span>';
                        $r = do_shortcode ( $string );
                        $result = '<div class="ajaxed">' . $r . $load_more_btn . '</div> ';
                        return $result;
            }
            else {
                        $load_more_auto_load = '';
                        if ( isset ( $attr[ 'load_more_auto_load' ] ) ) {
                                    $load_more_auto_load = 'auto-loading';
                        }
                        $load_more_button_hide = '';
                        if ( isset ( $attr[ 'load_more_button_hide' ] ) ) {
                                    $load_more_button_hide = 'hidden';
                        }
                        if ( $total_posts > $post_number ) {
                                    $load_more_btn = '<a href=\'#\'  class=\'button-link load-more ' . $load_more_button_hide . '\' data-type=\'posts\' data-extra=\'' . $shortcode . '\'>' . $load_more . '</a><div class="loading-gif  posts-loading-gif" style="display:none"></div>';
                                    $classes = 'human_related_posts loading-more  ' . $load_more_auto_load . ' ';
                        }
                        else {
                                    $post_number = '';
                                    $classes = '';
                                    $load_more_btn = '';
                        }
                        $classes = $classes . $extraclass;
                        $result = do_shortcode ( $string );
                        wp_reset_postdata ();
                        return '<div class="' . $classes . '">' . $result . $load_more_btn . '</div>';
            }
//return $string;

            /* Restore original Post Data */
//wp_reset_postdata();
}

require HUMAN_BASE_PATH . 'friends/content-shortcodes/f-heart/comment_looper.php';

add_shortcode ( 'human_comment_loops', 'human_comment_loops' );

function human_comment_loops ( $attr = null ) {
//$human_looped_template_id=0;


            if ( isset ( $attr[ 'post_id' ] ) ) {
                        $post_id = $attr[ 'post_id' ];
            }
            else {
                        $post_id = get_the_ID ();
            }

            if ( isset ( $attr[ 'human_looped_template' ] ) ) {
                        $human_looped_template = $attr[ 'human_looped_template' ];
                        if ( ! isset ( get_option ( 'human_transient' )[ 'human_comments' ][ $attr[ 'human_looped_template' ] ] ) ) {

                                    $shortcode = '[human_template name="' . $attr[ 'human_looped_template' ] . '" type="human_loops"]';
                                    $archive_layout = do_shortcode ( $shortcode );
                                    $human_looped_template = $attr[ 'human_looped_template' ];
                                    $transidients = get_option ( 'human_transient' );
                                    $transidients[ 'human_comments' ][ $attr[ 'human_looped_template' ] ] = $archive_layout;
                                    update_option ( 'human_transient', $transidients );
                        }
                        else {
                                    $archive_layout = get_option ( 'human_transient' )[ 'human_comments' ][ $attr[ 'human_looped_template' ] ];
                        }
            }
            else {
                        exit;
            }


            $comment_form_metas = array (
                        'comment_content',
                        'comment_date',
                        'rating',
                        'comment_reply_link',
                        'thumbs_up',
                        'thumbs_down',
                        'comment_flag'
            );
            $date_format = 'd-m-Y';
            if ( isset ( $attr[ 'date_format' ] ) ) {
                        $date_format = $attr[ 'date_format' ];
            }

            $human_comment_form_metas = [ ];
            if ( human_get_option_like ( 'human_comment_elems' ) ) {

                        $comment_option_metas = human_get_option_like ( 'human_comment_elems' );
                        $comment_fields = [ ];
                        foreach ( $comment_option_metas as $k => $v ) {
                                    $comment_ms[] = $v[ 'option_value' ];
                        }
                        foreach ( explode ( ',', implode ( ',', $comment_ms ) ) as $k => $v ) {
                                    $comment_fields[] = strtolower ( $v );
                        }
                        $comment_ms = array_unique ( $comment_fields );
                        $comment_form_metas = array_merge ( $comment_ms, $comment_form_metas );
                        foreach ( $comment_form_metas as $comment_form_meta ) {
                                    $human_comment_form_metas[] = '@human_get_' . $comment_form_meta . '@';
                        }
            }
            else {
                        $human_comment_form_metas = array (
                                    '@human_get_comment_content@',
                                    '@human_get_comment_date@',
                                    '@human_get_rating@',
                                    '@human_get_comment_reply_link@',
                                    '@human_get_thumbs_up@',
                                    '@human_get_thumbs_down@',
                                    '@human_get_comment_flag@'
                        );
            }

            $user_api_metas = user_api_metas ();
            foreach ( $user_api_metas as $usr_k => $usr_v ) {

                        $user_api_meta[] = '@author_meta_' . $usr_v . '@';
                        $user_metas_simple[] = $usr_v;
            }
            $string = '';
            $comment_parent = '';
            $comment_number = 10;
            if ( isset ( $attr[ 'comment_number' ] ) ) {
                        $comment_number = $attr[ 'comment_number' ];
            }
            $comment_next_number = $comment_number;
            $order_clause = '';

            if ( isset ( $attr[ 'comment_parent' ] ) ) {

                        $comment_parent = ' AND comment_parent=' . $attr[ 'comment_parent' ];
                        $comment_order = 'DESC';
                        $c_parent = true;
            }
            else {

                        $comment_parent = ' AND comment_parent=0';
            }

            if ( isset ( $_GET[ 'comment-order' ] ) ) {

                        if ( $_GET[ 'comment-order' ] === 'older' ) {
                                    $comment_order = 'ASC';
                        }
                        elseif ( $_GET[ 'comment-order' ] === 'new' ) {
                                    $comment_order = 'DESC';
                        }
            }
            elseif ( isset ( $attr[ 'comment_order' ] ) ) {
                        $comment_order = $attr[ 'comment_order' ];
            }
            else {
                        $comment_order = 'ASC';
            }

            $cach_order = $comment_order;
            if ( isset ( $attr[ 'last_number' ] ) ) {


                        $last_number = $attr[ 'last_number' ];

                        if ( isset ( $comment_order ) ) {
                                    if ( $comment_order === 'ASC' ) {
                                                $order_clause = ' AND comment_ID > ' . $last_number;
                                    }
                                    elseif ( $comment_order === 'DESC' ) {
                                                $order_clause = ' AND comment_ID < ' . $last_number;
                                    }
                        }
            }
            else {
                        $last_number = 0;
            }
            global $wpdb, $table_prefix;
            if ( isset ( $attr[ 'comment_reply' ] ) ) {
                        $querystr = "SELECT comment_ID FROM " . $table_prefix . "comments WHERE comment_post_id = " . $post_id . "" . $order_clause . $comment_parent . " AND comment_approved = 1 ORDER BY comment_ID DESC";
            }
            else {
                        $cach_order = $comment_order;
                        $querystr = "SELECT comment_ID FROM " . $table_prefix . "comments WHERE comment_post_id = " . $post_id . "" . $order_clause . $comment_parent . " AND comment_approved = 1 ORDER BY comment_ID " . $comment_order . " LIMIT $comment_number";
            }
            if ( isset ( $attr[ 'comment_parent' ] ) && $attr[ 'comment_parent' ] === 'new' ) {
                        $comment_id = $attr[ 'new_comment_id' ];

                        $querystr = "SELECT comment_ID FROM " . $table_prefix . "comments WHERE comment_ID=$comment_id AND comment_approved = 1";
            }

            $comments_array = $wpdb->get_results ( $querystr, ARRAY_A );

            $all_comments = [ ];
            $i = 0;
            $count_comments = 0;
            foreach ( $comments_array as $key => $v ) {
                        $i ++;
                        $all_comments[] = get_comment ( $v[ 'comment_ID' ] );
                        $count_comments+=$i;
            }
//print_r($count_comments);print_r($comment_number);
            if ( count ( $count_comments ) >= $comment_number ) {
                        $count_comments = true;
            }
            else {

                        $comment_number = 0;
            }

            if ( $comment_order === 'DESC' ) {
                        $last_comment = array_reverse ( $comments_array );
                        $last_comment = end ( $comments_array )[ 'comment_ID' ];
            }
            else {

                        $last_comment = array_reverse ( $comments_array );
                        $last_comment = end ( $comments_array )[ 'comment_ID' ];
            }

//print_r('<hr>-----'.$last_comment.'<br>'.$querystr.'---------------------<hr>');


            foreach ( $all_comments as $key => $val ) {
                        $user = get_user_by ( 'email', $val->comment_author_email );
                        $comment_id = $val->comment_ID;
                        $comment_user = '';
                        if ( $user === false ) {
                                    $comment_user[ 'comment_user' ] = $val->comment_author_email;
                                    $comment_user[ 'comment_id' ] = $comment_id;
                        }
                        unset ( $metas );
                        unset ( $comment_form_metas_to );
                        $string .=human_comment_looper ( $key, $val, $attr, $user_metas_simple, $archive_layout, $comment_form_metas, $date_format, $post_id, $user_api_meta, $human_comment_form_metas, $human_looped_template, $user, $comment_user );
            }


            $load_more = ' load more ';

            if ( isset ( $attr[ 'load_more_button' ] ) ) {
                        $load_more = $attr[ 'load_more_button' ];
            }

            if ( isset ( $attr[ 'comment_parent' ] ) ) {
                        return $string;
            }

            $shortcode = '[human_comment_loops human_looped_template="' . $human_looped_template . '" date_format="' . $date_format . '" last_number="' . $last_comment . '" comment_number="' . $comment_next_number . '" comment_order="' . $cach_order . '" post_id="' . $post_id . '" loop_id="' . $attr[ 'loop_id' ] . '" data_extra="1" ]';
            $reply_shortcode = '[human_comment_loops  human_looped_template="' . $human_looped_template . '"   post_id="' . $post_id . '" date_format="' . $date_format . '" loop_id="' . $attr[ 'loop_id' ] . '" comment_number="" comment_parent="@parent_id@" comment_reply="true" comment_order="DESC" level="0"]';

            $new_shortcode = '[human_comment_loops human_looped_template="' . $human_looped_template . '"  post_id="' . $post_id . '" date_format="' . $date_format . '" loop_id="' . $attr[ 'loop_id' ] . '" comment_number="" comment_parent="new" new_comment_id="@comment_id@" comment_order="DESC" level="0"]';
            if ( isset ( $attr[ 'data_extra' ] ) ) {
                        if ( ! empty ( trim ( $string ) ) ) {
                                    $load_more_btn = '<span id="data-extra" data-extra=\'' . $shortcode . '\'></span>';
                                    return $string . $load_more_btn;
                        }
            }
            else {
                        $load_more_auto_load = '';
                        if ( isset ( $attr[ 'load_more_auto_load' ] ) ) {
                                    $load_more_auto_load = 'auto-loading';
                        }
                        $load_more_button_hide = '';
                        if ( isset ( $attr[ 'load_more_button_hide' ] ) ) {
                                    $load_more_button_hide = 'hidden';
                        }

                        if ( $count_comments > 0 ) {
                                    $load_more_btn = ' <hr><a href=\'#\' class=\'button-link load-more ' . $load_more_button_hide . '\' data-type=\'comments\' data-extra=\'' . $shortcode . '\' data-reply=\'' . $reply_shortcode . '\' data-new=\'' . $new_shortcode . '\'>' . $load_more . '</a>
                                                                       <div class="loading-gif comments-loading-gif" style="display:none"></div>';
                                    $classes = 'human-comments loading-more  ' . $load_more_auto_load;
                        }
                        else {

                                    $load_more_btn = ' <hr><a href=\'#\' class=\'button-link load-more ' . $load_more_button_hide . '\' data-type=\'comments\' data-extra=\'' . $shortcode . '\' data-reply=\'' . $reply_shortcode . '\' data-new=\'' . $new_shortcode . '\' style="display:none">' . $load_more . '</a>
                                                                       <div class="loading-gif comments-loading-gif" style="display:none"></div>';
                                    $classes = 'human-comments';
                        }
                        return '<div class="' . $classes . '" itemprop="review" itemscope itemtype="http://schema.org/Review">' . $string . $load_more_btn . '</div>';
            }
}

add_action ( 'wp_ajax_human_loop_ajax', 'human_loop_ajax' );

function human_loop_ajax () {

            check_ajax_referer ( 'ajax-human-nonce', 'nonce' );

            if ( true ) {
                        $data_extra = '';
                        $ajax_type = esc_html ( $_POST[ 'ajax_type' ] );
                        $data_extra = stripcslashes ( $_POST[ 'data_extra' ] );
                        $data_extra = explode ( ',', $data_extra );
                        if ( $ajax_type === 'posts' ) {
                                    $i = 0;
                                    foreach ( $data_extra as $k => $v ) {
                                                $i ++;
                                                if ( $i > 0 ) {
                                                            if ( isset ( explode ( '=', $v )[ 1 ] ) && ! empty ( trim ( explode ( '=', $v )[ 1 ] ) ) ) {
                                                                        $key = explode ( '=', $v )[ 0 ];
                                                                        $val = explode ( '=', $v )[ 1 ];
                                                                        $attr[ $key ] = $val;
                                                            }
                                                }
                                    }
                                    $load_more = do_shortcode ( human_posts_loop ( $attr ) );

// $response = do_shortcode($load_more);
                                    wp_send_json_success ( array (
                                                'content' => $load_more ) );
                        }
                        else {

                                    $load_more_comments = do_shortcode ( stripcslashes ( $_POST[ 'data_extra' ] ) );
// print_r(array($load_more_comments));
// $response = do_shortcode($load_more);
                                    wp_send_json_success ( array (
                                                'content' => $load_more_comments ) );
                        }
//  print_r(array($load_more_comments));
            }
            else {

                        $response = array (
                                    'fail' => '1',
                                    'content' => 'wrong-nonce' );
                        wp_send_json_fail ( $response );
            }
}

if ( is_admin () ) {
            require HUMAN_BASE_PATH . 'friends/content-shortcodes/f-heart/vc_mapper.php';
}


add_action ( 'wp_ajax_nopriv_human_loop_ajax', 'human_loop_ajax' );

function clean_header () {
            wp_deregister_script ( 'comment-reply' );
}

add_action ( 'init', 'clean_header' );

<?php

function schema_rating_reviews ( $attr = null ) {
            $post_id = get_the_ID ();
            $mpn = $post_id * 1845;
            $thumb = get_the_post_thumbnail_url ( $post_id, 'full' );
            $rating = human_post_contents ( array (
                        "content_type" => "Rating",
                        "schema" => 1 ) );
            $sale = get_post_meta ( get_the_ID (), '_sale_price', true )[ 0 ];
            $price = get_post_meta ( get_the_ID (), '_regular_price', true )[ 0 ];
            if ( isset ( $sale ) && is_numeric ( $sale ) ) {
                        $price = $sale;
            }
            else {
                        if ( isset ( $price ) && is_numeric ( $price ) ) {
// price exists
                        }
                        else {
                                    $price = 0.00;
                        }
            }
            $comments = get_approved_comments ( get_the_ID () );
            $user_rating = [ ];
            foreach ( $comments as $key => $v ) {
                        if ( isset ( get_comment_meta ( $v->comment_ID, 'rating', true )[ 0 ] ) ) {
                                    $user_rating[] = '{            "@type": "Review",
                              "author": {
                                   "@type":"Person","name": "' . $v->comment_author . '"},
                              "datePublished": "' . $v->comment_date . '",
                              "name": "' . get_the_title () . '",
                              "description": "' . $v->comment_content . '",
                              "reviewRating": {
                                   "@type": "Rating",
                                    "bestRating": "' . $rating[ 'ratingValue' ] . '",
                                   "ratingValue": "' . get_comment_meta ( $v->comment_ID, 'rating', true )[ 0 ] . '",
                                   "worstRating": "' . $rating[ 'worstRating' ] . '"
                              }
                              }';
                        }
            }
            $user_rating = implode ( ',', $user_rating );
            $valid_until = date ( 'Y-m-d', strtotime ( '+1 years' ) );
            $schema = '[
                      {
                      "@context": "http://schema.org/",
                      "@type": "product",
                      "name": "' . get_the_title () . '",
                      "image": "' . $thumb . '",
                      "description": "' . get_post_meta ( $post_id, '_yoast_wpseo_metadesc', true ) . '",
                      "mpn": "' . $mpn . '",

                      "aggregateRating": {
                      "@type": "AggregateRating",
                      "ratingValue": "' . $rating[ 'ratingValue' ] . '",
                      "reviewCount": "' . $rating[ 'reviewCount' ] . '"
                      },

                      "brand": {
                      "@type": "Thing",
                      "name": "' . get_option ( 'business_name' ) . '"
                      },
                      "offers": {
                      "@type": "Offer",
                      "priceCurrency": "' . get_woocommerce_currency () . '",
                      "price": "0.00",
                      "priceValidUntil": "' . $valid_until . '",
                      "itemCondition": "https://schema.org/NewCondition",
                      "availability": "https://schema.org/InStock",
                      "seller": {
                          "@type": "Organization",
                           "name": "' . get_option ( 'business_name' ) . '"
                           }
                      },
                           "review":  [ ' . $user_rating . ']
                      }]';
            $schema_file_path = HUMAN_FRIENDS_PATH . '/5-star-rating/f-character/temper/5-star-rating-global.json';
            $schema_file = fopen ( $schema_file_path, 'w' );
            fwrite ( $schema_file, $schema );
            fclose ( $schema_file );
            return true;
}

function five_star_rating_footer () {
            //   schema_rating_reviews ();
            $schema_file_url = HUMAN_FRIENDS_URL . '/5-star-rating/f-character/temper/5-star-rating-global.json';
            //    wp_enqueue_script ( 'human-schema-org-snippet', $schema_file_url, array () );
            echo '<script  type="application/ld+json" async="async">' . file_get_contents ( $schema_file_url ) . '</script>';
}

add_action ( 'wp_footer', 'five_star_rating_footer', 99999999900 );

// Async load
function human_async_scripts ( $tag, $handle ) {

            return str_replace ( ' src', ' async="async" src', $tag );
}

//add_filter ( 'script_loader_tag', 'human_async_scripts', 10, 2 );

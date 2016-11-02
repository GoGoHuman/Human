<?php 

/**
 * @package Scrum
 * @subpackage SCRUM_POST_META
 * @developer SergeDirect
 */
 
  $WIDGET_POSITIONS = array('before_footer','right_bar','right_bar_menu','left_bar');
   
class SCRUM_POST_META{

   /**
    * @param Initialise package
    * @since 1.0
    */
 
    public function init(){
	
          if ($this->is_edit_page()){
               add_action( 'add_meta_boxes', array(&$this,'scrum_add_meta_box'),0 );
          }     
    }


   /**
    * @param Adds a metaboxes to the main column on the Post and Page edit screens.
	* @uses  $this->scrum_post_meta_futured_banner
    * @since 1.0
    */


    public function scrum_add_meta_box() {

    	$screens = array( 'post', 'page' );

	    foreach ( $screens as $screen ) {

		    add_meta_box(
			    'scrum_sectionid',
			    __( 'Additional Options', 'textdomain' ),
			    array(&$this,'scrum_additional_futures'),
			    $screen,'side'
		    );
                                                   add_meta_box(
			    'scrum_bootstrap_links',
			    __( 'Bootstrap Links', 'textdomain' ),
			    array(&$this,'scrum_post_meta_bootstrap_links'),
			    $screen
		    );
		  
                    
		  
                    
                    
	    }
            
    }

  
    
    public function scrum_post_meta_bootstrap_links($post){
        global $WIDGET_POSITIONS;
                            
	    wp_nonce_field( 'scrum_save_meta_box_data', 'scrum_meta_box_nonce' );
	   // $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
        
                             
                             
                                 
                      //  print_r(get_option("scrum_boostrap_links"));
                            // delete_option("scrum_boostrap_links");
                             foreach($WIDGET_POSITIONS as $key=>$pos){
                                   
                                    
                             if(isset(get_option("scrum_boostrap_links")[$pos]["b_".$post->ID])){
                                 $opt_id = get_option("scrum_boostrap_links")[$pos]["b_".$post->ID];
                                    $title =  $opt_id['link_title'];  // link title
                                    $text =  $opt_id['link_txt'];  // link txt 
                                    $btn =  $opt_id['link_btn'];  // link btn txt
                              }else{
                                  $opt_id = [];
                                    $pos_id = [];
                                    $title = '';
                                    $text = '';  
                                    $btn = '';
                              }
                                   echo '<h2>'.ucwords(str_replace("_"," ",$pos)).'</h2>';
                            //       print_r($opt_id);
                              ?>
        
                                        <label for="scrum_futured_banner">
	                <?php _e( '', 'textdomain' );?>
	                </label>
                                        <p>Link Title</p>
                                        <input type="text" name="scrum_link_title_<?php echo $pos;?>" value="<?php echo stripcslashes($title);?>" >  
                                        <p>Short description (Leave blank to use excerpt field)</p>
                                        <textarea name="scrum_link_textarea_<?php echo $pos;?>"><?php echo stripcslashes($text);?></textarea>
                                        <p> Select Sidebar(s) you want this page to be linked from </p>
                                        
                                        <p>Button Title</p>
                                        <input type="text" name="scrum_btn_title_<?php echo $pos;?>"   value="<?php echo stripcslashes($btn);?>">  
                                        <p>Choose a sidebar(s) &amp; Grid Order</p> 
                                        <table>
                                          
                                        <?php
                                            if(isset( $opt_id['order'])){
                                                         $b_order = $opt_id['order']; // bootstrap order
                                            }else{
                                                $b_order = '';
                                            }
                                             
                                                        echo '<tr><td>'.ucwords(str_replace('_',' ',$pos)).'</td>';
                                                        
                                                         echo '<td> <select name="'.$pos.'_order"><option value="">Select:</option>';
                                                
                                                                   foreach (range(1, 12) as $order) {
                                                                                  $b = "";
                                                                               //   print_r(  $b_order.",,<hr>");
                                                                                  if($order == $b_order){
                                                                                            $b = "selected";
                                                                                    }
                                                                             echo '<option value="'.$order.'"  '.$b.'>'.$order.'</option>';
                                                                   }
                                                          echo ' </td></tr>';
                                                 
                                                ?>
                                            
                                       </table>
               
                         <?php  }    ?> 
    
                                     

    
       <?php
          
    }  
    
    
    /**
     * @param add_meta_box call back function for featured banner
	 * @since 1.0
     */
	 
    public function scrum_additional_futures( $post ) {
	
	     // Add a nonce field so we can check for it later.

	    /*
	     * Use get_post_meta() to retrieve an existing value
	     * from the database and use the value for the form.
	     */ 
		 
	    wp_nonce_field( 'scrum_save_meta_box_data', 'scrum_meta_box_nonce2' );
		
		global $SCRUM_BRAIN; 
		//$SCRUM_BRAIN->media();
		
                           if(!isset(get_option("scrum_futured_banner")["b_".$post->ID])){
	    	
			
	        $banner = '';
	        $link = '';
	        $s_check = '';
	    }else{
		//print_r(get_option("scrum_futured_banner")["b_".$post->ID]);
	                          $banner = '<img src="'.get_option("scrum_futured_banner")["b_".$post->ID]["banner_url"].'" width="250" alt="">';
		    $link = get_option("scrum_futured_banner")["b_".$post->ID]["banner_url"];
		    $s_check = get_option("scrum_futured_banner")["b_".$post->ID]["render"];
			
		}?>
	                      <br>
	                     <label for="scrum_futured_banner">
	                     <?php _e( 'Futured Banner', 'textdomain' );?>
	                     </label>
           
	                              Upload: <?php echo $SCRUM_BRAIN->media("futured_banner",$banner);?>
		     <br>
		     <input class="scrum_upload_image_futured_banner" type="hidden" name="scrum_upload_image_futured_banner" value="<?php echo $link;?>" />
                        
	                             <div class="scrum_futured_banner"><?php echo $banner;?></div>
	                             <select name="scrum_futured_banner_render">
	                                      <option value="before_body" <?php if($s_check === 'before_body') echo 'selected';?>>before content wrapper</option>
		              <option value="inside_body_before" <?php if($s_check === 'inside_body_before') echo 'selected';?>>Inside content wrapper - before content</option>
		              <option value="inside_body_after" <?php if($s_check === 'inside_body_after') echo 'selected';?>>Inside content wrapper - after content</option>
		              <option value="after_body_after" <?php if($s_check === 'after_body_after') echo 'selected';?>>after content wrapper</option>
	                        </select>
                                               <hr>
                                                <label for="scrum_page_category">
	                                      <?php _e( 'Bootstrap Menu Category', 'textdomain' );?>
	                        </label>
                                                   <input name="scrum_bootstrap_menu_category" value="<?php if(isset(get_option('scrum_bootstrap_menu_category')[$post->ID                                      ])) echo get_option('scrum_bootstrap_menu_category')[$post->ID];?>">
                                                   <h2>Insert revolution slider into header</h2>
                                                   <select id="slider_id" name="slider_id">
                                                       <option value="0">Select Slider</option><?php
                                                             global $wpdb;
                                                             $allrevs =  $wpdb->get_results("SELECT title,alias FROM wp_revslider_sliders", ARRAY_A);
                                                           
                                                             foreach($allrevs as $key=>$val){
                                                                   if(isset(get_option('slider_page_id')[$post->ID])){
                                                                           $checked = get_option('slider_page_id')[$post->ID];
                                                                           if($checked === $val['alias']){
                                                                               $checked = 'selected';
                                                                           }else{
                                                                               $checked = '';
                                                                           }
                                                                   }      print_r($allrevs); print_r($post->ID);
                                                                    echo   ' <option value="'.$val['alias'].'" '.$checked.'>'.$val['title'].'</option>';
                                                             }
                                                         //    print_r($allrevs);
                                                   
                                                   ?></select>
<?php
			
  }

   /**
    * @param When the post is saved, saves our custom data.
    * @param int $post_id The ID of the post being saved.
	* @since 1.0
    */
	
    public function scrum_save_meta_box_data( $post_id ) {
 
        
	    /*
	     * We need to verify this came from our screen and with proper authorization,
	     * because the save_post action can be triggered at other times.
	     */

	     // Check if our nonce is set.
	     if ( ! isset( $_POST['scrum_meta_box_nonce'] )  ||  ! isset( $_POST['scrum_meta_box_nonce2'] ) ) {
                 
		    return;
	     }

        
	     // Verify that the nonce is valid.
	     if ( ! wp_verify_nonce( $_POST['scrum_meta_box_nonce'], 'scrum_save_meta_box_data' ) ||  ! wp_verify_nonce( $_POST['scrum_meta_box_nonce2'], 'scrum_save_meta_box_data' ) ) {
		    return;
	     }

	     // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	    	return;
	     }

	     // Check the user's permissions.
	     if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

	    	if ( ! current_user_can( 'edit_page', $post_id ) ) {
			    return;
		    }

	     } else {

		    if ( ! current_user_can( 'edit_post', $post_id ) ) {
			    return;
		    }
	     }
                            if ( wp_is_post_revision( $post_id ) ){
		return;
                            }
	     /* OK, it's safe for us to save the data now. */
	
	     // Make sure that it is set.
	     if ( isset( $_POST['scrum_upload_image_futured_banner'] ) ) {
		
		    $update = get_option("scrum_futured_banner");
		     $update["b_".$post_id] = array(
			"banner_url" => sanitize_text_field($_POST['scrum_upload_image_futured_banner']),
			"render" => esc_html($_POST['scrum_futured_banner_render'])
			);
		    update_option("scrum_futured_banner",$update);
		
	     }  
                          if($_POST['slider_id'] !== 0){
                                  $slider_options = get_option('slider_page_id');
                                  $slider_options[$post_id] = $_POST['slider_id'];
                                   update_option('slider_page_id',$slider_options);
                          }
                        if(isset($_POST['scrum_bootstrap_menu_category'])){
                              
                             $b_cats =   get_option('scrum_bootstrap_menu_category');
                            $b_cats[$post_id] = str_replace(" ","_",esc_attr($_POST['scrum_bootstrap_menu_category']));
                              update_option('scrum_bootstrap_menu_category',$b_cats);
                        }
                        global  $WIDGET_POSITIONS;
                                 
                        foreach($WIDGET_POSITIONS as $key=>$pos){
                            
                            
                             if( isset($_POST['scrum_link_title_'.$pos]) && !empty($_POST['scrum_link_title_'.$pos])){
                                 
                                       
                                       
                                        $scrum_link_title = sanitize_text_field($_POST['scrum_link_title_'.$pos]);
                                 
                                 
                                         $scrum_link_textarea = sanitize_text_field($_POST['scrum_link_textarea_'.$pos]);
                                         $scrum_link_btn  = sanitize_text_field($_POST['scrum_btn_title_'.$pos]);
                                         $pos_order = sanitize_text_field($_POST[$pos.'_order']);
                                      
                                         
                                         $opt_id_links = get_option("scrum_boostrap_links");
                                         
                                        
                                         $opt_id_links[$pos]["b_".$post_id] = array(
                                             
			"link_title" => $scrum_link_title,
			"link_txt" => $scrum_link_textarea,
			"link_btn" => $scrum_link_btn,
                                                                        "order" => $pos_order
                                                 
			);
                                         
                                         update_option("scrum_boostrap_links",$opt_id_links);
                             }
                             
                        }
		 

}

 /**
 * is_edit_page 
 * function to check if the current page is a post edit page
 * 
 * @author Ohad Raz <admin@bainternet.info>
 * 
 * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
 * @return boolean
 */
function is_edit_page($new_edit = null){
    global $pagenow;
    //make sure we are on the backend
    if (!is_admin()) return false;


    if($new_edit == "edit")
        return in_array( $pagenow, array( 'post.php',  ) );
    elseif($new_edit == "new") //check for new post page
        return in_array( $pagenow, array( 'post-new.php' ) );
    else //check for either new or edit
        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

}

$SCRUM_POST_META = new SCRUM_POST_META();

add_action( 'save_post', array($SCRUM_POST_META,'scrum_save_meta_box_data' ));   


   $SCRUM_POST_META->init(); 




<?php


class SCRUM_POSTS{
    
    
        public function  init(){
            
                          add_shortcode( 'scrum_get_posts  ',array(&$this,'scrum_get_posts  '));
                          add_shortcode('scrum_bootstrap_menu',array(&$this,'scrum_bootstrap_menu'));
                          add_shortcode('scrum_bootstrap_form',array(&$this,'scrum_bootstrap_form'));
                          add_shortcode('scrum_google_map',array(&$this,'scrum_google_map'));
                          add_shortcode('menu_plugin_menu',array(&$this,'menu_plugin_menu'));
        }
    
     
        public function scrum_food_menu($attr){
            
                  $catname = ucwords($attr['category']);
                  $grid = $attr['grid'];
                  if($attr['category'] !== 'all'){
              //    print_r(get_option('scrum_fmenu_categories'));
                   if(isset(get_option('scrum_fmenu_categories')[$catname]['misc'])){
                        $cats = get_option('scrum_fmenu_categories')[$catname]['misc'];
                              
                                        for($i=0;$i<=count($cats)-1;$i++){
                            
                                               $cat_options[] = $cats[$i];
                                        }
                                       
                               $pricegrid = 12/count($cat_options);
                               if($pricegrid == 12){$pricegrid = 11;}
                             //  echo $pricegrid;
                               $fullgrid = 12-count($cat_options);
                               $c_number = count($cat_options);
                                   
                                if(isset( get_option('scrum_fmenu_products')[$catname]['products'])){
                               echo '<div class="row food_menu_wrap">'
                                              . '<div class="col-xs-'.$fullgrid.'"><h2> '.str_replace("_"," ",$catname).'</h2></div>';
                               $cat_opt_index = 0;
                               foreach($cat_options as $keys=>$opts){
                                     $cat_opt_index  ++;
                                     $cat_opts[$cat_opt_index] = $opts;
                                              echo '<div class="col-xs-1 parent_category_option"><p class="xs-none">'.esc_scrum($opts).'</p></div>';
                               }
    
                                if(get_option('scrum_currency')){
                                            $user_currency = get_option('scrum_currency');
                                }else{
                                            $user_currency = "&pound;";
                                }
                                $products = get_option('scrum_fmenu_products')[$catname]['products'];
        
              
                                $gridCheck = count($products)/(12/$grid);
                                
                                foreach($products as $key=>$val){
             
                                        $subcaCheck = $products;
                                        unset( $subcaCheck[$key]['desc']);
                                        //    print_r($subcaCheck[$key]);
                                        if(empty(array_filter($subcaCheck[$key]))){ // sub category and description
                                                       
                                                echo '<div class="col-sm-12"><div class="food_sub_category"><h3>'.str_replace('desc','',str_replace("_"," ",$key)).'</h3><p>'.$products[$key]['desc'].'</p></div></div>';
                                                
                                        }else{
                           
                       
                                               echo '<div class="col-sm-12">';
                                               $i = 0;
                                               foreach($val as $t=>$p){     
                                  
                                                       if($t === 'desc' ){
                                  
                                                                  echo '<div class="col-xs-'.$fullgrid.'"><h4>'.str_replace("_"," ",$key).'</h4><p>'. $p.'</p></div>';
                        
                                                      }else{
                                                                 $i++;
                                                                echo '<div class="col-xs-1 price"><p> <span class="xs-block"> '.$cat_opts[$i].' : </span>'.$user_currency.$p.'</p></div>';
                              
                                                     }
                                              }
                                              echo '</div>';
                                }
                       
                                }
                                
                                       echo '</div>'; 
                                }
            
                  }
                  
                }else{
                           //   echo "<hr>bb";
                             global $scrum_page_ID;
                  
                                  //   print_r( get_option("scrum_fmenu_categories"));
                                      $opt_id = get_option("scrum_fmenu_categories");
	               foreach ( $opt_id as $key => $menu_item ) {
                                    
                                            //   print_r($title);
                                              echo  do_shortcode('[scrum_food_menu category="'.$key.'" grid=12]');
                                      }
                             
                   } 
            
            
        }
        
        public function scrum_google_map($attr = null){
            ?>

         <script type="text/javascript">
                              //<![CDATA[
                              var geocoder = new google.maps.Geocoder();
                              var address = "<?php echo $attr['address'];?>"; //Add your address here, all on one line.
                              var latitude;
                              var longitude;
                              var color = ""; //Set your tint color. Needs to be a hex value.

                              function getGeocode() {
                                      geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
    		            latitude = results[0].geometry.location.lat();
		            longitude = results[0].geometry.location.lng(); 
		            initGoogleMap();   
    	                       } 
	              });
                              }

                              function initGoogleMap() {
                                      var styles = [
	                    
    {
        "featureType": "all",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": "0"
            },
            {
                "gamma": "1.00"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text",
        "stylers": [
            {
                "lightness": "0"
            },
            {
                "visibility": "on"
            },
            {
                "gamma": "1.00"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#000000"
            },
            {
                "lightness": "58"
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "administrative.country",
        "elementType": "labels.text",
        "stylers": [
            {
                "color": "#bfbfbf"
            },
            {
                "weight": "0.75"
            }
        ]
    },
    {
        "featureType": "administrative.province",
        "elementType": "labels.text",
        "stylers": [
            {
                "color": "#bfbfbf"
            },
            {
                "weight": "0.5"
            }
        ]
    },
    {
        "featureType": "administrative.locality",
        "elementType": "labels.text",
        "stylers": [
            {
                "color": "#bfbfbf"
            },
            {
                "weight": "0.3"
            }
        ]
    },
    {
        "featureType": "administrative.neighborhood",
        "elementType": "labels.text",
        "stylers": [
            {
                "weight": "0.2"
            },
            {
                "color": "#bfbfbf"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "administrative.land_parcel",
        "elementType": "labels.text",
        "stylers": [
            {
                "color": "#f06a21"
            },
            {
                "weight": "0.1"
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#384044"
            },
            {
                "lightness": "-13"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#384044"
            },
            {
                "lightness": "-8"
            },
            {
                "gamma": "1"
            },
            {
                "saturation": "0"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#f48120"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#f48120"
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text",
        "stylers": [
            {
                "lightness": "0"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "lightness": "26"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#384044"
            },
            {
                "lightness": "-27"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "lightness": 18
            },
            {
                "color": "#f48120"
            },
            {
                "weight": "0.5"
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#f48120"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#384044"
            },
            {
                "visibility": "on"
            },
            {
                "weight": "1.97"
            },
            {
                "lightness": "-27"
            },
            {
                "gamma": "1"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#384044"
            },
            {
                "lightness": "-27"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "weight": "0.59"
            },
            {
                "lightness": "-18"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#384044"
            },
            {
                "lightness": "-27"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#384044"
            },
            {
                "lightness": "7"
            }
        ]
    }
];
	
	               var options = {
		mapTypeControlOptions: {
			mapTypeIds: ['Styled']
		},
		center: new google.maps.LatLng(latitude, longitude),
		zoom: 15,
		scrollwheel: false,
		navigationControl: false,
		mapTypeControl: false,
		zoomControl: true,
		disableDefaultUI: true,	
		mapTypeId: 'Styled'
	              };
	             var div = document.getElementById('googleMap');
	             var map = new google.maps.Map(div, options);
                                     var iconBase = '<?php echo get_stylesheet_directory_uri().'/assets/assets/images/';?>';
                                     var marker = new google.maps.Marker({
                                               position: new google.maps.LatLng(latitude,longitude),
                                               map: map,
                                               icon: iconBase + 'map-icon.png'
                                       
                                               
	            });

	            var styledMapType = new google.maps.StyledMapType(styles, { name: 'Styled' });
	            map.mapTypes.set('Styled', styledMapType);
	
	            var infowindow = new google.maps.InfoWindow({
	                     content: "<div class='iwContent'>"+address+"</div>"
	            });
	            google.maps.event.addListener(marker, 'click', function() {
	                     infowindow.open(map,marker);
	            });
	
	
	
                        }
                       google.maps.event.addDomListener(window, 'load', getGeocode);
                      //]]>
           </script>
           
           <?php
           $map_ready = ' <div id="googleMap" style=""></div> ';
              return $map_ready;
        }
        
        
        public function scrum_bootstrap_form($attr){
            
                      
            $success = ''; $start = "";
            
                       if(isset($_POST['bootstrap_form'])){
                           
                                if(isset($attr['email'])){
                                    
                                           wp_mail($attr['email'],$attr['subject'], esc_html(implode("\n\r",$_POST)));
                                           if(isset($attr['mailchimp'])){
                           
                                           }
                                           if(isset($attr['thanks'])){
                                                  $success = '<h5 id="thank_you_title">'.$attr['thanks'].'</h5>';
                                           }else{
                                                   $success = '<h5 id="thank_you_title">Thank you for submission</h5>';
                                           }
                                }
                                
                       }
                       
                                    $wrap = '';
                                    $cols = $attr['cols'];
                                    
                                    if(isset($attr['wrap'])){
                                          $wrap = $attr['wrap'];
                                    }
                                    
                                    $start .= '<div class="row '.$wrap.'">'.$success.'<form method="POST" action="#thank_you_title"><input type="hidden" name="bootstrap_form" value="1">';
                                    
                                    for($i=1;$i<=$cols;$i++){
                                           $input = [];$title=[];
                                           $input =  explode(",",$attr['col_'.$i.'_input']);
                                           $grid=  $attr['col_'.$i.'_grid'];
                                           $title=  $attr['col_'.$i.'_title'];
                                           $title = explode(',',$title);
                                           $label = '';   
                                        
                                           $misc = '';
                                          $start .= '<div class="col-sm-'.$grid.'">';
                                          
                                    for($t=0;$t<count($title);$t++){    
                                           if(isset($attr['label']) === 'on'){
                                               $label = '<label for="'.$title[$t].'">'.$title[$t].'</label>';
                                           }
                                           if(isset($attr['placeholder']) === 'off'){
                                               $placeholder = '';
                                           }else{$placeholder = $title[$t];}
                                           if($input[$t] === 'submit'){
                                               
                                                 $start .=  '<input type="'.$input[$t].'" name="submit" id="submit"  value="'.$title[$t].'">'; 
                                           }elseif($input[$t] === 'textarea'){
                                                 $start .= $label.'<textarea name="'.$title[$t].'" id="'.$title[$t].'" placeholder="'.$placeholder.'"></textarea>'; 
                                           }elseif($input[$t] === 'select'){
                                               
                                               
                                                            global $SCRUM_BOOKING;
                                                            wp_enqueue_script('jquery');
                                                   wp_enqueue_script('jquery-ui-core');
                                                 $start .= '<select name="'.str_replace(array(" ","'","\"",","),array("","","",""),$title[$t]).'" id="'.str_replace(array(" ","'","\"",","),array("","","",""),$title[$t]).'"><option value="">Select Option</option>';
                                                                 foreach(explode(",",explode("#",$title[$t])[1]) as $option){
                                                                     $start .= '<option value="'.$option.'">'.$option.'</option>';
                                                                 } 
                                                                 $start ='</select>';
                                           }elseif($input[$t] === 'calendar'){
                                                            global $SCRUM_BOOKING;
                                                            wp_enqueue_script('jquery');
                                                   wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
       //$SCRUM_BOOKING->scripts();
                                                            $start .= $label. '<input type="text" name="'.$title[$t].'" id="'.$title[$t].'" class="scrum_calendar" placeholder="Pick a date" >'; 
                                                            ?>
                                                              <script>       
                                                                  jQuery(document).ready(function($){ 
                                                                               $(function() {			
	                                                                      $( ".scrum_calendar" ).datepicker({
                                                                                                               
				                    minDate: 0, 
                                                                                                                    beforeShow : function(){
                                                                                                                                  if(!$('.ll-skin-siena').length){
                                                                                                                                              $('#ui-datepicker-div').wrap('<div class="ll-skin-siena"></div>');
                                                                                                                                   }
                                                                                                                    }
                                                                                                });
	         
                                                                             });
                                                                });
                                                              </script>                               
                                                                
                                                                <?php
                                           }else{
                                                            $start  .= $label. '<input type="'.$input[$t].'" name="'.$title[$t].'" id="'.$title[$t].'" placeholder="'.$placeholder.'" >'; 
                                      
                                           }
                                           
                                    }
                                    $start .= '</div>';
                           }
                                    $start .= '</form></div>'; 
                                    return $start;
        }
        
        
        public function menu_plugin_menu($attr){
            
                 $url = explode('?', 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                  $scrum_page_ID=url_to_postid($url[0]);
                 
                 
                  if(isset(get_option('scrum_bootstrap_menu_category')[$scrum_page_ID])){
                                          $page_category = get_option('scrum_bootstrap_menu_category')[$scrum_page_ID];
                                          if($page_category === 'menus'){
                                                    wp_nav_menu( array( 'menu' => 'MENUS','menu_class'=>'bootstrap_menu_links row ','echo' => true ) );
                                          }
                  }
            
        }
        
        public function scrum_bootstrap_menu($attr){
            
                 $url = explode('?', 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                  $scrum_page_ID=url_to_postid($url[0]);
                                    $sidebar = $attr['name'];
                                    $grid = $attr['grid'];
                                    if(isset($attr['excerpt'])){
                                        $excerpt = explode(",",$attr['excerpt']);
                                    }else{
                                        $excerpt = array('0x');
                                    }
                                   
                                    $opt_id = get_option("scrum_boostrap_links");
                                    
                                   //  unset($opt_id['right_bar_menu']['b_151']);
                                   //    unset($opt_id['right_bar_menu']['b_153']);
                                   //     update_option('scrum_boostrap_links',$optid); 
                                   //           var_dump($opt_id[$sidebar]);
                                    
                                    
                                    if(isset(get_option('scrum_bootstrap_menu_category')[$scrum_page_ID])){
                                          $page_escerpt_category = get_option('scrum_bootstrap_menu_category')[$scrum_page_ID];
                                            
                                                  
                                    }else{
                                        $page_escerpt_category = "empty";
                                    } 
                                 
                                      if(isset($attr['only'])){
                                       //     print_r($attr['only']);
                                                            $only = $attr['only'];
                                                            
                                                             if($page_escerpt_category !== $only){
                                                                     return;
                                                             }
                                      }   
                                                  
                                                  
                                                  
              //   print_r($page_escerpt_category);print_r($only);
                    if(in_array($page_escerpt_category,$excerpt )){
                        
                             return;
                             
                    }else{
                        
                             if(isset($opt_id[$sidebar])){
                                 
	                    $wrap = '';
                                            $b_out = [];
                             
	           foreach ( $opt_id[$sidebar] as $key => $menu_item ) {
                                    
                                            $title =  stripcslashes($menu_item['link_title']);  // link title
                                            $text =  stripcslashes($menu_item['link_txt']);  // link txt 
                                            $btn =  stripcslashes($menu_item['link_btn']);  // link btn txt
	                    $url = get_permalink(str_replace("b_","",$key));
                                            global $scrum_page_ID;
                                      
                                           if(isset( $menu_item['order'])){
                                             
                                                         
	                       
                                                       
                                                         
                                                        if(!isset($text) || empty($text)){
                                                          
                                                                if(str_replace("b_","",$key) == $scrum_page_ID){ //detect active link for css class
                                                
                                                                              $active = 'scrum_active';
                                                  
                                                                  }else{
                                                
                                                                             $active = '';
                                                  
                                                                }
                                                                 //   print_r($opt_id);  
                                                             //  print_r("<hr>".str_replace("b_","",$key) ."-".$scrum_page_ID." - ".$active."<hr>");
                                                                if(empty($wrap)){
                                                                       $wrap .= '<ul class="bootstrap_menu_links row">@@</ul>';
                                                                }    
                                                                   //       print_r($wrap . $i.'<hr>');
                                                                    $b_out[$menu_item['order']]  = '<li class="'.$active.'"><a href="' . $url . '">' . $title . '</a></li>';
                                                            
                                                        }else{
                                                          
                                                              if(empty($wrap)){
                                                                       $wrap .= '<div class="bootstrap_links row">@@</div>';
                                                              }
                                                                   
                                                                  $b_out[$menu_item['order']] =  '<div class="col-md-'.$grid.'"><div class="scrum_inner"><h3>'.$title.'</h3><hr><p>'.$text.'</p><p class="scrum_btn"><a href="' . $url . '">' . $btn . '</a></p></div></div>';
                                                                   
                                                       // echo $menu_item['order'] .'<hr>';
                                                        }
                                         
                                            }
                                          
	         } 
                             
                                                //   print_r($b_out);
                                      ksort($b_out); 
                                 
                                       echo str_replace("@@",implode( " ",$b_out),$wrap);
                                 
                             }else{ 
                                      echo "Wrong or not existing widget name attribute value <hr>".$sidebar."<hr>";
                             }
                    }
            
        }
        
        public function scrum_get_posts ($attr){
                       
               
                        $a = $attr;
                        $excerptsize = $a['excerpt_length'];
                        $recent_post_number = array('numberposts' => $a['numberposts']);
                        $amount = array( 'numberposts' => '5' );
                        if(isset($a['t_width']) && isset($a['t_height'])){
                        $size = array( $a['t_width'], $a['t_height']); }
	$recent_posts = wp_get_recent_posts( $recent_post_number );
                        if(isset($a['title'])){
                            $title = '<h2>'.$a['title'].'</h2>';
                        }else{$title = '';}
                        
                       
                        echo '<div class="row">'. $title;
	foreach( $recent_posts as $recent ){
                      
                         
                           echo '<div class="'.$a['wrap'].'">';
                                   $title = ' ';$date = ' ';  $thumb = ' '; $text = ' ';
                               for($i=1;$i<=$a['cols'];$i++){
                                   if(isset($a["col_".$i."_class_2"])){
                                          $class2 = $a["col_".$i."_class_2"];
                                   }else{$class2 = '';}
                                   echo '<div class="'.$a["col_".$i."_class"].'"  style=""><div class="'.$class2.'">';
                                   $contents = explode(",",$a["col_".$i."_content"]);
                                   for($j=0;$j<count($contents);$j++){
                                        if($contents[$j] === "title"){               
                                             //  print_r($recent);
                                               echo '<h5><a href="'.get_permalink($recent["ID"]).'">'. $recent["post_title"].'</a></h5>';
                                     
                                        }elseif($contents[$j] === "date"){           
                                            $uk_date = explode("-",explode(" ",$recent["post_date"])[0]);
                                              echo   '<p class="foodbiz_color_4"><a href="'.get_permalink($recent["ID"]).'">'.$uk_date[1].'/'.$uk_date[0].'/'.$uk_date[2].'</a></p>';
                               
                                        }elseif(strpos($contents[$j],"dates-") !== false){                  
                                                  $old_date_timestamp = strtotime($recent["post_date"]);
                                                  $new_date = date(''.str_replace('dates-','',$contents[$j]).'', $old_date_timestamp); 
                                              echo   '<div class="scrum_single">'. $new_date.'</div>';
                               
                                        }elseif($contents[$j] ===  "thumb"){                  
                                   
                                               echo   '<div class="scrum_thumb"><a href="'.get_permalink($recent["ID"]).'">'.$this->get_the_post_thumbnail( $recent["ID"], $size ).'</a></div>';
                                     
                                         }elseif($contents[$j] ===  "excerpt"){          
                          
                                   
                                                echo   '<p class="foodbiz_color_6">'.substr($recent["post_excerpt"],0,$excerptsize).'</p>';
                                     
                                         }elseif($contents[$j] === "banner"){
                                                      if(isset(get_option("scrum_futured_banner")["b_".$recent["ID"]])){
                                                                echo '<p><a href="'.get_permalink($recent["ID"]).'"><img src="'.get_option("scrum_futured_banner")["b_".$recent["ID"]]["banner_url"].'" alt=""></a></p>';
                                                      }
                                         }
                               
                                     
                                   
                                           }
                                          echo '</div></div>';
                                  }
                                  
                          if(isset($a['separator'])){echo '<div class="col-md-12 news_page"  style=""><'. $a['separator'] .'></div>'; }    echo '</div>';
	} 
                        echo '</div>';
            
        }
    
        
           /**
            * @param Echoes the thumbnail of first post's image and returns success
            *
            * @access   private
            * @since     2.0
            *
            * @return    bool    success on finding an image
            */ 
        
           private function get_the_post_thumbnail( $post_id = null, $size = 'post-thumbnail', $attr = '' ) {
                      $post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
                      $post_thumbnail_id = get_post_thumbnail_id( $post_id );
 
               /**
                * Filter the post thumbnail size.
                *
                * @since 2.9.0
                *
                * @param string $size The post thumbnail size.
                */
                  $size = apply_filters( 'post_thumbnail_size', $size );
 
             if ( $post_thumbnail_id ) {
 
                 /**
                  * Fires before fetching the post thumbnail HTML.
                  *
                  * Provides "just in time" filtering of all filters in wp_get_attachment_image().
                  *
                  * @since 2.9.0
                  *
                  * @param string $post_id           The post ID.
                  * @param string $post_thumbnail_id The post thumbnail ID.
                  * @param string $size              The post thumbnail size.
                  */
                   do_action( 'begin_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size );
                       if ( in_the_loop() )
                       update_post_thumbnail_cache();
                        $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );
 
                       /**
                       * Fires after fetching the post thumbnail HTML.
                       *
                       * @since 2.9.0
                       *
                       * @param string $post_id           The post ID.
                       * @param string $post_thumbnail_id The post thumbnail ID.
                       * @param string $size              The post thumbnail size.
                       */
                         do_action( 'end_fetch_post_thumbnail_html', $post_id, $post_thumbnail_id, $size );
 
             } else {
                         $html = '';
             }
               /**
                * Filter the post thumbnail HTML.
                *
                * @since 2.9.0
                *
                * @param string $html              The post thumbnail HTML.
                * @param string $post_id           The post ID.
                * @param string $post_thumbnail_id The post thumbnail ID.
                * @param string $size              The post thumbnail size.
                * @param string $attr              Query string of attributes.
                */
             return apply_filters( 'post_thumbnail_html', $html, $post_id, $post_thumbnail_id, $size, $attr );
}
    
}

$SCRUM_POSTS = new SCRUM_POSTS();
$SCRUM_POSTS->init();


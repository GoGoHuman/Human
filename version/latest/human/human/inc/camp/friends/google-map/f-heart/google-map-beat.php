<?php

// add_action('wp_head',array(&$this,'preload_to_header'));
add_shortcode ( 'human_google_map', 'humanGoogleMap' );

add_action ( 'vc_before_init', 'humanGoogleMapVcMap', 4 );

function humanGoogleMapVcMap () {
            $human_template_names = human_template_names ( 'human_widgets' );
            $human_templates_name[] = "--- Select ---";
            foreach ( $human_template_names as $key => $human_templates_n ) {
                        //print_r($title);
                        $human_templates_name[ $human_templates_n[ 'post_title' ] ] = $human_templates_n[ 'post_title' ];
            }
            vc_map ( array (
                        "name" => __ ( "Human Google Map", "human" ),
                        "base" => "human_google_map",
                        "class" => "human_google_map",
                        "category" => __ ( "Human Content", "human" ),
                        "icon" => human_icon (),
                        "params" => array (
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_map_address",
                                                "heading" => __ ( "Business Address or Coordinates", "human" ),
                                                "param_name" => "human_map_address",
                                                "value" => "",
                                    ),
                                    array (
                                                "type" => "attach_image",
                                                "holder" => "div",
                                                "class" => "human_map_icon",
                                                "heading" => __ ( "Google map icon", "human" ),
                                                "param_name" => "human_map_icon",
                                                "value" => "",
                                    ),
                                    array (
                                                "type" => "dropdown",
                                                "holder" => "div",
                                                "class" => "human_map_content",
                                                "heading" => __ ( "Google map pop up content", "human" ),
                                                "param_name" => "human_map_content",
                                                "value" => $human_templates_name,
                                    ),
                                    array (
                                                "type" => "textarea_raw_html",
                                                "holder" => "div",
                                                "class" => "human_map_json",
                                                "heading" => __ ( "Google map json (optional)", "human" ),
                                                "param_name" => "human_map_json",
                                                "value" => "",
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_map_zoom",
                                                "heading" => __ ( "Google map zoom", "human" ),
                                                "param_name" => "human_map_zoom",
                                                "value" => "8",
                                    ),
                                    array (
                                                "type" => "textfield",
                                                "holder" => "div",
                                                "class" => "human_google_map_api",
                                                "heading" => __ ( "Google API key", "human" ),
                                                "param_name" => "human_google_map_api",
                                                "value" => "",
                                    ),
                        )
                        )
            );
}

function humandefineGoogleMap ( $api_key ) {
            wp_dequeue_script ( 'google-maps-api' );
            wp_enqueue_script ( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=' . $api_key, array (
                        'jquery' ) );
            //   return '<script id="google-maps-api" src="https://maps.googleapis.com/maps/api/js?key=' . $api_key . '"  async defer></script>';
}

function humanGoogleMap ( $attr = null ) {

            if ( isset ( $attr[ 'human_map_json' ] ) ) {
                        $json = html_entity_decode ( stripslashes ( urldecode ( base64_decode ( $attr[ 'human_map_json' ] ) ) ) );
            }
            else {
                        $json = '[{}]';
            }
            $zoom = 6;
            if ( isset ( $attr[ 'human_google_map_api' ] ) ) {
                        $apiKey = $attr[ 'human_google_map_api' ];
            }
            else {
                        $apiKey = ''; //AIzaSyC64Ne2cUKKz0BLnOrmfSKW3mOqNvRuvyk';
            }

            humandefineGoogleMap ( $apiKey );
            if ( isset ( $attr[ 'human_map_zoom' ] ) ) {
                        $zoom = $attr[ 'human_map_zoom' ];
            }
            $icon = '';
            if ( isset ( $attr[ 'human_map_icon' ] ) ) {
                        $icon = wp_get_attachment_image_src ( $attr[ 'human_map_icon' ] )[ 0 ];
            }
            $html_content = '';
            //print_r ( $content );

            $content_shortcode = '[human_template name="' . $attr[ 'human_map_content' ] . '" type="human_widgets"]';
            if ( isset ( $attr[ 'human_map_content' ] ) ) {
                        $html_content = "<div class='iwContent' id='human-map-content'>" . do_shortcode ( $content_shortcode ) . "</div>";
            }
            // defineGoogle();
            $script = '<textarea id="html_content"  style="display:none">' . $html_content . '</textarea>
                <script type="text/javascript">
                        jQuery(document).ready(function(){
                              html_content = jQuery("#html_content").val();
                              var geocoder = new google.maps.Geocoder();
                              var address = "' . $attr[ 'human_map_address' ] . '"; //Add your address here, all on one line.

                              var latitude;
                              var longitude;
                              var color = ""; //Set your tint color. Needs to be a hex value.

                              function getGeocode() {
                                      geocoder.geocode( { "address": address}, function(results, status) {
		              if (status == google.maps.GeocoderStatus.OK) {
    		                                latitude = results[0].geometry.location.lat();
		                                longitude = results[0].geometry.location.lng();
		                                initGoogleMap();
    	                                 }
	                    });
                              }

                              function initGoogleMap() {
                                          var styles = ' . $json . ';
	                        var options = {
		                                 mapTypeControlOptions: {
			                               mapTypeIds: ["Styled"]
		                              },
		                             center: new google.maps.LatLng(latitude, longitude),
		                             zoom: ' . $zoom . ',
		                             scrollwheel: false,
		                             navigationControl: false,
		                             disableDefaultUI: true,
			           mapTypeControl: false,
			           zoomControl: true,
                                                                 mapTypeId: "Styled"
	                          };
	                          var div = document.getElementById("googleMap");
	                          var map = new google.maps.Map(div, options);
		        if(window.innerWidth < 760){
                                                  map.setOptions({draggable: false});

	                          }else{

                                                  map.setOptions({draggable: true});
		        }
                                             var iconBase = "' . $icon . '";
                                             var marker = new google.maps.Marker({
                                               position: new google.maps.LatLng(latitude,longitude),
                                               map: map,
                                               icon: iconBase


	                          });


	                          var styledMapType = new google.maps.StyledMapType(styles, { name: "Styled" });
	                          map.mapTypes.set("Styled", styledMapType);


	                          var infowindow = new google.maps.InfoWindow({
	                                   content:html_content
	                          });
	                          google.maps.event.addListener(marker, "click", function() {
	                                   infowindow.open(map,marker);
	                          });



                            }
                            google.maps.event.addDomListener(window, "load", getGeocode);
	         google.maps.event.addDomListener(window, "resize", getGeocode);

                      });
           </script>

           <div id="googleMap" class="google-map" style="-webkit-appearance: none;width:100%;min-height:500px;background:grey"></div>';


            return $script;
}

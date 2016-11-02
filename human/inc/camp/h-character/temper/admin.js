function replaceAll ( str, find, replace ) {
          if ( typeof str !== 'undefined' ) {

                    return str.replace ( new RegExp ( find, 'g' ), replace );
          } else {
                    //  console.log(str + find + replace);
                    return;
          }
}


var getUrlParameter = function getUrlParameter ( sParam ) {
          var sPageURL = decodeURIComponent ( window.location.search.substring ( 1 ) ),
                    sURLVariables = sPageURL.split ( '&' ),
                    sParameterName,
                    i;

          for ( i = 0; i < sURLVariables.length; i++ ) {
                    sParameterName = sURLVariables[i].split ( '=' );

                    if ( sParameterName[0] === sParam ) {
                              return sParameterName[1] === undefined ? true : sParameterName[1];
                    }
          }
};


/**
 * @param Automatics ajax function
 * @requires Data = {
 action: action,
 nonce: humanAjax.nonce,
 more: data
 }
 function friend_name_ajax_call_back(callback){
 //do what you want with your callback
 }
 */
function human_ajax ( data, callback, datatype ) {
          var ajaxurl = humanAjax.ajaxurl;
          console.log ( ajaxurl );
          jQuery.ajax ( {
                    url: ajaxurl,
                    data: data,
                    type: 'POST',
                    datatype: 'json',
                    success: function ( response ) {
                              //  location.reload();
                              console.log ( response )
                              window[callback] ( response );
                    }
          } );

}

ajaxurl = humanAjax.ajaxurl;
jQuery ( document ).ready ( function ( $ ) {


          var post_type = '';
          if ( $ ( '#post_type' ).length > 0 && $ ( '#post_type' ).val () ) {
                    var post_type = $ ( '#post_type' ).val ();
          }
          //alert(post_type);
          if ( post_type === 'human_templates' || post_type === 'human_widgets' ) {
                    $ ( '#wpseo_meta' ).hide ();
          }


          $ ( '.human_media_holder' ).live ( 'change', function () {
                    console.log ( $ ( this ) );
                    $ ( this ).next ( '.human_media_holder_img' ).remove ();
                    $ ( '<img src="' + $ ( this ).val () + '" width="120" class="human_media_holder_img">' ).insertAfter ( $ ( this ) );
          } );

          var custom_uploader;


          /*
           *  @param  Human media uploader.
           *  @example:
           *  <a class="human-media" >upload </a>
           *  <input type="hidden" class="human_media_holder"> --> image url holder
           *
           */

          $ ( '.human-media' ).live ( "click", function ( e ) {

                    e.preventDefault ();
                    var link_parent = '';
                    var link = '';
                    link = $ ( this );
                    link_parent = $ ( this ).parent ().attr ( 'id' );
                    //console.log ( $ ( '#' + link_parent ) );
                    if ( $ ( this ).attr ( 'data-media-css-holder' ) ) {
                              var css_holder = $ ( this ).attr ( 'data-media-css-holder' );
                              // console.log(css_holder)
                    }
                    //If the uploader object has already been created, reopen the dialog
                    if ( custom_uploader ) {
                              //custom_uploader.open ();
                              //    return;
                    }

                    //Extend the wp.media object
                    custom_uploader = wp.media.frames.file_frame = wp.media ( {
                              title: 'Choose File',
                              button: {
                                        text: 'Choose File'
                              },
                              multiple: true
                    } );

                    //When a file is selected, grab the URL and set it as the text field's value
                    custom_uploader.on ( 'select', function (  ) {
                              attachment = custom_uploader.state ().get ( 'selection' ).first ().toJSON ()['url'];

                              if ( css_holder ) {

                                        //  console.log(attachment);
                                        $ ( '#' + css_holder ).val ( attachment );
                                        $ ( '#' + css_holder + '-holder' ).html ( '<img src="' + attachment + '" alt="" style="width:40px;height:40px">' );
                                        update_css ();
                              } else {
                                        if ( link_parent.length > 0 ) {
                                                  console.log ( $ ( '#' + link_parent ) );
                                                  $ ( '#' + link_parent ).find ( '.human_media_holder' ).val ( attachment );
                                                  console.log ( link );
                                                  $ ( '#' + link_parent ).find ( 'a' ).attr ( 'style', 'background:url("' + attachment + '");color:transparent' );
                                        } else {
                                                  console.log ( 'no media wrapper found' );
                                                  //$ ( '.human_media_holder' ).val ( attachment );
                                        }

                              }
                              link_parent = '';
                              return;
                    } );

                    //Open the uploader dialog
                    custom_uploader.open ();

          } );


} );

$ = jQuery;
function update_css_media_holder ( elem, img ) {
          var css_id = $ ( 'input[name="' + elem.replace ( /(\r\n|\n|\r)/gm, "" ).replace ( / /g, '' ) ).attr ( 'id' );

          if ( img === 1 ) {
                    $ ( '#' + css_id + '-holder' ).empty ();
          } else {

                    $ ( '#' + css_id + '-holder' ).html ( '<img src="' + img + '" alt="" style="width:40px;height:40px">' );
          }

}


function save_css_gen_ajax ( save ) {
          var save_template = 0;
          if ( save > '' && save !== 0 ) {
                    save_template = save;
          }
          var action = 'cssGenAjax';
          var ajaxurl = humanAjax.ajaxurl;
          var style_gens = btoa ( $ ( '#main-css-div-holder' ).find ( '.all-gens' ).html ().trim () );

          var style_gens_minified = $ ( "#vc_inline-frame" ).contents ().find ( "#human_temp_css_holder" ).find ( 'style' ).html ();
          var custom_css = $ ( "#vc_inline-frame" ).contents ().find ( "#custom-css-style-holder" ).find ( 'style' ).html ();
          var data = {
                    action: action,
                    nonce: humanAjax.nonce,
                    style_gens_minified: style_gens_minified,
                    style_gens: style_gens,
                    custom_css: custom_css,
                    autosave: 'normal',
                    save_template: save_template
          };
          console.log ( style_gens );
          if ( style_gens === null ) {
                    return;
          }
          toggle_human_gif ();
          console.log ( ajaxurl );
          jQuery.ajax ( {
                    url: ajaxurl,
                    data: data,
                    type: 'POST',
                    datatype: 'json',
                    success: function ( response ) {

                              console.log ( response );
                              if ( typeof response['data'] !== 'undefined' && typeof response['data']['error'] !== 'undefined' ) {
                                        console.log ( response['data']['error'] );
                              } else {

                                        $ ( '#css-is-saved' ).val ( 1 );
                                        $ ( '#vc_button-update' ).trigger ( 'click' );
                                        setTimeout ( function () {
                                                  toggle_human_gif ( 'hide' );
                                                  $ ( '#css-is-saved' ).val ( 0 );
                                        }, 10000 );

                              }
                    }
          } );

}
function html_css_bar ( folder, section, tag ) {
          return  '<div class="html-css-bar"><table><tr><td><span class="del-css-selector">[x]</span></td><td><button class="select-css">edit</button></td><td><span class="handle fa fa-arrows"></span></td></tr></table></div>';
}
function insRes ( current_resolution, res ) {
          if ( $ ( '#temp-css-' + current_resolution ).length === 0 && current_resolution > 0 ) {
                    $ ( '<div id="temp-css-' + current_resolution + '" class="temp-css resolutions" style="display: none;"  data-res_number="' + current_resolution + '"></div>' ).insertAfter ( '#' + res.attr ( 'id' ) );
          }
}


function update_css_holders ( s_styles, selected_tag, propname ) {
          var current_resolution = $ ( '#human_screen_res' ).val ();
          console.log ( $ ( '#temp-css-' + current_resolution ) );
          if ( current_resolution.length > '' && $ ( '#temp-css-' + current_resolution ).length === 0 ) {
                    var i = 0;
                    var resolutions = $ ( '.resolutions' );
                    console.log ( resolutions );
                    resolutions.each ( function () {
                              i++;
                              var c = i + 1;
                              console.log ( $ ( this ).attr ( 'data-res_number' ) );
                              console.log ( current_resolution );
                              console.log ( $ ( resolutions[i] ).attr ( 'data-res_number' ) );
                              console.log ( 'brake' );

                              if ( parseInt ( current_resolution ) < parseInt ( $ ( this ).attr ( 'data-res_number' ) ) && parseInt ( current_resolution ) > parseInt ( $ ( resolutions[i] ).attr ( 'data-res_number' ) ) ) {
                                        console.log ( '-----' );
                                        insRes ( current_resolution, $ ( this ) );
                                        return false;

                              }

                    } );
          }

          var resolution_start = '';
          var resolution_end = '';
          var resolution_style_id = '';
          var resolution = '';
          if ( $ ( '#human_screen_res' ).val () ) {
                    var resolution_start = '@media and screen (min-width:' + $ ( '#human_screen_res' ).val () + 'px){ ';
                    var resolution_end = ' }';
                    var resolution_style_id = $ ( '#human_screen_res' ).val ();
                    var resolution = '-' + $ ( '#human_screen_res' ).val ();
          }
          var s_styles = replaceAll ( s_styles, '&#039;', '"' );
          var propname = replaceAll ( propname, '"', '-' );
          var temp_style_id = 's_' + resolution_style_id + replaceAll ( propname, ',', '' );
          var html_css_bars = html_css_bar ();

          var temp_styles = html_css_bars + '<div class="styles">' + selected_tag + '\n' + '{ \n' +
                    s_styles + '\n} </div>';
          if ( $ ( '#' + temp_style_id ).length == 0 ) {

                    $ ( '#temp-css' + resolution ).append ( '<div id="' + temp_style_id + '"  css-folder="' + $ ( '#template_wraps' ).val () + '" css-section="' + $ ( '#custom_sections' ).val () + '" css-tag="' + selected_tag + '" >' + temp_styles + '</div>' );
                    //console.log(temp_style_id+' = doesn\'t exists');


          } else {

                    //console.log(getElementById(temp_style_id));
                    $ ( "#" + temp_style_id ).empty ();
                    //  console.log($('#'+temp_style_id).html());
                    $ ( "#" + temp_style_id ).append ( temp_styles );


          }
          var all_styles = '';
          var all_temp_styles = '';
          $.each ( $ ( '#temp-css .styles' ), function () {

                    all_styles += $ ( this ).html ();
                    all_temp_styles += $ ( this ).html ();
          } );

          var resolution = $ ( '#human_screen_res' ).val ();
          $.each ( $ ( '.resolutions' ), function () {
                    var and = '';
                    var res_number = parseInt ( $ ( this ).attr ( 'id' ).replace ( 'temp-css-', '' ) );

                    var to_res = '(max-width: ' + res_number + 'px)';

                    all_styles += '@media screen and ' + to_res + '{ ';
                    all_temp_styles += '@media screen and ' + to_res + '{ ';
                    $ ( this ).find ( '.styles' ).each ( function () {

                              all_styles += $ ( this ).html ();
                              if ( resolution ) {
                                        if ( res_number <= resolution ) {
                                                  all_temp_styles += $ ( this ).html ();

                                                  //  console.log(resolution+'-'+res_number);
                                        }
                              } else {
                                        all_temp_styles += $ ( this ).html ();
                              }
                    } );
                    all_styles += ' }';
                    all_temp_styles += ' }';
          } );

          var ready = replaceAll ( all_styles, '.human-page .human-page', '.human-page' );
          ready = replaceAll ( ready, '&gt;', '>' ).replace ( '&amp;gt;', '>' );
          var ready_temp = replaceAll ( all_temp_styles, '.human-page .human-page', '.human-page' );
          //  $('.gen-window').html(replaceAll(ready, '.human_select', '').replace(/\r?\n/g, '<br>'));
          // $('.gen-holder').html('<style type="text/css">' + replaceAll(ready, '.human_select', '') + '</style>');


          if ( $ ( "#vc_inline-frame" ).length > 0 ) {

                    if ( $ ( "#vc_inline-frame" ).contents ().find ( '#human_temp_css_holder' ).length === 0 ) {

                              $ ( "#vc_inline-frame" ).contents ().find ( '#human-dynamic-css-css' ).remove ();
                              $ ( "#vc_inline-frame" ).contents ().find ( "body" ).prepend ( '<div id="human_temp_css_holder"></div><div id="custom-css-style-holder"></div>' );

                    }


                    $ ( "#vc_inline-frame" ).contents ().find ( "#human_temp_css_holder" ).html ( '<style media="all">' + ready + '</style>' );
                    $ ( "#vc_inline-frame" ).contents ().find ( "#custom-css-style-holder" ).html ( '<style media="all">' + $ ( '#human-custom-css' ).val () + '</style>' );


          }



}

function update_css () {
          if ( $ ( '#selected_tag' ).val () ) {


                    var propnames = $ ( '#selected_tag' ).val ();
                    var allcontrols = $ ( ".controls_inner" ).find ( "input,select" );

                    var s_styles = "";
                    var align = $ ( '#text-align-holder' ).val ();
                    $ ( '.human-align' ).css ( 'opacity', 1 );
                    $ ( '*[data-align="' + align + '"]' ).css ( 'opacity', 0.5 );

                    $.each ( allcontrols, function () {

                              var pval = $ ( this ).val ();
                              var pname = $ ( this ).attr ( 'name' );
                              if ( $ ( this ).attr ( 'id' ) !== 'selected_tag' ) {
                                        if ( typeof pval !== 'undefined' && typeof pname !== 'undefined' ) {
                                                  if ( pval ) {

                                                            if ( pname === 'background-image' || pname === 'list-style-image' ) {
                                                                      if ( pval != 'none' ) {
                                                                                // pval = '/wp-content'+ pval.split('wp-content')[1];
                                                                                pval = 'url("' + pval + '")';
                                                                                // console.log(pval);
                                                                      } else {
                                                                                pval = pval;
                                                                      }
                                                            } else if ( pname.indexOf ( 'color' ) > -1 ) {

                                                            } else {
                                                                      if ( $ ( '#' + pname + '-unit' ).length > 0 ) {
                                                                                var unit = $ ( '#' + pname + '-unit' ).val ();
                                                                      } else {
                                                                                var unit = '';
                                                                      }
                                                                      var pval = pval + unit;


                                                            }
                                                            s_styles += pname + ':' + pval + ';\r';

                                                  }
                                        }
                              }

                    } );

                    //  $('#s_'+propname).remove();
                    var selected_tag = jQuery ( '#selected_tag' ).val ();
                    if ( selected_tag.indexOf ( 'placeholder' ) > -1 ) {
                              var selected_tag = $ ( '#human_sections' ).val () + ' .placeholder,*::-webkit-input-placeholder, *::-moz-placeholder, *:-ms-input-placeholder,*:-moz-placeholder';
                              selected_tag = selected_tag.split ( ',' );
                              $.each ( selected_tag, function () {


                                        var propnames = this.replace ( /\W+/g, "_" );
                                        var propname = propnames;
                                        update_css_holders ( s_styles, this, propname );

                              } );

                              return;
                    }

                    var propnames = selected_tag.replace ( /\W+/g, "_" );

                    var propname = propnames;

                    update_css_holders ( s_styles, selected_tag, propname );

                    current_color_pallete ();
                    return;
          } else {

                    alert ( 'Please select an element(s)' );
                    return;
          }

}


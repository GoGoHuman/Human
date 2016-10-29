
function toggle_css_holders () {

          $ ( '.temp-css' ).hide ();
          var res = '';
          if ( $ ( '#human_screen_res' ).val ().length > 0 ) {
                    var res = '-' + $ ( '#human_screen_res' ).val ();
          }

          $ ( "#temp-css" + res ).show ();
}

function css_select_links () {
          var tags = '<table>';
          var resolution = '';
          if ( $ ( '#human_screen_res' ).val ().length > 0 ) {
                    resolution = '-' + $ ( '#human_screen_res' ).val ();
          }
          $.each ( $ ( '#temp-css' + resolution ).find ( 'div' ), function () {


                    //   $('div[css_folder="' + $('#template_wraps').val() + '"][css_section="' + $('#custom_sections').val() + '"]')
                    if ( $ ( this ).attr ( 'css-folder' ) === $ ( '#template_wraps' ).val () && $ ( this ).attr ( 'css-section' ) === $ ( '#custom_sections' ).val () ) {
                              tags += '<tr related_tag="' + $ ( this ).attr ( 'css-tag' ) + '" class="related-tag-row"><td><i class="fa fa-pencil-square-o" aria-hidden="true"></i></td><td>' + $ ( this ).attr ( 'css-tag' ) + '</td></tr>';
                              //  console.log($(this));
                    } else if ( $ ( this ).attr ( 'css-folder' ) === $ ( '#template_wraps' ).val () && $ ( '#custom_sections' ).val ().length === 0 ) {

                              tags += '<tr related_tag="' + $ ( this ).attr ( 'css-tag' ) + '" css-id="' + $ ( this ).attr ( 'id' ) + '" class="related-tag-row"><td><i class="fa fa-pencil-square-o" aria-hidden="true"></i></td><td>' + $ ( this ).attr ( 'css-tag' ) + '</td></tr>';

                    }
          } );
          $ ( '#related_tags' ).html ( tags + '</table>' );
}

function current_color_pallete () {
          var color = 'none';

          var $ = jQuery;
          $ ( '.css-color-holder' ).each ( function () {
                    var t = $ ( this );
                    if ( t.val ().length > 0 ) {
                              var color = t.val ();

                              t.closest ( 'td' ).find ( '.toggle-iris' ).attr ( 'style', 'background:' + color );

                              // console.log(color);
                    } else {
                              t.closest ( 'td' ).find ( '.toggle-iris' ).removeAttr ( 'style' );
                    }
                    // console.log(t.closest('td').find('.toggle-iris'));
          } );
}
function toggle_css ( column ) {
          $ ( '.toggles' ).hide ();
          if ( !$ ( "#" + column ).hasClass ( 'css-expanded' ) ) {
                    $ ( "#" + column ).show ();
                    $ ( "#" + column ).addClass ( 'css-expanded' );
          } else {
                    $ ( '.css-expanded' ).removeClass ( 'css-expanded' );
          }
          toggle_css_holders ();
}
function change_tags () {

          var current_id = trim ( $ ( "#selected_tag" ).attr ( 'css-id' ) );
          var current_styles = $ ( '#' + current_id ).find ( '.styles' ).html ();
          var css_holder = $ ( "#new_tags" ).val ().replace ( /\W+/g, "_" );
          var resolution = '';
          if ( $ ( '#human_screen_res' ).val () ) {
                    var resolution = $ ( '#human_screen_res' ).val ();
          }
          var new_id = 's_' + resolution + css_holder;

          current_styles = $ ( "#new_tags" ).val () + '{' + current_styles.split ( '{' )[1];
          $ ( "#selected_tag" ).attr ( 'css-id', new_id );
          $ ( '#' + current_id ).find ( '.styles' ).html ( current_styles );
          $ ( '#' + current_id ).attr ( 'css-tag', $ ( "#new_tags" ).val () );
          $ ( '#' + current_id ).attr ( 'id', new_id );
          var css_folder = '';
          var css_section = '';
          if ( $ ( '#custom_sections' ).val ().length > 0 ) {
                    css_section = $ ( '#custom_sections' ).val ();
          }
          if ( $ ( '#template_wraps' ).val ().length > 0 ) {
                    css_folder = $ ( '#template_wraps' ).val ();
          }
          $ ( '#' + current_id ).attr ( 'css-folder', css_folder );
          $ ( '#' + current_id ).attr ( 'css-section', css_section );
          $ ( "#selected_tag" ).val ( $ ( "#new_tags" ).val () );

          select_tag ( 'preselect' );
          $ ( '#selected_tag' ).toggle ();
          $ ( '#new_tags_row' ).toggle ();
}

function select_tag ( tag ) {

          $ = jQuery;
          $ ( '.selected_wrapper' ).removeAttr ( 'id' );
          $ ( '.selected_wrapper' ).removeClass ( 'selected_wrapper' );
          $ ( '#selected-align' ).removeAttr ( 'style' );
          $ ( '#selected-align' ).removeAttr ( 'id' );
          var global_wraps = $ ( '#human_global_wrappers' ).val ();
          var template_wraps = '';
          var human_sections = '';
          var human_sections_pseudos = '';
          var human_elements = '';
          var human_elements_pseudos = '';
          var custom_sections = '';
          if ( $ ( '#template_wraps' ).val () ) {

                    var template_wraps = ' ' + $ ( '#template_wraps' ).val ();

          }
          if ( $ ( '#custom_sections' ).val () ) {
                    var custom_sections = ' ' + $ ( '#custom_sections' ).val ();
          }
          if ( $ ( '#human_sections' ).val () ) {
                    var human_sections = ' ' + $ ( '#human_sections' ).val ();
          }
          if ( $ ( '#human_sections_pseudos' ).val () ) {
                    var human_sections_pseudos = $ ( '#human_sections_pseudos' ).val ();
          }
          if ( $ ( '#human_elements' ).val () ) {
                    var human_elements = ' ' + $ ( '#human_elements' ).val ();
          }
          if ( $ ( '#human_elements_pseudos' ).val () ) {
                    var human_elements_pseudos = $ ( '#human_elements_pseudos' ).val ();
          }
          var current_tag = global_wraps + template_wraps + custom_sections + human_sections + human_sections_pseudos + human_elements + human_elements_pseudos;
          if ( tag ) {
                    if ( tag === 'preselect' ) {
                              var current_tag = $ ( "#selected_tag" ).val ();
                    }
          }
          $ ( "#selected_tag" ).val ( current_tag );



          var css_holder = current_tag.replace ( /\W+/g, "_" );

          //console.log(css_holder);
          $ ( '.controls_inner input' ).removeAttr ( 'value' );
          $ ( '.controls_inner select option:first-child' ).prop ( 'selected', true );
          $ ( '#human-controls-bg-image-holder' ).empty ();
          $ ( '#human-controls-list-style-image-holder' ).empty ();
          var resolution = '';
          if ( $ ( '#human_screen_res' ).val () ) {
                    var resolution_start = '@media and screen (min-width:' + $ ( '#human_screen_res' ).val () + 'px){ ';
                    var resolution_end = ' }';
                    var resolution_style_id = $ ( '#human_screen_res' ).val ();
                    var resolution = $ ( '#human_screen_res' ).val ();
          }

          $ ( "#selected_tag" ).attr ( 'css-id', 's_' + resolution + css_holder );

          $ ( "#selected_tag" ).attr ( 'title', $ ( "#selected_tag" ).val () );
          if ( $ ( '#s_' + resolution + css_holder ).html () ) {
                    var styles = $ ( '#s_' + resolution + css_holder ).html ().split ( '{' )[1].split ( '}' )[0];
                    var props = styles.split ( ';' );
                    var props_length = props.length - 1;
                    $.each ( props, function ( i ) {

                              var input = this.split ( ':' );
                              if ( i < props_length ) {
                                        var propname = input[0].replace ( /(\r\n|\n|\r)/gm, "" ).replace ( / /g, '' );

                                        if ( propname.indexOf ( 'image' ) > -1 ) {

                                                  var holder = propname;
                                                  if ( input[2] == 'none' ) {
                                                            var prop_val = 'none';

                                                            update_css_media_holder ( holder, 1 );
                                                  } else {
                                                            //	console.log(this.split(':'));
                                                            var bg_url = input[1] + ':' + input[2];

                                                            // ^ Either "none" or url("...urlhere..")
                                                            bg_url = /^url\((['"]?)(.*)\1\)$/.exec ( bg_url );

                                                            bg_url = bg_url ? bg_url[2] : ""; // If matched, retrieve url, otherwise ""

                                                            //bg_url = replaceAll(replaceAll(bg_url,'https','')bg_url,'http','');
                                                            //	alert(bg_url);
                                                            var prop_val = bg_url;

                                                            update_css_media_holder ( holder, bg_url );
                                                  }
                                        } else {
                                                  if ( $ ( "#" + propname + "-unit" ).length > 0 ) {
                                                            var n_val = input[1].replace ( /[^-\d\.]/g, '' );

                                                            var prop_val = n_val;
                                                            $ ( "#" + propname + "-unit" ).val ( input[1].replace ( /\d+/g, '' ).replace ( '.', '' ).replace ( '-', '' ) );
                                                            //    $( '.human-slider [data-prop="'+propname+'"]' ).slider( "value", prop_val );
                                                  } else {
                                                            var prop_val = input[1];
                                                  }
                                        }

                                        $ ( "[name=" + propname + "]" ).val ( prop_val );

                              }

                    } );
                    //console.log($('#css_iframe').contents().find(current_tag));
                    // console.log('=='+current_tag+'==');
                    if ( $ ( '#css_iframe' ).contents ().find ( current_tag ).length > 0 ) {
                              $ ( '#css_iframe' ).contents ().find ( current_tag ).addClass ( 'selected-temp' );


                              $ ( '#css_iframe' ).contents ().find ( '.selected-temp' ).each ( function () {

                                        $ ( this ).removeClass ( 'selected-temp', 50000 );


                                        //console.log($(this));
                              } );

                    }
          }
          $ ( '.human-units' ).each ( function () {
                    if ( !$ ( this ).val () ) {
                              $ ( this ).val ( $ ( this ).data ( 'value' ) );
                    }
          } );

          current_color_pallete ();
          toggle_css ();
          css_select_links ();
          toggle_human_gif ( 'hide' );
          return;


}

function css_section () {
          if ( jQuery ( '#human_sections' ).val () !== 'human-page' && jQuery ( '#human_sections' ).val () !== 'human-page-inner' ) {
                    jQuery ( '.human-page-inner' ).find ( '.sections_id' ).removeAttr ( 'class' ).attr ( 'class', 'sections_id ' + jQuery ( '#human_sections' ).val () );
          } else {
                    jQuery ( '.human-page-inner' ).find ( '.sections_id' ).removeAttr ( 'class' ).attr ( 'class', 'sections_id' );
          }
}

function human_tag_css_helper ( str ) {
          if ( str && str.length > 3 ) {
                    $ ( '#human_tag_css_helper' ).html ( str );
                    var height = $ ( '#human_tag_css_helper' ).height ();
                    $ ( "#vc_inline-frame" ).height ( $ ( "body" ).height () - height - 56 );
          } else {
                    //    var height = $('#human_tag_css_helper').height();
                    $ ( "#vc_inline-frame" ).height ( $ ( "body" ).height () - 56 );
                    $ ( '#human_tag_css_helper' ).empty ();
          }
}


function tag_convert_test ( newClasses, tag, thisClasses ) {

          var ready = '';
          var hc = '<div style="">';
          for ( i = 0; i < newClasses.length; i++ ) {

                    var tips = '';
                    for ( j = 0; j < newClasses[i].length; j++ ) {
                              if ( j > 0 ) {
                                        tips += ' .' + newClasses[i][j];
                              } else {

                                        tips += '.' + newClasses[i][j];
                              }
                    }
                    var actual = tips + ' ' + tag;
                    var actualClass = tips + ' ' + tag + thisClasses;
                    var simple = actual.replace ( /\W+/g, "_" );
                    simple = 's_' + simple;
                    var existing = '';
                    var existing_with = '';
                    if ( $ ( '#' + simple ).length > 0 ) {
                              existing = 'human-rule-exist';
                    } else {
                              console.log ( simple );
                    }
                    if ( $ ( '#' + simple + thisClasses ).length > 0 ) {
                              existing_with = 'human-rule-exist';
                    } else {
                              console.log ( '#' + simple + thisClasses );
                    }
                    hc += '<div style="position:relative;width:49%;background:#fff;float:left" class="' + existing + '" select_convert_tag="' + actual + '">';
                    hc += i + '.1: ' + actual + '</div>';
                    if ( thisClasses.length > 1 ) {

                              hc += '<div style="position:relative;width:49%;background:#fff;float:left" class="' + existing_with + '"  select_convert_tag="' + actualClass + '">';
                              hc += i + '.2: ' + actualClass + '</div>';
                    }

          }
          hc += '</div>';
          human_tag_css_helper ( hc );
          //     console.log(hc);
}

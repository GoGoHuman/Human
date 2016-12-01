
window.onload = function () {
          window.addEventListener ( "beforeunload", function ( e ) {
                    if ( jQuery ( '#css-is-saved' ).val () > 0 ) {
                              return 'undefined';
                    }
                    var confirmationMessage = 'It looks like you have an unsaved CSS. '
                              + 'If you leave before saving, your changes will be lost.';
                    ( e || window.event ).returnValue = confirmationMessage; //Gecko + IE
                    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
          } );
};
jQuery ( document ).ready ( function ( $ ) {

          toggle_human_gif ();
          $ ( '.toggle-css' ).live ( 'click', function () {
                    var column = $ ( this ).attr ( 'data-toggle' );
                    toggle_css ( column );
          } );
          $ ( "#section_folder" ).autocomplete ( {
                    appendTo: "#section_folder_holder",
                    source: function ( request, resolve ) {
                              // fetch new values with request.term
                              //     console.log($('#section_folder_tags').html().split(','));
                              resolve ( $ ( '#section_folder_tags' ).html ().split ( ',' ) );
                    },
                    minLength: 1 }
          );
          $ ( '.human-media' ).live ( "click", function ( e ) {

                    e.preventDefault ();
                    if ( $ ( this ).attr ( 'data-media-css-holder' ) ) {
                              var css_holder = $ ( this ).attr ( 'data-media-css-holder' );
                              // console.log(css_holder);
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
                    custom_uploader.on ( 'select', function () {
                              attachment = custom_uploader.state ().get ( 'selection' ).first ().toJSON ()['url'];
                              if ( css_holder ) {

                                        // console.log(attachment);
                                        $ ( '#' + css_holder ).val ( attachment );
                                        $ ( '#' + css_holder + '-holder' ).html ( '<img src="' + attachment + '" alt="" style="width:40px;height:40px">' );
                                        update_css ();
                              } else {
                                        $ ( '.human_media_holder' ).val ( attachment );
                              }
                    } );
                    //Open the uploader dialog
                    custom_uploader.open ();
          } );
          $ ( '#control-tabs a' ).on ( 'click', function ( e ) {
                    e.preventDefault ();
                    $ ( '.human-controls-sections' ).hide ( 500 );
                    $ ( '#human-controls-section-' + $ ( this ).attr ( 'data-human-control-section' ) ).show ( 500 );
                    $ ( '.human-active' ).removeClass ( 'human-active' );
                    $ ( this ).addClass ( 'human-active' );
          } );
          $ ( '.human-iris' ).iris ();
          $ ( '.human-iris' ).iris (
                    {
                              change: function ( event, ui )
                              {
// box.css("background", this.value);

                                        $ ( this ).prev ( '.css-color-holder' ).val ( this.value );
                                        update_css ();
                                        $ ( '#css-is-saved' ).val ( '0' );
                              }
                    } );
          $ ( '.human-iris' ).after ( '<div  class="toggle-iris" style=""></div>' );
          $ ( '.controls_inner .iris-picker' ).hide ();
          $ ( '.toggle-iris' ).click ( function ( e ) {

                    $ ( this ).next ( '.iris-picker' ).toggle ( 500 );
          } );
          var isAutoHeight = function ( element ) {
                    // make a staging area for all our work.
                    $ ( 'body' ).append ( '<div></div>' );
                    // assume false by default
                    var autoHeight = false;
                    // clone the div and move it; get its height
                    var clone = element.clone ();
                    clone.appendTo ( '#stage' );
                    var initialHeight = clone.height ();
                    // destroy all the content and compare height
                    clone.html ( '' );
                    var currentHeight = clone.height ();
                    if ( currentHeight && initialHeight ) {
                              autoHeight = true;
                    }

                    // get that clone and its smelly duplicate ID out of the DOM!
                    clone.remove ();
                    // do the same for the stage
                    $ ( '#stage' ).remove ();
                    return autoHeight;
          };
          $ ( '.row-css-description' ).live ( 'click', function () {
                    $ ( this ).closest ( '.html-css-bar' ).find ( '.html-css-description' ).toggleClass ( 'expanded' );
          } );
          //var compose_btn = '<li class="menupop" id="css-builder-menu-wrapper"><a class="ab-item" aria-haspopup="true" href="#"><img src="//human.camp/wp-content/uploads/2016/01/cropped-human-32x32.png" alt="" style="width: 27px;"></a><div class="ab-sub-wrapper"><ul class="ab-submenu"><li class="css-gen-toggle-show"><a class="ab-item text-center" href="#">Css Builder</a></li></ul></li>';
          // alert('vc_navbar-nav');

          $ ( '#vc_logo' ).addClass ( 'css-gen-toggle-show' );
          $ ( '.del-css-selector' ).live ( 'click', function ( e ) {
                    $ ( this ).parents ( '.html-css-bar' ).parent ( 'div' ).remove ();
                    //.log($(this).parent());
                    update_css ();
          } );
          $ ( '#save_css_template' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    e.stopPropagation ();
                    if ( $ ( '#css_template_name' ).val ().length > 0 ) {
                              save_css_gen_ajax ( $ ( '#css_template_name' ).val () );
                    }
          } );


          // $('.del-css-selector').remove();
          if ( typeof jQuery.ui !== 'undefined' ) {

                    $ ( ".css-draggable" ).draggable ();
                    //$(".ui-draggable").disableSelection();
                    $ ( ".css-draggable" ).draggable ( {
                              handle: ".css-bar-logo"

                    } );
                    var selectors = '';
                    $ ( '.temp-css>div' ).each ( function ( i ) {
                              var css_contents = $ ( this ).find ( 'div.styles' ).html ();
                              var selector = css_contents.split ( '{' )[0];
                              selectors = '@@' + selector;
                              var css = css_contents.split ( '{' )[1].split ( '}' )[0];
                              var properties = css.split ( ';' );
                              var new_css = selector + '{\r';
                              $.each ( properties, function () {
                                        var prop = this.split ( ':' )[0];
                                        var val = this.split ( ':' )[1];
                                        if ( this.split ( ':' )[2] ) {

                                                  val = this.split ( ':' )[1] + ':' + this.split ( ':' )[2];
                                        }
                                        if ( prop && typeof val !== 'undefined' ) {
                                                  new_css += prop + ':' + val + ';\r';
                                        }
                              } );
                              new_css += '\n}';
                              // console.log(new_css);
                              // $('.html-css-bar').remove();
                              //$('.del-css-selector').remove();
                              $ ( this ).find ( 'div.styles' ).text ( new_css.replace ( '&gt;', '>' ) );
                              if ( $ ( this ).find ( '.html-css-bar' ).length > 0 ) {

                              } else {
                                        $ ( this ).prepend ( html_css_bar () );
                              }
                              get_all_selectors ();
                    } );
                    $ ( '.temp-css' ).sortable ( {
                              handle: '.handle',
                              cancel: '.html-css-description,.styles'
                    } );
                    $ ( '.html-css-description,.styles' ).live ( 'click', function () {
//  $('.temp-css').sortable("destroy");
                    } );
          }
          $ ( 'button.select-css' ).live ( 'click', function () {
//  console.log($(this));
                    var infobar = $ ( this ).closest ( '.html-css-bar' ).parent ( 'div' );
                    var styles = infobar.find ( '.styles' ).html ();
                    var css_folder = infobar.attr ( 'css-folder' );
                    var css_section = infobar.attr ( 'css-section' );
                    console.log ( css_folder );
                    console.log ( css_section );
                    $ ( '#template_wraps' ).val ( css_folder );
                    $ ( '#custom_sections' ).val ( css_section );
                    //   console.log(styles);
                    var selector = styles.split ( '{' )[0];
                    //   console.log(selector);
                    $ ( '#selected_tag' ).val ( selector.replace ( '&gt;', '>' ) );
                    select_tag ( 'preselect' );
          } );
          $ ( '#change_tags' ).on ( 'click', function () {
                    $ ( '#selected_tag' ).toggle ();
                    $ ( '#new_tags_row' ).toggle ();
                    $ ( '#new_tags' ).val ( $ ( '#selected_tag' ).val () );
          } );
          $ ( '#new_tags_action' ).on ( 'click', function () {
                    change_tags ();
          } );
          function get_all_selectors () {

                    var sel_ul = '<ul>';
                    $ ( '#temp-css>div' ).each ( function ( i ) {
                              var css_contents = $ ( this ).find ( 'div.styles' ).html ();
                              var selector = css_contents.split ( '{' )[0];
                              sel_ul += '<li data-selector="' + selector + '">' + selector + '</li>';
                    } );
                    sel_ul += '</ul>';
                    //  console.log(sel_ul);
          }

          $ ( '.add-color' ).on ( 'click', function () {
                    var color = $ ( this ).closest ( 'table' ).find ( '.css-color-holder' ).val ();
                    var palette = $ ( '.human-palette' );
                    var action = 'cssGenAjax';
                    var ajaxurl = humanAjax.ajaxurl;
                    var data = {
                              nonce: humanAjax.nonce,
                              action: action,
                              newcolor: color
                    };
                    // console.log(data);

                    toggle_human_gif ();
                    // palette.html('sss');      return;

                    console.log ( data );
                    $.post ( ajaxurl, data, function ( response ) {

                              console.log ( response );
                              if ( typeof response.data == 'undefined' ) {

                              } else {
                                        palette.html ( response['data']['colors'] );
                              }

                              toggle_human_gif ( 'hide' );
                    } );
          } );

          $ ( '#vc_screen-size-control' ).append ( '<div id="human-resolution-slider"></div>' );
          $ ( '#human-resolution-slider' ).slider ();
          console.log ( $ ( window ).width () );
          $ ( '#human-resolution-slider' ).slider ( {
                    orientation: "vertical",
                    min: 200,
                    max: 1600,
                    value: $ ( window ).width ()
          } );
          $ ( '#human-resolution-slider span' ).prepend ( '<span id="res-slider-caption">1600</span>' );
          $ ( "#human-resolution-slider" ).on ( "slide", function ( event, ui ) {
                    console.log ( ui );
                    console.log ( ui );
                    var css_res_value = ui.value;
                    var slider_value = css_res_value;
                    var iframe_width = slider_value;
                    if ( slider_value === $ ( window ).width () ) {
                              css_res_value = '';
                              iframe_width = '100%';
                    }
                    $ ( '#res-slider-caption' ).text ( slider_value );
                    $ ( '#human_screen_res' ).val ( css_res_value );
                    $ ( '#vc_inline-frame' ).css ( 'width', iframe_width );
                    select_tag ( 'preselect' );
          } );
          $ ( "#human-resolution-slider" ).on ( "slidestop", function ( event, ui ) {
                    console.log ( event );
                    select_tag ( 'preselect' );

          } );
          $ ( '.delete-palette' ).live ( 'click', function () {
                    var del_color = $ ( this ).attr ( 'data-color' );
                    var palette = $ ( '.human-palette' );
                    var action = 'cssGenAjax';
                    var ajaxurl = humanAjax.ajaxurl;
                    var data = {
                              nonce: humanAjax.nonce,
                              action: action,
                              delete_color: del_color
                    };
                    //  console.log(data);

                    toggle_human_gif ();
                    // palette.html('sss');      return;
                    $.post ( ajaxurl, data, function ( response ) {

                              console.log ( response );
                              if ( response.data['error'] ) {
                              } else {
                                        palette.html ( response['data']['colors'] );
                                        toggle_human_gif ( 'hide' );
                              }
                    } );
          } );

          $ ( '.human-palette span.palette-holder' ).live ( 'click', function () {
                    var color = $ ( this ).attr ( 'data-color' );
                    $ ( this ).closest ( '.color-palette-table' ).find ( '.css-color-holder' ).val ( color );
                    update_css ();
                    //   alert(  $(this).closest('.color-palette-table').find('.css-color-holder').length);
          } );
          $ ( '.human-clear-css' ).on ( 'click', function () {
                    var css_media_prop = $ ( this ).data ( 'clear-css' );
                    if ( $ ( '#' + css_media_prop ).val () === 'none' ) {
                              $ ( '#' + css_media_prop ).removeAttr ( 'value' );
                    } else {
                              $ ( '#' + css_media_prop ).val ( 'none' );
                    }
                    $ ( '#' + css_media_prop + '-holder' ).empty ();
                    update_css ();
          } );
          $ ( '#css-gen-toggle-hide' ).on ( 'click', function () {

                    $ ( '.human-compose-mode-css-gen' ).hide ();
          } );
          css_section ();
          var callback = 'css_gen_ajax_call_back';
          var action = 'cssGenAjax';
          if ( typeof getUrlParameter ( 'section_id' ) !== 'undefined' ) {
                    var section_id = '.' + getUrlParameter ( 'section_id' );
          } else {
                    var section_id = '';
          }
          $ ( '.search_tags_action' ).on ( 'click', function () {
                    console.log ( $ ( '#search_tags' ).val () );
                    if ( $ ( '#search_tags' ).val () == 1 ) {
                              human_tag_css_helper ();
                              $ ( '#search_tags' ).val ( 0 );
                              $ ( "#vc_inline-frame" ).contents ().find ( 'body' ).removeClass ( 'search_tag' );
                              console.log ( 'close' );
                    } else {

                              $ ( "#vc_inline-frame" ).contents ().find ( 'body' ).addClass ( 'search_tag' );
                              console.log ( 'open' );
                              get_search_tag ();
                              $ ( '#search_tags' ).val ( 1 );
                    }
          } );
          $ ( '#human_tag_css_helper div[select_convert_tag]' ).on ( 'click', function () {
                    console.log ( $ ( this ).attr ( 'select_convert_tag' ) );
                    $ ( '#selected_tag' ).val ( $ ( this ).attr ( 'select_convert_tag' ) );
          } );
          function get_search_tag () {
                    document.getElementById ( "vc_inline-frame" ).contentWindow.run_search_tag ();
          }



          $ ( '#vc_button-update' ).live ( 'click', function ( e ) {


                    if ( $ ( '#css-is-saved' ).val ( ) == 0 ) {
                              e.preventDefault ();
                              e.stopPropagation ();
                              console.log ( $ ( '#css-is-saved' ).val () );
                              var save = 'normal';
                              toggle_human_gif ();
                              save_css_gen_ajax ( save );

                    }
          } );

          $ ( '#rename_selected_folder' ).live ( 'click', function ( e ) {
                    if ( $ ( '#new_folder_name' ).val ().length > 0 ) {
                              e.preventDefault ();
                              var action = 'cssGenAjax';
                              var ajaxurl = humanAjax.ajaxurl;
                              var data = {
                                        action: action,
                                        nonce: humanAjax.nonce,
                                        rename_folder: $ ( '#template_wraps' ).val (),
                                        new_name: $ ( '#new_folder_name' ).val ()
                              };
                              //  console.log(data);
                              $.post ( ajaxurl, data, function ( response ) {
                                        if ( response.data['error'] ) {
                                        } else {
                                                  var ajax_response = response.data['folder_renamed'];
                                                  $ ( '#new_folder_name' ).hide ();
                                                  $ ( '#template_wraps' ).show ();
                                                  alert ( ajax_response );
                                                  var new_folder_name = $ ( '#template_wraps option[value="' + $ ( '#template_wraps' ).val () + '"]' );
                                                  new_folder_name.attr ( 'value', $ ( '#new_folder_name' ).val () );
                                                  new_folder_name.text ( $ ( '#new_folder_name' ).val () );
                                                  $ ( 'div[css-folder="' + $ ( '#template_wraps' ).val () + '"]' ).each ( function () {
                                                            $ ( this ).attr ( 'css-folder', $ ( '#new_folder_name' ).val () );
                                                  } );
                                                  $ ( '#new_folder_name' ).removeAttr ( 'value' );
                                                  select_tag ();
                                        }
                              } );
                    } else {
                              $ ( '#new_folder_name' ).attr ( 'placeholder', $ ( '#template_wraps' ).val () );
                              $ ( '#new_folder_name' ).toggle ();
                              $ ( '#template_wraps' ).toggle ();
                    }
          } );
          $ ( '#template_wraps' ).live ( 'change', function () {
                    var sub_sections = [ ];
                    if ( typeof $ ( '#template_wraps option[value="' + $ ( this ).val () + '"]' ).data ( 'folder-sections' ) == 'undefined' ) {
                              return;
                    }
                    sub_sections = $ ( '#template_wraps option[value="' + $ ( this ).val () + '"]' ).data ( 'folder-sections' ).split ( ',' );
                    var section_select = '<option value="" class="default_sections">Sections:</option>';
                    $.each ( sub_sections, function () {
                              section_select += '<option value="' + this + '">' + this + '</option>';
                    } );
                    section_select += '';
                    $ ( '#custom_sections' ).html ( section_select );
                    $ ( '#custom_sections' ).val ()
                    $ ( '#custom_sections option' ).filter ( function () {
                              return ( $ ( this ).text () == 'Sections:' ); //To select Blue
                    } ).prop ( 'selected', true );
                    select_tag ();
          } );
          $ ( '#delete_selected_section,#delete_selected_folder' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    var del_folder = '';
                    var del_option = '';
                    if ( $ ( this ).attr ( 'id' ) === 'delete_selected_section' ) {
                              del_folder = $ ( '#section_folder' ).val ();
                              del_option = $ ( '#custom_sections' ).val ();
                    }
                    var action = 'cssGenAjax';
                    var ajaxurl = humanAjax.ajaxurl;
                    var data = {
                              action: action,
                              nonce: humanAjax.nonce,
                              del_section: del_option,
                              del_folder: del_folder
                    };
                    $.post ( ajaxurl, data, function ( response ) {
                              if ( typeof response.data['error'] !== 'undefined' ) {
                                        console.log ( 'There was an error updating subwrappers' );
                              } else {
                                        var ajax_response = response.data['del_section'];
                                        alert ( ajax_response );
                                        $ ( '#custom_sections' ).find ( 'option[value="' + del_option + '"]' ).remove ();
                                        $ ( '#delete_selected_section' ).fadeOut ( 700 );
                              }
                    } );
          } );
          $ ( '#human_new_section_action' ).on ( 'click', function ( e ) {
                    e.preventDefault ();
                    e.stopPropagation ();
                    var action = 'cssGenAjax';
                    var ajaxurl = humanAjax.ajaxurl;
                    var data = {
                              action: action,
                              nonce: humanAjax.nonce,
                              new_section: $ ( '#human_new_section' ).val (),
                              section_folder: $ ( '#section_folder' ).val ()
                    };
                    toggle_human_gif ();
                    $.post ( ajaxurl, data, function ( response ) {
                              if ( response.data['error'] ) {
                              } else {
                                        var section_id = response.data['new_section'];
                                        if ( section_id === 'empty' ) {
                                                  alert ( 'Css Section Cannot be Empty!' );
                                        } else if ( section_id === 'exist' ) {
                                                  alert ( 'Css Section Already Exist!' );
                                        } else {
                                                  $ ( '#custom_sections' )
                                                            .append ( $ ( "<option></option>" )
                                                                      .attr ( "value", response.data['new_section'] )
                                                                      .text ( response.data['new_section'] ) );
                                                  $ ( '#custom_sections' ).find ( 'option[value="' + response.data['new_section'] + '"]' ).prop ( "selected", true );
                                                  $ ( '.human-page-inner' ).find ( '.sections_id' ).removeAttr ( 'class' ).attr ( 'class', 'sections_id ' + section_id );
                                                  if ( $ ( '#template_wraps option[value="' + response.data['css_folder'] + '"]' ).length > 0 ) {
                                                            var c_f = $ ( '#template_wraps' ).find ( 'option[value="' + response.data['css_folder'] + '"]' );
                                                            var c_f_s = c_f.attr ( "data-folder-sections" );
                                                            c_f.attr ( "data-folder-sections", c_f_s + ',' + response.data['new_section'] );

                                                  } else {
                                                            if ( response.data['css_folder'] !== 'global' ) {
                                                                      $ ( '#template_wraps' )
                                                                                .append ( $ ( "<option></option>" )
                                                                                          .attr ( "value", response.data['css_folder'] )
                                                                                          .text ( response.data['css_folder'] ) )
                                                                                .attr ( "data-folder-sections", response.data['new_section'] );
                                                            } else {
                                                                      var c_f = $ ( '#template_wraps' ).find ( 'option[value=""]' );
                                                                      var c_f_s = c_f.attr ( "data-folder-sections" );
                                                                      c_f.attr ( "data-folder-sections", c_f_s + ',' + response.data['new_section'] );
                                                            }
                                                  }
                                                  $ ( '#template_wraps' ).find ( 'option[value="' + response.data['css_folder'] + '"]' ).prop ( "selected", true );
                                                  select_tag ();
                                                  $ ( '#delete_selected_section' ).fadeIn ();
                                                  $ ( '#delete_selected_folder' ).fadeIn ();
                                        }

                                        toggle_human_gif ( 'hide' );
                              }
                    } );
          } );


          $ ( '#selected_tag,#human_screen_res' ).on ( 'change', function ( e ) {
                    e.preventDefault ();
                    e.stopPropagation ();
                    //console.log('preselcted');
                    select_tag ( 'preselect' );
          } );
          $ ( '.human_top_section_selects' ).on ( 'change', function ( e ) {

                    select_tag ();
          } );
          $ ( '.controls_inner input,.controls_inner select,#text-align-holder,#human-custom-css' ).bind ( 'change click', function ( e ) {

                    update_css ();
                    $ ( '#css-is-saved' ).val ( '0' );
          } );

          /*
           $('.controls input,.controls select,#text-align-holder').live('change', function () {

           update_css();
           $('#css-is-saved').val('0');

           });
           */
          $ ( '.css-gen-toggle-show' ).live ( 'click', function ( e ) {

                    e.preventDefault ();
                    $ ( '.human-compose-mode-css-gen' ).show ();
                    toggle_human_gif ();
                    if ( $ ( "#css_iframe" ).length === 0 ) {
//   $('body').prepend(css_frame);
                              //       $('.vc_navbar-nav').append('<li style="padding-right: 8px;    border-right: 1px solid #34588F;color:#fff" id="save_css_gen_ajax" ><a href="#">Update CSS</a></li>');
                    }

                    select_tag ();
          } );
          $ ( '#custom_sections,#template_wraps' ).live ( 'change', function ( e ) {
                    var elem = e.target;
                    var selected_value = elem.options[elem.selectedIndex];
                    var fade = '#delete_selected_section';
                    if ( $ ( this ).attr ( 'id' ) === 'template_wraps' ) {
                              fade = '#rename_selected_folder';
                    }
                    if ( $ ( selected_value ).hasClass ( 'default_sections' ) ) {
// this is default section

                              $ ( fade ).fadeOut ( 700 );
                    } else {

                              $ ( fade ).fadeIn ( 700 );
                    }
                    if ( $ ( '#advanced_tools' ).hasClass ( 'expanded' ) ) {
                              select_tag ();
                    } else {

                              css_select_links ();
                    }
          } );
          $ ( '.human-align' ).live ( 'click', function () {
                    if ( $ ( this ).attr ( 'id' ) === 'selected-align' ) {

                              $ ( this ).css ( 'opacity', 1 );
                              $ ( this ).removeAttr ( 'id' );
                              $ ( '#text-align-holder' ).removeAttr ( 'value' );
                              return;
                    }
                    $ ( '#selected-align' ).removeAttr ( 'id' );
                    $ ( '.human-align' ).css ( 'opacity', 1 );
                    $ ( this ).css ( 'opacity', 0.3 );
                    $ ( this ).attr ( 'id', 'selected-align' );
                    $ ( '#text-align-holder' ).val ( $ ( this ).data ( 'align' ) );
                    update_css ();
          } );
          $ ( '#related_tags .related-tag-row' ).live ( 'click', function () {
                    $ ( '.selectedTag' ).removeClass ( 'selectedTag' );
                    var tag = $ ( this ).attr ( 'related_tag' );
                    $ ( '#selected_tag' ).val ( tag );
                    $ ( this ).addClass ( 'selected-tag-row' );
                    select_tag ( 'preselect' );
          } );
          $ ( '#vc_screen-size-control .vc_dropdown-list .vc_screen-width' ).on ( 'click', function () {
                    if ( $ ( this ).attr ( 'data-size' ) !== '100%' ) {
                              var res = $ ( this ).attr ( 'data-size' ).split ( 'px' )[0];

                              //    console.log(res);
                              $ ( '#human_screen_res' ).val ( res );
                    } else {
                              $ ( '#human_screen_res' ).removeAttr ( 'value' );

                              //   console.log($('#human_screen_res').val());
                    }

                    select_tag ( 'preselect' );
          } );
          /*
           *  @param Admin Content to iFrame loader
           *  @param Testing resolution between deifferent screens
           */



} );
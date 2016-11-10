
function meta_templates ( meta, post_id ) {

          var meta_template = '<div class="human-meta-child">';

          meta_template += '<h3>' + meta[ 'title' ] + '</h3>';

          meta_template += '<p>' + meta[ 'desc' ] + '</p>';
          // return 'in<hr>' . $meta[ 'type' ];
          var id = post_id + '_post_id-' + meta[ 'id' ];
          var meta_value = '';
          if ( typeof meta[ 'value' ] !== undefined ) {
                    meta_value = meta[ 'value' ];
          }
          if ( meta[ 'type' ] === 'image' ) {
                    var link_bg = '';
                    if ( typeof meta[ 'value' ] !== undefined && meta[ 'value' ].length > 1 ) {
                              link_bg = "background:url('" + meta[ 'value' ] + "');color:transparent";
                    }
                    meta_template += '<div class="human-controls"><div class="human-media-wrapper">\n\
                                                                        <a class="human-media" style="' + link_bg + '">upload </a>\n\
                                                                        <span class="fa fa-close human-del-media"></span>\n\
                                                                        <input type="hidden" class="human_media_holder human_meta_field" meta_title="' + meta[ 'title' ] + '"  meta_desc="' + meta[ 'desc' ] + '"  meta_id="' + meta[ 'id' ] + '" meta_type="' + meta[ 'type' ] + '" name="' + id + '" id="' + id + '" value="' + meta_value + '">\n\
                                              </div></div>';
          }
          else if ( meta[ 'type' ] === 'text' ) {
                    meta_template += '<div class="human-controls human-text-wrapper">\n\
                                                                       <input type="text" class="human-meta-textfield human_meta_field" name="' + id + '" id="' + id + '" value="' + meta_value + '" meta_title="' + meta[ 'title' ] + '"  meta_desc="' + meta[ 'desc' ] + '"  meta_id="' + meta[ 'id' ] + '" meta_type="' + meta[ 'type' ] + '">\n\
                                             </div>';
          }
          else if ( meta[ 'type' ] === 'wysiwyg' ) {
                    var content = meta[ 'value' ];
                    var editor_id = id;
                    meta_template += '<div class="human-controls human-tiny-wrapper">';
                    meta_template += '<div id="wp-content-editor-tools" class="wp-editor-tools hide-if-no-js" style="position: absolute; top: 0px; width: 847px;"> <div id="wp-content-media-buttons" class="wp-media-buttons"> <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="' + editor_id + '"> <span class="wp-media-buttons-icon"></span> Add Media </button> </div><div class="wp-editor-tabs"> <button type="button" id="' + editor_id + '-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="' + editor_id + '">Visual</button> <button type="button" id="' + editor_id + '-html" class="wp-switch-editor switch-html" data-wp-editor-id="' + editor_id + '">Text</button> </div></div>';
                    meta_template += '<textarea name="' + id + '" id="' + id + '" class="tiny-editor human_meta_field" meta_title="' + meta[ 'title' ] + '"  meta_desc="' + meta[ 'desc' ] + '" meta_id="' + meta[ 'id' ] + '" meta_type="' + meta[ 'type' ] + '" >' + meta_value + '</textarea>';
                    //    meta_template += '<table id="post-status-info" style=""><tbody><tr><td id="wp-word-count" class="hide-if-no-js">Word count: <span class="word-count">2</span></td><td class="autosave-info"><span class="autosave-message">&nbsp;</span><span id="last-edit"></span></td><td id="content-resize-handle" class="hide-if-no-js"><br></td></tr></tbody></table>';
                    meta_template += '</div>';
          }
          else {
                    return;
          }


          meta_template += '</div>';


          return meta_template;
}


function default_fields ( field, post_id ) {
          var d_field = '<div class="human-default-child"><div class="human-controls"><h3>' + field['id'] + '</h3>';
          var id = post_id + 'default_id' + field['id'];
          if ( field['id'] === 'post_status' ) {
                    var draft = '';
                    var publish = '';
                    if ( field['value'] == 'draft' ) {
                              draft = ' selected';
                    } else {
                              publish = ' selected';
                    }
                    d_field += '<select name="' + id + '" id="' + id + '" class="wp-defaults-field" wp_defaults="' + field['id'] + '" old_value="' + field['value'] + '"><option value="draft"' + draft + '>Draft</option><option value="publish"' + publish + '>Publish</option></select>';
          } else if ( field['id'] === 'post_excerpt' ) {
                    d_field += '<textarea name="' + id + '" id="' + id + '"  class="wp-defaults-field" wp_defaults="' + field['id'] + '">' + field['value'] + '</textarea>';
          }
          d_field += '</div></div>';
          return d_field;
}

jQuery ( document ).ready ( function ( $ ) {

          /*
           *    Fetch through content,
           *    Find all human-metas update human-metas & values in DB through ajax
           *    @param Update Post / page / Human templates, widgets, forms
           *    @return null
           */

          function find_human_metas () {
                    var new_content = $ ( '#content' ).val ();

                    var content = new_content.split ( '[human_templates_content_meta' );
                    //    console.log ( content );
                    // var new_origins=[];
                    var meta_boxes = [ ];

                    for ( i = 0; i < content.length; i++ ) {
                              if ( content[i].match ( "^ meta_type" ) ) {

                                        /*
                                         *  build array of meta_boxes & values
                                         */
                                        var origin = content[i].split ( ']' )[0];
                                        var id = origin.split ( ' meta_id="' )[1].split ( '"' )[0];
                                        //console.log ( id );

                                        meta_boxes[i] = {
                                                  id: id,
                                                  title: origin.split ( ' meta_title="' )[1].split ( '"' )[0],
                                                  desc: origin.split ( ' meta_desc="' )[1].split ( '"' )[0],
                                                  type: origin.split ( ' meta_type="' )[1].split ( '"' )[0]
                                        };




                              }
                    }
                    return meta_boxes;

          }



          function update_post_metas ( meta_boxes, wp_metas, wp_defaults, post_id ) {
                    var datas = {
                              nonce: humanAjax.nonce,
                              action: "meta_boxes_ajax",
                              save_data: 1,
                              data: meta_boxes,
                              wp_metas: wp_metas,
                              wp_defaults: wp_defaults,
                              post_id: post_id
                    };
                    //     console.log ( meta_boxes );
                    if ( $ ( '#human-saving-metas' ).length > 0 ) {
                              //  alert ( 'Human "update_post_metas" already active' );
                              return;
                    } else {
                              // alert ( 'saving data now' );
                    }
                    $ ( 'body' ).prepend ( '<span id="human-saving-metas">1</span>' );
                    jQuery.ajax ( {
                              url: ajaxurl,
                              data: datas,
                              type: 'POST',
                              datatype: 'json',
                              success: function ( response ) {
                                        console.log ( response );
                              }
                    } );
          }

          function update_meta_boxes (  ) {
                    var meta_boxes = find_human_metas ();
                    console.log ( meta_boxes );
                    var post_id = $ ( '#post_ID' ).val ();
                    update_post_metas ( meta_boxes, 0, 0, post_id );

          }


          function populate_meta_frame () {
                    var data = {
                              nonce: humanAjax.nonce,
                              action: "meta_boxes_ajax", type: 'populate_frame',
                              post_id: $ ( '#post_ID' ).val ()
                    };
                    var ajaxurl = humanAjax.ajaxurl;
                    // console.log ( ajaxurl );

                    humanTinyMceSettings = tinyMCEPreInit.mceInit.content;
                    jQuery.ajax ( {
                              url: ajaxurl,
                              data: data,
                              type: 'POST',
                              datatype: 'json',
                              success: function ( response ) {
                                        //   console.log ( response );
                                        var meta_menu = '<ul id="human-metas-links" class="human-admin-menu">';

                                        //   var all_metas = '';
                                        $ ( '.human_meta_holder' ).empty ();
                                        var i = 0;
                                        var response = $.makeArray ( response );
                                        //    console.log ( response );
                                        $.each ( response[0]['data'], function ( index ) {

                                                  //     console.log ( $ ( this ) );
                                                  console.log ( this );
                                                  i++;
                                                  var post_type = index;
                                                  meta_menu += '<li post_type_wrapper="' + post_type + '"><a href="#" post_type="' + index + '">' + replaceAll ( index, '_', ' ' ).toUpperCase () + '</a><ul>';
                                                  var pages = this;
                                                  $ ( pages ).each ( function ( p_index ) {

                                                            var page = this;

                                                            var post_id = page['post_id'];
                                                            meta_menu += '<li><span class="post_id_info">' + page['post_id'] + '</span><a href="#" post_id="' + page['post_id'] + '" class="edit-post">' + page['title'] + '</a><a href="#" class="fa fa-plus new-post" post_type="' + post_type + '">&nbsp;</a></li>';
                                                            //    insert_boxes ( post_id, page );
                                                  } );
                                                  meta_menu += '</ul></li>';
                                        } );
                                        meta_menu += '</ul>';
                                        //  console.log ( meta_menu );

                                        $ ( '<div id="human-meta-menu-wrapper"></div>' ).insertBefore ( '.human_meta_holder' );
                                        $ ( '#human-meta-menu-wrapper' ).html ( meta_menu );
                              }
                    } );

          }

          $ ( '#human-metas-links .edit-post' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    var post_id = $ ( this ).attr ( 'post_id' );
                    $ ( '.human-admin-menu .active' ).removeClass ( 'active' );

                    $ ( '.hidden-metas' ).fadeOut ();
                    $ ( '.hidden-metas.active' ).removeClass ( 'active' );
                    if ( $ ( '#human_post_meta_holder-' + post_id ).length > 0 ) {

                              $ ( '#human_post_meta_holder-' + post_id ).fadeIn ();
                              $ ( '#human_post_meta_holder-' + post_id ).addClass ( 'active' );


                    } else {
                              var post_type = $ ( this ).parent ().find ( 'a[post_type]' ).attr ( 'post_type' );
                              var datas = {
                                        nonce: humanAjax.nonce,
                                        action: "human_new_post",
                                        post_id: post_id,
                                        old_post: 1
                              };
                              //    console.log ( datas );
                              jQuery.ajax ( {
                                        url: ajaxurl,
                                        data: datas,
                                        type: 'POST',
                                        datatype: 'json',
                                        success: function ( response ) {

                                                  console.log ( response.data[post_type] );
                                                  insert_boxes ( post_id, response.data[post_type][0] );

                                        }, error: function ( error ) {
                                                  console.log ( error );
                                        }
                              } );
                    }
          } );
          $ ( '.human-close-parent' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    $ ( this ).parents ( '.human-parent' ).fadeOut ();
                    $ ( 'body' ).removeClass ( 'human-over' );
          } );
          $ ( '.human_meta_manager' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    //     alert ( 'clicked' );
                    var min_height = 42 + $ ( '#wpwrap' ).height ();
                    var meta_frame = '<div class="meta_frame_wrapper human-parent wrap">\n\
                                                       <div class="meta_frame_body">\n\
                                                            <div class="human_meta_generator human-branding">\n\
                                                               <h3>Human Content Generator</h3>\n\
                                                                 <a href="#" class="human-meta-save page-title-action">Update</a>\n\
                                                                 <a href="#" class="human-close-parent fa fa-close"></a>\n\
                                                            </div>\n\
                                                            <div class="human_meta_holder"></div>\n\
                    </div>\n\
                              </div>';

                    if ( $ ( '.meta_frame_wrapper' ).length > 0 ) {
                              $ ( '.meta_frame_wrapper' ).fadeIn ();

                              //  populate_meta_frame ();
                    } else {
                              $ ( '#wpwrap' ).prepend ( meta_frame );
                              populate_meta_frame ();
                    }
                    $ ( 'body' ).addClass ( 'human-over' );
          } );

          function human_meta_manager () {
                    if ( $ ( '#wp-admin-bar-root-default' ).find ( '.human_meta_manager' ).length === 0 ) {
                              $ ( '<li><a class="human-branding human_meta_manager" href="#">HUMAN META</a></li>' ).insertAfter ( '#wp-admin-bar-menu-toggle' );
                    }
                    if ( $ ( '.wpb_switch-to-front-composer' ).length > 0 ) {
                              var human_meta = '<span class="vc_spacer"></span>';
                              human_meta += '<a class="human-branding human-btn human_meta_manager" href="#">HUMAN META</a>';
                              $ ( human_meta ).insertAfter ( '.wpb_switch-to-front-composer' );
                    } else {
                              setTimeout ( function () {
                                        human_meta_manager ();
                              }, 1000 );
                    }
          }

          human_meta_manager ();
          $ ( '.human-del-media' ).live ( 'click', function () {

                    $ ( this ).parent ().find ( '.human_media_holder' ).removeAttr ( 'value' );
                    $ ( this ).parent ().find ( '.human-media' ).removeAttr ( 'style' );
          } );



          $ ( '.human-meta-save' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    tinymce.triggerSave ();
                    var post_id = $ ( '.hidden-metas.active' ).attr ( 'id' ).split ( '-' )[1];
                    var meta_boxes = [ ];
                    var wp_metas = [ ];
                    var wp_defaults = [ ];
                    var i = 0;
                    $ ( '.hidden-metas.active' ).find ( '.human_meta_field' ).each ( function () {
                              i++;
                              var value = $ ( this ).val ();
                              console.log ( $ ( this ) );
                              var id = $ ( this ).attr ( 'id' ).split ( 'post_id-' )[1];
                              meta_boxes[i] = {
                                        id: id,
                                        title: $ ( this ).attr ( 'meta_title' ),
                                        desc: $ ( this ).attr ( 'meta_desc' ),
                                        type: $ ( this ).attr ( 'meta_type' ),
                                        value: value
                              };
                    } );
                    var i = 0;
                    $ ( '.hidden-metas.active' ).find ( '.wp-meta-field,input[meta="featured_image"]' ).each ( function () {
                              i++;
                              console.log ( $ ( this ) );
                              wp_metas[i] = {
                                        id: $ ( this ).attr ( 'meta' ),
                                        val: $ ( this ).val ()
                              };
                    } );
                    var i = 0;
                    $ ( '.hidden-metas.active' ).find ( '.wp-defaults-field' ).each ( function () {
                              i++;
                              wp_defaults[i] = {
                                        id: $ ( this ).attr ( 'wp_defaults' ),
                                        val: $ ( this ).val ()
                              };
                              if ( $ ( this ).attr ( 'wp_defaults' ) === 'post_title' ) {
                                        $ ( 'a[post_id="2"]' ).text ( $ ( this ).val () );
                              }
                    } );

                    console.log ( wp_metas );
                    $ ( '#human-saving-metas' ).remove ();
                    update_post_metas ( meta_boxes, wp_metas, wp_defaults, post_id );
          } );

          $ ( '#post' ).on ( 'submit', function (  ) {

                    update_meta_boxes ();

          } );
          $ ( 'a[post_type]' ).live ( 'click', function () {
                    var active = $ ( '.human-admin-menu .active' );
                    active.removeClass ( 'active' );
                    if ( $ ( this ).parent ().find ( '.active' ).length === 0 ) {
                              $ ( this ).parent ().find ( 'ul' ).addClass ( 'active' );
                    }
          } );
          $ ( '.human_meta_holder' ).live ( 'click', function () {
                    var active = $ ( '.human-admin-menu .active' );
                    active.removeClass ( 'active' );
          } );
          function populate_clone ( page ) {
                    var meta_menu = '';


                    var post_type = page['post_type'];
                    var post_id = page['post_id'];
                    meta_menu += '<li><span class="post_id_info">' + page['post_id'] + '</span><a href="#" post_id="' + page['post_id'] + '" id="cloned_post-' + post_id + '" class="edit-post">' + page['title'] + '</a><a href="#" class="fa fa-plus new-post" post_type="' + post_type + '">&nbsp;</a></li>';
                    $ ( 'li[post_type_wrapper="' + post_type + '"]' ).find ( 'ul' ).append ( meta_menu );

                    $ ( '.hidden-metas.active' ).fadeOut ();
                    $ ( '.hidden-metas.active' ).removeClass ( 'active' );
                    $ ( '#cloned_post-' + post_id ).trigger ( 'click' );

          }

          $ ( '.human-admin-menu .new-post' ).live ( 'click', function ( e ) {
                    e.preventDefault ();

                    $ ( '.human-admin-menu .active' ).removeClass ( 'active' );
                    var clone_id = $ ( this ).parent ().find ( 'a[post_id]' ).attr ( 'post_id' );
                    var post_type = $ ( this ).attr ( 'post_type' );
                    var datas = {
                              nonce: humanAjax.nonce,
                              action: "human_new_post",
                              post_id: clone_id
                    };
                    //    console.log ( datas );
                    jQuery.ajax ( {
                              url: ajaxurl,
                              data: datas,
                              type: 'POST',
                              datatype: 'json',
                              success: function ( response ) {

                                        //      console.log ( response.data[post_type] );
                                        populate_clone ( response.data[post_type] );

                              }, error: function ( error ) {
                                        console.log ( error );
                              }
                    } );

          } );
          // fix for default values not being saved
          function vc_meta_fix () {
                    if ( $ ( 'div.vc_active[data-vc-shortcode="human_templates_content_meta"]' ).length > 0 ) {

                              console.log ( $ ( 'input.meta_post_id' ).length );
                              if ( $ ( 'div.vc_active[data-vc-shortcode="human_templates_content_meta"]' ).find ( 'input.meta_post_id' ).length > 0 ) {



                                        console.log ( $ ( 'input.meta_post_id' ).length );
                                        $ ( 'div.vc_active[data-vc-shortcode="human_templates_content_meta"]' ).find ( 'input.meta_post_id' ).val ( $ ( '#post_ID' ).val () );

                              } else {
                                        setTimeout ( function () {
                                                  //alert ( 'does - not exists' );
                                                  vc_meta_fix ();
                                        }, 1000 );
                              }
                    }
          }
          $ ( '.human_templates_content_meta .vc_control-btn-edit' ).live ( 'click', function () {
                    vc_meta_fix ();
          } );

          $ ( '.human-controls-sidebar .snippet-editor__button' ).live ( 'click', function ( e ) {
                    e.preventDefault ();
                    $ ( this ).parents ( '.human-controls-sidebar' ).find ( '.yoast-controls-wrapper' ).toggle ();
                    yoast_widget_progress ();
          } );
          $ ( '.yoast-controls' ).live ( 'keyup', function () {
                    // console.log ( $ ( this ).val ().length );
                    $ ( this ).parent ().find ( 'progress' ).val ( $ ( this ).val ().length );
                    yoast_widget_progress ();
          } );
          $ ( '.vc_ui-panel-window-inner .vc_ui-button-action' ).live ( 'click', function () {
                    vc_meta_fix ();
                    $ ( '.vc_ui-panel-window-inner .vc_ui-button-action' ).each ( function () {
                              //  $ ( this ).trigger ( 'click' );
                    } );
          } );
          $ ( '#human_templates_content_meta' ).live ( 'click', function () {
                    vc_meta_fix ();
          } );
          $ ( document ).click ( function ( e ) {
                    //    console.log ( "document clicked!" );
                    var t = $ ( e.target );
                    if ( t.attr ( "id" ) == "human_templates_content_meta" ) {
                              console.log ( "tree node clicked" );
                              vc_meta_fix ();
                    }
          } );
} );


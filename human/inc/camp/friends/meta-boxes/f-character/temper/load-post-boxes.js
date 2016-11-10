$ = jQuery;
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
                    meta_template += '<div class="human-controls human-media-wrapper" id="media-' + post_id + meta[ 'id' ] + '">\n\
                                                                        <a class="human-media" style="' + link_bg + '">upload </a>\n\
                                                                        <span class="fa fa-close human-del-media"></span>\n\
                                                                        <input type="hidden" class="human_media_holder human_meta_field" meta_title="' + meta[ 'title' ] + '"  meta_desc="' + meta[ 'desc' ] + '"  meta_id="' + meta[ 'id' ] + '" meta_type="' + meta[ 'type' ] + '" name="' + id + '" id="' + id + '" value="' + meta_value + '">\n\
                                              </div>';
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
                    meta_template += '<table id="' + id + '-status-info" style=""><tbody><tr><td id="wp-word-count" class="hide-if-no-js">Word count: <span class="word-count"></span></td><td class="autosave-info"><span class="autosave-message">&nbsp;</span><span id="last-edit"></span></td><td id="content-resize-handle" class="hide-if-no-js"><br></td></tr></tbody></table>';
                    meta_template += '</div>';
          }
          else {

                    console.log ( meta );
                    return;
          }


          meta_template += '</div>';

          return meta_template;
}


function yoast_widget_progress () {

          $ ( '.yoast-controls' ).each ( function () {
                    var char = 121;
                    if ( $ ( this ).attr ( 'meta' ) === 'meta_title' ) {

                              $ ( this ).parents ( '.human-controls-sidebar' ).find ( '#snippet_title' ).html ( $ ( this ).val () );
                              char = 55;
                    } else {
                              $ ( this ).parents ( '.human-controls-sidebar' ).find ( '#snippet_meta' ).html ( $ ( this ).val () );
                    }
                    $ ( this ).parents ( '.yoast-controls-wrapper' ).find ( 'progress' ).val ( $ ( this ).val ().length );
                    if ( $ ( this ).val ().length >= char ) {
                              $ ( this ).parents ( '.yoast-controls-wrapper' ).find ( 'progress' ).addClass ( 'snippet-editor__progress--good' );
                    } else {
                              $ ( this ).parents ( '.yoast-controls-wrapper' ).find ( 'progress' ).removeClass ( 'snippet-editor__progress--good' );
                    }
                    //    console.log ( $ ( this ).val () );
          } );
}


function insert_meta_menu () {

}

function yoast_snippet ( post_id, page ) {
          console.log ( page.post_id );
          return   '<section class="snippet-editor__preview"><h3 class="snippet-editor__heading snippet-editor__heading-icon-eye"><span style="color:blue">Google</span> Snippet preview</h3><p class="screen-reader-text">You can click on each element in the preview to jump to the Snippet Editor.</p><div class="snippet_container snippet-editor__container" id="title_container"><span class="screen-reader-text">SEO title preview:</span><span class="title" id="render_title_container"><span id="snippet_title"></span></span><span class="title" id="snippet_sitename"></span></div><div class="snippet_container snippet-editor__container" id="url_container"><span class="screen-reader-text">Slug preview:</span><cite class="url urlBase" id="snippet_citeBase"></cite><cite class="url yoast-url-snipped-id-' + page.post_id + '" id="snippet_cite"></cite></div><div class="snippet_container snippet-editor__container" id="meta_container"><span class="screen-reader-text">Meta description preview:</span><span class="desc desc-default" id="snippet_meta"></span></div><button class="snippet-editor__button snippet-editor__edit-button" type="button" aria-expanded="true">Edit snippet</button></section>';
}
function yoast_url_snipped_id_change () {
          var post_id = $ ( '.hidden-metas.active' ).attr ( 'id' ).split ( '-' )[1];
          console.log ( 'yoast-url-snipped-id-' + post_id );
          console.log ( ajaxurl.split ( 'wp-admin' )[0] + replaceAll ( $ ( '#post_title-' + post_id ).val () + '/', ' ', '-' ) );
          $ ( '.yoast-url-snipped-id-' + post_id ).html ( ajaxurl.replace ( 'http://', '' ).replace ( 'https://', '' ).split ( 'wp-admin' )[0] + replaceAll ( $ ( '#post_title-' + post_id ).val () + '/', ' ', '-' ).toLowerCase () );
}
$ ( '.human-post-title' ).live ( 'change', function () {
          yoast_url_snipped_id_change ();
} );
function insert_boxes ( post_id, page ) {

          $ ( '.human_meta_holder' ).append ( '<div id="human_post_meta_holder-' + post_id + '" class="hidden-metas active" style="display:block"><input type="text" id="post_title-' + post_id + '" value="' + page['title'] + '" wp_defaults="post_title" class="human-post-title wp-defaults-field"><div class="human-post-metas-wrapper"></div><div class="human-controls-sidebar"><div class="wp-post-metas-wrapper"></div></div></div>' );


          $.each ( page['wp_metas'], function ( index ) {
                    var wp_meta_field = '<input type="text" value="' + this + '" class="wp-meta-field" meta="' + index + '">';
                    var yoast_wrapper = '';
                    var yoast_widget = '';
                    if ( index === 'meta_description' ) {
                              wp_meta_field = '<textarea meta="' + index + '" class="wp-meta-field yoast-controls">' + this + '</textarea><progress value="" class="snippet-editor__progress snippet-editor__progress-meta-description snippet-editor__progress--ok" aria-hidden="true" max="156"><div class="snippet-editor__progress-bar" style=""></div></progress>';
                              yoast_wrapper = 'yoast-controls-wrapper';
                              yoast_widget = yoast_snippet ( post_id, page );
                    } else if ( index === 'meta_title' ) {
                              wp_meta_field = '<input type="text" value="' + this + '" class="wp-meta-field yoast-controls" meta="' + index + '"><progress value="" class="snippet-editor__progress snippet-editor__progress-meta-description snippet-editor__progress--ok" aria-hidden="true" max="70"><div class="snippet-editor__progress-bar" style=""></div></progress>';
                              yoast_wrapper = 'yoast-controls-wrapper';
                    } else if ( index === 'featured_image' ) {
                              var link_bg = '';
                              if ( typeof this !== undefined && this.length > 1 ) {
                                        link_bg = "background:url('" + this + "');color:transparent";
                              }
                              wp_meta_field = '<div class="human-media-wrapper" id="media-' + index + '-' + post_id + '"><a class="human-media" style="' + link_bg + '" meta_id="' + post_id + '">upload </a><span class="fa fa-close human-del-media"></span><input type="hidden" class="wp_meta_field human_media_holder" meta="' + index + '"  value="' + this + '"></div>';
                    }
                    var wp_meta = '<div class="wp-meta human-controls ' + yoast_wrapper + '"  wp-meta="' + index + '"><p>' + index + '</p>' + wp_meta_field + '</div>' + yoast_widget;
                    $ ( '#human_post_meta_holder-' + post_id ).find ( '.wp-post-metas-wrapper' ).append ( wp_meta );
          } );
          //   $ ( yoast_snippet ( post_id, page ) ).insertAfter ( $ ( '#human_post_meta_holder-' + post_id ).find ( '.yoast-controls-wrapper:last-child' ) );
          $.each ( page['defaults'], function ( d_index ) {
                    var field = [ ];
                    field['id'] = d_index;
                    field['value'] = this;
                    var d_field = default_fields ( field, post_id );
                    //  console.log ( field );
                    $ ( '#human_post_meta_holder-' + post_id ).find ( '.human-controls-sidebar' ).prepend ( d_field );

          } );
          var register_tinymces = [ ];
          var i = 0;
          $.each ( page['metas'], function (  ) {
                    $.each ( this, function ( index ) {
                              i++;
                              if ( typeof this['id'] !== undefined ) {
                                        // var metas = this;
                                        var meta = this;

                                        console.log ( meta['type'] );
                                        var meta_id = post_id + '_post_id-' + meta[ 'id' ];
                                        register_tinymces[i] = meta_id;
                                        $ ( '#human_post_meta_holder-' + post_id ).find ( '.human-post-metas-wrapper' ).append ( meta_templates ( meta, post_id ) );
                                        if ( meta['type'] === 'wysiwyg' ) {

                                                  var default_init = tinymce.activeEditor.settings;
                                                  var humanTinyMceSettings = jQuery.extend ( { }, default_init );
                                                  humanTinyMceSettings.selector = meta_id;
                                                  tinymce.init ( humanTinyMceSettings );


                                                  quicktags ( { id: meta_id, buttons: "a,media,html,strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw" } );

                                                  tinymce.execCommand ( 'mceAddEditor', true, meta_id );
                                                  //    tinyMCE.execCommand ( 'mceAddControl', false, meta_id );
                                        }
                                        if ( typeof meta['value'] !== undefined ) {
                                                  $ ( '#' + meta_id ).val ( meta['value'] );
                                        }
                              } else {
                                        //   console.log ( this );
                              }
                    } );
          } );
          console.log ( register_tinymces );
          $ ( document ).on ( 'shown.bs.modal wplink-open', function ( e ) {
                    $ ( document.body ).removeClass ( 'modal-open' );
          } );
          yoast_widget_progress ();
          yoast_url_snipped_id_change ();
}
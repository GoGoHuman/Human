jQuery ( document ).ready ( function ( $ ) {
          var post_type = '';
          if ( $ ( '#post_type' ).length > 0 && $ ( '#post_type' ).val () ) {
                    var post_type = $ ( '#post_type' ).val ();
          }
          //alert(post_type);
          if ( post_type === 'human_templates' || post_type === 'human_widgets' || post_type === 'human_loops' || post_type === 'human_forms' ) {
                    $ ( '#wpseo_meta' ).hide ();
          }
} );

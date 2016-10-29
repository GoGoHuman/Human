( function ( $ ) {
          $ ( function () {
                    console.log ( humanAjax.ajaxurl );
                    var url = humanAjax.ajaxurl + "?action=human_search";
                    $ ( "#human_search_bar" ).autocomplete ( {
                              source: url,
                              delay: 500,
                              minLength: 3,
                              select: function ( event, ui ) {
                                        var permalink = ui.item.permalink; // Get permalink from the datasource

                                        window.location.replace ( permalink );
                              }
                    } );
          } );

} ) ( jQuery );

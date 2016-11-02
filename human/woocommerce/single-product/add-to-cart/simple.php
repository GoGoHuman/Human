<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit (); // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable () ) {
            return;
}
?>

<?php
// Availability
$availability = $product->get_availability ();
$availability_html = empty ( $availability [ 'availability' ] ) ? '' : '<p class="stock ' . esc_attr ( $availability [ 'class' ] ) . '">' . esc_html ( $availability [ 'availability' ] ) . '</p>';

echo apply_filters ( 'woocommerce_stock_html', $availability_html, $availability [ 'availability' ], $product );
?>

<?php if ( $product->is_in_stock () ) : ?>

            <?php //do_action ( 'woocommerce_before_add_to_cart_form' ); ?>

            <form class="cart" method="post" enctype='multipart/form-data'>

                      <?php do_action ( 'woocommerce_before_add_to_cart_button' ); ?>

                      <?php
                      if ( ! $product->is_sold_individually () ) {
                                  woocommerce_quantity_input ( array (
                                              'min_value' => apply_filters ( 'woocommerce_quantity_input_min', 1, $product ),
                                              'max_value' => apply_filters ( 'woocommerce_quantity_input_max', $product->backorders_allowed () ? '' : $product->get_stock_quantity (), $product ),
                                              'input_value' => (isset ( $_POST [ 'quantity' ] ) ? wc_stock_amount ( $_POST [ 'quantity' ] ) : 1)
                                  ) );
                      }
                      ?>
                      <div class="vc_row" style="">
                                <div class="wpb_column vc_column_container vc_col-md-6">

                                          <h1 itemprop="name" class="product_title entry-title single-product-title"><?php the_title (); ?></h1>
                                          <h2 class="calendar-heading"><span class=" fa fa-calendar"></span> Choose your preferred date(s)</h2>

                                          <?php
                                          global $post;
                                          $booking_dates = get_post_meta ( $post->ID, 'booking-dates', true );
                                          $bd = [ ];
                                          $slot_dates = [ ];
                                          if ( $booking_dates ) {
                                                      foreach ( $booking_dates as $b_date ) {
                                                                  // print_r ( $b_date );
                                                                  $bd [] = '"' . $b_date [ 'select-a-date' ] . '"';
                                                                  $slots[ $b_date [ 'select-a-date' ] ] = $b_date [ 'availability-stock' ];
                                                                  $slot_dates[] = $b_date [ 'select-a-date' ];
                                                      }
                                          }


                                          $orders_times = [ ];
                                          $orders_times = human_get_order_ids ( $post->ID );
                                          $dates = [ ];
                                          if ( ! empty ( $orders_times ) ) {


                                                      foreach ( $orders_times as $k => $v ) {
                                                                  if ( ! isset ( $dates[ $v[ 'meta_value' ] ] ) ) {
                                                                              $dates[ $v[ 'meta_value' ] ] = 1;
                                                                  }
                                                                  else {
                                                                              $dates[ $v[ 'meta_value' ] ] = $dates[ $v[ 'meta_value' ] ] + 1;
                                                                  }
                                                      }

                                                      // print_r ( $dates );
                                          }


                                          foreach ( $slots as $k => $v ) {
                                                      if ( isset ( $dates[ $k ] ) ) {
                                                                  if ( $slots[ $k ] - $dates[ $k ] >= 0 ) {
                                                                              $sum_bks[ $k ] = $slots[ $k ] - $dates[ $k ];
                                                                  }
                                                                  else {
                                                                              $sum_bks[ $k ] = 0;
                                                                  }
                                                      }
                                                      else {
                                                                  $sum_bks[ $k ] = $slots[ $k ];
                                                      }
                                          }
                                          //  print_r ( $slots );
                                          ?>




                                          <div class="human-date-picker ll-skin-cangas"
                                               id="date-picker-holder-<?php echo the_ID (); ?>"></div>
                                          <input type="hidden" class="human-date-picker"
                                                 id="date-picker-<?php echo the_ID (); ?>" name="human_date_picker">
                                          <script type="text/javascript">

                                                      jQuery ( document ).ready ( function ( $ ) {
                                                                function after_show_days ( ) {
                                                                          //  $ ( '.available-slots' ).remove ( );
                                                                          if ( $ ( 'td.available' ).length > 0 ) {
                                                                                    $ ( 'td.available' ).each ( function ( ) {
                                                                                              if ( $ ( this ).find ( '.available-slots' ).length < 1 ) {
                                                                                                        $ ( this ).addClass ( 'ttt' );
                                                                                                        var slots = $ ( this ).attr ( 'class' ).split ( 'human-booking-slots-' )[1].split ( ' ' )[0];
                                                                                                        var add_hover = '';

                                                                                                        if ( $ ( this ).hasClass ( 'selected-booking-date' ) ) {
                                                                                                                  add_hover = ' selected';
                                                                                                        }
                                                                                                        $ ( this ).prepend ( '<span class="available-slots' + add_hover + '">' + slots + ' <span class="xs-hide">places</span></span>' );
                                                                                              }
                                                                                    } );

                                                                                    if ( $ ( this ).find ( 'a.ui-state-hover' ).length > 0 ) {
                                                                                              var uis = [ ];
                                                                                              uis = $ ( this ).find ( 'span' );
                                                                                              uis.addClass ( 'hover' );
                                                                                    }

                                                                          }


                                                                }

                                                                function slottimeout ( ) {
                                                                          after_show_days ( );
                                                                          setTimeout ( function ( ) {
                                                                                    slottimeout ( );
                                                                          }, 3000 );
                                                                }
                                                                slottimeout ( );
                                                                function booking_quantity ( ) {

                                                                          var val = parseInt ( $ ( '#travellers' ).val ( ) ) + parseInt ( $ ( '.date_manager' ).length );
            <?php
            $product = new WC_Product ( get_the_ID () );
            $price = $product->price;
            ?>


                                                                          //  $ ( '.days-part-price' ).html ( days * parseInt (<?php echo $price; ?> ) );
                                                                          var people = $ ( '#travellers' ).val ( );
                                                                          var days = parseInt ( $ ( '.date_manager' ).length );
                                                                          $ ( '.count-days' ).html ( days );
                                                                          $ ( '.count-people' ).html ( people );
                                                                          $ ( 'input[name*="quantity"]' ).val ( val );
                                                                          $ ( '.total-price-display,.days-part-price' ).html ( people * days * parseInt (<?php echo $price; ?> ) );
                                                                          after_show_days ( );
                                                                }
                                                                var availableDates = [ <?php echo implode ( ',', $bd ); ?> ];
                                                                function available ( date ) {
                                                                          after_show_days ( );
                                                                          var currentMonth = date.getMonth ( ) + 1;
                                                                          if ( date.getMonth ( ) + 1 < 10 ) {
                                                                                    currentMonth = '0' + currentMonth;
                                                                          }
                                                                          var dmy = date.getDate ( ) + "-" + ( currentMonth ) + "-" + date.getFullYear ( );
                                                                          //console.log ( dmy + ' : ' + ( $.inArray ( dmy, availableDates ) ) );
                                                                          var selectedDates = $ ( 'input[name*="booking_dates"]' ).val ( ).split ( ',' );
                                                                          var slots = [ ];
            <?php
            foreach ( $sum_bks as $k => $v ) {
                        echo 'slots["' . $k . '"] = "' . $v . '";';
            }
            ?>

                                                                          if ( $.inArray ( dmy, availableDates ) !== -1 ) {
                                                                                    // console.log ( dmy );
                                                                                    // console.log ( slots );
                                                                                    var cl = 'human-booking-slots-' + slots[dmy];
                                                                                    if ( slots[dmy] ) {
                                                                                              var title = slots[dmy] + ' Places Left';
                                                                                    } else {
                                                                                              var title = '0 Places Left';
                                                                                    }
                                                                                    if ( $.inArray ( dmy, selectedDates ) !== -1 ) {
                                                                                              var c = cl + ' selected-booking-date available';
                                                                                    } else {
                                                                                              //  console.log ( availableDates );
                                                                                              // return [ true, "", "Available" ];

                                                                                              var c = cl + ' available';
                                                                                    }
                                                                                    if ( slots[dmy] <= 0 ) {
                                                                                              var cl = 'all-booked human-booking-slots-' + slots[dmy];
                                                                                              return [ false, cl, "unAvailable" ];
                                                                                    }
                                                                                    return [ true, c + " slot-" + dmy, title ];
                                                                          } else {
                                                                                    return [ false, "", "unAvailable" ];
                                                                          }

                                                                }

                                                                function select_dates ( selectedDate ) {
                                                                          var current_selected = $ ( 'input[name*="booking_dates"]' ).val ( );

                                                                          if ( !$ ( '.slot-' + selectedDate ).hasClass ( 'selected-booking-date' ) ) {
                                                                                    //   $ ( 'input[name^="booking_dates"]' ).attr ( 'data-dates', $ ( 'input[name*="booking_dates"]' ).val ( ) );
                                                                                    var coma = '';
                                                                                    if ( current_selected.length > 0 ) {
                                                                                              var coma = ',';
                                                                                    }

                                                                                    current_selected = current_selected + coma + selectedDate;
                                                                                    $ ( 'input[name*="booking_dates"]' ).val ( current_selected );

                                                                                    //$ ( '#dates_manager' ).empty ( );
                                                                                    $.each ( current_selected.split ( ',' ), function ( ) {
                                                                                              //console.log ( $ ( '.availableslot-' + this ).attr ( 'class' ).split ( 'human-booking-slots-' )[1].split ( ' ' )[0] );
                                                                                              var slots = '';
                                                                                              if ( $ ( '.slot-' + this ).attr ( 'class' ) ) {
                                                                                                        if ( $ ( '.slot-' + this ).attr ( 'class' ).length > 0 && $ ( '.slot-' + this ).attr ( 'class' ).indexOf ( 'slot-' ) ) {
                                                                                                                  slots = $ ( '.slot-' + this ).attr ( 'class' ).split ( 'human-booking-slots-' )[1].split ( ' ' )[0];
                                                                                                                  $ ( '#date_manager_' + selectedDate ).remove ();
                                                                                                                  $ ( '#dates_manager' ).prepend ( '<div class="date_manager" id="date_manager_' + selectedDate + '" data-slots="' + slots + '"><date class="button">' + this + '</date></div>' );
                                                                                                        }
                                                                                              }


                                                                                              //console.log ( this );
                                                                                    } );
                                                                          } else {

                                                                                    $ ( '.slot-' + selectedDate ).find ( 'a' ).removeClass ( 'ui-state-active' );
                                                                                    current_selected = current_selected.replace ( ',' + selectedDate, '' );
                                                                                    current_selected = current_selected.replace ( selectedDate, '' );
                                                                                    $ ( 'input[name*="booking_dates"]' ).val ( current_selected );
                                                                                    $ ( '#date_manager_' + selectedDate ).remove ( );
                                                                          }
                                                                          //console.log ( current_selected );
                                                                          booking_quantity ( );

                                                                          after_show_days ( );
                                                                }
                                                                $ ( "#date-picker-holder-<?php echo the_ID (); ?>" ).datepicker ( {
                                                                          inline: true,
                                                                          showOtherMonths: true,
                                                                          dateFormat: "dd-mm-yy",
                                                                          beforeShowDay: available,
                                                                          beforeShow: after_show_days,
                                                                          onSelect: function ( selectedDate ) {
                                                                                    $ ( '#date-picker-<?php echo the_ID (); ?>' ).val ( selectedDate );
                                                                                    console.log ( selectedDate );
                                                                                    select_dates ( selectedDate );
                                                                                    after_show_days ( );
                                                                          },
                                                                          onChangeMonthYear: after_show_days,
                                                                          firstDay: 1
                                                                } );
                                                                after_show_days ( );
                                                                $ ( '.travellers .fa' ).on ( 'click', function ( ) {
                                                                          function minMaxId ( selector ) {
                                                                                    var min = null,
                                                                                              max = null;
                                                                                    $ ( '' + selector + '' ).each ( function () {
                                                                                              console.log ( $ ( this ) );
                                                                                              var id = parseInt ( $ ( this ).attr ( 'data-slots' ) );
                                                                                              if ( ( min === null ) || ( id < min ) ) {
                                                                                                        min = id;
                                                                                              }
                                                                                              if ( ( max === null ) || ( id > max ) ) {
                                                                                                        max = id;
                                                                                              }
                                                                                    } );
                                                                                    return min;
                                                                          }

                                                                          var maxs = minMaxId ( '.date_manager' );
                                                                          //alert ( maxs );
                                                                          if ( $ ( this ).hasClass ( 'remove-traveller' ) ) {

                                                                                    if ( $ ( '#travellers' ).val ( ).length > 0 ) {
                                                                                              var people = parseInt ( $ ( '#travellers' ).val ( ) ) - 1;
                                                                                              if ( people < 0 ) {
                                                                                                        return;
                                                                                              }
                                                                                    }
                                                                          } else {

                                                                                    var people = parseInt ( $ ( '#travellers' ).val ( ) ) + 1;
                                                                                    if ( maxs < people ) {
                                                                                              alert ( 'Date(s) you have choosen is full. Please select another date(s)' );
                                                                                              return;

                                                                                    }
                                                                          }
                                                                          $ ( '#travellers' ).val ( people );
                                                                          $ ( 'input[name*="travellers_count"]' ).val ( people );
                                                                          booking_quantity ( );
                                                                } );

                                                                $ ( '#travellers' ).val ( 1 );
                                                                $ ( 'input[name*="travellers_count"]' ).val ( 1 );
                                                                $ ( '.ttt' ).live ( {
                                                                          mouseenter: function ( ) {
                                                                                    //var uis = [ ];
                                                                                    // uis = $ ( this ).find ( 'span' );
                                                                                    $ ( this ).addClass ( 'hover' );

                                                                          },
                                                                          mouseleave: function () {
                                                                                    $ ( this ).removeClass ( 'hover' );
                                                                          }
                                                                } );
                                                      } );
                                          </script>
                                          <p>
                                                    <span class="span_availability green fa fa-square">&nbsp;</span> - Available
                                                    <span  class="span_availability red fa fa-square">&nbsp;</span> - Fully Booked
                                          </p>
                                          <h3><i class="fa fa-users"></i> Select the number of travellers</h3>
                                          <div class="travellers clearfix">
                                                    <div class="travellers-border"> <input type="text" id="travellers" value="1"> <span>Traveler(s)</span></div>  <i class="remove-traveller fa fa-minus-square-o red"></i><i class="add-traveller fa fa-plus-square-o green"></i>
                                          </div>

                                          <input type="hidden" name="add-to-cart"
                                                 value="<?php echo esc_attr ( $product->id ); ?>" />
                                          <button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html ( $product->single_add_to_cart_text () ); ?></button>
                                </div>

                                <div class="wpb_column vc_column_container vc_col-md-6">
                                          <div class="vc_row grey_bg mini-section">
                                                    <div class="wpb_column vc_column_container vc_col-md-7">
                                                              Do you need assistance?<br>
                                                              Please call us.<br>
                                                              Mon/Fri: 09:00 - 17:00<br>
                                                              Sa/Sun: 12:00 - 17:00<br>
                                                    </div>
                                                    <div class="wpb_column vc_column_container vc_col-md-5">
                                                              <i class="fa fa-phone"></i> 020-7605077
                                                    </div>

                                          </div>


                                          <div class="vc_row grey_bg mini-section">

                                                    <div class="wpb_column vc_column_container vc_col-md-3">
                                                            <?php echo get_the_post_thumbnail (); ?>
                                                    </div>
                                                    <div class="wpb_column vc_column_container vc_col-md-9">
                                                              <h2>Tour Details</h2>
                                                              <div itemprop="description">
                                                                      <?php echo apply_filters ( 'woocommerce_short_description', $post->post_excerpt ) ?>
                                                              </div>
                                                    </div>

                                                    <div class="wpb_column vc_column_container vc_col-md-12">
                                                              <hr>
                                                              <h2><?php echo get_the_title (); ?></h2>

                                                    </div>
                                                    <hr>
                                                    <div class="wpb_column vc_column_container vc_col-md-6">

                                                              <h3 class="">Dates</h3>

                                                              <div id="dates_manager"></div>
                                                    </div>
                                                    <div class="wpb_column vc_column_container vc_col-md-6">
                                                              <table class="totale-fields">
                                                                        <tr>
                                                                                  <td>Travellers</td>
                                                                                  <td class="count-people">0</td>
                                                                                  <td></td>
                                                                        </tr>
                                                                        <tr>
                                                                                  <td>Days</td>
                                                                                  <td class="count-days">0</td>
                                                                                  <td></td>
                                                                        </tr>
                                                              </table>
                                                              <h3 class="text-center">Total <?php echo get_woocommerce_currency_symbol (); ?><price class="text-right total-price-display"><?php //echo $price;                                                                                                                                                                                                                                                                 ?>0</price></h3>

                                                    </div>

                                          </div>
                                </div>
                      </div>
            </form>
            <?php do_action ( 'woocommerce_after_add_to_cart_button' ); ?>



<?php endif; ?>

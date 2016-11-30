<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly
?>



<div class="wrap human_wrapper" id="human_setting_wrapper">


          <form method="post" >
                  <?php
                  if ( isset ( $_POST[ 'human-my-money' ] ) ) {
                              //   echo $_POST[ 'human-my-money' ] . '<hr>';
                              if ( is_numeric ( $_POST[ 'human-my-money' ] ) ) {
                                          update_option ( 'human-my-money', $_POST[ 'human-my-money' ] );
                              }
                  }

                  $money = '';

                  $checked = '';
                  echo $money;
                  $on = '';
                  $off = 'checked';
                  $money = get_option ( 'human-my-money' );
                  if ( isset ( $money ) && ! empty ( $money ) ) {
                              if ( $money == 2 ) {
                                          $on = 'checked';
                                          $off = '';
                              }
                  }
                  ?>
                    <h4>Enable user my wallet functionality</h4>
                    <input type="text" name="human-my-money" value="<?php echo get_option ( 'human-my-money' ); ?>" > <hr>
                    <p>Please specify you My Money Woocommrce product page ID</p>
                    <hr>
                    <button class="page-title-action">Update</button>
          </form>
</div>
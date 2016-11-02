<?php
if ( isset ( $_POST[ 'check' ] ) ) {
            if ( isset ( $_POST[ 'new_font' ] ) && ! empty ( $_POST[ 'new_font' ] ) ) {
                        if ( isset ( $_POST[ 'new_font_selector' ] ) && ! empty ( $_POST[ 'new_font_selector' ] ) ) {
                                    update_option ( 'human-google-fonts', array (
                                                $_POST[ 'new_font' ],
                                                $_POST[ 'new_font_selector' ] ) );
                        }
                        else {
                                    update_option ( 'human-google-fonts', array () );
                        }
            }
            else {
                        update_option ( 'human-google-fonts', array () );
            }
}
$current_url = $current_selector = '';
if ( get_option ( 'human-google-fonts' ) ) {
            $arr = get_option ( 'human-google-fonts' );
            $current_url = $arr[ 0 ];
            $current_selector = $arr[ 1 ];
}
?>


<div class="wrap human_wrapper" id="human_setting_wrapper">



          <h3>Assign Google Fonts</h3>
          <form method="post">
                  <?php
                  if ( isset ( $error ) ) {
                              echo '<span class="error">' . $error . '</span><br>';
                  }
                  ?>
                    <input type="hidden" name="check" value="1">
                    Font URL       <input type="text" name="new_font" value="<?php echo $current_url; ?>" class="form-300" placeholder="e.g. https://fonts.googleapis.com/css?family=Baloo+Bhaina|PT+Sans">
                    Font Selector <input type="text" name="new_font_selector" class="form-300"  value="<?php echo $current_selector; ?>" placeholder="e.g. Baloo Bhaina, cursive;PT Sans, sans-serif;">
                    <button type="submit">Update</button>
          </form>

</div>
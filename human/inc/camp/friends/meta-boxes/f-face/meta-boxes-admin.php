<?php
if ( isset ( $_POST[ 'human_attachments' ] ) ) {
            update_option ( 'human_attachments', $_POST[ 'human_attachments' ] );
}
?>
<h3>Content Writer Post Types</h3>
<p>Coma separated "post_types" to add additional meta fields on edit Post screen</p>
<form method="post">
          <p>Attachment Meta Field</p>
          <input type="text" name="human_attachments" value="<?php
          if ( get_option ( 'human_attachments' ) ) {
                      echo get_option ( 'human_attachments' );
          }
          ?>">
          <button type="submit">Save</button>
</form>
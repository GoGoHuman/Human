<?php
$wp_content_path = explode ( 'uploads', wp_upload_dir ()[ 'basedir' ] )[ 0 ];

function return_conten_path () {
            $array = array (
                        'basedir' => 'yisdfiysuploadsdiyfosdiyfs',
                        2 => 2,
                        3,
                        4,
                        5
            );
            return $array;
}

print_r ( return_conten_path () );

function copy_directory ( $source1, $dest1, $permissions = 0755 ) {
            // Check for symlinks
            $dest = str_replace ( array (
                        '\\',
                        '//' ), '/', $dest1 );

            $source = str_replace ( array (
                        '\\',
                        '//' ), '/', $source1 );
            if ( is_link ( $source ) ) {
                        return symlink ( readlink ( $source ), $dest );
            }

            // Simple copy for a file
            if ( is_file ( $source ) ) {

                        //  echo $source;
                        // echo $source . '<hr>';
                        if ( is_file ( $dest ) ) {
                                    if ( strpos ( $dest, 'plugins/' ) > 0 ) {
                                                return "<br>" . $dest . "File exist";
                                    }

                                    return "<br>" . $dest . "File exist and overriten";
                        }
                        if ( strpos ( $dest, '_translationstatus' ) > 0 ) {
                                    return '<br>Uncopatible file';
                        }
                        $file = fopen ( $dest, 'w+' );

                        fwrite ( $file, file_get_contents ( $source ) );

                        fclose ( $file );
                        return;
            }

            // Make destination directory
            if ( is_dir ( $source ) && ! is_dir ( $dest ) ) {

                        //echo $source . 'is dir<hr>';
                        mkdir ( $dest, $permissions );
            }

            // Loop through the folder
            $dir = dir ( $source );
            if ( isset ( $dir ) ) {

                        //echo $source;
                        while ( false !== $entry = $dir->read () ) {
                                    // Skip pointers
                                    if ( $entry == '.' || $entry == '..' ) {
                                                continue;
                                    }

                                    // Deep copy directories
                                    copy_directory ( "$source/$entry", "$dest/$entry", $permissions );
                        }
            }

            // Clean up
            $dir->close ();
            return true;
}

function my_print_error () {

            global $wpdb;

            if ( $wpdb->last_error !== '' ) :

                        print($wpdb->last_result );
                        $query = htmlspecialchars ( $wpdb->last_query, ENT_QUOTES );

                        print "<div id='error'>
        <p class='wpdberror'><strong>WordPress database error:</strong><br />
        <code>$query</code></p>
        </div>";

            endif;
}

function import_baby ( $baby_path ) {
            echo 'Importing Human Baby theme from : ' . $baby_path;
            if ( isset ( $_POST[ 'mysql_path' ] ) ) {
                        update_option ( 'human-mysql-path', esc_html ( $_POST[ 'mysql_path' ] ) );
            }
            $db_import = [ ];
            $file_export = [ ];
            $baby_db_path = $baby_path . '/db-backup/';
            $wp_content_path = explode ( 'uploads', wp_upload_dir ()[ 'basedir' ] )[ 0 ];
            require HUMAN_FRIENDS_PATH . '/backup/f-heart/restore-db.php';
            //     $wpdb->query('CREATE TEMPORARY TABLE human_temp');

            echo '<hr>Importing DB tables:<br>';
            foreach ( new DirectoryIterator ( $baby_db_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $available_files = $file->getFilename ();
                        $db_str = $file->getPathname ();
                        echo restore_db ( $db_str );
            }

            echo '<hr>Copying contents:<br>';
            echo copy_directory ( $baby_path . '/wp-content', $wp_content_path );
            return;
}
?>



<div class="wrap human_wrapper" id="human_setting_wrapper">

          <?php
          //   print ob_get_level ();
          if ( isset ( $_POST[ 'template_name' ] ) ) {
                      ob_start ();
                      echo "Backup started...<br>";
                      ob_flush ();


                      if ( isset ( $_POST[ 'mysqldump_path' ] ) ) {
                                  update_option ( 'human-mysqldump-path', esc_html ( $_POST[ 'mysqldump_path' ] ) );
                      }

                      $template_name = str_replace ( array (
                                  ' ',
                                  ',' ), '-', $_POST[ 'template_name' ] );



                      $common_wp_content_items = array (
                                  'plugins',
                                  'uploads',
                                  'themes'
                      );
                      $files = [ ];



                      $directory = HUMAN_CHILD_PATH . '/babies/';
                      $css_directory = $directory . $template_name . '/wp-content/';
                      $db_directory = $directory . $template_name . '/db-backup';
                      //     print_r ( $css_directory . '<hr>' );
                      if ( ! file_exists ( $directory . $template_name . '/' ) ) {
                                  mkdir ( $directory . $template_name . '/', 0755 );
                      }
                      if ( ! file_exists ( $css_directory ) ) {
                                  mkdir ( $css_directory, 0755 );
                      }
                      if ( ! file_exists ( $db_directory ) ) {
                                  mkdir ( $db_directory, 0755 );
                      }
                      //echo HUMAN_FRIENDS_PATH . '\backup\f-heart\backup-db.php';
                      require HUMAN_FRIENDS_PATH . '/backup/f-heart/backup-db.php';
                      if ( function_exists ( 'backup_tables' ) ) {

                                  //fclose($css_directory);
                                  $html_css = $directory . $template_name . '/wp-content/human.css.html';
                                  $human_css = $directory . $template_name . '/wp-content/human.css';
                                  $custom_css = $directory . $template_name . '/wp-content/human-custom.css';
                                  $custom_fonts = $directory . $template_name . '/wp-content/custom-fonts.css';
                                  $child_css = $directory . $template_name . '/wp-content/child-style.css';
                                  $file = fopen ( $html_css, 'w' );

                                  fwrite ( $file, ABSPATH . 'wp-content/wp-content/human.css.html' );

                                  fclose ( $file );

                                  $file = fopen ( $human_css, 'w' );

                                  fwrite ( $file, ABSPATH . 'wp-content/wp-content/human.css' );

                                  fclose ( $file );

                                  $file = fopen ( $custom_css, 'w' );

                                  fwrite ( $file, ABSPATH . 'wp-content/wp-content/human-custom.css' );

                                  fclose ( $file );

                                  $file = fopen ( $custom_fonts, 'w' );

                                  fwrite ( $file, ABSPATH . 'wp-content/wp-content/human-fonts.css' );

                                  fclose ( $file );
                                  $file = fopen ( $child_css, 'w' );

                                  fwrite ( $file, file_get_contents ( HUMAN_CHILD_URL . '/style.css' ) );

                                  fclose ( $file );

                                  ob_start ();
                                  echo backup_tables ( $db_directory );
                                  ob_flush ();

                                  global $table_prefix;
                                  // print_r ( $db_directory . '/' . $table_prefix . 'users.sql' . '<hr>' );
                                  unlink ( $db_directory . '/' . $table_prefix . 'users.sql' );
                                  //  print_r ( $db_directory . '/' . $table_prefix . 'usermeta.sql' . '<hr>' );
                                  unlink ( $db_directory . '/' . $table_prefix . 'usermeta.sql' );

                                  foreach ( $common_wp_content_items as $item ) {

                                              $rootpath = $wp_content_path . $item . '/';
                                              ob_start ();
                                              copy_directory ( $rootpath, $css_directory . $item );

                                              //    echo $item . " directory created.<br>";
                                              ob_flush ();
                                  }
                                  $template_created = $template_name;
                      }
                      else {
                                  echo "backup_tables() function is not detected";
                      }
          }

          if ( isset ( $template_created ) ) {
                      echo '<h4 style="color:blue">' . $template_created . ' Baby Created</h4>';
                      ob_end_flush ();
          }
          ?>
          <form method="post">
                    <h3>Save as Template</h3>
                    <?php
                    if ( strtoupper ( substr ( PHP_OS, 0, 3 ) ) === 'WIN' ) {
                                $mysqlpath = '';
                                if ( get_option ( 'human-mysqldump-path' ) ) {
                                            $mysqlpath = get_option ( 'human-mysqldump-path' );
                                }

                                echo '<b>Please enter full path to mysql</b><br><b style="color:green">e.g. "C:/xampp/mysql/bin/mysqldump"</b><br>';
                                echo '<input type="text" name="mysqldump_path" value="' . $mysqlpath . '" class="form-300">';
                    }
                    else {
                                //  echo 'This is a server not using Windows!';
                    }
                    ?>
                    <input name="template_name" placeholder="url friendly e.g. pink_template"  class="form-300">
                    <button type="submit">Save</button>
          </form>
          <hr>

          <form method="post">
                  <?php
                  if ( isset ( $_POST[ 'import_template_path' ] ) ) {
                              $css_import = import_baby ( $_POST[ 'import_template_path' ] );
                  }

                  if ( isset ( $css_import ) ) {
                              echo '<h4 style="color:blue">Template Import was successful</h4>';
                  }
                  ?>
                    <h3>Import from Template</h3>
                    <?php
                    if ( strtoupper ( substr ( PHP_OS, 0, 3 ) ) === 'WIN' ) {
                                $mysqlpath = '';
                                if ( get_option ( 'human-mysql-path' ) ) {
                                            $mysqlpath = get_option ( 'human-mysql-path' );
                                }

                                echo '<b>Please enter full path to mysql</b><br><b style="color:green">e.g. "C:/xampp/mysql/bin/mysql"</b><br>';
                                echo '<input type="text" name="mysql_path" value="' . $mysqlpath . '" class="form-300">';
                    }
                    else {
                                //  echo 'This is a server not using Windows!';
                    }
                    ?>
                    <select name="import_template_path">
                            <?php
                            $directory = HUMAN_CHILD_PATH . '/babies';
                            foreach ( new DirectoryIterator ( $directory ) as $file ) {
                                        if ( $file->isDot () )
                                                    continue;
                                        $available_template = $file->getFilename ();
                                        if ( $available_template !== '.htaccess' && $available_template !== 'index.html' )
                                                    echo '<option value="' . str_replace ( '\\', '/', $directory ) . '/' . $available_template . '">' . $available_template . '</option>';
                            }
                            ?>
                    </select>
                    <input type="checkbox" name="import_css_template" value="1"> - Select here if you really want to override this.
                    <p>
                              <b>
                                        <i>Warning this will delete any custom css, human_templates, human_widgets, human_loops, human_forms &amp; will reset theme to chosen template settings</i>
                              </b>
                    </p>

                    <button type="submit">Import Template</button>

          </form>
          <hr><br>
</div>


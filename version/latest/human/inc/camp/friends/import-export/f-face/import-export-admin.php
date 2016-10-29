<?php
/* █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████HUMAN THEME FRAMEWORK██████████████████████████████████████████
 * ████████████████████████████████████████████████████████████<https://human.camp>████████████████████████████████████████████████████
 * ██████████████████████████████████████████████████        support@human.camp        █████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████                   █████████████████████████████████████████████████████████████████
 * ███████████████████████████████████████████████      ██████████   ████████████    ████████       ████████████████████████████████████████████████████
 * █████████████████████████████████████████████      ██████████   ███      ███    ████████       ██████████████████████████████████████████████████████
 * ███████████████████████████████████████████      ██████████████     ███    ███████████       ████████████████████████████████████████████████████████
 * █████████████████████████████████████████      █████████████████████████████████████       ██████████████████████████████████████████████████████████
 * ████████████████████████████████████████                                                 ████████████████████████████████████████████████████████████
 * █████████████████████████████████████████               HUMAN               ████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████                                       █████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████       █████████████████████       █████████████████████████████████████████████████████████████████████████
 * ████████████████████████████████████████      ██████████████████████      ███████████████████████████████████████████████████████████████████████████
 * ███████████████████████████████████████     ██████████████████████      █████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 * █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 *
 * @param Human Import - Export
 * @author SergeDirect <itpal24@gmail.com>
 *
 *
 */



$wp_content_path = explode ( 'uploads', wp_upload_dir ()[ 'basedir' ] )[ 0 ];

/**
 *  Human Theme Package Installer
 */
function copy_directory ( $source1, $dest1, $permissions = 0755 ) {
            // Check for symlinks
            $dest = str_replace ( array (
                        '\\',
                        '//' ), '/', $dest1 );

            $source = str_replace ( array (
                        '\\',
                        '//' ), '/', $source1 );
            if ( is_link ( $source ) ) {
                        //  echo '<br> is simbolic ' . $source;
                        return symlink ( readlink ( $source ), $dest );
            }

            // Simple copy for a file
            if ( is_file ( $source ) ) {

                        // echo $source;
                        // echo $source . '<hr>';
                        if ( is_file ( $dest ) ) {
                                    if ( strpos ( $dest, 'plugins/' ) > 0 ) {
                                                //   echo "<br>" . $dest . "File exist";
                                    }

                                    //     echo "<br>" . $dest . "File exist and overriten";
                        }
                        if ( strpos ( $dest, '_translationstatus' ) > 0 ) {
                                    echo '<br>Uncopatible file';
                        }
                        $file = fopen ( $dest, 'w' );

                        fwrite ( $file, file_get_contents ( $source ) );

                        fclose ( $file );
                        return;
            }

            // Make destination directory
            if ( is_dir ( $source ) && ! is_dir ( $dest ) ) {

                        //    echo '<br>' . $source . ' is dir<hr>';
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

function import_baby ( $baby_path ) {

            $baby_db_path = str_replace ( '.zip', '', $baby_path ) . '/wp-content/backup-db';
            echo 'Importing Human Baby theme from : ' . $baby_path;
            if ( isset ( $_POST[ 'mysql_path' ] ) ) {
                        update_option ( 'human-mysql-path', esc_html ( $_POST[ 'mysql_path' ] ) );
            }


            $zip = new ZipArchive;

            if ( $zip->open ( $baby_path ) === TRUE ) {
                        $zip->extractTo ( HUMAN_CHILD_PATH . '/babies' );
                        $zip->close ();

                        echo '<br>Extracting files...<br>';
            }
            else {
                        return 'Couldn\'t extract Baby';
            }

            $wp_content_source = str_replace ( '.zip', '', $baby_path ) . '/wp-content';
            $wp_content_dest = ABSPATH . 'wp-content';
            //  echo '<br>Out ' . $wp_content_source . '<br>In ' . $wp_content_dest;
            copy_directory ( $wp_content_source, $wp_content_dest );

            require HUMAN_FRIENDS_PATH . '/backup/f-heart/restore-db.php';

            //   echo '<hr>Importing DB tables from ' . $baby_db_path . ':<br>';
            foreach ( new DirectoryIterator ( $baby_db_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $db_str = $file->getPathname ();
                        echo restore_db ( $db_str );
            }
            unlink ( str_replace ( '.zip', '', $baby_path ) );
            unlink ( ABSPATH . 'wp-content/backup-db' );
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


                      $directory = HUMAN_CHILD_PATH . '/babies/';

                      require_once(HUMAN_BASE_PATH . 'friends/backup/f-heart/lib/human-zip.php');
                      CreateWPFullBackupZip ( $template_name, $zipmode = 1 );

                      $template_created = $template_name;
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
                  $iError = 'Please select one of the options below';

                  if ( isset ( $_POST[ 'import_template_path' ] ) ) {

                              if ( isset ( $_POST[ 'baby_push' ] ) ) {
                                          $baby_push = $_POST[ 'baby_push' ];
                                          if ( $baby_push === 1 ) {
                                                      $css_import = import_baby ( $_POST[ 'import_template_path' ] );
                                          }
                                          elseif ( $baby_push === 2 ) {
                                                      if ( isset ( $_POST[ 'human_push_description' ] ) && ! empty ( $_POST[ 'human_push_description' ] ) ) {
                                                                  $temp_url = HUMAN_CHILD_URL . '/babies/' . end ( explode ( '/', $_POST[ 'import_template_path' ] ) );
                                                                  unlink ( HUMAN_CHILD_PATH . '/babies/.htaccess' );
                                                                  $human_partner_api = 'https://human.camp/api/partners.php?desc=' . urlencode ( $_POST[ 'human_push_description' ] . '@@' . home_url () ) . '&temp_url=' . urlencode ( $temp_url );
                                                                  //    print_r ( $human_partner_api );
                                                                  $response = wp_remote_get ( $human_partner_api );
                                                                  if ( is_array ( $response ) ) {
                                                                              print_r ( $response[ 'body' ] ); // $human_partner_api;
                                                                              $file = fopen ();
                                                                  }
                                                                  fwrite ( $file, '<FilesMatch ".*">
    Order Allow,Deny
    Deny from All
</FilesMatch>' );
                                                                  fclose ( $file );
                                                      }
                                                      else {
                                                                  $import_error = 'You must provide a valid description for the Theme Package';
                                                      }
                                          }
                              }
                              else {
                                          $import_error = $iError;
                              }
                  }
                  if ( isset ( $css_import ) ) {
                              echo '<h4 style="color:blue">Template Import was successful</h4>';
                  }
                  ?>
                    <h3>Install Baby from Template or Push Baby to Human Marketplace</h3>
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
                              <option>Baby Select</option>
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
                    <?php
                    if ( isset ( $import_error ) ) {
                                echo '<h4 class="error">' . $import_error . '</h4>';
                    }
                    ?>
                    <br>       <br>
                    <input type="radio" name="baby_push" value="1" class="toggle-push"> - Select here if you want to override your current setup.
                    <p>
                              <b>
                                        <i>Warning this will delete any custom css, human_templates, human_widgets, human_loops, human_forms &amp; will reset theme to chosen template settings</i>
                              </b>
                    </p>

                    <input type="radio" name="baby_push" id="push_to_human" value="2"> - Select here if you want to submit this version to Human Market Place
                    <script>
                                jQuery ( document ).ready ( function ( $ ) {
                                          $ ( '#push_to_human' ).on ( 'click', function () {
                                                    if ( $ ( this ).is ( ':checked' ) ) {
                                                              $ ( '#human_push_description' ).fadeIn ();
                                                    } else {

                                                              $ ( '#human_push_description' ).fadeOut ();
                                                    }
                                          } );
                                          $ ( '.toggle-push' ).on ( 'click', function () {
                                                    $ ( '#human_push_description' ).fadeOut ();
                                          } );
                                } );
                    </script>
                    <p>&nbsp;</p>
                    <hr>
                    <div  id="human_push_description" style="display:none;width:100%;">
                              Please provide a description of your Theme Package, for the market place at www.human.camp<br>
                              <textarea name="human_push_description" style="width:100%;max-width:600px;min-height:300px"></textarea>


                    </div>

                    <button type="submit">Go Baby Go</button>

          </form>
          <hr><br>
          <h3>Download available packages from Human</h3>
          <?php
          if ( isset ( $_GET[ 'human_push' ] ) ) {

                      function humanBabyImport ( $url, $path, $dest = null ) {
                                  print_r ( $url );
                                  print('<hr>' );
                                  print($path );
                                  $name = str_replace ( '.zip', '', end ( explode ( '/', $url ) ) );
                                  if ( is_file ( $path ) ) {
                                              unlink ( $path );
                                  }
                                  $newfname = $path . '/' . $name . '.zip';
                                  $file = fopen ( $url, 'rb' );
                                  if ( $file ) {

                                              $newf = fopen ( $newfname, 'wb' );
                                              //   print_r($newf);
                                              if ( $newf ) {

                                                          while ( ! feof ( $file ) ) {
                                                                      fwrite ( $newf, fread ( $file, 1024 * 8 ), 1024 * 8 );
                                                          }
                                              }
                                  }
                                  if ( $file ) {
                                              fclose ( $file );
                                  }
                                  if ( $newf ) {
                                              fclose ( $newf );
                                  }
                                  return '<hr>Theme Package Has been Imported successefully<hr>';
                      }

                      $url = urldecode ( $_GET[ 'human_push' ] );
                      echo humanBabyImport ( $url, HUMAN_CHILD_PATH . '/babies' );
                      ?>
                      <script>location.assign ( '?page=human-import-export-settings' );</script><?php
}
          ?>
          <div class="human-packages-wrapper">
                  <?php
                  $packages = wp_remote_get ( 'https://human.camp/api/show_packages.php' );
                  if ( is_array ( $packages ) ) {
                              $packages = json_decode ( $packages[ 'body' ], true );
                              //  print_r ( $packages );
                              foreach ( $packages as $key => $val ) {
                                          $desc = $val[ 'desc' ];

                                          $ver = explode ( '@@', $desc );
                                          $url = explode ( '@@', $desc )[ 1 ];
                                          $desc = explode ( '@@', $desc )[ 0 ];
                                          $verify = '<hr>';
                                          // echo explode ( '@@', $desc )[ 2 ];
                                          if ( isset ( $ver[ 2 ] ) ) {
                                                      $verify = '<div class="human-verified"><h3 style="color:green"><span class="dashicons dashicons-yes"></span> &nbsp;Quality Verified By Human</span></h3></div>';
                                          }
                                          echo '<div class="human-packages" style="float:left;text-align:center;float: left;text-align: center;max-width: 300px;padding: 0 10px;"><h4>' . $val[ 'name' ] . '</h4><img src="' . $url . '/wp-content/themes/human-child/screenshot.png" alt="" style="width:300px">' . $verify . '<p>' . $desc . '</p><a href="?page=human-import-export-settings&human_push=https://human.camp/api/verified/' . $val[ 'name' ] . '/' . $val[ 'name' ] . '.zip" class="button button-primary button-large">Import Package</a>
                                                   <a href="#" class="to-preview" data-url="' . $url . '">Preview</a>  </div>';
                              }
                  }
                  ?>
                    <div id="preview_frame_wrapper" style="position:fixed;display:none;top:0;left:0;width:100%;height:100vh;z-index:99999999999999999999;background:rgba(0,0,0,.6)">
                              <a href="#" class="toggle" style="position: absolute;top: 0; right: 15px;background: #fff; width: 40px;height: 25px;text-align: center;">[x]</a>

                    </div>
                    <script>
                                jQuery ( document ).ready ( function ( $ ) {
                                          $ ( 'a.to-preview' ).on ( 'click', function ( e ) {
                                                    e.preventDefault ();
                                                    //  alert ( 'prevew started' );
                                                    $ ( '#preview_frame_wrapper' ).append ( '<iframe src="' + $ ( this ).attr ( 'data-url' ) + '" style="width:90%;height:90vh;position:relative;top:5vh;left:5%"></iframe>' );
                                                    $ ( '#preview_frame_wrapper' ).fadeIn ();

                                          } );
                                          $ ( '#preview_frame_wrapper a.toggle' ).on ( 'click', function ( e ) {
                                                    e.preventDefault ();
                                                    $ ( '#preview_frame_wrapper' ).fadeOut ();
                                          } );
                                } );
                    </script>
                    <span style="clear:both"></span>
          </div>
</div>


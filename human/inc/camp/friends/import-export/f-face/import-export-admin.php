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

/**
 *  Human Theme Package Installer
 */
$wp_content_path = explode ( 'uploads', wp_upload_dir ()[ 'basedir' ] )[ 0 ];

function humanDeleteDirectory ( $dir ) {
            if ( ! file_exists ( $dir ) ) {
                        return true;
            }

            if ( ! is_dir ( $dir ) ) {
                        return unlink ( $dir );
            }

            foreach ( scandir ( $dir ) as $item ) {
                        if ( $item == '.' || $item == '..' ) {
                                    continue;
                        }

                        if ( ! humanDeleteDirectory ( $dir . DIRECTORY_SEPARATOR . $item ) ) {
                                    return false;
                        }
            }

            return rmdir ( $dir );
}

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
                        $contents = file_get_contents ( $source );

                        $file = fopen ( $dest, 'w' );

                        fwrite ( $file, $contents );

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
            echo 'Installing Human Baby theme from : ' . $baby_path . '<hr>';
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
            copy_directory ( $wp_content_source, $wp_content_dest );

            require HUMAN_FRIENDS_PATH . '/backup/f-heart/restore-db.php';
            global $table_prefix;
            foreach ( new DirectoryIterator ( $baby_db_path ) as $file ) {
                        if ( $file->isDot () )
                                    continue;
                        $db_str = $file->getPathname ();

                        $db_replace_to = array (
                                    $table_prefix,
                                    home_url () );
                        $db_replace = array (
                                    'human_prefix',
                                    'human_old_url' );

                        $sql_contents = file_get_contents ( $db_str );
                        $db_file = fopen ( $db_str, 'w' );
                        fwrite ( $db_file, str_replace ( $db_replace, $db_replace_to, $sql_contents ) );
                        fclose ( $db_file );
                        echo import_db_file ( $db_str );
            }
            humanDeleteDirectory ( str_replace ( '.zip', '', $baby_path ) );
            humanDeleteDirectory ( ABSPATH . 'wp-content/backup-db' );
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

                  if ( isset ( $_POST[ 'import_template_path' ] ) && ! empty ( $_POST[ 'import_template_path' ] ) ) {

                              if ( isset ( $_POST[ 'baby_push' ] ) && ! empty ( $_POST[ 'baby_push' ] ) ) {
                                          $baby_push = intval ( $_POST[ 'baby_push' ] );
                                          $baby_n = explode ( '/', $_POST[ 'import_template_path' ] );
                                          $baby_name = end ( $baby_n );
                                          if ( $baby_push === 1 ) {
                                                      $css_import = import_baby ( $_POST[ 'import_template_path' ] );
                                          }
                                          elseif ( $baby_push === 2 ) {
                                                      if ( isset ( $_POST[ 'human_push_description' ] ) && ! empty ( $_POST[ 'human_push_description' ] ) ) {
                                                                  global $table_prefix;
                                                                  $baby_path = HUMAN_CHILD_PATH . '/babies/' . $baby_name;

                                                                  humanDeleteDirectory ( HUMAN_CHILD_PATH . '/t' );
                                                                  mkdir ( HUMAN_CHILD_PATH . '/t' );
                                                                  $temp_baby_path = HUMAN_CHILD_PATH . '/t/' . $baby_name;
                                                                  copy_directory ( $baby_path, $temp_baby_path );
                                                                  $temp_url = HUMAN_CHILD_URL . '/t/' . $baby_name;
                                                                  $human_partner_api = 'https://human.camp/api/partners.php?desc=' . urlencode ( $_POST[ 'human_push_description' ] ) . '&demo_url=' . home_url () . '&temp_url=' . urlencode ( $temp_url ) . '&pre=' . $table_prefix;

                                                                  $response = wp_remote_get ( $human_partner_api );
                                                                  if ( is_array ( $response ) ) {
                                                                              print_r ( $response[ 'body' ] );
                                                                  }
                                                                  else {
                                                                              echo 'Fatal Error: Opps, there was a problem to upload ' . $baby_name . ' package to Human Marketplace in ' . __FILE__ . ' on line ' . __LINE__;
                                                                              exit;
                                                                  }

                                                                  humanDeleteDirectory ( HUMAN_CHILD_PATH . '/t' );
                                                      }
                                                      else {
                                                                  $import_error = 'You must provide a valid description for the Theme Package';
                                                      }
                                          }
                                          else {
                                                      $import_error = 'Please select at list one option below';
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
                                  $name = explode ( '/', $url );
                                  $name = end ( $name );
                                  $name = str_replace ( '.zip', '', $name );
                                  if ( is_file ( $path ) ) {
                                              unlink ( $path );
                                  }
                                  $newfname = $path . '/' . $name . '.zip';
                                  $file = fopen ( $url, 'rb' );
                                  $demo_url = urldecode ( $_GET[ 'demo_url' ] );
                                  if ( $file ) {

                                              $newf = fopen ( $newfname, 'wb' );

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
                                  return '<hr><h2 style="color:green">Theme Package has been Imported successefully</h2><hr>';
                      }

                      $url = urldecode ( $_GET[ 'human_push' ] );
                      echo humanBabyImport ( $url, HUMAN_CHILD_PATH . '/babies' );
                      ?>
                      <script>location.assign ( '?page=human-import-export-settings' );</script><?php
          }
          ?>
          <div class="human-packages-wrapper">
                  <?php
                  //  $packages = [ ];
                  $package = wp_remote_get ( 'https://human.camp/api/show_packages.php' );
                  if ( isset ( $package[ 'body' ] ) && ! empty ( $package[ 'body' ] ) ) {


                              $packages = json_decode ( $package[ 'body' ], true );

                              foreach ( $packages as $key => $val ) {
                                          $desc = $val[ 'desc' ];

                                          $ver = explode ( '@@', $desc );
                                          $urls = explode ( '@@', $desc )[ 1 ];
                                          $url = explode ( '##', $urls )[ 0 ];
                                          $pre = explode ( '##', $urls )[ 1 ];
                                          $desc = explode ( '@@', $desc )[ 0 ];
                                          $verify = '<hr>';
                                          // echo explode ( '@@', $desc )[ 2 ];
                                          if ( isset ( $ver[ 2 ] ) ) {
                                                      $verify = '<div class="human-verified"><h3 style="color:green"><span class="dashicons dashicons-yes"></span> &nbsp;Quality Verified By Human</span></h3></div>';
                                          }
                                          echo '<div class="human-packages" style="float:left;text-align:center;float: left;text-align: center;max-width: 300px;padding: 0 10px;"><h4>' . $val[ 'name' ] . '</h4><img src="' . $url . '/wp-content/themes/human-child/screenshot.png" alt="" style="width:300px">' . $verify . '<p>' . $desc . '</p><a href="?page=human-import-export-settings&human_push=https://human.camp/api/verified/' . $val[ 'name' ] . '/' . $val[ 'name' ] . '.zip&demo_url=' . $url . '&pre=' . $pre . '" class="button button-primary button-large">Import Package</a>
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


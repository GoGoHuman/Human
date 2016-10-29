<?php
/**
 * @package: HUMAN
 * @subpackage: CSS Generator ADMIN VIEW
 * @author: Sergei  Pavlov <itpal24@gmail.com>
 * @param: CSS Generator ADMIN PAGE
 */
if ( ! defined ( 'ABSPATH' ) ) {
            exit;
} // Exit if accessed directly

function css_builder ( $css_font_list ) {


            $pseudos = '<option value="">None</option>
                <option value="a">Link</option>
                <option value=":first-child">First Child</option>
                <option value=":last-child">Last Child</option>
                <option value=":nth-child(2)">2d Child</option>
                <option value=":nth-child(3)">3d Child</option>
                <option value=":nth-child(4)">4th Child</option>
                <option value=":nth-child(5)">5th Child</option>
                <option value=":nth-child(6)">6th Child</option>
                <option value=":nth-child(7)">7th Child</option>
                <option value=":nth-child(8)">8th Child</option>
                <option value=":nth-child(9)">9th Child</option>
                <option value=":hover">Hovered</option>
                <option value=":visited">Visited</option>
                <option value=":focus">Focused</option>
                <option value=":before">Before</option>
                <option value=":after">After</option>';

            if ( isset ( $_POST[ 'font-measure' ] ) ) {

                        $font_size_type = sanitize_text_field ( $_POST[ 'font-measure' ] );
                        $style_gens = esc_html ( $_POST[ 'style-gens' ] );
                        update_option ( 'FONT-MEASURE-TYPE', $font_size_type );
                        update_option ( 'STYLE-GENS', $style_gens );
            }
            $pixels_selected = '';
            $points_selected = '';
            $em_selected = '';
            if ( get_option ( 'FONT-MEASURE-TYPE' ) === 'pixels' ) {

                        $pixels_selected = 'selected';
            }
            elseif ( get_option ( 'FONT-MEASURE-TYPE' ) === 'points' ) {

                        $points_selected = 'selected';
            }
            else {

                        $em_selected = 'selected';
            }

            function unitgen ( $name, $min, $max, $step, $type, $unit = null ) {
                        if ( ! isset ( $unit ) ) {
                                    $unit = 'px';
                        }
                        return '<td><input type="' . $type . '" name="' . $name . '"  min="' . $min . '" step="' . $step . '" > <input type="text" id="' . $name . '-unit" class="human-units" value="' . $unit . '"  data-value="' . $unit . '"> <br>' . $name . '</td>';
            }

            $css_sections = [ ];
            $css_folders_list = [ ];
            $css_folders = [ ];
            $global = [ ];
            $global_tags = [ ];
            if ( get_option ( 'human_css_sections' ) && is_array ( get_option ( 'human_css_sections' ) ) ) {

                        foreach ( get_option ( 'human_css_sections' ) as $key => $val ) {


                                    $css_sections = '';
                                    $css_sections = [ ];
                                    foreach ( $val as $v ) {
                                                $css_sections[] = $v;
                                    }
                                    if ( $key === 'global' ) {

                                                $global[] = 'data-folder-sections="' . implode ( ',', $css_sections ) . '"';
                                                foreach ( $css_sections as $gl_tag ) {
                                                            $global_tags[] = '<option value="' . $gl_tag . '">' . $gl_tag . '</option>';
                                                }
                                    }
                                    else {
                                                $css_folders[] = '<option value="' . $key . '" data-folder-sections="' . implode ( ',', $css_sections ) . '">' . $key . '</option>';
                                                $css_folders_list[] = $key;
                                    }
                        }

                        $css_folders_list = implode ( ',', $css_folders_list );
                        $css_sections = implode ( $css_sections );
                        $css_folders = implode ( $css_folders );
            }
            ?>

            <div id="css-gen_wrapper" class="wrap human_wrapper">
                      <input type="hidden" id="css-is-saved" value="1">
                      <textarea name="style-gens" id="style-gens" class="human-hidden">
                              <?php
                              if ( is_file ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css.html" ) ) {

                                          $file_css_gen = file_get_contents ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css.html" );
                              }
                              else {
                                          $file_css_gen = file_get_contents ( HUMAN_FRIENDS_PATH . '/css-gen/f-face/templates/css-gen.html' );
                              }
                              echo $file_css_gen;
                              ?>
                      </textarea>
                      <textarea name="style-gens-minified" id="style-gens-minified" class="human-hidden"> <?php
                              $file_css_gen_min = '';
                              if ( is_file ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css" ) ) {

                                          $file_css_gen_min = file_get_contents ( explode ( '/wp-content', HUMAN_BASE_PATH )[ 0 ] . "/wp-content/human.css" );
                              }
                              echo $file_css_gen_min;
                              ?>
                      </textarea>
                      <script>
                                  jQuery ( document ).ready ( function () {

                                  } );</script>
                      <div class="row ui-draggable-handle human-top-controls radius-top-right">
                                <div>
                                          <table>

                                                    <tr class="top-bar-row">
                                                              <td>
                                                                        <span class="css-bar-logo top-bar-logo" style="cursor:move;"><img src="<?php echo site_url () . '/wp-content/themes/human/assets/images/human-logo-white.png' ?>" alt=""></span>
                                                              </td>

                                                              <td class="css-top-bar-column">
                                                                        <input type="text" id="human_screen_res" class="page-title-action" disabled placeholder="Screen: Desktop">


                                                                        <span class="toggle-css page-title-action" data-toggle="main-css-div-holder"><></span>
                                                                        <span class="toggle-css page-title-action" data-toggle="custom-css-div-holder">custom <></span>
                                                                        <span class="fa fa-search  page-title-action search_tags_action"><input type="hidden" id="search_tags"></span>
                                                                        <span class="dashicons dashicons-no" id="css-gen-toggle-hide" ></span>
                                                              </td>
                                                    </tr>

                                          </table>
                                          <table>
                                                    <tr>
                                                              <td style="" class="human-top-controls-inner">
                                                                        <div style="display:none" id="section_folder_tags">Global<?php echo $css_folders_list; ?></div>
                                                                        <div class="ui-widget" id="section_folder_holder">
                                                                                  <label for="section_folder">Folders: </label>
                                                                                  <input id="section_folder" type="text" class="page-title-action" placeholder="CSS Folder">
                                                                        </div>


                                                                        <input type="text" id="human_new_section" placeholder="new section class e.g. section_offers"  class="page-title-action">
                                                              </td>
                                                              <td> <a id="human_new_section_action" class="page-title-action" style="margin-right: 11px;">
                                                                                  Go</a>
                                                              </td>

                                                    </tr>
                                          </table>
                                          <table id="human_top_selects">
                                                    <tr>
                                                              <td>

                                                                        <div class="custom-select vc-only vc-100">
                                                                                  <select id="human_global_wrappers"  class="human_top_section_selects" style="">
                                                                                            <option value=".human-page">Global Pages</option>
                                                                                            <option value="body .human-page">Global Wrapper</option>


                                                                                            <option value=".human-id-<?php
                                                                                            if ( isset ( $_GET[ 'post_id' ] ) ) {
                                                                                                        echo $_GET[ 'post_id' ];
                                                                                            }
                                                                                            elseif ( get_the_ID () ) {
                                                                                                        echo get_the_ID ();
                                                                                            }
                                                                                            ?> .human-page">Current post/page</option>
                                                                                            <option value=".single-post .human-page">Single Post</option>
                                                                                            <option value=".archive .human-page">Archive</option>
                                                                                            <option value=".archive.woocommerce .human-page">Woo Archive</option>
                                                                                            <option value=".single.woocommerce .human-page">Woo Product</option>
                                                                                            <option value=".logged-in  .human-page">User Logged In</option>
                                                                                  </select>
                                                                        </div>
                                                              </td>
                                                              <td>
                                                                        <div class="custom-select vc-100" >
                                                                                  <select id="template_wraps"  class="human_top_section_selects" style="">
                                                                                            <option value=""  class="default_sections" <?php
                                                                                            if ( isset ( $global[ 0 ] ) ) {
                                                                                                        echo $global[ 0 ];
                                                                                            }
                                                                                            ?>>CSS Folders</option>

                                                                                            <?php
                                                                                            echo $css_folders;
                                                                                            ?>
                                                                                  </select>

                                                                                  <input type="text" style="display:none" id="new_folder_name" value="" class="page-title-action">
                                                                                  <a href="#" id="rename_selected_folder" style="display:none">Rename this folder</a>
                                                                        </div>
                                                              </td>
                                                              <td>

                                                                        <div class="custom-select vc-only vc-100" id="custom_sections_wrapper">
                                                                                  <select id="custom_sections"  class="human_top_section_selects" style="">
                                                                                            <option value="" class="default_sections">Sections:</option>
                                                                                            <?php
                                                                                            echo implode ( $global_tags );
                                                                                            ?>
                                                                                  </select>
                                                                                  <br>
                                                                                  <a href="#" id="delete_selected_section" style="display:none" >[x] Delete this section</a>
                                                                        </div>
                                                              </td>
                                                    </tr>
                                          </table>
                                          <div class="advanced_tools" style="display:none ">
                                                    <table>
                                                              <tr>
                                                                        <td>
                                                                                  <div class="custom-select vc-only vc-100">
                                                                                            <select id="human_sections" class="human_top_section_selects">


                                                                                                      <option value="" class="default_sections">Global Section</option>

                                                                                                      <option value=".section" class="default_sections">Section</option>
                                                                                                      <option value=".section>.vc_column_container>.vc_column-inner" class="default_sections">Section Inner Content</option>
                                                                                                      <option value=".vc_row:nth-of-type(1)" class="default_sections">Every 1st row</option>
                                                                                                      <option value=".vc_column_container:nth-of-type(1)" class="default_sections">Every 1st column</option>
                                                                                                      <option value=".nav-menu" class="default_sections">Navigation Menu Row</option>
                                                                                                      <option value=".nav-menu ul.menu" class="default_sections">Nav Wrapper</option>
                                                                                                      <option value=".nav-menu ul.menu>li"   class="default_sections">Nav item wrapper</option>

                                                                                                      <option value=".nav-menu ul.menu li.current_page_item"   class="default_sections">Nav item wrapper Current Page Item </option>

                                                                                                      <option value=".site-header .menu-item-has-children>a:first-of-type:after" class="default_sections">Menu Link Dropdown icon</option>
                                                                                                      <option value=".menu-item-has-children" class="default_sections">Menu Item Has Children Wrapper</option>

                                                                                                      <option value=".menu-item-has-children .menu-item-has-children>a:first-of-type:after"   class="default_sections">Menu Link Right Arrow Icon</option>
                                                                                                      <option value=".menu-item-has-children .menu-item-has-children"   class="default_sections">Menu Item Has Children Wrapper - level 2</option>
                                                                                                      <option value=".mega-menu" class="default_sections">Mega Menu</option>
                                                                                                      <option value=".mega-menu .inline" class="default_sections">Mega Menu Columns</option>

                                                                                                      <option value=".logo" class="default_sections">Logo</option>
                                                                                                      <option value=".slogan" class="default_sections">Slogan</option>
                                                                                                      <option value=".post-title" class="default_sections">Post Title</option>

                                                                                                      <option value=".post-date" class="default_sections">Post Date</option>
                                                                                                      <option value=".post-content" class="default_sections">Post Content</option>
                                                                                                      <option value=".post-featured-img" class="default_sections">Post Featured Image</option>
                                                                                                      <option value=".mobile-bar" class="default_sections">Mobile Bar</option>
                                                                                                      <option value=".human-form-msg" class="default_sections">Human Form Msg Box</option>
                                                                                                      <option value=".iwContent" class="default_sections">Human Map Caption</option>
                                                                                                      <option value=".cookie_policy" class="default_sections">Cookie Policy</option>
                                                                                                      <option value=".social-share" class="default_sections">Social Share Wrapper</option>
                                                                                                      <option value=".h-social-login " class="default_sections">Social Login Wrapper</option>
                                                                                                      <option value=".human-form-msg-success" class="default_sections">Human Form Success Msg</option>
                                                                                                      <option value=".human-form-msg-fail" class="default_sections">Human Form Success Fail Msg</option>
                                                                                                      <option value=".human-comments" class="default_sections">Comment Wrapper</option>
                                                                                                      <option value=".comment_thumbs" class="default_sections">Comment Rating</option>
                                                                                                      <option value=".comment_content" class="default_sections">Comment Content</option>
                                                                                                      <option value=".comment_flag" class="default_sections">Comment Flag</option>
                                                                                                      <option value=".comment_date" class="default_sections">Comment Date</option>
                                                                                                      <option value=".comment-bar" class="default_sections">Comment  Bar</option>
                                                                                                      <option value=".comment-wrapper" class="default_sections">Comment Wrapper</option>
                                                                                                      <option value=".avatar" class="default_sections">Comment Avatar</option>
                                                                                                      <option value=".display_name" class="default_sections">Comment Display Name</option>
                                                                                                      <option value=".comment_reply_link a" class="default_sections">Comment Reply Link</option>
                                                                                                      <option value=".human-form-wrapper.reply-expanded"  class="default_sections">Comment Reply Wrapper</option>
                                                                                                      <option value=".input-text"  class="default_sections">.input-text</option>
                                                                                                      <option value=".form-elem"  class="default_sections">Human global Form Field</option>
                                                                                                      <option value=".button">.button</option>
                                                                                                      <option value=".woocommerce"  class="default_sections">.woocommerce</option><option value=".onsale">.onsale</option>
                                                                                                      <option value=".wc-tabs"  class="default_sections">.wc-tabs</option>
                                                                                                      <option value=".wc-tabs li"  class="default_sections">.wc-tabs Li</option>
                                                                                                      <option value=".product"  class="default_sections">.product</option>
                                                                                                      <option value=".woocommerce-result-count"  class="default_sections">.woocommerce-result-count</option>
                                                                                                      <option value=".woocommerce-ordering"  class="default_sections">.woocommerce-ordering</option>
                                                                                                      <option value=".products"  class="default_sections">.products</option>
                                                                                                      <option value=".products li"  class="default_sections">.products Li</option>
                                                                                                      <option value="#main-comment-form"  class="default_sections">#main-comment-form</option>
                                                                                                      <option value=".comment-wrapper"  class="default_sections">.comment-wrapper</option>

                                                                                                      <option value=".comment-reply"  class="default_sections">Comment Reply Wrapper</option>
                                                                                                      <option value=".comment_reply_link"  class="default_sections">.comment Reply Link Wrapper</option>
                                                                                                      <option value=".woocommerce-message"  class="default_sections">.woocommerce-message</option>
                                                                                                      <option value=".woocommerce-main-image"  class="default_sections">.woocommerce-main-image</option>
                                                                                                      <option value=".woocommerce .button"  class="default_sections">.woocommerce .button</option>
                                                                                                      <option value=".woocommerce li"  class="default_sections">.woocommerce Li</option>

                                                                                                      <option value="figure"  class="default_sections">Figure</option>
                                                                                                      <option value=".human-recent-thumb"  class="default_sections">.human-recent-thumb</option>
                                                                                                      <option value=".load-more"  class="default_sections">.load-more</option>



                                                                                            </select>
                                                                                  </div>
                                                                        </td>
                                                                        <td>

                                                                                  <div class="display-table-column">
                                                                                            <div class="custom-select vc-only vc-100" >
                                                                                                      <select id="human_sections_pseudos"  class="human_top_section_selects" style="">
                                                                                                              <?php echo $pseudos; ?>
                                                                                                      </select>
                                                                                            </div>
                                                                                  </div>
                                                                        </td>
                                                              </tr>
                                                    </table>
                                                    <table>
                                                              <tr>
                                                                        <td>
                                                                                  <div class="custom-select vc-only vc-100">
                                                                                            <select id="human_elements" class="human_top_section_selects">
                                                                                                      <option value="">Select Element(s)</option>

                                                                                                      <option value="h1" >Heading 1</option>
                                                                                                      <option value="h2" >Heading 2</option>
                                                                                                      <option value="h3" >Heading 3</option>
                                                                                                      <option value="h4" >Heading 4</option>
                                                                                                      <option value="h5" >Heading 5</option>
                                                                                                      <option value="h6" >Heading 6</option>

                                                                                                      <option value="p">Paragraph</option>
                                                                                                      <option value="img">Image</option>
                                                                                                      <option value="a">Link</option>
                                                                                                      <option value="a:hover">Link Mouse Over</option>
                                                                                                      <option value="a:visited">Visited Links</option>
                                                                                                      <option value=".button-link">Buttons</option>
                                                                                                      <option value=".button-link:hover">Button Links/buttons Hover</option>
                                                                                                      <option value="ul">Unordered List Wrapper</option>
                                                                                                      <option value="ul li">Unordered List text</option>
                                                                                                      <option value="ul li a">Unordered List links</option>
                                                                                                      <option value="ul li a:hover">Unordered List Hovered Links</option>
                                                                                                      <option value="ol">Ordered List Wrapper</option>
                                                                                                      <option value="ol li">Ordered List text</option>
                                                                                                      <option value="ol li a">Ordered List links</option>
                                                                                                      <option value="ol li a:hover">Ordered List Hovered Links</option>
                                                                                                      <option value="blockquote">Blockquote</option>
                                                                                                      <option value='input,input [type="text"],input[type="email"],input [type="phone"],input[type="url"]'>Text Field</option>
                                                                                                      <option value='input[type="submit"]'>Form Submit</option>
                                                                                                      <option value=".placeholder">Form Field Placeholder</option>
                                                                                                      <option value="label">Form Labels</option>
                                                                                                      <option value=".custom-select">Form Select Element</option>
                                                                                                      <option value="textarea">Textarea</option>
                                                                                                      <option value="table">Table</option>
                                                                                                      <option value="tr">Table row</option>
                                                                                                      <option value="td">Table Cell</option>
                                                                                                      <option value=".humancaption">Slider Caption</option>
                                                                                                      <option value=".humancaption a.button-links">Slider Caption Button Link</option>

                                                                                                      <option value="time">Time/Date</option>
                                                                                                      <option value=".google-map">Google map</option>
                                                                                                      <option value=".fa">Font Awesome Icons</option>

                                                                                            </select>
                                                                                  </div>
                                                                        </td>
                                                                        <td>

                                                                                  <div class="display-table-column">
                                                                                            <div class="custom-select vc-only vc-100" >
                                                                                                      <select id="human_elements_pseudos"  class="human_top_section_selects" style="">
                                                                                                              <?php echo $pseudos; ?>
                                                                                                      </select>
                                                                                            </div>
                                                                                  </div>
                                                                        </td>
                                                              </tr>
                                                    </table>
                                          </div>
                                          <div id="related_tags" style="display:none" class="expanded"></div>
                                          <table>

                                                    <tr>
                                                              <td style="width: 100px;">
                                                                        Selected: <a href="#" id="change_tags" class="fa fa-pencil-square-o" title="Click to modify CSS selector">&nbsp;</a>
                                                              </td>
                                                              <td>
                                                                        <input type="text" id="selected_tag" name="selected_tag" style="width:100%;" value=".human-page" class="">
                                                                        <div id="new_tags_row" style="display:none">
                                                                                  <input type="text" id="new_tags"  style="width:90%;" value="" class=""><a class="fa fa-check " id="new_tags_action" href="#">&nbsp;</a>
                                                                        </div>
                                                              </td>
                                                    </tr>
                                          </table>
                                          <table>
                                                    <tr>
                                                              <td>
                                                                        <div id="control-tabs">
                                                                                  <a href="#" class="page-title-action human-active" data-human-control-section="text">text</a>
                                                                                  <a href="#" class="page-title-action" data-human-control-section="bg">BG</a>
                                                                                  <a href="#" class="page-title-action" data-human-control-section="spacing">spacing</a>
                                                                                  <a href="#" class="page-title-action" data-human-control-section="sizepos">size &amp; pos</a>
                                                                                  <a href="#" class="page-title-action" data-human-control-section="borders">Borders</a>
                                                                                  <span class="css-bar-logo" style="float:right;cursor:move;"><img src="<?php echo site_url () . '/wp-content/themes/human/assets/images/human-print-logo.png' ?>" alt=""></span>
                                                                        </div>
                                                              </td>
                                                    </tr>
                                          </table>
                                </div>

                                <div class="human-css-gen-body "  style="">
                                          <div class="human-controls-inner ">


                                                    <div class="controls_inner">
                                                              <div id="human-controls-section-text" class="human-controls-sections" style="display:block">


                                                                        <table>
                                                                                  <tr>
                                                                                            <td>
                                                                                                      <div class="custom-select">
                                                                                                                <select name="font-family" id="font-family">

                                                                                                                          <option value="">Font Family</option>
                                                                                                                          <option value="inherit">Inherit</option>
                                                                                                                          <option value="sans-serif">sans-serif</option>
                                                                                                                          <?php
                                                                                                                          echo $css_font_list;
                                                                                                                          ?>

                                                                                                                          <option value="FontAwesome">Font Awesome</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>
                                                                                            <td>

                                                                                                      <div class="custom-select">
                                                                                                                <select name="text-transform">
                                                                                                                          <option value="">text-transform</option>
                                                                                                                          <option value="uppercase">uppercase</option>
                                                                                                                          <option value="lowercase">lowercase</option>
                                                                                                                          <option value="capitalize">capitalize</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>
                                                                                  </tr>


                                                                        </table>
                                                                        <table>
                                                                                  <tr>
                                                                                            <td>

                                                                                                      <div class="custom-select">
                                                                                                                <select name="font-style">
                                                                                                                          <option value="">Font Style</option>
                                                                                                                          <option value="italic">italic</option>
                                                                                                                          <option value="normal">normal</option>
                                                                                                                          <option value="oblique">oblique</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>
                                                                                            <td>

                                                                                                      <div class="custom-select">
                                                                                                                <select name="text-decoration">
                                                                                                                          <option value="">Text Decoration</option>
                                                                                                                          <option value="none">none</option>
                                                                                                                          <option value="underline">underline</option>
                                                                                                                          <option value="line-through">line-through</option>
                                                                                                                          <option value="overline">overline</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>

                                                                                            <td>

                                                                                                      <input type="text" style="" name="text-align" id="text-align-holder">
                                                                                                      <span class="dashicons dashicons-editor-justify human-align" data-align="justify"></span>
                                                                                                      <span class="dashicons dashicons-editor-alignleft human-align" data-align="left"></span>
                                                                                                      <span class="dashicons dashicons-editor-aligncenter human-align"  data-align="center"></span>
                                                                                                      <span class="dashicons dashicons-editor-alignright human-align"  data-align="right"></span></td>
                                                                                  </tr>
                                                                        </table>
                                                                        <table>
                                                                                  <tr>
                                                                                          <?php echo unitgen ( 'font-size', 0, 50, 0.02, 'number', 'em' ); ?>

                                                                                            <?php echo unitgen ( 'line-height', 0, 50, 1, 'number', ' ' ); ?>

                                                                                            <td><input type="text" name="font-weight" class=""> <br>font weight                 </td>


                                                                                            <?php echo unitgen ( 'letter-spacing', 0, 50, 1, 'number', 'px' ); ?>
                                                                                  </tr>
                                                                        </table>

                                                                        <table class="color-palette-table">
                                                                                  <tr>

                                                                                            <td> color </td>
                                                                                            <td><input name="color" class="css-color-holder"><input type="hidden" class="human-iris">
                                                                                                      <span class="fa fa-plus-square add-color"></span></td>
                                                                                            <td><div class="human-palette"><?php
                                                                                                            if ( isset ( human_palette_colors ()[ 'colors' ] ) ) {
                                                                                                                        echo human_palette_colors ()[ 'colors' ];
                                                                                                            }
                                                                                                            ?>
                                                                                                      </div>

                                                                                            </td>
                                                                                  </tr>
                                                                        </table>
                                                                        <table>
                                                                                  <tr>
                                                                                            <td><input type="text" name="content"><br>Content<br> (Must be wrapped in double quotes)</td>
                                                                                            <td>
                                                                                                      <div class="custom-select">
                                                                                                                <select name="list-style">
                                                                                                                          <option value="">List Style</option>
                                                                                                                          <option value="none">None</option>
                                                                                                                          <option value="disc">Disc</option>
                                                                                                                          <option value="circle">Circle</option>
                                                                                                                          <option value="decimal">Decimal</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>

                                                                                            <td><span class="human-media dashicons dashicons-format-image" style="font-size: 19px;" data-media-css-holder="human-controls-list-style-image"></span><span  class="dashicons dashicons-dismiss human-clear-css" data-clear-css="human-controls-list-style-image"></span><input type="text" name="list-style-image" id="human-controls-list-style-image"  class="css-media-input"><br>List Style Image</td>

                                                                                            <td id="human-controls-list-style-image-holder"></td>

                                                                                  </tr>
                                                                        </table>

                                                              </div>

                                                              <div id="human-controls-section-bg" class="human-controls-sections">
                                                                        <table class="color-palette-table">
                                                                                  <tr>

                                                                                            <td>Background<br> color </td>
                                                                                            <td><input name="background-color" class="css-color-holder"><input type="hidden" class="human-iris">
                                                                                                      <span class="fa fa-plus-square add-color" ></span>
                                                                                            </td>
                                                                                            <td><div class="human-palette"><?php
                                                                                                            if ( isset ( human_palette_colors ()[ 'colors' ] ) ) {
                                                                                                                        echo human_palette_colors ()[ 'colors' ];
                                                                                                            }
                                                                                                            ?>
                                                                                                      </div>

                                                                                            </td>
                                                                                  </tr>
                                                                        </table>
                                                                        <table>
                                                                                  <tr>
                                                                                            <td>BG image </td>
                                                                                            <td>
                                                                                                      <span class="human-media dashicons dashicons-format-image" style="font-size: 19px;" data-media-css-holder="human-controls-bg-image"></span><span  class="dashicons dashicons-dismiss human-clear-css" data-clear-css="human-controls-bg-image"></span>
                                                                                            </td>

                                                                                            <td id="human-controls-bg-image-holder"></td>
                                                                                            <td><input type="text" name="background-image" id="human-controls-bg-image" class="css-media-input"></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                            <td>BG Attachment</td>
                                                                                            <td>

                                                                                                      <div class="custom-select">
                                                                                                                <select name="background-attachment">
                                                                                                                          <option value="">none</option>
                                                                                                                          <option value="fixed">Fixed</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>

                                                                                            <td>BG size</td>
                                                                                            <td><input type="text" name="background-size"></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                            <td>BG position</td>
                                                                                            <td><input type="text" name="background-position"></td>

                                                                                            <td>

                                                                                                      <div class="custom-select">
                                                                                                                <select name="background-repeat">
                                                                                                                          <option value="">BG Repeat</option>
                                                                                                                          <option value="no-repeat">no-repeat</option>
                                                                                                                          <option value="repeat-x">repeat-x</option>
                                                                                                                          <option value="repeat-y">repeat-y</option>
                                                                                                                </select>
                                                                                                      </div>

                                                                                            </td>
                                                                                  </tr>
                                                                        </table>
                                                              </div>
                                                              <div id="human-controls-section-spacing" class="human-controls-sections">
                                                                        <br>


                                                                        <table>




                                                                                  <tr>
                                                                                          <?php echo unitgen ( 'padding-left', 0, 500, 1, 'number' ); ?>

                                                                                            <?php echo unitgen ( 'padding-right', 0, 500, 1, 'number' ); ?>

                                                                                            <?php echo unitgen ( 'padding-top', 0, 500, 1, 'number' ); ?>

                                                                                            <?php echo unitgen ( 'padding-bottom', 0, 500, 1, 'number' ); ?>
                                                                                  </tr>

                                                                                  <tr>
                                                                                          <?php echo unitgen ( 'margin-left', -300, 300, 1, 'number' ); ?>

                                                                                            <?php echo unitgen ( 'margin-right', -300, 300, 1, 'number' ); ?>

                                                                                            <?php echo unitgen ( 'margin-top', -300, 300, 1, 'number' ); ?>

                                                                                            <?php echo unitgen ( 'margin-bottom', -300, 300, 1, 'number' ); ?>
                                                                                  </tr>
                                                                        </table>
                                                                        <hr>
                                                                        <table>

                                                                                  <tr>
                                                                                            <td>1 line Padding</td>
                                                                                            <td>
                                                                                                      <div class=""><input type="text" name="padding"></div></td>

                                                                                            <td>1 line Margin</td>
                                                                                            <td>
                                                                                                      <div class=""><input type="text" name="margin"></div></td>
                                                                                  </tr>
                                                                        </table>
                                                              </div>
                                                              <div id="human-controls-section-borders" class="human-controls-sections">
                                                                        <table class="color-palette-table">
                                                                                  <tr>

                                                                                            <td>border<br> color </td>
                                                                                            <td><input name="border-color" class="css-color-holder"><input type="hidden" class="human-iris">
                                                                                                      <span class="fa fa-plus-square add-color"></span></td>
                                                                                            <td><div class="human-palette"><?php echo human_palette_colors ()[ 'colors' ]; ?>
                                                                                                      </div>

                                                                                            </td>
                                                                                  </tr>
                                                                        </table>
                                                                        <table>

                                                                                  <tr>
                                                                                            <td>

                                                                                                      <div class="custom-select">
                                                                                                                <select name="border-style" id="border-style-select">
                                                                                                                          <option value="">Border style</option>
                                                                                                                          <option value="none">none</option>
                                                                                                                          <option value="solid">Solid</option>
                                                                                                                          <option value="double">Double</option>
                                                                                                                          <option value="dotted">Dotted</option>
                                                                                                                </select>
                                                                                                      </div>
                                                                                            </td>
                                                                                  </tr>
                                                                        </table>
                                                                        <table>
                                                                                  <tr>
                                                                                          <?php echo unitgen ( 'border-left-width', 0, 10, 1, 'number', 'px' ); ?>
                                                                                          <?php echo unitgen ( 'border-right-width', 0, 10, 1, 'number', 'px' ); ?>

                                                                                            <?php echo unitgen ( 'border-top-width', 0, 10, 1, 'number', 'px' ); ?>
                                                                                            <?php echo unitgen ( 'border-bottom-width', 0, 10, 1, 'number', 'px' ); ?>
                                                                                  </tr><tr>
                                                                                          <?php echo unitgen ( 'border-top-left-radius', 0, 360, 1, 'number', 'px' ); ?>

                                                                                            <?php echo unitgen ( 'border-top-right-radius', 0, 360, 1, 'number', 'px' ); ?>

                                                                                            <?php echo unitgen ( 'border-bottom-left-radius', 0, 360, 1, 'number', 'px' ); ?>

                                                                                            <?php echo unitgen ( 'border-bottom-right-radius', 0, 360, 1, 'number', 'px' ); ?>
                                                                                  </tr>

                                                                        </table>
                                                              </div>

                                                              <div id="human-controls-section-sizepos" class="human-controls-sections">


                                                                        <table>
                                                                                  <tr>
                                                                                          <?php echo unitgen ( 'width', 0, 100, 0.5, 'number', '%' ); ?>
                                                                                          <?php echo unitgen ( 'height', 0, 100, 0.5, 'number', 'px' ); ?>

                                                                                            <?php echo unitgen ( 'max-width', 0, 100, 0.5, 'number', 'px' ); ?>
                                                                                            <?php echo unitgen ( 'max-height', 0, 100, 0.5, 'number', 'px' ); ?>
                                                                                  </tr><tr>
                                                                                          <?php echo unitgen ( 'min-width', 0, 100, 0.5, 'number', 'px' ); ?>
                                                                                          <?php echo unitgen ( 'min-height', 0, 100, 0.5, 'number', 'px' ); ?>

                                                                                            <?php echo unitgen ( 'top', -100, 100, 1, 'number' ); ?>
                                                                                            <?php echo unitgen ( 'bottom', -100, 100, 1, 'number' ); ?>
                                                                                  </tr><tr>
                                                                                          <?php echo unitgen ( 'left', -100, 100, 1, 'number' ); ?>
                                                                                          <?php echo unitgen ( 'right', -100, 100, 1, 'number' ); ?>
                                                                                  </tr>
                                                                        </table>
                                                                        <hr>
                                                                        <table>
                                                                                  <tr>
                                                                                            <td>Position</td>
                                                                                            <td>
                                                                                                      <select name="position" style="width: 88px;">
                                                                                                                <option value="">None</option>
                                                                                                                <option value="initial">Initial</option>
                                                                                                                <option value="absolute">
                                                                                                                          Absolute
                                                                                                                </option>
                                                                                                                <option value="relative">
                                                                                                                          Relative
                                                                                                                </option>
                                                                                                                <option value="fixed">
                                                                                                                          Fixed
                                                                                                                </option>

                                                                                                      </select>
                                                                                            </td>
                                                                                            <td>z-index</td>
                                                                                            <td><input type="number" name="z-index"></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                            <td>Overflow</td>
                                                                                            <td><input type="text" name="overflow"></td>
                                                                                            <td>Clear</td>
                                                                                            <td><input type="text" name="clear"></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                            <td>Float</td>
                                                                                            <td><input type="text" name="float"></td>

                                                                                            <td>Display</td>
                                                                                            <td><input type="text" name="display">
                                                                                            </td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                            <td>Opacity</td>
                                                                                            <td><input type="text" name="opacity"></td>
                                                                                  </tr>
                                                                        </table>

                                                              </div>



                                                    </div>
                                                    <span class="css-bar-logo bottom-bar-logo ui-draggable-handle" style="cursor:move;"><img src="<?php echo site_url (); ?>/wp-content/themes/human/assets/images/human-print-logo.png" alt=""> Human CSS Builder 2.0</span>
                                          </div>
                                </div>
                                <div id="human_tag_css_helper"></div>
                                <div class="display-table-column  radius-top-right toggles"  id="main-css-div-holder" style="display:none" >
                                          <div class="all-gens">
                                                  <?php
                                                  echo $file_css_gen;
                                                  ?>
                                          </div>

                                </div>

                      </div>
            </div>
            <div id="custom-css-div-holder" class='toggles' style="display:none">
                    <?php
                    if ( is_file ( ABSPATH . 'wp-content/human-custom.css' ) ) {


                                $human_custom_css = file_get_contents ( ABSPATH . 'wp-content/human-custom.css' );
                    }
                    else {
                                $human_custom_css = '';
                    }
                    ?>
                      <div style="text-align:right">Custom CSS: <span class="toggle-css" data-toggle="human-custom-css">hide [-]</span><hr></div>
                      <textarea name="custom-css" id="human-custom-css"><?php echo $human_custom_css; ?></textarea>
            </div>
<?php } ?>
<?php

/**
 * @package Human
 * @subpackage Human Fonts Uploader and Assigner
 * @developer Sergei Pavlov <itpal24@gmail.com>
 *
 */
function font_assigner () {

            $css_font_list = '';
            if ( get_option ( 'human-google-fonts' ) ) {

                        $arr = get_option ( 'human-google-fonts' );
                        $current_url = $arr[ 0 ];
                        $current_selectors = explode ( ';', $arr[ 1 ] );
                        $fonts_face[ count ( $fonts_face ) ] = "@import '$current_url';";
                        foreach ( $current_selectors as $current_selector ) {
                                    if ( ! empty ( $current_selector ) ) {
                                                $css_font_list .= '<option value="' . $current_selector . '">' . $current_selector . '</option>';
                                    }
                        }
            }
            foreach ( new DirectoryIterator ( HUMAN_CHILD_PATH . '/assets/fonts/' ) as $file ) {

                        if ( $file->isDot () )
                                    continue;
                        if ( $file->isDir () ) {

                                    $font_name[] = $file->getFilename ();
                                    $font_named = $file->getFilename ();
                                    $exts = array (
                                                'otf',
                                                'ttf',
                                                'eot' );
                                    $font_packs = '';
                                    $font_packs = [ ];
                                    foreach ( $exts as $extk => $ext ) {
                                                if ( $ext === 'eot' ) {
                                                            $src = 'url';
                                                }
                                                else {
                                                            $src = 'src';
                                                }
                                                $ext_url = HUMAN_CHILD_URL . '/assets/fonts/' . $font_named . '/' . $font_named . '.' . $ext;
                                                $ext_path = HUMAN_CHILD_PATH . '/assets/fonts/' . $font_named . '/' . $font_named . '.' . $ext;
                                                if ( is_file ( $ext_path ) !== false ) {

                                                            $font_packs[ $ext ] = $src . ":  url('" . $ext_url . "');";
                                                }
                                                else {
                                                            //  $font_packs[ $ext ] = $ext_url;
                                                }
                                    }

                                    $strped_name = str_replace ( "-", "", explode ( ".", $font_named )[ 0 ] );

                                    $fonts_face[] = "@font-face {
                                               font-family : " . $strped_name . ";
                                               " . implode ( $font_packs ) . "
                                               font-weight : normal;
                                               font-style : normal;
                                            }";

                                    $css_font_list .= '<option value="' . str_replace ( "-", "", explode ( ".", $file->getFilename () )[ 0 ] ) . ', sans-serif">' . str_replace ( "-", "", explode ( ".", $file->getFilename () )[ 0 ] ) . ', sans-serif</option>';
                        }
            }

            return array (
                        $fonts_face,
                        $css_font_list );
}

if ( is_admin () || isset ( $_GET[ 'vc_action' ] ) && $_GET[ 'vc_action' ] === 'vc_inline' && current_user_can ( 'administrator' ) ) {
            $fonts_face = implode ( font_assigner ()[ 0 ] );
            $css_font_list = font_assigner ()[ 1 ];
}

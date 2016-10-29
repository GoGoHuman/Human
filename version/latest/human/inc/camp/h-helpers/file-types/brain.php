<?php



function my_mime_types($mime_types){
    $mime_types['zip'] = 'application/zip';
    $mime_types['rar'] = 'application/x-rar-compressed';
    $mime_types['tar'] = 'application/x-tar';
    $mime_types['gz'] = 'application/x-gzip';
    $mime_types['gzip'] = 'application/x-gzip';
    $mime_types['tiff'] = 'image/tiff';
    $mime_types['tif'] = 'image/tiff';
    $mime_types['bmp'] = 'image/bmp';
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['psd'] = 'image/vnd.adobe.photoshop';
  //  $mime_types['ai'] = 'application/postscript';
    $mime_types['indd'] = 'application/x-indesign'; // not official, but might still work
  //  $mime_types['eps'] = 'application/postscript';
  //  $mime_types['rtf'] = 'application/rtf';
    $mime_types['txt'] = 'text/plain';
    $mime_types['wav'] = 'audio/x-wav';
    $mime_types['csv'] = 'text/csv';
   // $mime_types['xml'] = 'application/xml';
    $mime_types['flv'] = 'video/x-flv';
   // $mime_types['swf'] = 'application/x-shockwave-flash';
    $mime_types['vcf'] = 'text/x-vcard';
    $mime_types['html'] = 'text/html';
   // $mime_types['htm'] = 'text/html';
    $mime_types['css'] = 'text/css';
  //  $mime_types['js'] = 'application/javascript';
    $mime_types['ico'] = 'image/x-icon';
    $mime_types['otf'] = 'application/x-font-otf';
    $mime_types['ttf'] = 'application/x-font-ttf';
    $mime_types['woff'] = 'application/x-font-woff';
    $mime_types['ics'] = 'text/calendar';
    return $mime_types;
}

add_filter('upload_mimes', 'my_mime_types', 1, 1);



?>
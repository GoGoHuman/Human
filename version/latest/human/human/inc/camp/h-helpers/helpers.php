<?php

// Add the filter
add_filter('upload_mimes', 'ap_extended_mime_types');

// Function to add mime types
function ap_extended_mime_types ( $mime_types=array() ) {

	// add your extension & app info to mime-types.txt in this format
	//	doc,doct application/msword
	//	pdf application/pdf
	//	etc...
	$file = HUMAN_BASE_PATH.'/helpers/file-types/mime-types.txt';
	$file = plugins_url() . $file;
	$mime_file_lines = file($file);

	foreach ($mime_file_lines as $line) {
		//Catch all sorts of line endings - CR/CRLF/LF
		$mime_type = explode(' ',rtrim(rtrim($line,"\n"),"\r"));
		$mime_types[$mime_type[0]] = $mime_type[1];
	}

	// add as many as you like
	return $mime_types;
	
}
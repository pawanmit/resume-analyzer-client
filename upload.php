<?php

require_once("utils.php");

$files = $_FILES;

if ( count($files['userfile']) > 0 ) {
	fixFilesArray($files['userfile']);
} else {
	//Return error if no files were selected
	$response_json = createResponseJson('', 'Error: No files selected');
	echo $response_json;
	return -1;
}

$keyword_string = getKeywordsAsString($_POST);

if ( strlen( trim ( str_replace(',', '', $keyword_string) ) ) < 1 ) {
	//Return error if no keywords were selected were selected
	$response_json = createResponseJson('', 'Error: No keywords found');
	echo $response_json;
	return -1;
}

$file_size_error = '';
foreach($files['userfile'] as $file)  {	
	if ( $file['size'] > 1048576 ) {
	 $file_size_error .= $file['name'] . ' is more than max allowed size of 1 MB <BR>';
	}
}

if (strlen($file_size_error) > 0) {
	$response_json = createResponseJson('', $file_size_error);
	echo $response_json;
	return -1;
}

$keyword_finder_url = getPropertyValue("KEYWORD_FINDER_URL");

$tmp_file_array = array();

$scanned_resume_array = array();
 
foreach($files['userfile'] as $file) {
	$tmp_file = $file['tmp_name'];
	$upload_file_name = $file['name'];
	$target_url = $keyword_finder_url.'?filepath='.$tmp_file . '&keywords='.$keyword_string;
	$response_json = makeCurlCall($target_url);
	$result_object = json_decode(stripslashes($response_json), true);
	
	if ( strlen(trim($result_object['output']['error'])) > 0 ) {
		//If Json output has a value for error stop execution and return error message
		$response_json = createResponseJson('', $result_object['output']['error']);
		echo $response_json;
		return -1;
	} else {
		//Save the keywords and counts in an object and add the object to an array
		$keyword_count_map =  $result_object['output']['keywordCountMap'];
     	$scanned_resume = new ScannedResume();
     	$scanned_resume->upload_file_name = $upload_file_name;
     	$scanned_resume->keyword_count_map = $keyword_count_map;		
		//$scanned_resume = createResumeObject($upload_file_name, $keyword_count_map);
		array_push( $scanned_resume_array, $scanned_resume);
	}
}//foreach

//Converts the JSON into an html table
$keyword_count_table = createKeywordCountDisplayTable($scanned_resume_array);

$response_json = createResponseJson($keyword_count_table, '');

echo $response_json;

?>
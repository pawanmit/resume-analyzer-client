<?php

$filename = $_SERVER['HTTP_X_FILENAME'];
$keywords = $_SERVER['HTTP_X_KEYWORDS'];
error_log('uploading file ' . $filename);

$contents = file_get_contents('php://input');

//error_log("Contents:" . $contents);

$tempfile = '/Applications/MAMP/htdocs/resumeparser/uploads/'.$filename;

try {
	file_put_contents($tempfile,$contents);
} catch (Exception $e) {
	error_log('Error saving file:' . $tempfile);
}
$resume_app_url = 'http://localhost:8080/parserclient/resume/scan/keywords';

$ch = curl_init($resume_app_url.'?filepath='.$tempfile . '&keywords='.$keywords);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$curl_error = curl_error($ch);

curl_close($ch);

if($curl_error) {
	error_log("Error:" . $curl_error);
	$resultObject =  new StdClass;
	$resultObject->output->error = $curl_error;
	$resultObject->output->keywordCountMap = "";
	$resultJson =  json_encode($resultObject);
	log_object($resultObject);
} else {

	$resultObject = json_decode(stripslashes($response), true);
	
	log_object($resultObject);
	
	$resultJson =  json_encode($resultObject);


}
	
error_log($resultJson);

echo $resultJson;


function log_object($object) {
	ob_start();

	var_dump($object);

	$output = ob_get_contents();

	error_log("Result Object: " . $output);

	ob_end_clean();

}

?>
<?php

require_once 'ScannedResume.php';

/**
 * Logs an object to php_error.log
 *
 *@param object $object
 *
 */
function logObject($object) {
	ob_start();
	var_dump($object);
	$output = ob_get_contents();
	error_log("Result Object: " . $output);
	ob_end_clean();
}

/**
 * Fixes the odd indexing of multiple file uploads from the format:
 * $_FILES['field']['key']['index']
 * To the more standard and appropriate:
 *
 * $_FILES['field']['index']['key']
 *
 * @param array $files
 * @author Corey Ballou
 * @link http://www.jqueryin.com
 */
function fixFilesArray(&$files) {
    $names = array( 'name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);
	
    foreach ($files as $key => $part) {
        // only deal with valid keys and multiple files
        $key = (string) $key;
        if (isset($names[$key]) && is_array($part)) {
            foreach ($part as $position => $value) {
                $files[$position][$key] = $value;
            }
            // remove old key reference
            unset($files[$key]);
        }
    }//foreach
}//function

	/**
	* Grab keywords from HTTP post object
	* creates and returns a comma seprated string of these keywords
	*
	* @param $post HTTP $_POST object
	* @return keyword_string a comma seprated string of keywords
	*/

	function getKeywordsAsString() {
		$keyword_string = '';
		foreach($_POST as $key=>$value) {
			if ( strpos($key, 'keyword-') === 0 ) {
			$keyword_string .= $value . ',';
			//error_log('Key:' . $key . ' Value:' . $value . ' String position:' . strpos($key, 'keyword') );
			}
		}//foreach
		//remove last ,
		return substr($keyword_string, 0, strlen($keyword_string) -1 );
	}//function


    /**
    * Makes a curl call given a URL url and returns a JSON result object
    *
    * @param $apiUrl URL of the API to make curl call.
    * @return JSON result object.
    */
    function makeCurlCall($target_url) {
        # Initial curl
        //error_log("Making curl call for " . $target_url);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,$target_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
        //Uncomment this line if httpp certificate for curl not available
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response_json = curl_exec($curl);
		$curl_error = curl_error($curl);
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		//If curl resulted in an error than send error
		if($curl_error) {
			error_log("Error:" . $curl_error);
			$response_json =  createResponseJson('', $curl_error);
		} else if ($http_status != 200) {
			error_log("Error. Curl returned HTTP status code of " . $http_status);
			$response_json =  createResponseJson('', 'Service Down. HTTP Status Code: ' . $http_status );
		} else {
			//converts response json into a stdclass object and then converts the object back to json
			//$result_object = json_decode(stripslashes($response_json), true);	
			//$response_json =  json_encode($result_object);
		}
        curl_close($curl);
        return $response_json;
    }
    
    /**
    * Creates a json for keyword map and error message
    * @param $keyword_count_map A map matching keywords and their count
    * @param $error error message
    * @return $response_json A json object to be displayed in UI
    */
    function createResponseJson($keyword_count_map='', $error='') {
		$result_object =  new StdClass;
		$result_object->output->error = $error;
		$result_object->output->keywordCountMap = $keyword_count_map;
		$response_json =  json_encode($result_object);
		return $response_json;
    }
    
    /**
    * Creates a resume object of type ScannedResume
    * @param
    * @param
    * 
    * @return $scanned_resume
    */
    function createResumeObject($upload_file_name, $keyword_count_map) {
     	$scanned_resume = new ScannedResume();
     	$scanned_resume->upload_file_name = $upload_file_name;
     	$scanned_resume->keyword_count_map = $keyword_count_map;
     	//logObject($scanned_resume);
     	return $scanned_resume;
    }

/**
* Creates ahtml table from $scanned_resume_array
* @param $scanned_resume_array
* @return $keyword_count_table an html table
*/

function createKeywordCountDisplayTable($scanned_resume_array) {
	
	$keyword_count_table = '<table width="100%" border="0" cellspacing="1" cellpadding="1">';
	//http://www.noupe.com/how-tos/better-ui-design-proper-use-of-tables.html
	
	$count = 1;
	foreach($scanned_resume_array as $scanned_resume) {
		$upload_file_name = $scanned_resume->upload_file_name;
		$keyword_count_map = $scanned_resume->keyword_count_map;
		
		ksort($keyword_count_map);
		$keywords = array_keys($keyword_count_map);
		
		if ($count == 1) {
		//Create header of keywords
		  //logObject($keywords);
		  //Start a new row for header
		  $keyword_count_table .= '<tr>';
		  //Insert an empty column for [0,0]
		  $keyword_count_table .= '<th></th>';
		  foreach($keywords as $keyword) {
		  	$keyword_count_table .= '<th>' . $keyword . '</th>';
		  }
		}//if count =1
		$keyword_count_table .= '</tr>';
		$count++;
		
		//Start a new row
		$keyword_count_table .= '<tr>';
		$keyword_count_table .=  '<td>' . $upload_file_name . '</td>';
		
		foreach($keywords as $keyword) {
			$keyword_count_table .=  '<td class="keyword_count">' . $keyword_count_map[$keyword]. '</td>';
		}
		$keyword_count_table .= '</tr>';
	}//foreach
	return $keyword_count_table;
}


/**
* Loads a .ini file depending on host name and returns the value for a property. 
* If file is not loaded or property_name does not exisis then null is returned.
* @param property_name Name of property to load.
* @return property_value value for property_name
*/
function getPropertyValue($property_name) {
	$properties = array();
	if ( strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ) {
		$properties = parse_ini_file("./deployment/development.ini");
	} else {
		$properties = parse_ini_file("./deployment/production.ini");
	}
	
	return $properties[$property_name];
}


?>
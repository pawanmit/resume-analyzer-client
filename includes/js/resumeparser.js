//Solution for fileupload from 
//http://stackoverflow.com/questions/11046684/php-file-upload-using-jquery-post
        $(document).ready(function(event) {
            $('#resumeuploadform').ajaxForm(function(data) {
            	var obj = JSON.parse(data);
            	console.log(obj);
                $('#keywordCountMap').html(obj.output.keywordCountMap);
                $('#error').html(obj.output.error);
            });
        });
	
 	//Return the keywords as comma seprated string
 	function getKeywords() {
 		var keywords = "";
 		var inputKeywords = jQuery("#keywordList li");
 		
 		//console.log(inputKeywords);
		inputKeywords.each(function(idx, li) {
			var keyword = jQuery(li).find("input[type=text]").val().trim();
			if( keyword.length > 0 ) {
				keywords += "<li class='keyword'>" + keyword.trim() + "</li>";
			}
		});
		return keywords;
 	}
 	
	//Adds an input text field for keywords
	jQuery("#addKeywordButton").bind("click", function() {
		var lastKeywordName= jQuery("#keywordList li").last().find("input").attr("name");
		newKeywordCount = parseInt( lastKeywordName.substring(lastKeywordName.length - 1) ) + 1;
		var newKeywordName = "keyword" +  newKeywordCount;
		//alert(newKeywordName);
		jQuery("#keywordList").append("<li class='keyword'> <input type='text' name='" + newKeywordName + "'>");
	});	

	//Parses the keywordCountMap for keyword and counts and generates html
	function displayKeywordMap(keywordCountMap) {
    	//Loop through keywords
		for (keyword in keywordCountMap) {
    		if (!keywordCountMap.hasOwnProperty(keyword)) {
        		//The current property is not a direct property of p
        			continue;
    		}
    		//document.getElementById("keywords").innerHTML=xmlhttp.responseText;
    		jQuery("#keywordCountMap").append("<li class='keywordCount'> <span class='keyword'>" + keyword + "</span><span class='count'>" +  keywordCountMap[keyword] + "</span></li>");
		}//for 	
	}
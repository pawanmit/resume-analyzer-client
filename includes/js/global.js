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
	
		var currentKeywordsCount = jQuery("#keywordList > li").length;
		
		if (currentKeywordsCount >= 5) {
			alert("Only 5 keywords allowed");
			return;
		}
		
		var lastKeywordName= jQuery("#keywordList li").last().find("input").attr("name");
		newKeywordCount = parseInt( lastKeywordName.split("-")[1] )  + 1 ;
		var newKeywordName = "keyword-" +  newKeywordCount;
		//alert(newKeywordName);
		jQuery("#keywordList").append("<li class='keyword' id='"+newKeywordName+"'> <input type='text' name='" + newKeywordName + "'><input type='button' value='Remove' class='removeKeywordButton' id='removeKeywordButton-"+newKeywordCount+"'>");
	});

	$('body').on('click', '.removeKeywordButton', function() {
		var removeKeywordButtonId = this.id;
		var keywordCount = parseInt( removeKeywordButtonId.split("-")[1] );
		var listId = "keyword-" + keywordCount;
		jQuery("#"+listId).remove();
    	//alert( keywordCount );
	});
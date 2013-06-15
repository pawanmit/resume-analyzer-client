//Solution for fileupload from 
//http://stackoverflow.com/questions/11046684/php-file-upload-using-jquery-post
        $(document).ready(function(event) {
            $('#resumeuploadform').ajaxForm(function(data) {
            	$('#keywordCountMap').removeClass("ty_success").html("");
            	$('#error').removeClass("ty_error").html("");
            	//Hide submit button and show progress bar (doesn't work here. calling this when submit button is clicked)
            	//jQuery('#register_btn').hide();
            	//jQuery('#progressbar').show();
            	var obj = JSON.parse(data);
            	console.log(obj);
            	if (obj.output.keywordCountMap.length > 0) {
               		$('#keywordCountMap').addClass("ty_success").html(obj.output.keywordCountMap);
               		jQuery('html,body').animate({scrollTop: jQuery("#keywordCountMap").offset().top},'slow');
               	}
               	
                if (obj.output.error.length > 0) {
                	$('#error').addClass("ty_error").html(obj.output.error);
                }
                //Hide progress bar and show submit button
            	jQuery('#register_btn').show();
            	jQuery('#progressbar').hide();                
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
		jQuery("#keywordList").append("<li class='keyword' id='"+newKeywordName+"'> <input type='text' name='" + newKeywordName + "' class='text_box required'><input type='button' value='Remove'  id='removeKeywordButton-"+newKeywordCount+"' class='remove_keyword_button'>");
	});

	$('body').on('click', '.remove_keyword_button', function() {
		var removeKeywordButtonId = this.id;
		var keywordCount = parseInt( removeKeywordButtonId.split("-")[1] );
		var listId = "keyword-" + keywordCount;
		jQuery("#"+listId).remove();
    	//alert( keywordCount );
	});
	
	jQuery('#register_btn').bind("click", function() {
        jQuery('#register_btn').hide();
        jQuery('#progressbar').show();	
	});
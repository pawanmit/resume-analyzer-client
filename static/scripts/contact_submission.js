		jQuery("#contactform").validate({
			debug: false,
			rules: {
				user_name: "required",
				user_email: {
					required: true,
					email: true
				},
				user_comments: {
					required: true
				} 
			},
			messages: {
				user_name: "Please enter a name.",
				user_email: "Email address required.",
				image_url: "Please enter a valid image url."
			},
			submitHandler: function(form) {
				// do other stuff for a valid form
				//form.event.preventDefault();
				jQuery.post('contact_submission.php', jQuery("#contactform").serialize(), function(data) {
					jQuery('body').append(data);
                    if (success == 1) {
                        //Clear form values
						$('form :input').val('').html('');
                    	jQuery('#message').html('<h3>Thank you for contacting me. I will get back to you shortly.</h3>');
                    } else if (success == -1) {
                        jQuery('#message').html("<h3>There was a problem submitting your entry. Please check for any special characters like ' and & and try again</h3>");
                    }
				});
			}
		});
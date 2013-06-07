<?php
if(isset($_POST['user_email'])) {
     
    // EDIT THE 2 LINES BELOW AS REQUIRED
    $email_to = "mitpawan@gmail.com";
    $email_subject = "Email from resumescanner.net";
     
    // validation expected data exists
    //error_log("Post email:" . $_POST['user_email']);
    //error_log("Post email:" . $_POST['user_name']);
    //error_log("Post email:" . $_POST['user_comments']);
    
    if(!isset($_POST['user_name']) ||
        !isset($_POST['user_email']) ||
        !isset($_POST['user_comments'])) {
        echo "<script type='text/javascript'>  var success = -1; </script>";
        return;
    }

function clean_string($string) {
	$bad = array("content-type","bcc:","to:","cc:","href");
	return str_replace($bad,"",$string);
}     
    $user_name = $_POST['user_name']; // required
    $user_email = $_POST['user_email']; // required
    $user_comments = $_POST['user_comments']; // required
     
    $error_message = "";
    $email_message = "Form details below.\n\n";     
    $email_message .= "First Name: ".clean_string($user_name)."\n";
    $email_message .= "Email: ".clean_string($user_email)."\n";
    $email_message .= "Comments: ".clean_string($user_comments)."\n";
     
     
// create email headers
$headers = 'From: '.$user_email."\r\n".
'Reply-To: '.$user_email."\r\n" .
'X-Mailer: PHP/' . phpversion();
@mail($email_to, $email_subject, $email_message, $headers);
echo "<script type='text/javascript'>  var success = 1; </script>";

?>
  
<?php
}
?>
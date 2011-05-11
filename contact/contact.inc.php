<?php
require_once('../conf/conf.inc.php');
require_once('../conf/db.class.php');

$response_data = '';
$error = null;
$publickey = "6Lf-OgYAAAAAABuvX1QVI1h1b1DsXRlkCw30UVdI";
$privatekey = "6Lf-OgYAAAAAAE6itXDNjFULu2m8sH2nK73vQJLL";

if ($_POST) {

  $valid = false;
  $contact_name = isset($_POST["contact_name"]) ? $_POST["contact_name"] : "";
  $contact_email = isset($_POST["contact_email_addy"]) ? $_POST["contact_email_addy"] : "";
  $contact_message = isset($_POST["contact_message"]) ? $_POST["contact_message"] : "";
  $contact_type = isset($_POST["contact_type"]) ? $_POST["contact_type"] : "contact";

  $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);

  //check for valid recaptcha
  if ($resp->is_valid) {
    $valid = true;
  }
  else {
    $valid = false;
    $response_data .= '$("#recaptcha_response_field").css({\'background-color\':\'#FFB6C1\'});';
    $response_data .= '$("#recaptcha_validation").html(\'<span class="fail">words were not correctly reproduced</span>\');';
  }

  //check that name is present
  if($contact_name) {
    $valid = $valid && true;	
  }
  else {
    $valid = false;
    $response_data .= '$("#contact_name").css({\'background-color\':\'#FFB6C1\'});';
    $response_data .= '$("#name_validation").html(\'<span class="fail">name is required</span>\');';
  }

  //check for valid email address
  if($contact_email != "" && check_email_address($contact_email)) {
    $valid = $valid && true;	
  }
  else {
	$valid = $valid && false;
  	$response_data .= '$("#contact_email_addy").css({\'background-color\':\'#FFB6C1\'});';
    $response_data .= '$("#email_validation").html(\'<span class="fail">email address was not valid</span>\');';
   }

  //check for a message
  if ($contact_message) {
 	 $valid = $valid && true; 
  }
  else {
	$valid = $valid && false;
  	$response_data .= '$("#contact_message").css({\'background-color\':\'#FFB6C1\'});';
    $response_data .= '$("#message_validation").html(\'<span class="fail">a message is required</span>\');';
   }

   if($valid) {
	 switch($contact_type) {
	   case 'contact':
	     $message_type = 'contact';
	   break;
	
	   case 'services':
	     $message_type = 'services';
	   break;
	 }
	
	
	   $subject = 'LifeDesk inquiry';
	
       $rec1 = 'feedback@lifedesks.org';
       $EMAIL_CONTENT1="form submitted by $contact_name ($contact_email) \n\n $contact_message";

       $rec2 = $contact_email;
       $EMAIL_CONTENT2="Thank you for contacting us. We will respond to your inquiry as soon as possible. We included a copy of your message for your records.\n\n Thanks, \n The LifeDesks Team\n\n************************\n\n $contact_message";

       $db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);

       $record = array(
	     'name' => strip_tags($contact_name),
	     'email' => strip_tags($contact_email),
	     'message' => strip_tags($contact_message),
	     'type' => $message_type,
	     'created' => strtotime("now"),
	   );
     
      $result = $db->query_insert("request_contact_us", $record); 

	  require '../conf/phpmailer/class.phpmailer.php';

	  try {
		$mail = new PHPMailer(true); //New instance, with exceptions enabled
		$body             = $EMAIL_CONTENT1;
		$mail->IsSMTP();                           // tell the class to use SMTP
		$mail->SMTPAuth   = false;
		$mail->Port       = 25;                    // set the SMTP server port
		$mail->Host       = SMTP_SERVER;        // SMTP server
		$mail->From       = $rec2;
		$mail->FromName   = "LifeDesks Team";
		$mail->AddAddress($rec1);
		$mail->Subject  = $subject;
		$mail->WordWrap   = 80;                    // set word wrap
		$mail->Body = $body;
		$mail->IsHTML(false);
		$mail->Send();
	  } 
	  catch (phpmailerException $e) {
		$sent_mail1 = false;
		error_log($e->errorMessage());
	  }
	
	  try {
		$mail2 = new PHPMailer(true); //New instance, with exceptions enabled
		$body             = $EMAIL_CONTENT2;
		$mail2->IsSMTP();                           // tell the class to use SMTP
		$mail2->SMTPAuth   = false;
		$mail2->Port       = 25;                    // set the SMTP server port
		$mail2->Host       = SMTP_SERVER;        // SMTP server
		$mail2->From       = $rec1;
		$mail2->FromName   = $contact_name;
		$mail2->AddAddress($rec2);
		$mail2->Subject  = $subject;
		$mail2->WordWrap   = 80;                    // set word wrap
		$mail2->Body = $body;
		$mail2->IsHTML(false);
		$mail2->Send();	
	  }
	  catch (phpmailerException $e) {
		$sent_mail2 = false;
		error_log($e->errorMessage());
	  }
	
      $response_data .= '$("#lifedesk_response_overlay").fadeIn();';
      $response_data .= 'var message = $("#lifedesk_response_message");';
      $response_data .= 'message.fadeIn("slow").html("<div id=\"expert_response_message\"><p>Thanks for contacting us. We will respond to your inquiry or comment as soon as we can.</p><div class=\"submit\"><input id=\"lifedesk_response_button\" type=\"image\" value=\"close\" src=\"/images/btn_close.gif\" onclick=\"close_message();\" /></div></div>")';

  }

}

function check_email_address($email) {	
	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
		return true;
	}
	return false;
}

?>
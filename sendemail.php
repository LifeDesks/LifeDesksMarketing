<?php
require_once('conf/conf.inc.php');

if(!$_POST) exit;

function valid_email($email) {	
	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
		return true;
	}
	return false;
}
	
$my_name = isset($_POST['my_name']) ? $_POST['my_name'] : "";
$my_email = isset($_POST['my_email']) ? $_POST['my_email'] : "";
$friends_name = isset($_POST['friends_name']) ? $_POST['friends_name'] : "";  
$friends_email = isset($_POST['friends_email']) ? $_POST['friends_email'] : "";

$from = $my_email;

$errors = '';
 
if (valid_email($my_email)==FALSE) { $errors[] = 'Please enter your valid e-mail address'; }
if (valid_email($friends_email)==FALSE) { $errors[] = 'Please enter a valid e-mail address for the recipient'; }
			

if(is_array($errors)) {
	while (list($key,$value) = each($errors)){
		echo '<span class="errors">'.$value.'</span><br />';
	}
}
else{

	require_once('conf/phpmailer/class.phpmailer.php');

	  $include_me = $_POST["include_me"];
	  $subject = 'LifeDesks';
	  $EMAIL_CONTENT='Hello '.$friends_name.',' . "\r\n\r\n" . $my_name.' invites you to visit LifeDesks at '.BASE_URL.'.' . "\r\n\r\n" . 'LifeDesks are dynamic web environments that help you manage and share biodiversity data.';

	try {
		$mail = new PHPMailer(true); //New instance, with exceptions enabled
		$body             = $EMAIL_CONTENT;
		$mail->IsSMTP();                           // tell the class to use SMTP
		$mail->SMTPAuth   = false;
		$mail->Port       = 25;                    // set the SMTP server port
		$mail->Host       = SMTP_SERVER; // SMTP server
		$mail->AddReplyTo($from,$my_name);
		$mail->From       = $from;
		$mail->FromName   = $my_name;
		$mail->AddAddress($friends_email);
		if($include_me != "") {
		  $mail->AddCC($from, $my_name);	
		}
		$mail->Subject  = $subject;
		$mail->WordWrap   = 80; // set word wrap
		$mail->Body = $body;
		$mail->IsHTML(false);
		$mail->Send();
		echo '<span class="emailSent">Thank you, your message has been sent.</span>';
	} 
	catch (phpmailerException $e) {
		$sent_mail = false;
		error_log($e->errorMessage());
	}
	
}
?>


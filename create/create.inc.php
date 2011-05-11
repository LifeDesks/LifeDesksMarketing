<?php
require_once('../conf/conf.inc.php');
require_once('../conf/db.class.php');

$error = "";
$response_data = "";
$sent_mail = false;
$sending_mail = false;
$publickey = "6Lf-OgYAAAAAABuvX1QVI1h1b1DsXRlkCw30UVdI";
$privatekey = "6Lf-OgYAAAAAAE6itXDNjFULu2m8sH2nK73vQJLL";

//only have insert priv on the lifedesk database to avoid potential for sql injection attacks.

if($_POST) {

  $url = parse_url(BASE_URL);
  $domain = explode(".",$url['host'],2);
  $host = $domain[1];

  $form_type = $_POST["formType"];

  $db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
      
  if ($form_type == "expert") {
	
	  //blank out the citizen form to prevent errors
	  $cit_person_name = '';
	  $cit_email_addy = '';
	  $cit_message = '';
	  $resp = '';
	
	  //grab expert vars and push into a sql query
	  $title = @strip_tags($_POST["ld_title"]);
	  $url_req = @strip_tags($_POST["url_req"]);
	  $person_givenname = @strip_tags($_POST["person_givenname"]);
	  $person_name = @strip_tags($_POST["person_name"]);
	  $email_addy = @strip_tags($_POST["email_addy"]);
	  $username = @strip_tags($_POST["username"]);
      $pass = @$_POST["your_pass"];
      $pass_again = @$_POST["your_pass_again"];
	  $understand_terms = @$_POST["understand_terms"];
	  $md5 = @md5($url_req . time());
	  $now = strtotime("now");

      $valid = false;

      //need per field validation here to throw errors to client
      if($title != "" && strlen($title) < 40) {
	    $valid = true;
      }
      else {
	    $response_data .= '$("#ld_title").css({\'background-color\':\'#FFB6C1\'});';
	    $response_data .= '$("#title_validation").html(\'<span class="fail">must be less than 40 characters long</span>\');';
	    $valid = false;	 
      }
	  
	  if($person_givenname != "") {
	    $valid = $valid && true;	
	  }
	  else {
	    $response_data .= '$("#person_givenname").css({\'background-color\':\'#FFB6C1\'});';
	    $valid = $valid && false;	
	  }

      if(strlen($person_givenname) < 32) {
	    $valid = $valid && true;
      }
      else {
		$response_data .= '$("#person_givenname").css({\'background-color\':\'#FFB6C1\'});';
		$response_data .= '$("#person_givenname_validation").html(\'<span class="fail">name too long</span>\');';
		$valid = $valid && false;
      }
	
	  if($person_name != "") {
	    $valid = $valid && true;	
	  }
	  else {
		$response_data .= '$("#person_name").css({\'background-color\':\'#FFB6C1\'});';
		$valid = $valid && false;
	  }
	
	  if(strlen($person_name) < 32) {
	    $valid = $valid && true;	
	  }
	  else {
		$response_data .= '$("#person_name").css({\'background-color\':\'#FFB6C1\'});';
		$response_data .= '$("#person_name_validation").html(\'<span class="fail">name too long</span>\');';
		$valid = $valid && false;	    	
	  }
	
	  if($email_addy != "" && check_email_address($email_addy)) {
	    $valid = $valid && true;	
	  }
	  else {
		$response_data .= '$("#email_addy").css({\'background-color\':\'#FFB6C1\'});';
		$response_data .= '$("#email_validation").html(\'<span class="fail">invalid email address</span>\');';
		$valid = $valid && false;
	  }

      if(strlen($url_req) > 4) {
	    $valid = $valid && true;
      }
      else {
	    $response_data .= '$("#url_req").css({\'background-color\':\'#FFB6C1\'});';
	    $response_data .= '$("#key_validation").html(\'<span class="fail">must be more than 4 characters long\');';
	    $valid = $valid && false;
      }

      if(strlen($url_req) <= 20) {
	    $valid = $valid && true;
      }
      else {
	    $response_data .= '$("#url_req").css({\'background-color\':\'#FFB6C1\'});';
	    $response_data .= '$("#key_validation").html(\'<span class="fail">must be less than 20 characters long\');';
	    $valid = $valid && false;
      }      

      $json = json_decode(implode("",file(ADMIN_URL . "/check_sitename/" . $url_req)));
	
	  if($json->status == true) {
	    $valid = $valid && true;	
	  }
	  elseif ($json->status == false && !empty($json->link)) {
		$response_data .= '$("#url_req").css({\'background-color\':\'#FFB6C1\'});';
		$response_data .= '$("#key_validation").html(\'<span class="fail">taken,  <a href="' . $json->link . '"> visit site <img src="/images/application_go.gif" height="14px" alt="Visit ' . $json->link . '"  title="Visit ' . $json->link . '.' . $host . '"></a></span>\');';
		$valid = $valid && false;
	  }
	  elseif ($json->status == false && empty($json->link) && strlen($url_req) > 3 && strlen($url_req) <= 20) {
		$response_data .= '$("#url_req").css({\'background-color\':\'#FFB6C1\'});';
		$response_data .= '$("#key_validation").html(\'<span class="fail">only letters a-z accepted</span>\');';
		$valid = $valid && false;	
	  }
	  else {	
	  }

      if(strlen($username) > 4 ) {
	    $valid = $valid && true;
      }
      else {
	    $response_data .= '$("#username").css({\'background-color\':\'#FFB6C1\'});';
	    $response_data .= '$("#username_validation").html(\'<span class="fail">must be more than 4 characters long</span>\');';
	    $valid = $valid && false;
      }

      if(strlen($pass) > 4 ) {
	    $valid = $valid && true;
      }
      else {
	    $response_data .= '$("#your_pass").css({\'background-color\':\'#FFB6C1\'});';
	    $response_data .= '$("#pass_validation").html(\'<span class="fail">must be more than 4 characters long</span>\');';
	    $valid = $valid && false;
      }
	
	  if($pass == $pass_again) {
	    $valid = $valid && true;	
	  }
	  else {
	    $response_data .= '$("#your_pass").css({\'background-color\':\'#FFB6C1\'});';
	    $response_data .= '$("#pass_again_validation").html(\'<span class="fail">your passwords did not match</span>\');';
	    $valid = $valid && false;	
	  }
	
	  if($understand_terms == 'on') {
	    $valid = $valid && true;	
	  }
	  else {
	    $response_data .= '$("#terms_acceptance").css({\'font-weight\':\'bold\',\'font-size\':\'150%\', \'color\':\'#FF0000\'});';
	    $valid = $valid && false;
	  }
	
	  if ($valid) {
		
		  $record = array(
			'givenname' => $person_givenname,
			'surname' => $person_name,
			'email' => $email_addy,
			'username' => $username,
			'password' => md5($pass),
			'title' => $title,
			'url_requested' => $url_req,
			'md5' => $md5,
			'status' => 0,
			'created' => $now,
		  ); 
		
	      $db->query_insert("request_experts", $record);

              $response_data .= '$("#lifedesk_response_overlay").fadeIn();';
              $response_data .= 'var message = $("#lifedesk_response_message");';
              $response_data .= 'message.fadeIn("slow").html("<div id=\"expert_response_message\"><p>Thanks for your interest in LifeDesk ' . $person_givenname . '.<br />Please check your email to activate your new site,<br /><em>' . $title . '</em></p><div class=\"submit\"><input id=\"lifedesk_response_button\" type=\"image\" value=\"close\" src=\"/images/btn_close.gif\" onclick=\"close_message();\" /></div></div>")';

              require '../conf/phpmailer/class.phpmailer.php';
		
		  	  $subject = 'LifeDesk Activation';
			  $EMAIL_CONTENT = 'Hello ' . $person_givenname . ' ' . $person_name . ',' . "\n\n" ;
			  $EMAIL_CONTENT .= 'Thank you for your interest in LifeDesks. You are one step away from creating your new site, "' . $title . '" that will be located at http://' . $url_req . '.' . $host . '. Click the following link or copy/paste it into your browser\'s address bar:' . "\n\n";
		      $EMAIL_CONTENT .= BASE_URL . '/create/expert/?q=' . $md5 . "\n\n";
		      $EMAIL_CONTENT .= 'To access your new site, you may use the credentials you chose earlier:' . "\n\n";
              $EMAIL_CONTENT .= 'Username: ' . $username . "\n\n";
		      $EMAIL_CONTENT .= 'Once your site has been activated you may visit http://help.lifedesks.org/quickstart for help on getting started.' . "\n\n";
		      $EMAIL_CONTENT .= 'Thanks again,' . "\n\n";
		      $EMAIL_CONTENT .= 'The LifeDesks Team';

			try {
				$mail = new PHPMailer(true); //New instance, with exceptions enabled
				$body             = $EMAIL_CONTENT;
				$mail->IsSMTP();                           // tell the class to use SMTP
				$mail->SMTPAuth   = false;
				$mail->Port       = 25;                    // set the SMTP server port
				$mail->Host       = SMTP_SERVER;        // SMTP server
				$mail->From       = "lifedesk@eol.org";
				$mail->FromName   = "The LifeDesks Team";
				$mail->AddAddress($email_addy);
				$mail->Subject  = $subject;
				$mail->WordWrap   = 80;                    // set word wrap
				$mail->Body = $body;
				$mail->IsHTML(false);
				$mail->Send();
			} 
			catch (phpmailerException $e) {
				$sent_mail = false;
				error_log($e->errorMessage());
			}
	  }

  }

}
else {
	$title = '';
	$person_givenname = '';
	$person_name = '';
	$url_req = '';
	$email_addy = '';
	$username = '';
	$understand_terms = '';
	
	$cit_person_name = '';
	$cit_email_addy = '';
	$cit_message = '';
	$resp = '';
}

function check_email_address($email) {	
	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
		return true;
	}
	return false;
}

?>
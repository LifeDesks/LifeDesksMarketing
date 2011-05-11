<?php
require_once('recaptchalib.php');
require_once('contact.inc.php');
require_once('../conf/vars.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Contact Us | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
<script type="text/javascript" src="/js/lifedeskcontact.js"></script>
<script type="text/javascript">$(function(){<?php print $response_data;?>});</script>
<script type="text/javascript">jQuery.extend(LifeDesks.settings, {"baseUrl" : "<?php print BASE_URL ?>"});</script>
</head>

<body>

<div id="wrapper">
	<div id="container">

        <?php echo $VARS_BANNER; ?>
		
		<!-- content -->
			<div id="content" class="subpage">
				
				<div id="contact_us_text">
					<h2>Contact Us</h2>

						<span id="form_response"> </span>
						<form id="contact_form" action="" method="post">
							<ol>
								<li>
									<label for="">Name<span>*</span><small><i>ex.</i> John Smith</small></label>
									<input type="text" name="contact_name" id="contact_name" class="contactinput input1" value="<?php isset($_POST["contact_name"]) ? print($_POST["contact_name"]) : '' ?>" />
									<span id="name_validation" class="real-time-validation2"> </span>
								</li>
								<li>
									<label for="">Email Address<span>*</span></label>
									<input type="text" name="contact_email_addy" id="contact_email_addy" class="contactinput input1 <?php isset($invalid_email) ? print("error") : "" ?>" value="<?php isset($_POST["contact_email_addy"]) ? print($_POST["contact_email_addy"]) : ''  ?>"/>
									<span id="email_validation" class="real-time-validation2"> </span>
								</li>
								<li>
									<label for="">Message<span>*</span></label>
									<textarea id="contact_message" class="contactinput textarea1" name="contact_message" maxlength="300"><?php isset($_POST['contact_message']) ? print($_POST['contact_message']) : '' ?></textarea>
									<span id="message_validation" class="real-time-validation2"> </span>
								</li>
							</ol>
							<div class="recaptcha">
								<?php echo recaptcha_get_html($publickey,$error); ?>
							</div>
							<div class="clear"></div>
							<input type="hidden" name="request_type" value="contact"></input>
							<div class="contact_submit submit"><input type="image" value="submit" src="../images/btn_submit.gif"/></div>
						</form>
				</div>
				<img src="../images/bluebox_bottom_bg.gif" alt="" /><br  /><br />
			</div><!-- /content -->
			
			<div id="sidebar">
				<div class="sidebanner"><span>Tell a friend about LifeDesks</span></div>
				<div class="sidecontent">
				<?php echo $VARS_TELLFRIEND; ?>
				</div><!-- /sidecontent -->
			</div><!-- /sidebar -->
			
	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->
<?php echo $VARS_NAVBAR . "\n"; ?>
<?php echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>

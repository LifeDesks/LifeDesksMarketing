<?php
require_once('recaptchalib.php');
require_once('create.inc.php');
require_once('../conf/vars.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Create a LifeDesk | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
<script type="text/javascript" charset="utf-8" src="/js/lifedeskcreate.js"></script>
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
                <h2>Create a LifeDesk</h2>
                <div class="announcement">
	              New development on LifeDesks officially ceased August 1, 2010. Bugs are fixed as needed.
	            </div>
<?php 
  $admin_is_accessible = file(ADMIN_URL . "/check_sitename/eolspecies");
  if ($admin_is_accessible === FALSE) {
?>
<div class="error">Sorry, LifeDesks creation is unavailable at this time due to technical issues, please try again later.</div>
<?php } ?>
				<form id="create_expert_lifedesk" action="" method="post" <?php if ($admin_is_accessible === FALSE) { ?>style="display:none;"<?php } ?>>
				  <input type="hidden" name="formType" id="formType" value="expert" />
                  <ol>
	                <li>
					  <label for="">Title of your site<span>*</span><small><i>ex.</i> iBeetles, eMagnolia</small></label>
					  <input type="text" name="ld_title" id="ld_title" class="createinput input1" value="<?php print $title; ?>" />
					  <span id="title_validation" class="real-time-validation"> </span>
					</li>
	                <li>
					  <label for="">Possible taxa<small><i>ex.</i> Carabidae, Aotus</small></label>
					  <input type="text" name="ld_taxa" id="ld_taxa" class="createinput input1" value="" />
					  <span id="throbber"></span>
					  <div id="ld_taxa_found"><p>Sites that might be of interest:</p><div class="exarrowl"><a class="side-prev" href="#"><img src="../images/arrow2_left.gif" alt="" /></a></div><div class="sites" id="ld_sites"></div><div class="exarrowl"><a class="side-next" href="#"><img src="../images/arrow2_right.gif" alt="" /></a></div></div>
					</li>
					<li>
					<label for="">Site URL<span>*</span></label>
					<input type="text" name="url_req" id="url_req"  class="createinput input2" value="<?php print $url_req; ?>"/>
					<label class="fixed-input">.lifedesks.org</label>
					<span id="key_validation" class="real-time-validation"> </span>
				</li>
				<li>					
					<label for="">Given name<span>*</span><small><i>ex.</i> John</small></label>
					<input type="text" name="person_givenname" id="person_givenname" class="createinput input1" value="<?php print $person_givenname; ?>" />
					<span id="person_givenname_validation" class="real-time-validation"> </span>
				</li>
				<li>												
					<label for="">Surname<span>*</span><small><i>ex.</i> Smith</small></label>
					<input type="text" name="person_name" id="person_name" class="createinput input1" value="<?php print $person_name; ?>" />
					<span id="person_name_validation" class="real-time-validation"> </span>
				</li>
				<li>					
					<label for="">Email Address<span>*</span></label>
					<input type="text" name="email_addy" id="email_addy" class="createinput input2" value="<?php print $email_addy; ?>" />
					<span id="email_validation" class="real-time-validation"> </span>
				</li>
				<li>					
					<label for="">Username<span>*</span></label>
					<input type="text" name="username" id="username" class="createinput input2" value="<?php print $username; ?>" />
					<span id="username_validation" class="real-time-validation"> </span>
				</li>
				<li>					
					<label for="">Password <span>*</span></label>
					<input type="password" name="your_pass" id="your_pass" class="createinput input2" />
					<span id="pass_validation" class="real-time-validation"> </span>
				</li>
				<li>
					<label for="">Verify Password <span>*</span></label>
					<input type="password" name="your_pass_again" id="your_pass_again" class="createinput input2" />
					<span id="pass_again_validation" class="real-time-validation"> </span>
				</li>
				<li>
					 <div id="terms">					
					<p><strong>Terms</strong></p>
          <div id="scrollTerms">
            <dl id="termsList">
			<dt>LifeDesk</dt>
			<dd>The term 'LifeDesk' represents a publicly accessible web site. Your LifeDesk will be a discrete implementation of a Content Management System provided by the Encyclopedia of Life.</dd>
			<dt>Agreement</dt>
			<dd>This agreement is between the <a href="http://www.eol.org">Encyclopedia of Life</a> and its agents (collectively &ldquo;EOL&rdquo;), and you and your agents (collectively &ldquo;you&rdquo;) regarding the use of your LifeDesk (the "Site"). By using the Site, you agree to the Terms and Conditions in this document.</dd>
			<dt>Ownership of Site</dt>
			<dd>The text, graphics, sound and software (collectively "Content"; i.e. material that you have up-loaded) on this Site is owned by you and your agents and you bear sole and ultimate responsibility for this Content. EOL supports the computer hardware infrastructure and software content management system that provides access to this Content</dd>
			<dt>Access to Services and Termination of Access</dt>
			<dd>You are responsible for all activity logged through your user account and for the activity of other persons or entities you grant access to this Site. You agree to notify EOL immediately you become aware of any unauthorized use. EOL may terminate your access privileges, remove Content or close down the site without notice if EOL believes you have violated any provision of this Agreement. You agree that termination of your access to the Site shall not result in any liability or other obligation of EOL to you or any third party in connection with such termination.</dd>
			<dt>Purpose of Site</dt>
			<dd>The Site is made available to you to foster collaborative research in taxonomy and to make information available for re-use through a public domain or Creative Commons (attribution) license with or without a non-commercial clause and with or without a share-alike clause.  EOL expects to be able to harvest the content for inclusion within EOL where the author, source (your LifeDesk) and license agreement will be displayed. The Site must not restrict membership to any single geographical area. Areas of the Site may be restricted to nominated parts of your membership (&ldquo;private areas&rdquo;) at your discretion. You agree to manage requests for membership and to allocate appropriate access privileges.</dd>
			<dt>Content</dt>
			<dd>All Content placed on the Site must be legal, decent and truthful. Through your use of the Site you represent and warrant that you have all the rights necessary to receive, use, transmit and disclose all data that you use in any way with the Site. You agree and acknowledge that you are solely responsible for any liabilities, fines, or penalties occasioned by any such violations or lack of rights and that you are solely responsible for the accuracy and adequacy of information and data furnished on the Site. EOL will, as part of the provision of the Site, implement a periodic archive of the Content that can be made available to you on request. You understand and acknowledge that EOL assumes no responsibility to screen or review Content and that EOL shall have the right, but not the obligation, in its sole discretion to review, refuse, monitor, edit or remove any Content. EOL expressly disclaims all responsibility or liability to you or any other person or entity for the Content and you acknowledge and agree that you assume all risk associated with the use of any and all Content. All information currently in the public domain will remain in the public domain. Neither the EOL nor the LifeDesk owner will seek to assert any intellectual property rights over any public domain materials that are made available through the EOL.</dd>
			<dt>Content Partnership With EOL</dt>
			<dd>In providing your content to EOL via a LifeDesk, you will: provide content via a format and interchange method compatible with the EOL, facilitated by the LifeDesk; provide data objects as-is with no warranty or claim of fitness for purpose; respond to the originators of comments or feedback communicated to you via the EOL regarding its content; work with the EOL to improve the quality of the provided data objects and reduce errors. In handling the content provided by your LifeDesk, EOL will provide attribution information for all content that it serves. EOL will also indicate the Creative Commons license attached to each object (text, structured data, graphics, multimedia, <em>etc.</em>). The specific style and techniques employed for display of attribution for data objects may change as the EOL evolves. EOL will present or pass along your data objects to others using web services (such as APIs) which will carry the same Creative Commons licenses and specified manner of attribution. EOL will refer all requests for re-usage of your content to you. EOL will not alter the data objects provided; however EOL will enable annotations or comments to be added. EOL will notify you of any comments or feedback received about your data objects and work with you to improve the quality of the provided data objects and reduce errors. EOL will notify you about statistics about the usage of its data objects. EOL will show your logo and project description on its list of data partners. EOL will post on its  site a statement designating an agent for receiving notice of any content alleged to violate copyright or other rights. EOL may elect at its sole discretion to present all, portions or none of any data objects provided by your LifeDesk and reserves the right to redact or remove any illegal, offensive, or objectionable content. The parties do not intend to create a legal partnership, principal/agent, or joint venture relationship and nothing in this agreement shall be construed as creating such a relationship between the parties. Neither party may incur any obligation on behalf of the other. This agreement is non-exclusive, and in no way restricts either EOL or you from participating in similar activities or arrangements with other public or private initiatives, organizations, or individuals. This agreement and the obligations set forth herein shall be subject to available funding.
			</dd>
			<dt>Disclaimer of Warranties</dt>
			<dd>The use of the Site is solely at your own risk. The site is provided on an "as is" and "as available" basis and EOL expressly disclaims all warranties of any kind with respect to the site, whether express or implied. EOL makes no warranty that the access to the site and/or Content therein will be uninterrupted or secure. Your remedy with respect to any defect in or unresolved dissatisfaction with the Site is to cease using the Site.</dd>
			<dt>Limitation of Liability</dt>
			<dd>You understand and agree that EOL shall not be liable for any direct, indirect, incidental, special, consequential, or exemplary damages resulting from any matter related to you or other persons use of the site.</dd>
			</dl>

          </div></div>
		</li>
		<li>					
				  <div id="terms_acceptance">
				  	<input type="checkbox" name="understand_terms" id="understand_terms" class="input3" <?php if($understand_terms) {print 'checked';} ?> /> I read and I accept the Terms<span>*</span> 
				  </div>
				</li>
				<li>
					<div class="clear"></div>
					<div class="submit"><input type="image" src="/images/btn_create_my_site.gif" /></div>
				</li>
				</ol>
				</form>
			  </div>
			
			  <img src="../images/bluebox_bottom_bg.gif" alt="" /><br  /><br />
			
			</div> <!-- /content -->

			<div id="sidebar">
				<div class="sidebanner"><span>Tell a friend about LifeDesks</span></div>
				<div class="sidecontent">
					<?php echo $VARS_TELLFRIEND; ?>
				</div><!-- /sidecontent -->
			</div><!-- /sidebar -->

	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->
<?php echo $VARS_NAVBAR; ?>
<?php echo $VARS_ANALYTICS; ?>
</body>
</html>

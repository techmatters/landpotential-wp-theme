<?php
/**
 * The template for displaying inline newsletter forms.
 *
 * @package LandPKS
 * @since   1.0.0
 */

?>
<form id="inline-subscribe" class="newsletter-subscribe" method="post">
	<div class="form-fields">
		<div class="signup-input">
			<label class="screen-reader">First Name</label><input type="text" name="first_name" placeholder="First name">
		</div>
		<div class="signup-input">
			<label class="screen-reader">Last Name</label><input type="text" name="last_name" placeholder="Last name">
		</div>
		<div class="signup-input">
			<label class="screen-reader">Email address</label><input type="email" name="email" placeholder="Email address *" required="required">
		</div>
		<div class="signup-input">
			<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
			<input type="submit" class="et_pb_button signup g-recaptcha" value="Get Updates" />
		</div>
	</div>
	<div class="et_pb_bg_layout_dark message"></div>
</form>

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
			<label class="screen-reader">Email address</label>
			<input type="email" name="email" placeholder="Email address" required="required">
		</div>
		<div class="signup-input">
			<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
			<input type="submit" class="signup g-recaptcha" value="Subscribe" />
		</div>
	</div>
	<div class="message"></div>
</form>

<?php
/**
 * The template for displaying inline newsletter forms.
 *
 * @package LandPKS
 * @since   1.0.0
 */

?>
<form id="inline-subscribe" class="newsletter-subscribe email-only" method="post">
	<div class="form-fields">
		<label for="inline-subscribe-email">Email address:</label>
		<input id="inline-subscribe-email" type="email" name="email" placeholder="email@example.com" required="required">
	</div>
	<div class="signup-input">
		<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
		<input type="submit" class="signup g-recaptcha" value="Subscribe" />
	</div>
	<div class="message"></div>
</form>

<?php
/**
 * The template for displaying inline newsletter forms.
 *
 * @package LandPKS
 * @since   1.0.0
 */

?>
<form id="inline-subscribe" class="newsletter-subscribe" method="post">
	<div class="mc4wp-form-fields">
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
			<?php wp_nonce_field( Newsletter::FIELD_NAME, Newsletter::NONCE_KEY ); ?>

			<input id="recaptcha-token" type="hidden" name="token" />
			<input type="submit" class="et_pb_button signup g-recaptcha" value="Get Updates" data-sitekey="<?php echo esc_attr( Google_Recaptcha::get_site_key() ); ?>" data-callback='lpksSubmitSubscribeForm' data-action='submit' />
		</div>
	</div>
</form>

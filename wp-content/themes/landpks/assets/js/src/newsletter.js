window.lpks = {
	gcaptchaHandler: function() {
		grecaptcha.execute( localize.sitekey, {action: 'validate_captcha'} ).then( function( token ) {
			document.getElementById( 'g-recaptcha-response' ).value = token;
		} );
	},

	processSubscription: function( event ) {
		event.preventDefault();

		jQuery.ajax( {
			type: 'POST',
			url: localize._ajax_url,
			data: {
				_ajax_nonce: localize._ajax_nonce,
				action: 'newsletter_subscribe',
				token: document.getElementById( 'g-recaptcha-response' ).value,
				email: event.target.querySelector( 'input[name="email"]' ).value,
				first_name: event.target.querySelector( 'input[name="first_name"]' ).value,
				last_name: event.target.querySelector( 'input[name="last_name"]' ).value
			},
			success: ( res ) => {
				if ( true === res.success ) {
					jQuery( event.target.querySelector( '.form-fields' ) ).slideUp();
					event.target.querySelector( '.message' ).textContent = localize.success;
				} else {
					let errorMessage = localize.error_codes[res?.data?.error_code];
					if ( 4 === res?.data?.error_code ) { // 4 is a JSON parsing error.
						errorMessage += ` (${res?.data?.error_message})`;
					}
					event.target.querySelector( '.message' ).textContent = errorMessage;

					// Reset the CAPTCHA after a failure.
					window.lpks.gcaptchaHandler();
				}
			}
		} );
	},

	setup: function() {
		const subscribeForm = document.getElementById( 'inline-subscribe' );

		if ( subscribeForm ) {
			subscribeForm.addEventListener( 'submit', window.lpks.processSubscription );
			grecaptcha.ready( window.lpks.gcaptchaHandler );
		}
	}
};

document.addEventListener( 'DOMContentLoaded', window.lpks.setup );

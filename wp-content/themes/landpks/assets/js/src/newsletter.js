window.lpks = {
	submitSubscribeForm: function( token ) {
		console.log( '...submitSubscribeForm()' );

		const tokenField = document.getElementById( 'recaptcha-token' );
		const subscribeForm = document.getElementById( 'inline-subscribe' );
		if ( tokenField ) {
			tokenField.value = token;
		}
		if ( subscribeForm ) {
			console.log( 'submitting underlying item' );
			subscribeForm.submit();
		}
	},

	handleSubmitEvent: function( event ) {
		event.preventDefault();
		jQuery.ajax( {
			type: 'POST',
			url: localize._ajax_url,
			data: {
				_ajax_nonce: localize._ajax_nonce,
				action: 'newsletter_subscribe',
				email: event.target.querySelector( 'input[name="email"]' ).value,
				first_name: event.target.querySelector( 'input[name="first_name"]' ).value,
				last_name: event.target.querySelector( 'input[name="last_name"]' ).value
			},
			success: ( res ) => {
				if ( true === res.success ) {
					jQuery( event.target.querySelector( '.form-fields' ) ).slideUp();
					event.target.querySelector( '.message' ).textContent = localize.success;
				} else {
					let errorMessage = localize.error_codes[res.data.error_code];
					if ( 4 === res.data.error_code ) {
						errorMessage += ` (${res.data.error_message})`;
					}
					event.target.querySelector( '.message' ).textContent = errorMessage;
				}
			}
		} );
	},

	setup: function() {
		console.log( 'setting up...' );
		const subscribeForm = document.getElementById( 'inline-subscribe' );

		if ( subscribeForm ) {
			console.log( 'hooked' );
			window.lpksSubmitSubscribeForm = window.lpks.submitSubscribeForm; // ReCAPTCHA callback needs to be in the global namespace
			subscribeForm.addEventListener( 'submit', window.lpks.handleSubmitEvent );
		}
	}
};

document.addEventListener( 'DOMContentLoaded', window.lpks.setup );
console.log( 'newsletter.js start' );

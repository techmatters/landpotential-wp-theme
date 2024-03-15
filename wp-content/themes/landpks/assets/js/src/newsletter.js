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

	setup: function() {
		console.log( 'setting up...' );
		const subscribeForm = document.getElementById( 'inline-subscribe' );

		if ( subscribeForm ) {
			console.log( 'hooked' );
			window.lpksSubmitSubscribeForm = window.lpks.submitSubscribeForm; // ReCAPTCHA callback needs to be in the global namespace
		}
	}
};

document.addEventListener( 'DOMContentLoaded', window.lpks.setup );
console.log( 'newsletter.js start' );

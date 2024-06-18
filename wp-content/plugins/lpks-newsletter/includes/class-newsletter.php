<?php
/**
 * Newsletter forms for LandPKS
 *
 * @package LandPKS
 * @since   1.0.0
 */

/**
 * Holds methods for Newsletter
 * Class Newsletter
 */
class Newsletter {

	const BASE_URL = 'https://api.hubapi.com';

	const SUBSCRIBE_SUCCESS        = 1;
	const SUBSCRIBE_ERROR_EXISTING = 2;
	const SUBSCRIBE_ERROR_INVALID  = 3;
	const JSON_ERROR               = 4;
	const NO_CONTACT_FOUND         = 5;
	const MISSING_EMAIL            = 6;
	const RECAPTCHA_MISSING        = 7;
	const RECAPTCHA_FAILED         = 8;
	const NONCE_FAILED             = 9;


	const FIELD_NAME = 'newsletter_form';
	const NONCE_KEY  = 'newsletter_form_nonce';

	/**
	 * Add actions and filters.
	 */
	public static function hooks() {
		add_shortcode( 'newsletter-inline-form', [ __CLASS__, 'render_inline_form' ] );
		add_shortcode( 'newsletter-email-form', [ __CLASS__, 'render_email_form' ] );
		add_action( 'wp_ajax_nopriv_newsletter_subscribe', [ __CLASS__, 'newsletter_subscribe' ] );
		add_action( 'wp_ajax_newsletter_subscribe', [ __CLASS__, 'newsletter_subscribe' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public static function enqueue_scripts() {
		$ext = defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? 'src' : 'min';

		wp_enqueue_script(
			'newsletter',
			plugins_url( "/assets/js/newsletter.{$ext}.js", __DIR__ ),
			[ 'jquery' ],
			LPKS_PLUGIN_VERSION,
			false
		);

		wp_enqueue_style(
			'newsletter',
			plugins_url( "/assets/css/newsletter.{$ext}.css", __DIR__ ),
			[],
			LPKS_PLUGIN_VERSION
		);

		wp_localize_script(
			'newsletter',
			'localize',
			[
				'_ajax_url'   => admin_url( 'admin-ajax.php' ),
				'_ajax_nonce' => wp_create_nonce( self::FIELD_NAME ),
				'sitekey'     => Google_Recaptcha::get_site_key(),
				'success'     => 'Thank you for subscribing to the LandPKS newsletter.',
				'error_codes' => [
					self::SUBSCRIBE_ERROR_EXISTING => 'You’re already subscribed to the LandPKS newsletter.',
					self::SUBSCRIBE_ERROR_INVALID  => 'Please supply a valid email address',
					self::JSON_ERROR               => 'Unable to parse JSON result.',
					self::NO_CONTACT_FOUND         => 'Unable to find valid HubSpot contact ID.',
					self::MISSING_EMAIL            => 'Please supply an email address',
					self::RECAPTCHA_MISSING        => 'The ReCATCHA token was missing.',
					self::RECAPTCHA_FAILED         => 'ReCAPTCHA could not validate you are a human.',
					self::NONCE_FAILED             => 'WordPress could not validate you are a human.',
				],
			]
		);
	}

	/**
	 * Generate headers needed by HubSpot API.
	 *
	 * @throws Exception If no API token found.
	 * @return array     Array of Authorization and Content-Type headers.
	 */
	private static function headers() {
		$token = get_option( 'lpks_hubspot_token' );
		if ( ! $token ) {
			throw new Exception( 'Missing HubSpot API token.' );
		}
		return [
			'Authorization' => "Bearer {$token}",
			'Content-Type'  => 'application/json',
		];
	}

	/**
	 * Get HubSpot list id for this newsletter.
	 *
	 * @throws Exception If no API token found.
	 * @return int       HubSpot list identifier.
	 */
	private static function list_id() {
		$list_id = intval( get_option( 'lpks_hubspot_list_id' ) );
		if ( ! $list_id ) {
			throw new Exception( 'Missing HubSpot list ID.' );
		}

		return $list_id;
	}

	/**
	 * Subscribe the user to a Hubspot list. This is done in two steps:
	 * 1. Attempt to create a Hubspot Contact for this email address. If it already exists,
	 *    this call will fail. That is fine.
	 * 2. Attempt to subscribe the user to a list.
	 *
	 * You cannot do this ina. single API call, i.e. call the add-to-list call directly. If you do this
	 * and the email address is not in HubSpot's database, it will fail.
	 *
	 * Note that (1) uses v3 of Hubspot's API and (2) uses v1. As of 2024-03-15, there is
	 * no subscribe call in the v3 API.
	 *
	 * @param string $email              User's email address.
	 * @param string $first_name         User's first name.
	 * @param string $last_name          User's last name.
	 */
	public static function subscribe( $email, $first_name = false, $last_name = false ) {
		if ( ! $email ) {
			return self::MISSING_EMAIL;
		}

		$properties = [ 'email' => $email ];
		if ( $first_name ) {
			$properties['firstname'] = $first_name;
		}
		if ( $last_name ) {
			$properties['lastname'] = $last_name;
		}
		$data = [ 'properties' => $properties ];

		$raw_result = wp_remote_post(
			self::BASE_URL . '/crm/v3/objects/contacts',
			[
				'headers' => self::headers(),
				'body'    => wp_json_encode( $data ),
			]
		);

		$result = json_decode( $raw_result['body'], true );
		if ( ! $result ) {
			return self::JSON_ERROR;
		}
		if ( isset( $result['status'] ) && 'error' !== $result['status'] ) {
			$id = intval( $result['id'] );
			if ( ! $id ) {
				return self::NO_CONTACT_FOUND;
			}
		}

		$data       = [ 'emails' => [ $email ] ];
		$raw_result = wp_remote_post(
			self::BASE_URL . '/contacts/v1/lists/' . self::list_id() . '/add',
			[
				'headers' => self::headers(),
				'body'    => wp_json_encode( $data ),
			]
		);

		$result = json_decode( $raw_result['body'], true );
		if ( ! $result ) {
			return self::JSON_ERROR;
		}

		if ( $result['updated'] ) {
			return self::SUBSCRIBE_SUCCESS;
		} elseif ( $result['discarded'] ) {
			return self::SUBSCRIBE_ERROR_EXISTING;
		} elseif ( $result['invalidEmails'] ) {
			return self::SUBSCRIBE_ERROR_INVALID;
		}
	}

	/**
	 * AJAX action handler for newsletter subscription action.
	 */
	public static function newsletter_subscribe() {
		if ( ! check_ajax_referer( self::FIELD_NAME, self::NONCE_KEY, true ) ) {
			wp_send_json_error( [ 'error_code' => self::NONCE_FAILED ] );
		}

		if ( Google_Recaptcha::is_configured() ) {
			$token = empty( $_POST['token'] ) ?: trim( sanitize_text_field( wp_unslash( $_POST['token'] ) ) );
			if ( ! $token ) {
				wp_send_json_error( [ 'error_code' => self::RECAPTCHA_MISSING ] );
			}
			$ip_address = filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP );

			$recaptcha_result = Google_Recaptcha::verify( $token, $ip_address );
			if ( ! $recaptcha_result ) {
				wp_send_json_error( [ 'error_code' => self::RECAPTCHA_FAILED ] );
			}
		}

		$email      = empty( $_POST['email'] ) ?: trim( sanitize_email( wp_unslash( $_POST['email'] ) ) );
		$first_name = empty( $_POST['first_name'] ) ?: trim( sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) );
		$last_name  = empty( $_POST['last_name'] ) ?: trim( sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) );

		$result = self::subscribe( $email, $first_name, $last_name );
		if ( self::SUBSCRIBE_SUCCESS === $result ) {
			wp_send_json_success();
		}

		$data = [ 'error_code' => $result ];
		if ( self::JSON_ERROR === $result ) {
			$data['error_message'] = json_last_error();
		}

		wp_send_json_error( $data );
	}

	/**
	 * Get the template part in an output buffer and return it
	 *
	 * @param string $template_name Template file name.
	 */
	public static function get_template_part( $template_name ) {
		$located = dirname( __DIR__ ) . '/' . $template_name;

		ob_start();
		require $located; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		return ob_get_clean();
	}

	/**
	 * Render inline hubspot form.
	 *
	 * @return string
	 */
	public static function render_inline_form() {
		return self::get_template_part( 'template-parts/form-inline.php' );
	}

	/**
	 * Render email-only hubspot form.
	 *
	 * @return string
	 */
	public static function render_email_form() {
		return self::get_template_part( 'template-parts/form-email.php' );
	}
}

add_action( 'after_setup_theme', [ 'Newsletter', 'hooks' ] );

<?php
/**
 * Newsletter forms for LandPKS
 *
 * @package LandPKS
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

	/**
	 * Add actions and filters.
	 */
	public static function hooks() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
	}

	/**
	 * Generate headers needed by HubSpot API.
	 *
	 * @throws Exception If no API token found.
	 * @return array  Array of Authorization and Content-Type headers.
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
	 * @return in   HubSpot list identifier.
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
			return;
		}

		$properties = [ 'email' => filter_var( trim( $email ), FILTER_SANITIZE_EMAIL ) ];
		if ( $first_name ) {
			$properties['firstname'] = sanitize_text_field( trim( $first_name ) );
		}
		if ( $last_name ) {
			$properties['lastname'] = sanitize_text_field( trim( $last_name ) );
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
			error_log( json_last_error() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			return self::JSON_ERROR;
		}
		if ( 'error' !== $result['status'] ) {
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
			error_log( json_last_error() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
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
}

add_action( 'after_setup_theme', [ 'Newsletter', 'hooks' ] );

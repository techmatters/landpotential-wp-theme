<?php
/**
 * Additional styles for LandPKS
 *
 * @package LandPKS
 * @since   1.0.0
 */

/**
 * Holds methods for Styles
 * Class Styles
 */
class Styles {

	/**
	 * Add actions and filters.
	 */
	public static function hooks() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public static function enqueue_scripts() {
		$ext = defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? 'src' : 'min';

		wp_enqueue_script(
			'newsletter',
			plugins_url( "/assets/js/main.{$ext}.js", __DIR__ ),
			[ 'jquery' ],
			LPKS_PLUGIN_VERSION,
			false
		);

		wp_enqueue_style(
			'newsletter',
			plugins_url( "/assets/css/main.{$ext}.css", __DIR__ ),
			[],
			LPKS_PLUGIN_VERSION
		);
	}
}

add_action( 'after_setup_theme', [ 'Styles', 'hooks' ] );

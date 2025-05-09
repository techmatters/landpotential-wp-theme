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
		
		// GeneratePress does not have these tools enabled by default, since it is not a block theme.
		add_theme_support( 'appearance-tools' );
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public static function enqueue_scripts() {
		$ext = defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? 'src' : 'min';

		wp_enqueue_script(
			'styles',
			plugins_url( "/assets/js/main.{$ext}.js", __DIR__ ),
			[ 'jquery' ],
			LPKS_STYLES_PLUGIN_VERSION,
			false
		);

		wp_enqueue_style(
			'styles',
			plugins_url( "/assets/css/main.{$ext}.css", __DIR__ ),
			[],
			LPKS_STYLES_PLUGIN_VERSION
		);
	}
}

add_action( 'after_setup_theme', [ 'Styles', 'hooks' ] );

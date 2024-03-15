<?php
/**
 * Terraso site core functions.
 *
 * @package LandPKS
 * @since   1.0.0
 */

/**
 * Holds methods for LandPotential site.
 * Class LandPKS
 */
class LandPKS {

	/**
	 * Add actions and filters.
	 */
	public static function hooks() {
		add_action( 'intermediate_image_sizes_advanced', [ __CLASS__, 'remove_extra_image_sizes' ] );
		add_filter( 'et_project_posttype_args', [ __CLASS__, 'project_posttype_args' ], 10, 1 );
	}

	/**
	 * Divi adjustments. Hide internal project post type.
	 *
	 * @param array $args              Array of post type arguments.
	 */
	public static function project_posttype_args( $args ) {
		return array_merge(
			$args,
			[
				'public'              => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'show_in_nav_menus'   => false,
				'show_ui'             => false,
			]
		);
	}

	/**
	 * Remove unnecessary Divi image sizes.
	 *
	 * @param array $sizes              Array of image sizes.
	 */
	public static function remove_extra_image_sizes( $sizes ) {
		unset( $sizes['et-pb-portfolio-image'] );
		unset( $sizes['et-pb-portfolio-module-image'] );
		unset( $sizes['et-pb-portfolio-image-single'] );
		unset( $sizes['et-pb-gallery-module-image-portrait'] );
		unset( $sizes['et-pb-gallery-module-image-portrait'] );
		unset( $sizes['et-pb-gallery-module-image-portrait'] );
		return $sizes;
	}
}

add_action( 'after_setup_theme', [ 'LandPKS', 'hooks' ] );

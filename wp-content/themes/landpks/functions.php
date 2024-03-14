<?php


// Divi adjustments
// hide - not removing - the internal project post type.
add_filter( 'et_project_posttype_args', 'mytheme_et_project_posttype_args', 10, 1 );
function mytheme_et_project_posttype_args( $args ) {
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

// Remove unnecessary Divi image sizes 
add_action( 'intermediate_image_sizes_advanced', 'cp_remove_extra_image_sizes' );
function cp_remove_extra_image_sizes( $sizes ) {
	unset( $sizes['et-pb-portfolio-image'] );
	unset( $sizes['et-pb-portfolio-module-image'] );
	unset( $sizes['et-pb-portfolio-image-single'] );
	unset( $sizes['et-pb-gallery-module-image-portrait'] );
	unset( $sizes['et-pb-gallery-module-image-portrait'] );
	unset( $sizes['et-pb-gallery-module-image-portrait'] );
	return $sizes;
}

<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package LandPKS
 * @since   1.0.0
 */

get_header();
?>
<div class="entry-content not-found">
	<?php echo do_shortcode( '[et_pb_section global_module="13972"][/et_pb_section]' ); ?>
</div>
<div id="main-content">
	<div class="et_pb_section">
		<div class="et_pb_row clearfix">
				<div class=" ">
				<article id="post-0" <?php post_class( 'et_pb_post not_found' ); ?>>
					<h6><?php esc_html_e( 'Page Not Found', 'Divi' ); ?></h6>
					<p><?php esc_html_e( 'It looks like the page you were looking for doesn\'t exist. Please use the menu above to find what you are looking for.', 'Divi' ); ?></p>
				</article> <!-- .et_pb_post -->
			</div>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<div class="entry-content">
	<?php echo do_shortcode( '[et_pb_section global_module="1059"][/et_pb_section]' ); ?>
	<?php echo do_shortcode( '[et_pb_section global_module="502"][/et_pb_section]' ); ?>
	<?php echo do_shortcode( '[et_pb_section global_module="503"][/et_pb_section]' ); ?>
</div>

<?php get_footer(); ?>

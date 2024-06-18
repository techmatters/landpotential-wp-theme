<?php
/**
 * The template for archive pages
 *
 * @package LandPKS
 * @since   1.0.0
 */

get_header();
?>

<div id="main-content"><!-- archive -->

		<div class="entry-content">
<?php
while ( have_posts() ) :
	the_post();
	?>


		<?php
			the_content();
		?>


<?php endwhile; ?>
		</div> <!-- .entry-content -->

</div> <!-- #main-content -->

<?php get_footer(); ?>

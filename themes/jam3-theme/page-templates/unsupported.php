<?php
/**
 * Template Name: Unsupported Page
 *
 * @package backend
 */

?>
<?php get_header( 'unsupported' ); ?>
<div class="container">
	<div class="message">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php the_content(); ?>

	<?php endwhile; endif; ?>
	</div>
</div>
<?php get_footer( 'unsupported' ); ?>

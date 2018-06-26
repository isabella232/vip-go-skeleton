<?php
/**
 * Header page
 *
 * Partial template for the index.php
 *
 * @access public
 * @package backend
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

	<head>
		<?php get_template_part( 'template-parts/header/header', 'meta' ); ?>
		<title><?php wp_title( '', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php get_template_part( 'template-parts/header/header', 'icon' ); ?>

		<?php wp_head(); ?>
		<style>
			#qm {
				display: none !important;
			}
		</style>

	</head>

	<body>
		<?php do_action( 'prj_start_body_tag' ); ?>

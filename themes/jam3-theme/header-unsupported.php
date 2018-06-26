<?php
/**
 * Unsupported header page
 *
 * Partial template for the unsupported.php
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
			html,
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				background-color: #ffffff;
				background-position: 50% 50%;
				font-family: "Helvetica Neue", Arial, sans-serif;
			}
			.container {
				display: table;
				width: 100%;
				height: 100%;
			}
			.message {
				display: table-cell;
				vertical-align: middle;
				text-align: center;
			}
		</style>

	</head>

	<body>
		<?php do_action( 'prj_start_body_tag' ); ?>

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
		<title>Maintenance page</title>
		<meta name="description" content="Maintenance page">
		<link rel="profile" href="http://gmpg.org/xfn/11">
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
			.title {
				font-size: 40px;
				color: #000000;
				margin: 20px auto;
				max-width: 250px;
			}
			.subtitle {
				font-size: 16px;
				color: #6A6A6A;
				margin: 20px auto;
				max-width: 250px;
			}
			@media screen and (min-width: 768px) {
				.title, .subtitle {
					max-width: 100%;
				}
			}
			@media screen and (min-width: 1024px) {
				.title {
					font-size: 64px;
				}
				.subtitle {
					font-size: 18px;
				}
			}
		</style>
	</head>

	<body>

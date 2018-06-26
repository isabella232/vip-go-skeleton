<?php
/*
 * We recommend all plugins for your site are
 * loaded in code, either from a file like this
 * one or from your theme (if the plugins are
 * specific to your theme and do not need to be
 * loaded as early as this in the WordPress boot
 * sequence.
 *
 * @see https://vip.wordpress.com/documentation/vip-go/understanding-your-vip-go-codebase/
 */

// wpcom_vip_load_plugin( 'plugin-name' );
// Note the above requires a specific naming structure: /plugin-name/plugin-name.php
// You can also specify a specific root file: wpcom_vip_load_plugin( 'plugin-name/plugin.php' );

//Boot jam3 mu plugin
$jam3_core_loader = dirname( __FILE__ ) . '/jam3-core/class-jam3.php';
if ( file_exists( $jam3_core_loader ) && validate_file( $jam3_core_loader ) === 0 ) {
	require_once $jam3_core_loader;
}

//Boot project mu plugin
$project_pl_loader = dirname( __FILE__ ) . '/project-core/project-core.php';
if ( file_exists( $project_pl_loader ) && validate_file( $project_pl_loader ) === 0 ) {
	require_once $project_pl_loader;
}

<?php
/**
 * Project Base Plugin File.
 *
 * @package PRJ-Plugin
 * @version 1.0
 */

/*
Plugin Name: Project Backend Plugin
Description: Provides all backend requirements for business logic, such as custom post types, rest api endpoints.
Author: Jam3
Version: 1.0
*/

/******************************************************************
 *  Maintenance Mode
 *****************************************************************/
define( 'VIP_MAINTENANCE_MODE', true );

/**
 * Current version of plugin
 */
define( 'PRJPL_PLUGIN_VERSION', '1.0' );

/**
 * Filesystem path to plugin
 */
define( 'PRJPL_PLUGIN_BASE_DIR', dirname( __FILE__ ) );
define( 'PRJPL_PLUGIN_BASE_URL', content_url() . '/client-mu-plugins/project-core' );

/**
 * Define min WordPress Version
 */
define( 'PRJPL_PLUGIN__MINIMUM_WP_VERSION', '4.8.3' );

/**
 * Setup post revisions
 */
define( 'WP_POST_REVISIONS', 3 );

/**
 * prjpl_restrict_external_rest_access
 *
 * @CALLED BY FILTER
 *     'jam3_core__rest_api_disable_restrict_external_rest_access'
 *
 * Decide if we should disable the external request firewall for rest api based
 *     on WP install domain
 *
 * @return bool
 * @access public
 * @author Ben Moody
 */
add_filter( 'jam3_core__rest_api_disable_restrict_external_rest_access', 'prjpl_restrict_external_rest_access' );
function prjpl_restrict_external_rest_access() {

	//vars
	$home_url = get_home_url();

	//Allow external rest access for any VIP domain (disable) method
	if ( strstr( $home_url, '[DEVELOP URL]' ) ) {
		return true;
	}

	//Enable restriction for all other domains
	return false;
}

/**
 * prjpl_theme_setup
 *
 * @CALLED BY ACTION 'init'
 *
 * Run anything we need to setup everything for theme
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'init', 'prjpl_theme_setup' );
function prjpl_theme_setup() {

	//Detect if Jam3 requied plugin is active
	if ( ! defined( 'JAM3_CORE_LOADED' ) && ! is_admin() ) {
		wp_die( '<p>Need Jam3 plugins activate, please review the readme.md file</p>' );
	}

	//Load maintenance mode plugin via code
	if ( function_exists( 'wpcom_vip_load_plugin' ) && Jam3_General::is_maintenance_mode() ) {
		wpcom_vip_load_plugin( 'maintenance-mode' );
	}

}

/**
 * Setup plugin textdomain folder
 *
 * @public
 */
add_action( 'after_setup_theme', 'prjpl_plugin_textdomain' );
function prjpl_plugin_textdomain() {
	load_plugin_textdomain( 'prjpl-plugin-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * prjpl_boot_plugin
 *
 * CALLED ON ACTION 'after_setup_theme'
 *
 * Includes all class files for plugin, runs on 'after_theme_setup' to allows
 * themes to override some classes/functions
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'plugins_loaded', 'prjpl_boot_plugin' ); //Allows themes to override classes, functions
function prjpl_boot_plugin() {

	//vars
	$includes_type_path    = PRJPL_PLUGIN_BASE_DIR . '/includes';
	$custom_post_type_path = PRJPL_PLUGIN_BASE_DIR . '/includes/custom-post-types';
	$fieldmanager_path     = PRJPL_PLUGIN_BASE_DIR . '/includes/fieldmanager';
	$rest_api_path         = PRJPL_PLUGIN_BASE_DIR . '/includes/rest-api';

	//Include php helpers
	prjpl_include_file( "{$includes_type_path}/helper-functions.php" );

	//Include all custom post type classes
	prjpl_include_all_files( $custom_post_type_path );

	//Include all rest api classes
	prjpl_include_all_files( $rest_api_path );

	//Include all fieldmanager classes
	prjpl_include_all_files( $fieldmanager_path );

	define( 'PRJPL_PLUGIN_LOADED', true );

}

/**
 * prjpl_include_file
 *
 * Helper to test file include validation and include_once if safe
 *
 * @param    string    Path to include
 *
 * @return    mixed    Bool/WP_Error
 * @access    public
 * @author    Ben Moody
 */
function prjpl_include_file( $path ) {

	//Check if a valid path for include
	if ( validate_file( $path ) > 0 ) {

		//Failed path validation
		return new WP_Error(
			'prjpl_include_file',
			'File include path failed path validation',
			$path
		);

	}

	include_once( $path );

	return true;
}

/**
 * prjpl_admin_styles
 *
 * @CALLED BY ACTION 'admin_enqueue_scripts'
 *
 * Add plugin admin styles
 *
 * @access    public
 * @author    Ben Moody
 */
add_action( 'admin_enqueue_scripts', 'prjpl_admin_styles' );
function prjpl_admin_styles() {

	//vars
	$admin_css_url = plugins_url( 'assets/css/admin.css', __FILE__ );

	wp_enqueue_style(
		'prjpl-admin-styles',
		esc_url( $admin_css_url )
	);

}

/**
 * prjpl_admin_scripts
 *
 * @CALLED BY ACTION 'admin_enqueue_scripts'
 *
 * Add plugin admin scripts
 *
 * @access    public
 * @author    Ben Moody
 */
add_action( 'admin_enqueue_scripts', 'prjpl_admin_scripts' );
function prjpl_admin_scripts( $hook ) {

	//vars
	$admin_script_url = plugins_url( 'assets/scripts/', __FILE__ );

	wp_enqueue_script(
		'prjpl-admin-scripts',
		esc_url( $admin_script_url . 'admin-fieldmanager.js' )
	);

}

/**
 * prjpl_navigation_menus
 *
 * @CALLED BY ACTION 'init'
 *
 * Register Theme navigation menus
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'init', 'prjpl_navigation_menus' );
function prjpl_navigation_menus() {

	$locations = array(
		'Footer Menu' => __( 'The Site Footer Menu (Footer Menu)', 'prjpl-plugin-domain' ),
	);
	register_nav_menus( $locations );

}

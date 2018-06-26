<?php
/**
 * Main functions of the theme
 *
 * Theme functions file, either contains or includes code required to render
 * theme
 *
 * @access public
 * @package backend
 */

if ( function_exists( 'wpcom_vip_load_plugin' ) ) {
	wpcom_vip_load_plugin( 'maintenance-mode' );
}

if ( ! class_exists( 'Jam3' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="error"><p><strong>Need Jam3 plugins activate, please review the readme.md file</strong></p></div>';
	} );

	return;
}

/**
 * Text domain
 */
load_theme_textdomain( 'prj-plugin-domain', get_stylesheet_directory() . '/languages' );

/**
 * Frontend folder release path inside the theme
 */
define( 'JAM3_FRONTEND_FOLDER', 'release' );

/**
 * Enabled Social Media integration
 */
define( 'JAM3_SOCIAL_MEDIA_ENABLED', true );

/**
 * Social media image name inside the template inside the image folder
 */
define( 'JAM3_SOCIAL_MEDIA_IMAGE', 'graph-image.png' );

/**
 * Set the admin favicon setting the route. The route is relative to
 * JAM3_FRONTEND_FOLDER
 */
define( 'JAM3_ADMIN_FAVICON_PATH', 'images/favicons/favicon.ico' );

/**
 * prj_get_google_tag_manager_id
 *
 * Helper to get correct Google Tag Manager ID based on current install domain
 *
 * @return string $code_id
 * @access public
 * @author Ben Moody
 */
function prj_get_google_tag_manager_id() {

	// Variables.
	$code_id = null;

	$code_id = Prjpl_Field_Manager_Global_Options::get_google_tag_manager_code();

	return $code_id;
}

/**
 * prj_print_google_tag_manager
 *
 * @CALLED BY ACTION 'prj_end_body_tag'
 *
 * Print out Google Tag Manager code right at the end of the <body> tag
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'prj_end_body_tag', 'prj_print_google_tag_manager' );
function prj_print_google_tag_manager() {

	?>
	<?php get_template_part( '/template-parts/scripts/script', 'google-tag-manager' ); ?>
	<?php

}

/**
 * prj_print_google_tag_manager_body
 *
 * @CALLED BY ACTION 'prj_start_body_tag'
 *
 * Print out Google Tag Manager code right at the start of the <body> tag
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'prj_start_body_tag', 'prj_print_google_tag_manager_body' );
function prj_print_google_tag_manager_body() {

	// Variables.
	global $post;
	$code_id = prj_get_google_tag_manager_id();

	if ( ! isset( $post->ID ) ) {
		return;
	}

	// Try and get the page template.
	$page_template = get_page_template_slug( $post->ID );

	// No page template set.
	if ( empty( $page_template ) ) {
		return;
	}

	// Is this the 'Unsupported' page?
	if ( 'page-templates/unsupported.php' !== $page_template ) {
		return;
	}

	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $code_id ); ?>"
				height="0"
				width="0"
				style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php

}

/**
 * Avoid bundle concatenation to let apply deferring
 *
 * @CALLED BY ACTION 'js_do_concat'
 *
 * Avoid server concatenation
 *
 * @param string $handle Handler.
 *
 * @return bool
 * @access public
 */
add_filter( 'js_do_concat', 'prj_do_concat', 10, 1 );
function prj_do_concat( $handle ) {
	return false;
}

/**
 * prj_enqueue_scripts
 *
 * @CALLED BY ACTION 'wp_enqueue_scripts'
 *
 * Add/Remove project level scripts here
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'wp_enqueue_scripts', 'prj_enqueue_scripts', 9999 );
function prj_enqueue_scripts() {

	if ( is_admin() ) {
		return;
	}

	// Remove Jetpack devicepx script.
	wp_dequeue_script( 'devicepx' );

}

/**
 *
 * @CALLED BY FILTER 'jetpack_implode_frontend_css'
 *
 * Remove Jetpack CSS from front end
 *
 * @access public
 * @author Ben Moody
 */
add_filter( 'jetpack_implode_frontend_css', '__return_false' );

/**
 * Redirect 404 pages to the home url.
 *
 * @CALLED BY ACTION 'template_redirect'
 */
add_action( 'template_redirect', 'prj_redirect_404_to_homepage' );
function prj_redirect_404_to_homepage() {
	if ( is_404() ) {
		wp_safe_redirect( get_home_url(), 301 );
		exit();
	}
}

/**
 * prj_localize_vars
 *
 * @CALLED BY /ACTION 'wp_enqueue_scripts'
 *
 * Localize json objects with site data. Note site data is cached in transient
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'wp_enqueue_scripts', 'prj_localize_vars', 10 );
function prj_localize_vars() {

	if ( ! is_admin() && defined( 'PRJPL_PLUGIN_LOADED' ) ) {
		$handle     = 'jam3-bundle';
		$obj_name   = 'prjLocalVars';
		$data_array = array();
		$cache      = null;

		//Get cached output
		$cache = get_transient( 'prj-local-vars' );

		if ( false === $cache ) {

			if ( class_exists( 'Jam3_Base' ) ) {

				//Get general config
				$data_array['config'] = array(
					'theme_directory' => get_stylesheet_directory_uri(),
					'frontend_route'  => Jam3_Base::get_defined_frontend_folder(),
				);
			}

			//Get Global data
			$data_array['global'] = Prjpl_Field_Manager_Home_Page::get_global_data_for_json();

			//Try and cache data
			set_transient( 'prj-local-vars', $data_array, ( 10 * MINUTE_IN_SECONDS ) );

		} elseif ( is_array( $cache ) ) {

			$data_array = $cache;

		}

		//Cache current user device (outside cached values from transient)
		$data_array['device'] = prj_get_device_matrix();

		wp_localize_script( $handle, $obj_name, $data_array );
	}

}

/**
 * prj_get_device_matrix
 *
 * @CALLED BY prj_localize_vars()
 *
 * Uses Mobile_Detect class to detect the current user device matrix
 *
 * @return array $output
 * @access public
 * @author Ben Moody
 */
function prj_get_device_matrix() {

	// Variables.
	$output = array(
		'isPhone'  => false,
		'isTablet' => false,
		'isMobile' => false,
	);

	if ( ! method_exists( 'Jam3_General', 'get_device_type_matrix' ) ) {
		return $output;
	}

	// Mobile fist detection.
	$output = Jam3_General::get_device_type_matrix();

	return $output;
}

/**
 * prj_clear_cache
 *
 * @CALLED BY /ACTION 'save_post'
 *
 * Clear any site cache on save_post action
 *
 * @access public
 * @author Ben Moody
 */
add_action( 'save_post', 'prj_clear_cache' );
add_action( 'edit_attachment', 'prj_clear_cache', 10, 0 );
add_action( 'wp_update_nav_menu', 'prj_clear_cache', 10, 0 );
function prj_clear_cache() {

	//Clear local vars transient
	delete_transient( 'prj-local-vars' );

}

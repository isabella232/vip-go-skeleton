<?php
/**
 * Includes basic method and information for all the core classes
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Jam3 Class
 *
 * @class Jam3_Base
 * @version    1.0.0
 */
class Jam3_Base {

	/**
	 * Show admin notices to print debug info
	 *
	 * @param string $message Message to show.
	 * @param string $type Type of Message, by default is 'Error'.
	 */
	public function log( $message, $type = 'error' ) {
		if ( defined( 'WP_DEBUG' ) === true ) {
			self::admin_notice( $message, $type );
		}
	}

	/**
	 * Add notice to the backend
	 *
	 * @param string $message Message to show.
	 * @param string $type Type of Message.
	 */
	public function admin_notice( $message, $type ) {
		if ( is_multisite() ) {
			add_action( 'network_admin_notices', function () use ( $message, $type ) {
				echo '<div class="' . esc_attr( $type ) . '">' . esc_html( Jam3_Manager::get_slug() ) . ': ' . esc_html( $message ) . '</div>';
			} );
		} else {
			add_action( 'admin_notices', function () use ( $message, $type ) {
				echo '<div class="' . esc_attr( $type ) . '">' . esc_html( Jam3_Manager::get_slug() ) . ': ' . esc_html( $message ) . '</div>';
			} );
		}
	}

	/**
	 * Get the plugin slug
	 *
	 * @return string
	 */
	public function slug() {
		return Jam3_Manager::get_slug();
	}

	/**
	 * Get the plugins version
	 *
	 * @return string
	 */
	public function version() {
		return Jam3_Manager::get_version();
	}

	/**
	 * Get the defined frontend folder
	 *
	 * @return string
	 */
	public function get_textdomain() {
		return defined( 'JAM3_TEXT_DOMAIN' ) ? JAM3_TEXT_DOMAIN : 'jam3-locale';
	}

	/**
	 * Get the frontend folder set where the JS release the bundles
	 *
	 * @return string
	 */
	public function get_front_folder() {
		$base_front_folder = apply_filters( 'jam3_front_folder', get_stylesheet_directory_uri() . '/' . self::get_defined_frontend_folder() );

		return $base_front_folder;
	}

	/**
	 * Get the defined frontend folder
	 *
	 * @return string
	 */
	public static function get_defined_frontend_folder() {
		return defined( 'JAM3_FRONTEND_FOLDER' ) ? JAM3_FRONTEND_FOLDER : 'release';
	}

	/**
	 * Get Global configuration
	 *
	 * Helper to get any global config vars
	 *
	 * @return string
	 */
	public function get_global_config() {
		$global_config = apply_filters( 'jam3_global_frontend_config', array(
			'theme_directory' => get_stylesheet_directory_uri(),
			'frontend_route'  => self::get_defined_frontend_folder(),
		) );

		return array_map( 'sanitize_text_field', wp_unslash( $global_config ) );
	}
}

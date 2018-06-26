<?php
/**
 * This class is responsible to include the bundles and frontend configurations.
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Jam3_Assets
 *
 * @class Jam3_Assets
 * @version    1.0.0
 */
class Jam3_Assets extends Jam3_Base {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array(
			$this,
			'wp_enqueue_scripts',
		) );
		add_action( 'wp_print_scripts', array(
			$this,
			'enqueue_styles',
		) );
		add_filter( 'script_loader_tag', array(
			$this,
			'add_async_attribute',
		), 99, 2 );
	}


	/**
	 * Add global js vars to the theme load
	 *
	 * @CALLED BY ACTION 'wp_print_scripts'
	 */
	public function enqueue_front_script() {

		if ( ! is_admin() ) {
			$handle     = 'jam3-vendor';
			$obj_name   = 'backendLocalVars';
			$data_array = array();

			// Cache data for localization.
			$data_array['config'] = $this->get_global_config();

			wp_localize_script( $handle, $obj_name, $data_array );
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * Enqueue any theme JS here
	 *
	 * @CALLED BY ACTION 'wp_enqueue_scripts'
	 */
	public function wp_enqueue_scripts() {
		if ( is_admin() ) {
			return;
		}

		// Remove WP jquery.
		wp_deregister_script( 'jquery' );

		if ( defined( 'SCRIPT_DESKTOP_BUNDLE' ) ) {
			wp_enqueue_script( 'jam3-bundle',
				$this->get_front_folder() . '/' . SCRIPT_DESKTOP_BUNDLE,
				array(),
				null,
				true
			);
		}

	}


	/**
	 * Enqueue styles
	 *
	 * Enqueue any theme STYLES here
	 *
	 * @CALLED BY ACTION 'wp_print_styles'
	 */
	function enqueue_styles() {
		if ( is_admin() ) {
			return;
		}

		if ( defined( 'STYLE_DESKTOP_BUNDLE' ) ) {
			wp_enqueue_style( 'jam3-styles',
				$this->get_front_folder() . '/' . STYLE_DESKTOP_BUNDLE,
				array(),
				null
			);
		}
	}

	/**
	 * Transform scripts to defer
	 *
	 * Modify how the script are loaded
	 *
	 * @param string $tag HTML tag.
	 * @param string $handle Handler.
	 *
	 * @return string
	 */
	public function add_async_attribute( $tag, $handle ) {
		$scripts_to_async = apply_filters( 'jam3_key_to_async', array(
			'jam3-bundle',
		) );
		if ( in_array( $handle, $scripts_to_async, true ) ) {
			return str_replace( ' src', ' async="true" src', $tag );
		}

		return $tag;
	}
}

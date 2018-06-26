<?php
/**
 * Main Class
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jam3' ) ) {

	/**
	 * Jam3 Class
	 *
	 * @class Jam3
	 * @version    1.0.0
	 */
	class Jam3 {

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			$loaded_variables = $this->load_variables();

			if ( is_wp_error( $loaded_variables ) ) {

				define( 'JAM3_CORE_VARS_LOADED', false );

			} else {

				define( 'JAM3_CORE_VARS_LOADED', true );

			}

			define( 'JAM3_HTML_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR );

			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class-jam3-base.php';
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'class-jam3-manager.php';

			new Jam3_Manager();

			define( 'JAM3_CORE_LOADED', true );
		}

		/**
		 * Initialize the plugin.
		 */
		public function load_variables() {

			$variables_path = get_stylesheet_directory() . '/release/variables.php';
			if ( validate_file( $variables_path ) > 0 ) {
				// Failed path validation.
				return new WP_Error(
					'Jam3::constructor',
					'File include path failed path validation',
					$variables_path
				);

			}

			if ( ! file_exists( $variables_path ) ) {
				return new WP_Error(
					'Jam3::constructor::load_variables_file_exists',
					'File include path for variables.php does not exist',
					$variables_path
				);
			}

			require_once $variables_path;

			return true;
		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}

	/**
	 * Main instance of Jam3.
	 *
	 * Returns the main instance of Jam3 to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return Jam3
	 */
	function jam3() {
		return Jam3::get_instance();
	}

	/**
	 * Global variable to handle the plugin
	 */
	$GLOBALS['Jam3'] = jam3();
}

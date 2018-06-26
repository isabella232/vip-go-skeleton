<?php
/**
 * This class is responsible to load all other class and catch if exist any
 * throw errors
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Jam3_Manager
 *
 * @class Jam3_Manager
 * @version    1.0.0
 */
class Jam3_Manager extends Jam3_Base {

	/**
	 * Jam3 version.
	 *
	 * @var string
	 */
	protected static $version = '2.0.0';
	/**
	 * Jam3 slug.
	 *
	 * @var string
	 */
	private static $plugin_slug = 'jam3';

	/**
	 * Class Constructor.
	 */
	public function __construct() {

		try {
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-general.php';
			new Jam3_General();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-headers.php';
			new Jam3_Headers();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-social-meta.php';
			new Jam3_Social_Meta();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-admin.php';
			new Jam3_Admin();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-assets.php';
			new Jam3_Assets();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-admin-bar.php';
			new Jam3_Admin_Bar();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-doctype.php';
			new Jam3_DocType();
			require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-jam3-rest-api.php';
			new Jam3_Rest_Api();
		} catch ( Exception $ex ) {
			$this->log( esc_attr( 'Review your code: ' . $ex->getMessage() ) );
		}
	}

	/**
	 * Get plugin slug
	 *
	 * @return string
	 */
	public static function get_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Get the plugins version
	 *
	 * @return string
	 */
	public static function get_version() {
		if ( defined( 'FRONTEND_VERSION' ) ) {
			return FRONTEND_VERSION;
		}

		return self::$version;
	}
}

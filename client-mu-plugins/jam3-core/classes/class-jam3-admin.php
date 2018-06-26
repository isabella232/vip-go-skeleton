<?php
/**
 * Setup Administration Dashboard.
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Jam3_Admin
 *
 * @class Jam3_Admin
 * @version    1.0.0
 */
class Jam3_Admin extends Jam3_Base {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'login_head', array( $this, 'add_admin_favicon' ) );
		add_action( 'admin_head', array( $this, 'add_admin_favicon' ) );
	}

	/**
	 * Add favicon to the Administration Dashboard
	 */
	public function add_admin_favicon() {
		if ( defined( 'JAM3_ADMIN_FAVICON_PATH' ) ) {
			$favicon_url = $this->get_front_folder() . '/' . JAM3_ADMIN_FAVICON_PATH;
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon_url ) . '" />';
		}
	}
}

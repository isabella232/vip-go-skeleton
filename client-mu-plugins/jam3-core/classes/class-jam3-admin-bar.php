<?php
/**
 * This class is responsible to show the admin var to user with the capability
 * vip_support.
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Jam3_Admin_Bar
 *
 * @class Jam3_Admin_Bar
 * @version    1.0.0
 */
class Jam3_Admin_Bar {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_filter( 'show_admin_bar', array( $this, 'admin_bar' ) );
	}

	/**
	 * Admin bar
	 *
	 * @CALLED BY FILTER 'show_admin_bar'
	 *
	 * Hide admin bar for all the users except the ones with the role
	 *     vip_support
	 *
	 * @param string $content Admin bar content.
	 *
	 * @return bool
	 */
	public function admin_bar( $content ) {
		return ( current_user_can( 'vip_support' ) ) ? $content : false;
	}

}

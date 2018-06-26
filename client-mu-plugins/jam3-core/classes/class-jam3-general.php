<?php
/**
 * Configure the theme in order to improve the global performance.
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Jam3_General
 *
 * @class Jam3_General
 * @version    1.0.0
 */
class Jam3_General extends Jam3_Base {

	/**
	 * Class Constructor.
	 */
	public function __construct() {

		// Remove unused DNS-Precatch.
		remove_action( 'wp_head', 'wp_resource_hints', 2 );

		// Remove wp_generator from meta output.
		remove_action( 'wp_head', 'wp_generator' );

		// Remove Emoji Icons.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		add_action( 'init', array( $this, 'setup_textdomain' ) );
		add_filter( 'jetpack_implode_frontend_css', '__return_false' );
		add_filter( 'wp_title', array( $this, 'html_title_tag' ), 9999, 1 );
		add_action( 'init', array( $this, 'disable_embeds_init' ), 9999 );
		add_action( 'wp_head', array( $this, 'pingback_header' ) );

	}

	/**
	* get_device_type_matrix
	*
	* Helper to detect user device and return array of device info
	*
	* @access public $device_config
	* @author Ben Moody
	*/
	public static function get_device_type_matrix() {
		$is_mobile = false;
		$is_phone  = false;
		$is_tablet = false;

		if ( isset( $_SERVER['HTTP_X_MOBILE_CLASS'] ) && 'desktop' !== $_SERVER['HTTP_X_MOBILE_CLASS'] ) {
			$is_mobile = true;
		}

		if ( function_exists( 'jetpack_is_mobile' ) ) {
			$is_phone = jetpack_is_mobile();

			if ( method_exists( 'Jetpack_User_Agent_Info', 'is_tablet' ) && Jetpack_User_Agent_Info::is_tablet() ) {
				$is_tablet = true;
			}
		}

		$is_mobile = $is_mobile || $is_phone || $is_tablet;

		$device_config = array(
			'isPhone'  => $is_phone,
			'isTablet' => $is_tablet,
			'isMobile' => $is_mobile,
		);

		return $device_config;
	}

	/**
	 * Set always a valid HTML title
	 *
	 * @CALLED BY FILTER 'wp_title'
	 * @return string
	 *
	 * @param string $title HTML title.
	 */
	public function html_title_tag( $title ) {

		if ( empty( $title ) ) {
			return esc_html( get_bloginfo( 'name' ) );
		}

		return trim( sanitize_text_field( $title ) );
	}

	/**
	 * Setup text domain
	 *
	 * @CALLED BY FILTER 'init'
	 */
	public function setup_textdomain() {
		load_plugin_textdomain( $this->get_textdomain(), false, WP_CONTENT_DIR . '/languages' );
	}

	/**
	 * Disable embeds
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * Helper to get any global config vars
	 */
	public function disable_embeds_init() {

		// Remove the REST API endpoint.
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );

		// Turn off oEmbed auto discovery, don't filter oEmbed results.
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

		// Remove oEmbed discovery links.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

		// Remove oEmbed-specific JavaScript from the front-end and back-end.
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	/**
	 * Add a pingback url auto-discovery header for singularly identifiable
	 * articles.
	 *
	 * @CALLED BY ACTION 'wp_head'
	 */
	function pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * is_maintenance_mode
	 *
	 * Helper to detect VIP maintenance mode
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	public static function is_maintenance_mode() {

		if (
			( true === self::is_vip_env() ) &&
			( defined( 'VIP_MAINTENANCE_MODE' ) && ( true === VIP_MAINTENANCE_MODE ) )
		) {
			return true;
		}

		return false;
	}

	/**
	 * is_vip_env
	 *
	 * Helper to detect if current environment is VIP
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	public static function is_vip_env() {

		if (
		( defined( 'WPCOM_IS_VIP_ENV' ) && ( true === WPCOM_IS_VIP_ENV ) )
		) {
			return true;
		}

		return false;
	}

}

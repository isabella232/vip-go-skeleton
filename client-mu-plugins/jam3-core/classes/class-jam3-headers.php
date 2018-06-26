<?php
/**
 * Setup headers for the theme, mainly security headers
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Jam3_Headers
 *
 * @class Jam3_Headers
 * @version    1.0.0
 */
class Jam3_Headers {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'init', array(
			$this,
			'send_frame_options_header',
		), 10, 0 );
		add_action( 'init', array( $this, 'send_xss_options_header' ), 10, 0 );
		add_action( 'init', array( $this, 'send_hsts_options_header' ), 10, 0 );
		add_action( 'init', array(
			$this,
			'send_content_type_options_header',
		), 10, 0 );
		add_action( 'init', array( $this, 'send_csp_options_header' ), 10, 0 );
	}

	/**
	 * Send a HTTP header to stop the rendering of pages to same origin iframes.
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * @see https://developer.mozilla.org/en/the_x-frame-options_response_header
	 */
	public function send_frame_options_header() {
		header( 'X-Frame-Options: deny' );
	}

	/**
	 * Send a HTTP header to stop the rendering of pages in case of detect a
	 * XSS attack
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
	 */
	public function send_xss_options_header() {
		header( 'X-XSS-Protection:1; mode=block' );
	}

	/**
	 * Send a HTTP header to force the communication over https
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
	 */
	public function send_hsts_options_header() {
		header( 'Strict-Transport-Security: max-age=604800; preload' );
	}

	/**
	 * Send a HTTP header to force the use of the right MIME type
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
	 */
	public function send_content_type_options_header() {
		header( 'X-Content-Type-Options: nosniff' );
	}

	/**
	 * Send a HTTP header to setup the content security policy
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
	 */
	public function send_csp_options_header() {

		$csp_header   = '';
		$csp_settings = array();

		$content_security_policy = array();

		//WARNING!! you must have a SPACE after each item in the string, except the last one!!
		$default_src = explode(
			' ',
			"'self' 
			blob: 
			data: 
			https:"
		);

		//WARNING!! you must have a SPACE after each item in the string, except the last one!!
		$script_src = explode(
			' ',
			"'self' 
			'unsafe-inline' 
			'unsafe-eval' 
			*.google-analytics.com 
			*.googletagmanager.com 
			stats.wp.com 
			pixel.wp.com 
			bam.nr-data.net 
			js-agent.newrelic.com 
			s0.wp.com 
			s1.wp.com 
			*.facebook.net"
		);

		//WARNING!! you must have a SPACE after each item in the string, except the last one!!
		$style_src = explode(
			' ',
			"blob: 
			'self' 
			'unsafe-inline' 
			tagmanager.google.com 
			s0.wp.com 
			s1.wp.com"
		);

		//WARNING!! you must have a SPACE after each item in the string, except the last one!!
		$img_src = explode(
			' ',
			"'self' 
			blob: 
			data: 
			https: 
			*.google-analytics.com 
			s0.wp.com 
			s1.wp.com"
		);

		//WARNING!! you must have a SPACE after each item in the string, except the last one!!
		$media_src = explode(
			' ',
			"'self' 
			blob: 
			data: 
			https: 
			s0.wp.com 
			s1.wp.com"
		);

		//WARNING!! you must have a SPACE after each item in the string, except the last one!!
		$frame_src = explode(
			' ',
			"'self'"
		);

		if ( ! is_admin() ) {
			$csp_settings = array(
				'Content-Security-Policy:' => $content_security_policy,
				'default-src'              => apply_filters( 'jam3_core__csp_default_src', $default_src ),
				'script-src'               => apply_filters( 'jam3_core__csp_script_src', $script_src ),
				'style-src'                => apply_filters( 'jam3_core__csp_style_src', $style_src ),
				'img-src'                  => apply_filters( 'jam3_core__csp_img_src', $img_src ),
				'media-src'                => apply_filters( 'jam3_core__csp_media_src', $media_src ),
				'frame-src'                => apply_filters( 'jam3_core__csp_frame_src', $frame_src ),
			);

			/**
			 * Allow devs to change csp rules
			 *
			 * @SInCE 1.0.0
			 *
			 * @param array $csp_settings array of csp rules
			 */
			$csp_settings = apply_filters( 'jam3_core__csp_rules', $csp_settings );

			foreach ( $csp_settings as $rule => $attributes ) {

				$csp_header .= $rule . ' ';

				foreach ( $attributes as $key => $attr_val ) {

					if ( empty( $attr_val ) ) {
						continue;
					}

					$count = count( $attributes );

					$csp_header .= $attr_val;

					//Last item in list?
					if ( ( $count - 1 ) === $key ) {
						$csp_header .= ';';
					} else {
						$csp_header .= ' ';
					}
				}
			}

			header( sanitize_text_field( $csp_header ) );
		}
	}
}

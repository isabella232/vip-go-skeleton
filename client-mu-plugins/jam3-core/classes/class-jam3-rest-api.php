<?php
/**
 * Setup any common REST API actions required by most projects
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Jam3_Rest_Api
 *
 * @class Jam3_Rest_Api
 * @version    1.0.0
 */
class Jam3_Rest_Api {

	/**
	 * Class Constructor.
	 */
	public function __construct() {

		//Disable rest api to public
		add_filter( 'rest_authentication_errors', array(
			$this,
			'dra_only_allow_logged_in_rest_access',
		), 999, 1 );

		//Block certain api request methods
		add_filter( 'rest_dispatch_request', array(
			$this,
			'block_api_requests',
		), 999, 4 );

		//Remove endpoints
		add_filter( 'rest_endpoints', array(
			$this,
			'remove_endpoints',
		), 999, 1 );

		//Customize api headers
		add_action( 'rest_api_init', array(
			$this,
			'custom_api_headers',
		), 10 );

		//Filter api response object for posts and pages
		add_action( 'rest_prepare_post', array(
			$this,
			'filter_api_response',
		), 9999, 1 );
		add_action( 'rest_prepare_page', array(
			$this,
			'filter_api_response',
		), 9999, 1 );

		//Prevent non authenitcated users from accessing API when maintenance mode is active
		add_filter( 'rest_authentication_errors', array(
			$this,
			'restrict_non_authenticated_rest_access',
		) );

		add_filter( 'rest_authentication_errors', array(
			$this,
			'restrict_external_rest_access',
		) );
	}

	/**
	 * is_rest_request
	 *
	 * Helper to detect if current request is from rest api
	 *
	 * @access    public static
	 * @return    bool
	 * @author    Ben Moody
	 */
	public static function is_rest_request() {

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		return false;
	}

	/**
	 * clean_up_rest_response
	 *
	 * Helper to remove array of nodes from the rest api response object
	 *
	 * @param    object $response
	 * @param    array $items_to_remove
	 *
	 * @return    object
	 * @access    public static
	 * @author    Ben Moody
	 */
	public static function clean_up_rest_response( $response, $items_to_remove ) {

		foreach ( $items_to_remove as $item_key ) {

			if ( isset( $response->data[ $item_key ] ) ) {

				unset( $response->data[ $item_key ] );

			}
		}

		return $response;
	}

	/**
	 * Restrict access to the Rest API if the user is not login
	 *
	 * @CALLED BY FILTER 'rest_authentication_errors'
	 *
	 * Return error if the user is not login
	 *
	 * @param string $result Opengraph output.
	 *
	 * @access    public
	 */
	public function restrict_non_authenticated_rest_access( $result ) {

		if ( ! Jam3_General::is_maintenance_mode() ) {
			return $result;
		}

		if ( ! empty( $result ) ) {
			return $result;
		}
		if ( ! is_user_logged_in() ) {
			return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
		}

		return $result;
	}

	/**
	 * restrict_external_rest_access
	 *
	 * @CALLED BY FILTER 'rest_authentication_errors'
	 *
	 * Checks and validates HTTP_X_WP_NONCE in request, prevents reqeusts to
	 *     REST API without valid rest api nonce
	 *
	 * @param bool $result
	 *
	 * @return mixed WP_Error/bool
	 * @access public
	 * @author Ben Moody
	 */
	public function restrict_external_rest_access( $result ) {

		/**
		 * Allow devs to short circuit this function essentially disabling it
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::restrict_external_rest_access()
		 *
		 * @param bool $disable_function return true to disable this function / false to enable it
		 */
		$disable_function = apply_filters( 'jam3_core__rest_api_disable_restrict_external_rest_access', false );

		if ( true === $disable_function ) {
			return $result;
		}

		if ( ! empty( $result ) ) {
			return $result;
		}

		global $wp_rest_auth_cookie;

		/*
		 * Is cookie authentication being used? (If we get an auth
		 * error, but we're still logged in, another authentication
		 * must have been used).
		 */
		if ( true !== $wp_rest_auth_cookie && is_user_logged_in() ) {
			return $result;
		}

		// Determine if there is a nonce.
		$nonce = null;

		if ( isset( $_REQUEST['_wpnonce'] ) ) {
			$nonce = $_REQUEST['_wpnonce'];
		} elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
			$nonce = $_SERVER['HTTP_X_WP_NONCE'];
		}

		// Check the nonce.
		$result = wp_verify_nonce( $nonce, 'wp_rest' );

		if ( ! $result ) {
			return new WP_Error( 'rest_cookie_invalid_nonce', __( 'Cookie nonce is invalid' ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * filter_api_response
	 *
	 * @CALLED BY ACTION 'rest_prepare_page'
	 *
	 * Conduct any actions on rest api response object for posts and pages
	 *
	 * @param object $response
	 *
	 * @return object $response
	 * @access public
	 * @author Ben Moody
	 */
	public function filter_api_response( $response ) {

		//Remove _links from response
		self::clean_up_rest_links( $response );

		return $response;
	}

	/**
	 * clean_up_rest_links
	 *
	 * Helper to remove array of _links from the rest api response object
	 *
	 * @param    object $response
	 *
	 * @access    public static
	 * @author    Ben Moody
	 */
	public static function clean_up_rest_links( $response ) {

		//vars
		$links_to_remove = null;

		if ( ! method_exists( $response, 'get_links' ) ) {
			return;
		}

		$links_to_remove = $response->get_links();

		foreach ( $links_to_remove as $link_id => $link ) {

			$response->remove_link( $link_id );

		}

		return $response;
	}

	/**
	 * custom_api_headers
	 *
	 * @CALLED BY ACTION 'rest_api_init'
	 *
	 * Include CORS headers
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	public function custom_api_headers() {

		/**
		 * Allow devs to short circuit this function essentially disabling it
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::configure_api()
		 *
		 * @param bool $disable_function return true to disable this function / false to enable it
		 */
		$disable_function = apply_filters( 'jam3_core__rest_api_disable_custom_api_headers', true );

		//Detect function short circuit
		if ( true === $disable_function ) {
			return;
		}

		/**
		 * Only allow GET requests, disable CORS
		 */
		add_action( 'rest_api_init', array(
			$this,
			'setup_rest_api_headers',
		), 15 );
	}

	/**
	 * Return an authentication error if a user who is not logged in tries
	 * to query the REST API. This function is disabled by default, use the
	 * filter 'jam3_core__rest_api_disable_logged_in_access' to enable it if
	 * required
	 *
	 * @param $access
	 *
	 * @return WP_Error
	 */
	public function dra_only_allow_logged_in_rest_access( $access ) {

		//vars
		$allowed_request_routes = array();

		/**
		 * Allow devs to short circuit this function essentially disabling it
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::dra_only_allow_logged_in_rest_access()
		 *
		 * @param bool $disable_function return true to disable this function / false to enable it
		 */
		$disable_function = apply_filters( 'jam3_core__rest_api_disable_logged_in_access', true );

		//Detect function short circuit
		if ( true === $disable_function ) {
			return $access;
		}

		if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return $access;
		}

		//Set default values for allowed requests
		$allowed_request_routes = array(
			'jetpack',
			'vip',
			'cron-control',
		);
		/**
		 * Allow devs to filter request routes they wish to allow
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::block_api_requests()
		 *
		 * @param array $allowed_request_routes array of request endpoints you wish to allow
		 */
		$allowed_request_routes = apply_filters( 'jam3_core__rest_api_allow_request_route', $allowed_request_routes );

		//Loop allowed requests and handle
		foreach ( $allowed_request_routes as $request_type ) {

			$request_uri = esc_url_raw( $_SERVER['REQUEST_URI'] );
			$request_uri = esc_url_raw( $_SERVER['REQUEST_URI'] );

			if ( strpos( $request_uri, $request_type ) !== false ) {
				return $access;
			}
		}

		if ( false === is_user_logged_in() ) {
			return new WP_Error( 'rest_cannot_access', __( 'Only authenticated users can access the REST API.', 'disable-json-api' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $access;

	}

	/**
	 * block_api_requests
	 *
	 * @CALLED BY FILTER 'rest_dispatch_request'
	 *
	 * Block certain api request methods
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function block_api_requests( $dispatch_result, $request, $route, $handler ) {

		//vars
		$allowed_request_routes  = array();
		$blocked_request_methods = array();

		//Set default values for allowed requests
		$allowed_request_routes = array(
			'jetpack',
			'vip',
			'cron-control',
		);
		/**
		 * Allow devs to filter request routes they wish to allow
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::block_api_requests()
		 *
		 * @param array $allowed_request_routes array of request endpoints you wish to allow
		 */
		$allowed_request_routes = apply_filters( 'jam3_core__rest_api_allow_request_route', $allowed_request_routes );

		//Loop allowed requests and handle
		foreach ( $allowed_request_routes as $request_type ) {

			if ( strpos( $route, $request_type ) !== false ) {
				return $dispatch_result;
			}
		}

		//Set default values for blocked request methods
		$blocked_request_methods = array(
			'POST',
			'DELETE',
			'PUT',
			'OPTIONS',
			'PATCH',
		);
		/**
		 * Allow devs to filter requests they wish to block
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::block_api_requests()
		 *
		 * @param array $blocked_request_methods array of rest api requests methods you wish to block
		 */
		$blocked_request_methods = apply_filters( 'jam3_core__rest_api_block_request_methods', $blocked_request_methods, $route, $handler );

		//Loop BLOCKED requests and handle
		foreach ( $blocked_request_methods as $request_type ) {

			if ( isset( $handler['methods'][ $request_type ] ) && ( true === $handler['methods'][ $request_type ] ) ) {
				return false;
			}
		}

		return $dispatch_result;
	}

	/**
	 * remove_endpoints
	 *
	 * @CALLED BY FILTER 'rest_endpoints'
	 *
	 * Remove some default rest api endpoints
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function remove_endpoints( $endpoints ) {

		//vars
		$endpoints_to_remove = array(
			'media',
			'types',
			'statuses',
			'taxonomies',
			'tags',
			'users',
			'comments',
			'settings',
			//JETPACK
			'jp_pay_product',
			'jp_pay_order',
		);
		/**
		 * Allow devs to filter endpoints they wish to remove
		 *
		 * @since 1.0.0
		 *
		 * @see Jam3_Rest_Api::remove_endpoints()
		 *
		 * @param array $endpoints_to_remove array of endpoint titles you wish to remove from rest api
		 * @param array $endpoints array of active rest api endpoints
		 */
		$endpoints_to_remove = apply_filters( 'jam3_core__rest_api_remove_endpoints', $endpoints_to_remove, $endpoints );

		foreach ( $endpoints_to_remove as $endpoint ) {

			$base_endpoint = "/wp/v2/{$endpoint}";

			if ( isset( $endpoints[ $base_endpoint ] ) ) {
				unset( $endpoints[ $base_endpoint ] );
			}

			$_endpoint = "{$base_endpoint}/(?P<id>[\d]+)";

			if ( isset( $endpoints[ $_endpoint ] ) ) {
				unset( $endpoints[ $_endpoint ] );
			}

			if ( 'users' === $endpoint ) {

				$_endpoint = "{$base_endpoint}/me";

				if ( isset( $endpoints[ $_endpoint ] ) ) {
					unset( $endpoints[ $_endpoint ] );
				}
			}

			if ( 'types' === $endpoint ) {

				$_endpoint = "{$base_endpoint}/(?P<type>[\w-]+)";

				if ( isset( $endpoints[ $_endpoint ] ) ) {
					unset( $endpoints[ $_endpoint ] );
				}
			}

			if ( 'taxonomies' === $endpoint ) {

				$_endpoint = "{$base_endpoint}/(?P<taxonomy>[\w-]+)";

				if ( isset( $endpoints[ $_endpoint ] ) ) {
					unset( $endpoints[ $_endpoint ] );
				}
			}

			if ( 'statuses' === $endpoint ) {

				$_endpoint = "{$base_endpoint}/(?P<status>[\w-]+)";

				if ( isset( $endpoints[ $_endpoint ] ) ) {
					unset( $endpoints[ $_endpoint ] );
				}
			}
		}

		return $endpoints;
	}

	/**
	 * setup_rest_api_headers
	 *
	 * @CALLED BY ACTION 'rest_api_init'
	 *
	 * Add cors headers for rest api. Restrict to GET methods
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	public function setup_rest_api_headers() {

		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );

		add_filter( 'rest_pre_serve_request', function ( $value ) {

			$origin = get_http_origin();

			if ( $origin ) {
				header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
			}

			header( 'Access-Control-Allow-Origin: ' . esc_url_raw( site_url() ) );
			header( 'Access-Control-Allow-Methods: GET' );
			header( 'Access-Control-Allow-Credentials: true' );
			header( 'Access-Control-Expose-Headers: Link', false );

			return $value;

		} );

	}
}

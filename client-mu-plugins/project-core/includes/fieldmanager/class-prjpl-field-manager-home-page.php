<?php

/**
 * Prjpl_Field_Manager_Home_Page
 *
 * Meta Fields For: 'front-page.php'
 *
 * Register meta fields using FieldManager plugin
 *
 * @author    Ben Moody
 */
class Prjpl_Field_Manager_Home_Page {
	function __construct() {
			//Disable visual editor
			add_action( 'admin_init', array( $this, 'hide_editor' ), 999 );
	}

	/**
	 * get_data_for_json
	 *
	 * Helper to return array of global site meta data formatted for use as
	 * json object
	 *
	 * @access public static
	 * @author Ben Moody
	 */
	public static function get_global_data_for_json() {

		$data = array();

		//Site Global Options
		$data['site-globals'] = self::site_global_data();

		return $data;
	}

	/**
	 * site_global_data
	 *
	 * Return array of meta data formatted for use in a json object
	 *
	 * @return array
	 * @access public static
	 * @author Ben Moody
	 */
	public static function site_global_data() {

		//vars
		$post_id    = get_option( 'page_on_front' );
		$meta_data  = null;
		$data_array = array();

		//Try and get section meta data for home page
		$meta_data = get_post_meta( intval( $post_id ), 'site_global_settings', true );

		if ( ! empty( $meta_data ) && is_array( $meta_data ) ) {

			foreach ( $meta_data as $meta_key => $the_data ) {

				$data_array[ $meta_key ] = esc_html( $the_data );

			}
		}

		return $data_array;
	}

	/**
	 * hide_editor
	 *
	 * Hide visual editor if $this->show_custom_fields conditions are met
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	function hide_editor() {

		if ( self::show_custom_fields() ) {

			remove_post_type_support( 'page', 'editor' );

		}

	}

	/**
	 * show_custom_fields
	 *
	 * Helper to perform some logic on the current view and decide if we should
	 * render a fieldgroup
	 *
	 * @access    private
	 * @author    Ben Moody
	 */
	public static function show_custom_fields() {

		//vars
		$post_id                    = null;
		$current_page_template_slug = null;

		//Show all fields on ajax request
		if ( defined( 'DOING_AJAX' ) && ( DOING_AJAX === true ) ) {
			return true;
		}

		//Show fields when saving post?
		if ( isset( $_POST['post_ID'] ) ) {

			$post_id = intval( $_POST['post_ID'] );

		} elseif ( ! isset( $_GET['post'] ) ) {

			return false;

		} else {

			$post_id = intval( $_GET['post'] );

		}

		//Get page set as front page
		$front_page_id = get_option( 'page_on_front' );

		if ( false === $front_page_id ) {
			return false;
		}

		if ( $post_id !== intval( $front_page_id ) ) {
			return false;
		}

		return true;

	}

}



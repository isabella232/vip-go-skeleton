<?php

/**
 * CLASS Prjpl_Field_Manager_Global_Options
 *
 * This self instantiation class handles including files for FieldManager
 * custom meta fields.
 *
 * @author    Ben Moody
 */
class Prjpl_Field_Manager_Global_Options {

	public static $menu_meta_fields;
	private $menu_page_slug;

	function __construct() {

		//Cache main menu page slug
		$this->menu_page_slug = 'prj_global_settings';

		//Add global options menu in wp admin
		add_action( 'admin_menu', array( $this, 'create_admin_menu_page' ) );

		//Register fields
		add_action( 'init', array( $this, 'register_init_field_groups' ) );

	}

	/**
	* get_google_tag_manager_code
	*
	* Helper to get GTM code for current site
	*
	* @return string $gtm_code
	* @access public static
	* @author Ben Moody
	*/
	public static function get_google_tag_manager_code() {

		//vars
		$analytics_meta = get_option( 'google_analytics' );
		$gtm_code       = null;

		if ( is_array( $analytics_meta ) && isset( $analytics_meta['gtm_code'] ) ) {
			$gtm_code = esc_attr( $analytics_meta['gtm_code'] );
		}

		return $gtm_code;
	}

	/**
	 * register_init_field_groups
	 *
	 * @CALLED BY ACTION 'init'
	 *
	 * Call methods to render individual meta field groups
	 *
	 * @access    public
	 * @author    Ben Moody
	 */
	public function register_init_field_groups() {

		if ( ! is_admin() ) {
			return;
		}

		if ( ! function_exists( 'fm_register_submenu_page' ) ) {
			return;
		}

		//Create Global system pages menu
		$this->global_system_pages_menu();

		//Create Google Analytics pages menu
		$this->google_analytics_menu();

	}

	/**
	 * global_system_pages_menu
	 *
	 * @CALLED BY $this->register_init_field_groups()
	 *
	 * Register sub menu for global page options and render any option fields
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function global_system_pages_menu() {

		fm_register_submenu_page(
			'global_system_pages',
			$this->menu_page_slug,
			'Global System Pages'
		);

		add_action( 'fm_submenu_global_system_pages', function () {
			$fm = new Fieldmanager_Group( array(
				'name'     => 'global_system_pages',
				'children' => array(
					'404-post-id'          => new Fieldmanager_Autocomplete( array(
						'label'            => esc_html_x( 'Select 404 Page', 'field manager label', 'prjpl-plugin-domain' ),
						'description'      => esc_html_x( 'Start typing the title of the page you wish to select', 'field manager label', 'prjpl-plugin-domain' ),
						'validation_rules' => array( 'required' => true ),
						'datasource'       => new Fieldmanager_Datasource_Post( array(
							'query_args' => array(
								'post_type' => array(
									'page',
								),
							),
						) ),
					) ),
					'500-post-id'          => new Fieldmanager_Autocomplete( array(
						'label'            => esc_html_x( 'Select 500 Page', 'field manager label', 'prjpl-plugin-domain' ),
						'description'      => esc_html_x( 'Start typing the title of the page you wish to select', 'field manager label', 'prjpl-plugin-domain' ),
						'validation_rules' => array( 'required' => true ),
						'datasource'       => new Fieldmanager_Datasource_Post( array(
							'query_args' => array(
								'post_type' => array(
									'page',
								),
							),
						) ),
					) ),
					'unsupported-post-id'  => new Fieldmanager_Autocomplete( array(
						'label'            => esc_html_x( 'Select Unsupported Page', 'field manager label', 'prjpl-plugin-domain' ),
						'description'      => esc_html_x( 'Start typing the title of the page you wish to select', 'field manager label', 'prjpl-plugin-domain' ),
						'validation_rules' => array( 'required' => true ),
						'datasource'       => new Fieldmanager_Datasource_Post( array(
							'query_args' => array(
								'post_type' => array(
									'page',
								),
							),
						) ),
					) ),
					'server-error-post-id' => new Fieldmanager_Autocomplete( array(
						'label'            => esc_html_x( 'Select Server Error Page', 'field manager label', 'prjpl-plugin-domain' ),
						'description'      => esc_html_x( 'Start typing the title of the page you wish to select', 'field manager label', 'prjpl-plugin-domain' ),
						'validation_rules' => array( 'required' => true ),
						'datasource'       => new Fieldmanager_Datasource_Post( array(
							'query_args' => array(
								'post_type' => array(
									'page',
								),
							),
						) ),
					) ),
				),
			) );
			$fm->activate_submenu_page();
		} );

	}

	/**
	 * google_analytics_menu
	 *
	 * @CALLED BY $this->register_init_field_groups()
	 *
	 * Register sub menu for google_analytics and render any option fields
	 *
	 * @access private
	 * @author Ben Moody
	 */
	private function google_analytics_menu() {

		fm_register_submenu_page(
			'google_analytics',
			$this->menu_page_slug,
			'Google Tag Manager Settings'
		);

		add_action( 'fm_submenu_google_analytics', function () {
			$fm = new Fieldmanager_Group( array(
				'name'     => 'google_analytics',
				'children' => array(
					'gtm_code' => new Fieldmanager_TextField(
						esc_html_x( 'Google Tag Manager Code', 'field manager label', 'prjpl-plugin-domain' ),
						array(
							'attributes' => array(
								'size' => 100,
							),
						)
					),
				),
			) );
			$fm->activate_submenu_page();
		} );

	}

	/**
	 * create_admin_menu_page
	 *
	 * @CALLED BY ACTION 'admin_menu'
	 *
	 * Add PARENT menu page for plugin settings
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function create_admin_menu_page() {

		add_menu_page(
			'Site Settings',
			'Theme Settings',
			'administrator',
			$this->menu_page_slug,
			array( $this, 'render_admin_global_settings_page' ),
			'dashicons-admin-site'
		);

	}

	/**
	 * render_admin_global_settings_page
	 *
	 * @CALLED BY 'add_menu_page'
	 *
	 * Render content for PARENT menu page for plugin settings
	 *
	 * @access public
	 * @author Ben Moody
	 */
	public function render_admin_global_settings_page() {

		?>
		<h1><?php echo esc_html_x( 'Global Settings', 'text', 'prjpl-plugin-domain' ); ?></h1>

		<ul>
			<li>
				<a href="<?php menu_page_url( 'global_system_pages' ); ?>">Set
					global system pages</a>
			</li>
			<li>
				<a href="<?php menu_page_url( 'google_analytics' ); ?>">Google
					Anayltics</a>
			</li>
		</ul>
		<?php

	}


}

new Prjpl_Field_Manager_Global_Options();

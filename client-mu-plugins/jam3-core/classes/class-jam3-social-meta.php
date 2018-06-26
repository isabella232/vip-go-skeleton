<?php
/**
 * Add metadata for social network and other crawlers
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Jam3_Social_Meta
 *
 * @class Jam3_Social_Meta
 * @version    1.0.0
 */
class Jam3_Social_Meta extends Jam3_Base {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'social_meta_data' ), 1 );

		// Jetpack
		add_action( 'wp_enqueue_scripts', array( $this, 'jetpack_remove_styles' ), 11 );
		add_filter( 'jetpack_enable_open_graph', '__return_false' );
	}

	/**
	 * Get the image from the global config or set a default
	 *
	 * @CALLED BY ACTION 'wp_head'
	 *
	 * @return string
	 */
	public static function get_social_media_image() {
		$image = ( defined( 'JAM3_SOCIAL_MEDIA_IMAGE' ) ) ? JAM3_SOCIAL_MEDIA_IMAGE : 'logo.png';

		return $image;
	}

	/**
	 * Remove Jetpack social icons styles
	 *
	 * @CALLED BY ACTION 'wp_enqueue_scripts'
	 */
	public function jetpack_remove_styles() {
		wp_dequeue_style( 'jetpack-widget-social-icons-styles' );
	}

	/**
	 * Add open graph meta tags
	 *
	 * @CALLED BY ACTION 'wp_head'
	 */
	public function social_meta_data() {
		global $post;

		if ( defined( 'JAM3_SOCIAL_MEDIA_ENABLED' ) && JAM3_SOCIAL_MEDIA_ENABLED ) {
			$blog_name    = get_bloginfo( 'name' );
			$content_type = 'website';

			$title = $blog_name;
			$desc  = get_bloginfo( 'description' );

			//Is this the blog home
			if ( is_home() ) {

				$blog_post_id = get_option( 'page_for_posts' );

				$post = get_post( $blog_post_id );

			}

			if ( isset( $post ) ) {
				$post_title = get_post_meta( $post->ID, 'mt_seo_title', true );
				if ( ! empty( $post_title ) ) {
					$title = $post_title;
				}

				$post_desc = get_post_meta( $post->ID, 'mt_seo_description', true );
				if ( ! empty( $post_desc ) ) {
					$desc = $post_desc;
				}
			}

			$url = get_permalink();
			if ( empty( $url ) ) {
				$url = get_site_url();
			}

			require_once JAM3_HTML_DIR . 'social-meta-data.php';
		}

	}
}

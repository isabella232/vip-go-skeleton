<?php
/**
 * Handle the Doctype.
 *
 * @since       1.0.0
 * @package        Jam3
 * @author        Jam3 VIP Dev Team
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Jam3_DocType
 *
 * @class Jam3_DocType
 * @version    1.0.0
 */
class Jam3_DocType {

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_filter( 'language_attributes', array(
			$this,
			'language_attributes',
		) );
	}

	/**
	 * Add global opengraph options
	 *
	 * Add FB opengraph schema to doctype
	 *
	 * @CALLED BY FILTER 'language_attributes'
	 *
	 * @param string $output Opengraph output.
	 *
	 * @return string
	 */
	public function language_attributes( $output ) {
		return $output . '
		xmlns:og="http://opengraphprotocol.org/schema/"
		xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
}

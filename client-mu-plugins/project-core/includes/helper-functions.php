<?php
/**
 * helper-functions.php
 *
 * Contains all helper functions for project
 *
 * @access public
 * @author Ben Moody
 */

/**
 * messtpl_include_all_files
 *
 * Helper to autoload all php files found in the supplied path
 *
 * @param string $path_to_files
 *
 * @access public
 * @author Ben Moody
 */
function prjpl_include_all_files( $path_to_files = null ) {

	//vars
	$pathnames = array();

	if ( empty( $path_to_files ) ) {
		return;
	}

	//Get pathnames of files in destination
	$pathnames = glob( "{$path_to_files}/*.php" );

	if ( false === $pathnames ) {
		return;
	}

	//Loop and include each found file
	foreach ( $pathnames as $file_path ) {

		prjpl_include_file( $file_path );

	}

	return;
}

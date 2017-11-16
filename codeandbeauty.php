<?php
/**
 * Plugin Name: CodeAndBeauty Starter Plugin
 * Description: Write your plugin description here.
 * Author: Code&Beauty
 * Author URI: http://www.codeandbeauty.com
 * Plugin URI: http://www.codeandbeauty.com/wordpress-starter-plugin
 * Version: 1.0.0
 * Text Domain: TextDomain
 * License: GPLv2 or higher
 */

try {
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'inc/class-codeandbeauty.php';

	/*d: Replace `precodeandbeauty` with your actual plugin prefix/name d:*/
	function precodeandbeauty() {
		return CodeAndBeauty::instance();
	}

	// Backward compatibility
	$GLOBALS['CodeAndBeauty'] = precodeandbeauty();
} catch( Exception $e ) {
	// Let someone know that your plugin have conflict
	if ( function_exists( 'error_log' ) ) :
		error_log( $e->getMessage() );
	endif;
}

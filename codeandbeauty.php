<?php
/**
 * Plugin Name: CodeAndBeauty Starter Plugin
 * Description: Write your plugin description here.
 * Author: Code&Beauty
 * Author URI: http://www.codeandbeauty.com
 * Plugin URI: http://www.codeandbeauty.com/starter-plugin
 * Version: 1.0.0
 * Text Domain: TextDomain
 * License: GPLv2 or higher
 */

//d: Replace `codeandbeauty` with your actual plugin prefix/name
if ( ! function_exists( 'codeandbeauty' ) ) :
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'inc/class-codeandbeauty.php';

	function codeandbeauty() {
		return CodeAndBeauty::instance();
	}

	// Backward compatibility
	$GLOBALS['CodeAndBeauty'] = codeandbeauty();
else :
	// Let someone know that your plugin got mess up!
endif;

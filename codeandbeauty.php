<?php
/**
 * Plugin Name: CodeAndBeauty Starter Plugin
 * Description: Write your plugin description here.
 * Author: Code&Beauty
 * Author URI: http://www.codeandbeauty.com
 * Plugin URI: http://www.codeandbeauty.com/starter-plugin
 * Version: 1.0.0
 * Text Domain: cad
 * License: GPLv2 or higher
 */
final class CodeAndBeauty {
	/**
	 * Version number control.
	 *
	 * @var string
	 */
	private $version = '1.0.0'; // Replace with current plugin version

	/**
	 * The absolute path of this plugin.
	 *
	 * @var string
	 */
	private $plugin_path = '';

	/**
	 * The relative path of this plugin.
	 *
	 * @var string
	 */
	private $plugin_url = '';

	/**
	 * An static instance holder of this class.
	 *
	 * @var null
	 */
	static $_instance = null;

	/**
	 * A single instance caller.
	 *
	 * @return CodeAndBeauty|null
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new Self();
		}
		return self::$_instance;
	}

	public function __construct() {
		// Define absolute and relative path
		$this->plugin_path = __DIR__ . DIRECTORY_SEPARATOR;
		$this->plugin_url = plugins_url( 'codeandbeauty-starter-plugin/' ); //d: Replace with your actual plugin directory name

		/**
		 * Set activation and deactivation hooks
		 *
		 * If your plugin doesn't require these hooks, simply remove the two lines
		 * below and it's corresponding methods. `onPluginActivate` and `onPluginDeactivate`
		 */
		register_activation_hook( __FILE__, array( $this, 'onPluginActivate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'onPluginDeactivate' ) );

		// Initialize this plugin
		add_action( 'plugins_loaded', array( $this, 'initialized' ) );
	}

	public function __get( $name ) {
		if ( isset( $this->{$name} ) ) {
			return $this->{$name};
		}
	}

	public function __set( $name, $value ) {
		$this->{$name} = $value;
	}

	public function onPluginActivate() {
		//d: Write your code that will be called whenever this plugins is activated.
	}

	public function onPluginDeactivate() {
		//d: Write your code here that will be called upon plugin deactivation
	}

	public function initialized() {
		// Load the ajax class to handle ajax request
		if ( $this->render( 'inc/class-ajax' ) ) {
			$this->__set( 'ajax', new CodeAndBeauty_Ajax() );
		}

		// Load assets class to include the needed scripts and stylesheets
		if ( $this->render( 'inc/class-assets' ) ) {
			$this->__set( 'assets', new CodeAndBeauty_Assets( $this ) );
		}

		// Set admin menu page(s)
		if ( $this->render( 'inc/class-menu' ) ) {
			$this->__set( 'admin_menu', new CodeAndBeauty_Menu( $this ) );
		}

		/**
		 * Trigger when all required files and hooks are called.
		 *
		 * @since 1.0.0
		 */
		do_action( 'codeandbeauty_initialized' );
	}

	/**
	 * Helper function to load file and/or template.
	 *
	 * @param $template     The absolute location of the template.
	 * @param array $args   An array of arguments that will be made available on the included file.
	 * @param bool $echo    Whether to print the file directly or return as string.
	 * @param bool $raw     Whether to include the file as raw where PHP code is not executed.
	 *
	 * @return bool|mixed|string
	 */
	public function render( $template, $args = array(), $echo = true, $raw = false ) {
		if ( ! $args ) {
			$args = array();
		}

		$filename = $this->plugin_path . $template;

		if ( ! preg_match( '%\.(css|html)%', $template ) ) {
			// If file extension is neither css nor html, it's automatically assumed PHP.
			$filename .= '.php';
		}

		if ( file_exists( $filename ) && is_readable( $filename ) ) {
			if ( is_array( $args ) && ! empty( $args ) ) {
				foreach ( $args as $key => $value ) {
					$$key = $value;
				}
			}

			if ( $echo ) {
				require $filename;
			} else {
				if ( ! $raw ) {
					ob_start();

					require $filename;

					return ob_get_clean();
				} else {
					$raw_file = file_get_contents( $filename );

					if ( is_array( $args ) ) {
						foreach ( $args as $key => $value ) {
							$raw_file = str_replace( '$' . $key, $value, $raw_file );
						}
					}

					return $raw_file;
				}
			}

			return true;
		}

		return false;
	}
}

//d: Replace `codeandbeauty` with your actual plugin prefix/name
if ( ! function_exists( 'codeandbeauty' ) ) :
	function codeandbeauty() {
		return CodeAndBeauty::instance();
	}

	// Backward compatibility
	$GLOBALS['CodeAndBeauty'] = codeandbeauty();
else :
	// Let someone know that your plugin got mess up!
endif;
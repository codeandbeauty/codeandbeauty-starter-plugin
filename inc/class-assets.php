<?php
if ( ! defined( 'ABSPATH' ) ) {
	die(); // No direct access!!!
}

/**
 * Class CodeAndBeauty_Assets
 *
 * This class is use to load admin and front assets simultaneously.
 */
class CodeAndBeauty_Assets {
	/**
	 * The relative location declared from the main class.
	 *
	 * @var string
	 */
	private $src = '';

	/**
	 * The plugin version number declared from the main class.
	 *
	 * @var string
	 */
	private $version = '';

	/**
	 * Contains the list of valid pages that will be use to validate
	 * the current loaded page.
	 *
	 * @var array
	 */
	protected $valid_pages = array();

	/**
	 * Contains the list of admin valid pages that will be use to validate
	 * the current loaded page.
	 *
	 * @var array
	 */
	protected $admin_valid_pages = array();

	public function __construct( $mainClass ) {
		// Let's grab the plugin url and version first
		$this->src = $mainClass->__get( 'plugin_url' );
		$this->version = $mainClass->__get( 'version' );

		// Set front assets
		add_action( 'wp_enqueue_scripts', array( $this, 'front_assets' ) );

		// Set admin assets
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
	}

	/**
	 * Check whether the current loaded page is one of the specified valid pages.
	 *
	 * Use this method to optimize the page performance and load only your requires assets
	 * at the appropriate pages.
	 *
	 * @return bool
	 */
	protected function is_valid_admin_page() {
		global $pagenow, $typenow;

		$valid_pages = $this->admin_valid_pages;
		$screen = get_current_screen();

		if ( empty( $valid_pages )
			|| ( $pagenow && in_array( $pagenow, $valid_pages ) )
			|| ( $typenow && in_array( $typenow, $valid_pages ) )
			|| ( $screen && ! empty( $screen->id ) && in_array( $screen->id, $valid_pages ) ) ) {
			// If no defined valid admin pages,
			return true;
		}

		return false;
	}

	public function admin_assets() {
		if ( false === $this->is_valid_admin_page() ) {
			return; // Don't set admin assets of the current page is not valid!
		}

		/**
		 * Set stylesheet and JS dependencies.
		 *
		 * If your scripts and stylesheets doesn't require dependencies, simply remove
		 * the entries in the array.
		 */
		$css_dependencies = array( 'dashicons' );
		$js_dependencies = array( 'jquery' );

		/***************************************
		 * Include your stylesheets here
		 *
		 * Sample:
		 * `wp_enqueue_style( 'cad-stylesheet', $this->src . 'css/style.min.css', $css_dependencies, $this->version );`
		 **************************************/

		/**************************************
		 * Include your scripts here
		 *
		 * Note*: It is highly recommended to include your js before the closing </body> tag, you
		 *      can achieve this by setting the 6th parameter in the example below into true.
		 *
		 * Sample:
		 * `wp_enqueue_script( 'cad-script', $this->src . 'js/script.min.js', $js_dependencies, $this->version, true );`
		 *************************************/
	}

	public function front_assets() {
		/**
		 * Set stylesheet and JS dependencies.
		 *
		 * If your scripts and stylesheets doesn't require dependencies, simply remove
		 * the entries in the array.
		 */
		$css_dependencies = array( 'dashicons' );
		$js_dependencies = array( 'jquery' );

		/***************************************
		 * Include your stylesheets here
		 *
		 * Sample:
		 * `wp_enqueue_style( 'cad-stylesheet', $this->src . 'css/style.min.css', $css_dependencies, $this->version );`
		 **************************************/

		/**************************************
		 * Include your scripts here
		 *
		 * Note*: It is highly recommended to include your js before the closing </body> tag, you
		 *      can achieve this by setting the 6th parameter in the example below into true.
		 *
		 * Sample:
		 * `wp_enqueue_script( 'cad-script', $this->src . 'js/script.min.js', $js_dependencies, $this->version, true );`
		 *************************************/
	}
}
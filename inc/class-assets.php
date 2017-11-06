<?php
/**
 * Class CodeAndBeauty_Assets
 *
 * Upfront and backend assets handler.
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

	/**
	 * @var CodeAndBeauty
	 */
	protected $mainClass;

	public function __construct( CodeAndBeauty $code_and_beauty ) {
		$this->mainClass = $code_and_beauty;

		// Let's grab the plugin url and version first
		$this->src = $code_and_beauty->__get( 'plugin_url' ) . 'assets/';
		$this->version = $code_and_beauty->__get( 'version' );

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

	/**
	 * Helper function to add admin valid page.
	 *
	 * @param $page
	 */
	public function add_admin_valid_page( $page ) {
		array_push( $this->admin_valid_pages, $page );
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
		$js_dependencies = array( 'jquery', 'backbone' );

		/***************************************
		 * Include your stylesheets here
		 *
		 * Sample:
		 * `wp_enqueue_style( 'cad-stylesheet', $this->src . 'css/style.min.css', $css_dependencies, $this->version );`
		 **************************************/
		wp_enqueue_style( 'cad-admin', $this->src . 'css/admin.min.css', $css_dependencies, $this->version );

		/**************************************
		 * Include your scripts here
		 *
		 * Note*: It is highly recommended to include your js before the closing </body> tag, you
		 *      can achieve this by setting the 6th parameter in the example below into true.
		 *
		 * Sample:
		 * `wp_enqueue_script( 'cad-script', $this->src . 'js/script.min.js', $js_dependencies, $this->version, true );`
		 *************************************/
		// Admin build
		wp_enqueue_script( 'cad-admin-js', $this->src . 'js/admin.min.js', $js_dependencies, $this->version, true );

		// Set admin local variables
		$local_vars = $this->get_admin_local_vars();
		wp_localize_script( 'cad-admin-js', 'codeandbeauty', $local_vars );
	}

	protected function get_admin_local_vars() {
		$vars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'_wpnonce' => wp_create_nonce( 'codeandbeauty_nonce' ),
			'messages' => array(
				'server_error' => __( 'An error occur while processing. Please contact your administrator.', 'cad' ),
			)
		);

		/**
		 * Fired before local variables is printed.
		 *
		 * If your writing a complex plugin where you needed to add localize variables per say,
		 * this hook is useful to help you achieve that.
		 */
		$vars = apply_filters( 'codeandbeauty_admin_local_vars', $vars );

		return $vars;
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

	protected function get_local_vars() {
		$vars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'_wpnonce' => wp_create_nonce( 'codeandbeauty_nonce' ),
			'messages' => array(
				'server_error' => __( 'An error occur while processing. Please contact your administrator.', 'cad' ),
			),
		);

		return $vars;
	}
}
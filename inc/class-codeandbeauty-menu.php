<?php
/**
 * Class CodeAndBeauty_Menu
 *
 * Use to set administrative menu for this plugin.
 *
 * @Note: If your plugin doesn't require a menu, you may remove this class
 *         and it's corresponding code at inside `CodeAndBeauty::initialized` method.
 */
class CodeAndBeauty_Menu {
	/**
	 * @var CodeAndBeauty
	 */
	protected $main_class;

	public function __construct( CodeAndBeauty $code_and_beauty ) {
		$this->main_class = $code_and_beauty;

		// Set the adder hook
		add_action( 'admin_menu', array( $this, 'set_menu' ) );
	}

	public function set_menu() {
		$title      = __( 'Menu', 'ui' );
		$menu_title = __( 'Menu', 'ui' );

		$menu = add_menu_page( $title, $menu_title, 'manage_options', 'codeandbeauty-menu', array( $this, 'get_page' ) );

		// Let's add the unique menu ID as one of admin's valid pages
		$this->main_class->assets->add_admin_valid_page( $menu );

		// Set a hook unto the page that is called before it is rendered.
		add_action( "load-{$menu}", array( $this, 'pre_process_page' ) );
	}

	/**
	 * Fired before the corresponding admin page is rendered.
	 */
	public function pre_process_page() {
		// Write your code here that will be called before the page is rendered or a form submission.
	}

	public function get_page() {
		// Write your HTML blocks here or call the corresponding template.

		// Sample:
		// @Note: Customize `admin-page.php` template or remove the line below.
		$this->main_class->render( 'templates/admin-page' );
	}
}

<?php
/**
 * Class CodeAndBeauty_Ajax
 *
 * Use as ajax request/response handler.
 *
 * For each request require an `action`. The name of the action param is also the name of the
 * class method name that will be called and executed prior to the request.
 * Example: If the action name is `register_user` (action=register_user), there should be a method
 * added `public function register_user($request) {}`.
 *
 * @since 1.0.0
 */
class CodeAndBeauty_Ajax {
	public function __construct() {
		/**
		 * Set the only actionable hook our ajax request have.
		 *
		 * You may add additional actionable hooks below, depending on your usage.
		 */
		add_action( 'wp_ajax_codeandbeauty_ajax_request', array( $this, 'process_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_codeandbeauty_ajax_request', array( $this, 'process_ajax_request' ) );
	}

	/**
	 * Use to receive and process any ajax request.
	 */
	public function process_ajax_request() {
		$request = json_decode( file_get_contents( 'php://input' ) );

		if ( empty( $request ) ) {
			// Try the get $_REQUEST method, maybe the corresponding JS code is
			// using native javascript or jquery library.
			$request = json_encode( $_REQUEST );
		}

		$error = array(
			'code'    => 'cannot_process',
			'message' => __( 'Something went wrong. Please try again.', 'TEXTDOMAIN' ),
		);

		if ( ! empty( $request->_wpnonce )
			&& wp_verify_nonce( $request->_wpnonce, 'codeandbeauty_nonce' ) ) {
			$request_action = $request->action;

			if ( method_exists( $this, $request_action ) ) {
				$response = call_user_func( array( $this, $request_action ), $request );

				if ( ! empty( $response['success'] ) ) {
					wp_send_json_success( $response );
				}

				if ( ! empty( $response['code'] ) ) {
					$error = wp_parse_args( $response, $error );
				}
			}
		}

		wp_send_json_error( $error );
	}

	/**
	 * Test request.
	 *
	 * @todo: Remove this method on production. This method is only use to test the request process.
	 *
	 * @param $request {
	 *      `return` (boolean) Whether to return the success message or error.
	 * }
	 *
	 * @return array
	 */
	public function test_request( $request ) {
		$response = array();

		if ( ! empty( $request->return ) ) {
			$response['success'] = true;
			$response['message'] = __( 'Yes, it work beautifully!', 'TEXTDOMAIN' );
		} else {
			$response['error']   = true;
			$response['message'] = __( 'Ooopsy! The request failed!', 'TEXTDOMAIN' );
		}

		return $response;
	}
}

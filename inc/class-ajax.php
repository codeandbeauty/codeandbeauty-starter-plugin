<?php
if ( ! defined( 'ABSPATH' ) ) {
	die(); // No direct access!!!
}

/**
 * Class CodeAndBeauty_Ajax
 *
 * This class is use to set server side ajax request callback.
 * Each method added here, except `process_ajax_request` are callback method
 * use in JS ajax request.
 */
class CodeAndBeauty_Ajax {
	public function __construct() {
		/**
		 * Set ajax request hook.
		 *
		 * Note*:
		 *  Replace `{PREFIX}` with your actual plugin prefix
		 **/
		add_action( 'wp_ajax_{PREFIX}_request', array( $this, 'process_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_{PREFIX}_request', array( $this, 'process_ajax_request' ) );
	}

	public function process_ajax_request() {
		$request = json_decode( file_get_contents( 'php://input' ) );

		if ( empty( $request ) ) {
			// Try the get $_REQUEST method, maybe the corresponding JS code is
			// using native javascript or jquery
			$request = json_encode( $_REQUEST );
		}

		$error = array(
			'code' => 'cannot_process',
			'message' => __( 'Something went wrong. Please try again.', '{TEXT_DOMAIN}' ),
		);

		if ( ! empty( $request->_wpnonce )
			&& wp_verify_nonce( $request->_wpnonce, '{PLUGIN_NONCE}' ) ) {
			$request_action = $request->request_action;

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
}
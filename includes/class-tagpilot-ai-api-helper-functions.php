<?php
if ( ! class_exists( 'Tagpilot_Ai_Api_Helper_Functions' ) ) {
	class Tagpilot_Ai_Api_Helper_Functions {
		const DANDELION_API_URL = 'https://api.dandelion.eu/datatxt/nex/v1';

		/**
		 * Get dandelion data
		 *
		 * @param  array $args
		 * @return array
		 */
		public static function tagpilot_ai_get_dandelion_api_results( $args ) {
			$return['status']  = 'error';
			$return['message'] = esc_html__( 'No matched result from the API Server.', 'tagpilot-ai' );
			$return['results'] = array();

			$settings_data  = $args['settings_data'];
			$content        = $args['content'];
			$clean_content  = $args['clean_content'];
			$content_source = $args['content_source'];

			$post_id                        = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;
			$dandelion_api_token            = ! empty( $settings_data['tagpilot_ai_api_key'] ) ? $settings_data['tagpilot_ai_api_key'] : '';
			$dandelion_api_confidence_value = ! empty( $settings_data['tagpilot_ai_api_confidence'] ) ? $settings_data['tagpilot_ai_api_confidence'] : '0.6';

			if ( empty( trim( $dandelion_api_token ) ) ) {
				$return['status']  = 'error';
				$return['message'] = esc_html__(
					'The Dandelion integration requires an API Key. Please add your API Key and save the settings.',
					'tagpilot-ai'
				);
			} elseif ( empty( trim( $content ) ) ) {

				$return['status']  = 'error';
				$return['message'] = esc_html__(
					'Selected content is empty.',
					'tagpilot-ai'
				);

			} else {
				$request_ws_args                   = array();
				$request_ws_args['text']           = $clean_content;
				$request_ws_args['min_confidence'] = $dandelion_api_confidence_value;
				$request_ws_args['token']          = $dandelion_api_token;
				$response                          = wp_remote_post(
					self::DANDELION_API_URL,
					array(
						'user-agent' => 'WordPress tagpilot-ai',
						'body'       => $request_ws_args,
					)
				);

				if ( ! is_wp_error( $response ) && $response != null ) {
					$status_code = wp_remote_retrieve_response_code( $response );
					$body_data   = json_decode( wp_remote_retrieve_body( $response ) );

					if ( $status_code !== 200 ) {
						$error_message     = ( is_object( $body_data ) && isset( $body_data->message ) ) ? $body_data->message : $status_code;
						$return['status']  = 'error';
						$return['message'] = sprintf( esc_html__( 'API Error: %1s.', 'tagpilot-ai' ), $error_message );
					} else {
						$data  = is_object( $body_data ) ? $body_data->annotations : '';
						$terms = array();
						if ( ! empty( $data ) ) {
							$terms             = (array) $data;
							$terms             = array_column( $terms, 'title' );
							$return['status']  = 'success';
							$return['results'] = $terms;
							$return['message'] = esc_html__(
								'Result from api.',
								'tagpilot-ai'
							);

							update_post_meta( $post_id, 'tagpilot_ai_terms_log', $terms );
							update_post_meta( $post_id, 'tagpilot_ai_content_log', $content );
						} else {
							$return['status']  = 'error';
							$return['message'] = esc_html__( 'No matched result from the API Server.', 'tagpilot-ai' );
						}
					}
				} else {
					$return['status']  = 'error';
					$return['message'] = esc_html__(
						'Error establishing connection with the API server. Try again.',
						'tagpilot-ai'
					);
				}
			}

			return $return;
		}

	}

	new Tagpilot_Ai_Api_Helper_Functions();
}

<?php
/**
 * Mutation - Submit_Mutation
 *
 * Registers mutation for submit a form.
 *
 * @package WPGraphQL\NinjaForms\Mutation
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Mutation;

use GraphQL\Error\UserError;

/**
 * Class Submit_Mutation
 */
class Submit_Mutation {
	/**
	 * Registers mutation
	 */
	public static function register() {
		register_graphql_mutation(
			'submitForm',
			[
				'inputFields'         => self::get_input_fields(),
				'outputFields'        => self::get_output_fields(),
				'mutateAndGetPayload' => function ( $input ) {
					$response = self::submit( $input['formId'], $input['data'] );

					$success = empty( $response['errors'] );
					$errors  = [];
					$message = $success
						? __( 'OK', 'wp-graphql-ninja-forms' )
						: __( 'An unknown error occurred', 'wp-graphql-ninja-forms' );

					if ( ! $success ) {
						if ( isset( $response['errors'][0] ) && is_string( $response['errors'][0] ) ) {
							$message = $response['errors'][0];
						} elseif ( isset( $response['errors']['form'][0] ) ) {
							$message = $response['errors']['form'][0];
						}
					}

					if ( isset( $response['errors']['fields'] ) ) {
						$message = 'Field validation error';

						foreach ( $response['errors']['fields'] as $id => $error ) {
							$errors[] = [
								'fieldId' => $id,
								'slug'    => $error['slug'],
								'message' => $error['message'],
							];
						}
					}

					return [
						'success' => $success,
						'message' => $message,
						'errors'  => $errors,
					];
				},
			]
		);
	}

	/**
	 * Handle the ninja form submission.
	 * This method is a big tradeoff around ninja form NF_AJAX_Controllers_Submission class.
	 *
	 * @param int   $form_id form id.
	 * @param array $data form data.
	 *
	 * @return array
	 * @throws UserError When an unknown error occurs.
	 */
	public static function submit( $form_id, $data ) {

		// mock the submit action.
		$form_data = [
			'id'     => $form_id,
			'fields' => [],
			'extra'  => [],
		];

		foreach ( $data as $field ) {
			$form_data['fields'][ $field['id'] ] = $field;
		}

		$current_time_stamp = time();
		$new_nonce_name     = 'ninja_forms_display_nonce_' . $current_time_stamp;

		$_POST['formData']    = wp_json_encode( $form_data );
		$_REQUEST['nonce_ts'] = $current_time_stamp;
		$_REQUEST['security'] = wp_create_nonce( $new_nonce_name );

		// this is a work-around to avoid wp_die to stop the submit execution
		// throw an exception instad of die.
		add_filter(
			'wp_die_json_handler',
			function () {
				throw new UserError( 'WP_NO_DIE' );
			}
		);

		ob_start();
		try {
			$sub = new \NF_AJAX_Controllers_Submission();
			$sub->submit();
		} catch ( \Exception $e ) {
			// unknown exception.
			if ( $e->getMessage() !== 'WP_NO_DIE' ) {
				throw new UserError( $e->getMessage() );
			}
		}

		try {
			$result = \json_decode( ob_get_clean(), true );
		} catch ( \Exception $e ) {
			$result['errors'][] = $e->getMessage();
		}

		return $result;
	}

	/**
	 * Mutation input fields
	 *
	 * @return array[]
	 */
	public static function get_input_fields() {
		return [
			'formId' => [
				'type'        => 'Int',
				'description' => __( 'Submited form Id', 'wp-graphql-ninja-forms' ),
			],
			'data'   => [
				'type'        => [ 'list_of' => 'InputField' ],
				'description' => __( 'Submited form data', 'wp-graphql-ninja-forms' ),
			],
		];
	}

	/**
	 * Mutation output fields
	 *
	 * @return array[]
	 */
	public static function get_output_fields() {
		return [
			'success' => [
				'type'        => 'Boolean',
				'description' => __( 'Form submitted successfuly', 'wp-graphql-ninja-forms' ),
			],
			'message' => [
				'type'        => 'String',
				'description' => __( 'Generic operation message', 'wp-graphql-ninja-forms' ),
			],
			'errors'  => [
				'type'        => [ 'list_of' => 'FieldError' ],
				'description' => __( 'Field errors', 'wp-graphql-ninja-forms' ),
			],
		];
	}
}

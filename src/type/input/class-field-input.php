<?php
/**
 * Input Type - Field_Input
 *
 * Register Field input type.
 *
 * @package WPGraphQL\NinjaForms\Type\Input
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Type\Input;

/**
 * Class Field_Input
 */
class Field_Input {
	/**
	 * Register Field input type.
	 */
	public static function register() {
		register_graphql_input_type(
			'InputField',
			[
				'description' => __( 'Submitted field data', 'wp-graphql-ninja-forms' ),
				'fields'      => [
					'id'    => [
						'type'        => 'Int',
						'description' => __( 'Field Id', 'wp-graphql-ninja-forms' ),
					],
					'value' => [
						'type'        => 'String',
						'description' => __( 'Field value', 'wp-graphql-ninja-forms' ),
					],
				],
			]
		);
	}
}

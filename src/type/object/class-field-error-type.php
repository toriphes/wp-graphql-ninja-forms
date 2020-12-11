<?php
/**
 * WPObject Type - Field_Error_Type
 *
 * Register Field error type.
 *
 * @package WPGraphQL\NinjaForms\Type\WPObject
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Type\WPObject;

/**
 * Class Field_Error_Type
 */
class Field_Error_Type {
	/**
	 * Register Field error type.
	 */
	public static function register() {
		register_graphql_object_type(
			'FieldError',
			[
				'description' => __( 'Type of ID being used identify the form', 'wp-graphql-ninja-forms' ),
				'fields'      => [
					'fieldId' => [
						'type'        => [ 'non_null' => 'Int' ],
						'description' => __( 'Field Id', 'wp-graphql-ninja-forms' ),
					],
					'slug'    => [
						'type'        => 'String',
						'description' => __( 'Slug error identifier', 'wp-graphql-ninja-forms' ),
					],
					'message' => [
						'type'        => 'String',
						'description' => __( 'Localized error message', 'wp-graphql-ninja-forms' ),
					],
				],
			]
		);
	}
}

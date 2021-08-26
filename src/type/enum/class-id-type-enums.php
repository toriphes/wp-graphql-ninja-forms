<?php
/**
 * Register the Enum used for setting the field to identify ninja forms objects
 *
 * @package WPGraphQL\NinjaForms\Type\Enum
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Enum;

/**
 * Class Id_Type_Enums
 */
class Id_Type_Enums {
	/**
	 * Register the Enum used for setting the field to identify ninja forms objects
	 */
	public static function register() {
		register_graphql_enum_type(
			'FormIdTypeEnum',
			[
				'description'     => __(
					'The Type of Identifier used to fetch a single Form. Default is ID.',
					'wp-graphql-ninja-forms'
				),
				'values'          => [
					'ID'          => [
						'name'        => 'ID',
						'value'       => 'global_id',
						'description' => __(
							'Identify a resource by the (hashed) Global ID.',
							'wp-graphql-ninja-forms'
						),
					],
					'DATABASE_ID' => [
						'name'        => 'DATABASE_ID',
						'value'       => 'database_id',
						'description' => __( 'Identify a resource by the Database ID.', 'wp-graphql-ninja-forms' ),
					],
				],
			]
		);
	}
}

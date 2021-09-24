<?php
/**
 * Register the Enum used for the position of the field label
 *
 * @package WPGraphQL\NinjaForms\Type\Enum
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Enum;

/**
 * Class Field_label_Pos_Enums
 */
class Field_Label_Pos_Enums {
	/**
	 * Register the Enum used for the position of the field label
	 */
	public static function register() {
		register_graphql_enum_type(
			'FieldLabelPosEnum',
			[
				'description'     => __(
					'The Enum used for the position of the field label.',
					'wp-graphql-ninja-forms'
				),
				'values'      => [
					'DEFAULT'     => [
						'value'       => 'default',
					],
					'ABOVE'       => [
						'value'       => 'above',
					],
					'BELOW'       => [
						'value'       => 'below',
					],
					'LEFT'        => [
						'value'       => 'left',
					],
					'RIGHT'       => [
						'value'       => 'right',
					],
					'HIDDEN'       => [
						'value'       => 'hidden',
					],
				],
			]
		);
	}
}
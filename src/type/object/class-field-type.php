<?php
/**
 * WPObject Type - Field_Type
 *
 * Register Field related types to the WPGraphQL schema.
 *
 * @package WPGraphQL\NinjaForms\Type\WPObject
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Type\WPObject;

use GraphQL\Error\UserError;
use GraphQLRelay\Relay;
use WPGraphQL\AppContext;

use WPGraphQL\NinjaForms\Utils\NF_Mapper;

/**
 * Class Field_Type
 */
class Field_Type {
	/**
	 * Register Field related types to the WPGraphQL schema.
	 */
	public static function register() {
		/**
		 * Abstract field from ninja forms
		 *
		 * @var $field \NF_Abstracts_Field
		 */
		foreach ( \Ninja_Forms::instance()->fields as $field ) {
			$type_name = ucfirst( $field->get_name() ) . 'Field';

			$fields = array_merge(
				self::get_common_fields(),
				NF_Mapper::get_fields( $field->get_settings(), $type_name )
			);

			register_graphql_object_type(
				$type_name,
				[
					'description' => $field->get_nicename(),
					'interfaces'  => [ 'Node', 'DatabaseIdentifier', 'FormField' ],
					'fields'      => $fields,
				]
			);
		}

		register_graphql_field(
			'RootQuery',
			'formField',
			[
				'type'    => 'FormField',
				'args'    => [
					'id'     => [
						'type' => [ 'non_null' => 'ID' ],
					],
					'idType' => [
						'type' => 'FormIdTypeEnum',
					],
				],
				'fields'  => self::get_common_fields(),
				'resolve' => function ( $source, array $args, AppContext $context ) {
					$id      = isset( $args['id'] ) ? $args['id'] : null;
					$id_type = isset( $args['idType'] ) ? $args['idType'] : 'global_id';

					$form_id = null;

					switch ( $id_type ) {
						case 'database_id':
							$form_id = absint( $id );
							break;
						case 'global_id':
						default:
							$id_components = Relay::fromGlobalId( $args['id'] );
							if ( ! isset( $id_components['id'] ) || ! absint( $id_components['id'] ) ) {
								throw new UserError(
									__(
										'The ID input is invalid. Make sure you set the proper idType for your input.',
										'wp-graphql-ninja-forms'
									)
								);
							}
							$form_id = absint( $id_components['id'] );
							break;
					}

					return $context->get_loader( 'form_field' )->load_deferred( $form_id );
				},
			],
		);

		register_graphql_object_type(
			'FieldOption',
			[
				'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
				'fields'      => [
					'label'    => [
						'type'        => 'String',
						'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
					],
					'value'    => [
						'type'        => 'String',
						'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
					],
					'calc'     => [
						'type'        => 'String',
						'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
					],
					'selected' => [
						'type'        => 'Boolean',
						'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
					],
					'order'    => [
						'type'        => 'Int',
						'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
					],
				],
			],
		);
	}

	/**
	 * Returns an array of fields for the type *Field
	 *
	 * @return array
	 */
	public static function get_common_fields() {
		return [
			'id'                     => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'The globally unique identifier of the field', 'wp-graphql-ninja-forms' ),
			],
			'fieldId'                => [
				'type'        => 'Int',
				'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
			],
			'databaseId'             => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
			],
			'label'                  => [
				'type'        => 'String',
				'description' => __( 'Label of the field', 'wp-graphql-ninja-forms' ),
			],
			'key'                    => [
				'type'        => 'String',
				'description' => __( 'Key of the field', 'wp-graphql-ninja-forms' ),
			],
			'type'                   => [
				'type'        => 'String',
				'description' => __( 'type of the field', 'wp-graphql-ninja-forms' ),
			],
			'parentId'               => [
				'type'        => 'Int',
				'description' => __( 'Parent form id', 'wp-graphql-ninja-forms' ),
			],
			'createdAt'              => [
				'type'        => 'Int',
				'description' => __( 'Creation date', 'wp-graphql-ninja-forms' ),
			],
			'updatedAt'              => [
				'type'        => 'Int',
				'description' => __( 'Update date', 'wp-graphql-ninja-forms' ),
			],
			'fieldLabel'             => [
				'type'        => 'String',
				'description' => __( 'Label of the field', 'wp-graphql-ninja-forms' ),
			],
			'fieldKey'               => [
				'type'        => 'String',
				'description' => __( 'Key of the field', 'wp-graphql-ninja-forms' ),
			],
			'order'                  => [
				'type'        => 'Int',
				'description' => __( 'Position order of the field', 'wp-graphql-ninja-forms' ),
			],
			'required'               => [
				'type'        => 'Boolean',
				'description' => __( 'The field is required?', 'wp-graphql-ninja-forms' ),
			],
			'labelPos'               => [
				'type'        => 'FieldLabelPosEnum',
				'description' => __( 'Position of the label', 'wp-graphql-ninja-forms' ),
			],
			'personallyIdentifiable' => [
				'type'        => 'Boolean',
				'description' => __( 'Indtifiable?', 'wp-graphql-ninja-forms' ),
			],
		];
	}
}

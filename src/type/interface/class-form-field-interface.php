<?php
/**
 * WPInterface Type - FormField
 *
 * Registers FormField interface to the graphql schema
 *
 * @package WPGraphQL\NinjaForms\Type\WPInterface
 * @since   0.1.0
 */

namespace WPGraphQL\NinjaForms\Type\WPInterface;

use \WPGraphQL\Registry\TypeRegistry;

use WPGraphQL\NinjaForms\NinjaForms;
use WPGraphQL\NinjaForms\Model\Field_Model;

/**
 * Class Form_Field_Interface
 */
class Form_Field_Interface {
	/**
	 * Registers FormField interface to the graphql schema
	 *
	 * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public static function register( &$type_registry ) {
		register_graphql_interface_type(
			'FormField',
			[
				'description' => __( 'Action object', 'wp-graphql-ninja-forms' ),
				'fields'      => [
					'id'                     => [
						'type'        => [ 'non_null' => 'ID' ],
						'description' => __( 'The globally unique identifier of the field', 'wp-graphql-ninja-forms' ),
					],
					'fieldId'                => [
						'type'        => 'Int',
						'description' => __( 'The Id of the field', 'wp-graphql-ninja-forms' ),
					],
					'databaseId'             => [
						'type'        => 'Int!',
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
						FieldLabelPosEnum						'type'        => 'String',
						'description' => __( 'Position of the label', 'wp-graphql-ninja-forms' ),
					],
					'personallyIdentifiable' => [
						'type'        => 'Boolean',
						'description' => __( 'Indtifiable?', 'wp-graphql-ninja-forms' ),
					],
				],
				'resolveType' => function ( Field_Model $model ) use ( &$type_registry ) {
					$possible_types = NinjaForms::get_aviable_fields();

					if ( isset( $possible_types[ $model->type ] ) ) {
						return $type_registry->get_type( $possible_types[ $model->type ] );
					}

					return null;
				},
			]
		);
	}
}

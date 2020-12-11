<?php
/**
 * WPObject Type - Form_Type
 *
 * Register Form related types to the WPGraphQL schema.
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
 * Class Form_Type
 */
class Form_Type {
	/**
	 * Register Form related types to the WPGraphQL schema.
	 */
	public static function register() {
		self::register_form();
	}

	/**
	 * Register GraphQL Form type
	 */
	public static function register_form() {
		register_graphql_object_type(
			'Form',
			[
				'description' => __( 'The form object', 'wp-graphql-ninja-forms' ),
				'interfaces'  => [ 'Node', 'DatabaseIdentifier' ],
				'fields'      => self::get_fields(),
			]
		);

		// register form field in the RootQuery.
		register_graphql_field(
			'RootQuery',
			'form',
			[
				'description' => __( 'Ninja form object data.', 'wp-graphql-ninja-forms' ),
				'type'        => 'Form',
				'args'        => [
					'id'     => [
						'type' => [ 'non_null' => 'ID' ],
					],
					'idType' => [
						'type' => 'FormIdTypeEnum',
					],
				],
				'resolve'     => function ( $source, array $args, AppContext $context ) {
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

					return $context->get_loader( 'form' )->load_deferred( $form_id );
				},
			]
		);
	}

	/**
	 * Returns the form field list
	 *
	 * @return array
	 */
	public static function get_fields() {
		$fields = [
			'id'         => [
				'type'        => [ 'non_null' => 'ID' ],
				'description' => __( 'The globally unique identifier of the form', 'wp-graphql-ninja-forms' ),
			],
			'formId'     => [
				'type'        => 'Int',
				'description' => __( 'The Id of the form', 'wp-graphql-ninja-forms' ),
			],
			'databaseId' => [
				'type'        => [ 'non_null' => 'Int' ],
				'description' => __( 'The Id of the form', 'wp-graphql-ninja-forms' ),
			],
			'seqNum'     => [
				'type'        => 'Int',
				'description' => __( 'The Locale of the form', 'wp-graphql-ninja-forms' ),
			],

		];

		$fields += NF_Mapper::get_fields( \Ninja_Forms::config( 'FormDisplaySettings' ), 'Form' );
		$fields += NF_Mapper::get_fields( \Ninja_Forms::config( 'FormRestrictionSettings' ), 'Form' );

		return $fields;
	}
}

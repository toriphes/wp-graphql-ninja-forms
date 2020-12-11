<?php
/**
 * Connection - Fields
 *
 * Registers connections to Fields
 *
 * @package WPGraphQL\NinjaForms\Connection
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

use WPGraphQL\NinjaForms\Data\Connection\Field_Connection_Resolver;

/**
 * Class Form_Connection
 */
class Field_Connection {
	/**
	 * Registers field connection from RootQuery
	 */
	public static function register() {
		register_graphql_connection(
			[
				'fromType'       => 'Form',
				'toType'         => 'FormField',
				'fromFieldName'  => 'fields',
				'connectionArgs' => [],
				'resolve'        => function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$resolver = new Field_Connection_Resolver( $source, $args, $context, $info );

					return $resolver->get_connection();
				},
			]
		);
	}
}

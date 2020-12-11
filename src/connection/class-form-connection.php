<?php
/**
 * Connection - Forms
 *
 * Registers connections to Forms
 *
 * @package WPGraphQL\NinjaForms\Connection
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

use WPGraphQL\NinjaForms\Data\Connection\Form_Connection_Resolver;

/**
 * Class Form_Connection
 */
class Form_Connection {
	/**
	 * Registers form connection from RootQuery
	 */
	public static function register() {
		register_graphql_connection(
			[
				'fromType'       => 'RootQuery',
				'toType'         => 'Form',
				'fromFieldName'  => 'forms',
				'connectionArgs' => [],
				'resolve'        => function ( $source, array $args, AppContext $context, ResolveInfo $info ) {
					$resolver = new Form_Connection_Resolver( $source, $args, $context, $info );

					return $resolver->get_connection();
				},
			]
		);
	}
}

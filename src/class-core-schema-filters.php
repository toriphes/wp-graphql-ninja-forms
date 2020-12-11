<?php
/**
 * Adds filters that modify core schema.
 *
 * @package WPGraphQL\NinjaForms
 * @since   0.1.0
 */

namespace WPGraphQL\NinjaForms;

use WPGraphQL\AppContext;
use WPGraphQL\NinjaForms\Data\Loader\Form_Loader;
use WPGraphQL\NinjaForms\Data\Loader\Field_Loader;
use WPGraphQL\NinjaForms\Model\Form_Model;

/**
 * Class CoreSchemaFilters
 */
class Core_Schema_Filters {
	/**
	 * Register filters
	 */
	public static function add_filters() {
		// Add data-loaders to AppContext.
		add_filter( 'graphql_data_loaders', [ __CLASS__, 'graphql_data_loaders' ], 10, 2 );
		add_filter( 'graphql_resolve_node_type', [ __CLASS__, 'resolve_node_type' ], 10, 2 );
	}

	/**
	 * Registers data-loaders to be used when resolving Ninja Forms related GraphQL types.
	 *
	 * @param array      $loaders assigned loaders.
	 * @param AppContext $context AppContext instance.
	 *
	 * @return array
	 */
	public static function graphql_data_loaders( $loaders, $context ) {
		$loaders['form']       = new Form_Loader( $context );
		$loaders['form_field'] = new Field_Loader( $context );

		/**
		 * $loaders['FormAction'] = new Action_Loader( $context );
		 */

		return $loaders;
	}

	/**
	 * Resolves Relay node type for some NinjaForm types.
	 *
	 * @param string|null $type Node type.
	 * @param mixed       $node Node object.
	 *
	 * @return string|null
	 */
	public static function resolve_node_type( $type, $node ) {
		switch ( true ) {
			case is_a( $node, Form_Model::class ):
				$type = 'Form';
				break;
		}

		return $type;
	}
}

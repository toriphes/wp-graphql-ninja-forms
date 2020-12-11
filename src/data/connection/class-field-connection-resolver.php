<?php
/**
 * ConnectionResolver - Field_Connection_Resolver
 *
 * Resolves connections to Fields
 *
 * @package WPGraphQL\NinjaForms\Data\Connection
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Data\Connection;

use WPGraphQL\Data\Connection\AbstractConnectionResolver;
use WPGraphQL\NinjaForms\Model\Form_Model;

/**
 * Class Field_Connection_Resolver
 */
class Field_Connection_Resolver extends AbstractConnectionResolver {
	/**
	 * Return the name of the loader to be used with the connection resolver
	 *
	 * @return string
	 */
	public function get_loader_name() {
		return 'form_field';
	}

	/**
	 * Creates query arguments array
	 *
	 * @return array
	 */
	public function get_query_args() {
		$query_args = [];


		if ( $this->source instanceof Form_Model ) {
			$query_args['parentId'] = $this->source->formId;
		}

		return $query_args;
	}

	/**
	 * Executes the query
	 *
	 * @return array
	 */
	public function get_query() {
		// @TODO: handle query args
		$parent_id = isset( $this->query_args['parentId'] ) ? $this->query_args['parentId'] : '';

		return Ninja_Forms()->form( $parent_id )->get_fields();
	}

	/**
	 * Return an array of items from the query
	 *
	 * @return array
	 */
	public function get_ids() {
		$queried = $this->query;
		$ids     = [];

		foreach ( $queried as $key => $item ) {
			$ids[ $key ] = $item->get_id();
		}

		return $ids;
	}

	public function get_nodes() {
		$nodes = parent::get_nodes();
		if ( isset( $this->args['after'] ) ) {
			$key   = array_search( $this->get_offset(), array_keys( $nodes ), true );
			$nodes = array_slice( $nodes, $key + 1, null, true );
		}

		if ( isset( $this->args['before'] ) ) {
			$nodes = array_reverse( $nodes );
			$key   = array_search( $this->get_offset(), array_keys( $nodes ), true );
			$nodes = array_slice( $nodes, $key + 1, null, true );
			$nodes = array_reverse( $nodes );
		}

		$nodes = array_slice( $nodes, 0, $this->query_amount, true );

		return ! empty( $this->args['last'] ) ? array_filter( array_reverse( $nodes, true ) ) : $nodes;
	}

	/**
	 * Checks if user is authorized to query forms
	 *
	 * @return bool
	 */
	public function should_execute() {
		return true;
	}

	/**
	 * Determine whether or not the the offset is valid.
	 *
	 * @param int $offset object id.
	 *
	 * @return bool
	 */
	public function is_valid_offset( $offset ) {
		return in_array( $offset, array_values( $this->get_ids() ), true );
	}
}

<?php
/**
 * ConnectionResolver - Form_Connection_Resolver
 *
 * Resolves connections to Forms
 *
 * @package WPGraphQL\NinjaForms\Data\Connection
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Data\Connection;

use WPGraphQL\Data\Connection\AbstractConnectionResolver;

/**
 * Class Form_Connection_Resolver
 */
class Form_Connection_Resolver extends AbstractConnectionResolver {
	/**
	 * Return the name of the loader to be used with the connection resolver
	 *
	 * @return string
	 */
	public function get_loader_name() {
		return 'form';
	}

	/**
	 * Creates query arguments array
	 *
	 * @return array
	 */
	public function get_query_args() {
		return [];
	}

	/**
	 * Executes the query
	 *
	 * @return array
	 */
	public function get_query() {
		// @TODO: handle query args
		return Ninja_Forms()->form()->get_forms();
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

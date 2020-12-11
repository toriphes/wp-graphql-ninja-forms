<?php
/**
 * DataLoader - Form_Loader
 *
 * Loads Form Model
 *
 * @package WPGraphQL\NinjaForms\Data\Loader
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Data\Loader;

use WPGraphQL\Data\Loader\AbstractDataLoader;
use WPGraphQL\NinjaForms\Model\Form_Model;

/**
 * Class Form_Loader
 */
class Form_Loader extends AbstractDataLoader {
	/**
	 * Processes given array keys and loads Model
	 *
	 * @param array $keys array of ninja form IDs.
	 *
	 * @return array
	 * @throws \Exception When ninja form data object is empty.
	 */
	public function loadKeys( array $keys ) {
		$loaded_forms = [];

		if ( empty( $keys ) ) {
			return $loaded_forms;
		}

		foreach ( $keys as $id ) {
			$loaded_forms[ $id ] = new Form_Model( \Ninja_Forms()->form( $id )->get() );
		}

		return $loaded_forms;
	}
}

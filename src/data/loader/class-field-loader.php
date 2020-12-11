<?php
/**
 * DataLoader - Field_Loader
 *
 * Loads Field Model
 *
 * @package WPGraphQL\NinjaForms\Data\Loader
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Data\Loader;

use WPGraphQL\Data\Loader\AbstractDataLoader;
use WPGraphQL\NinjaForms\Model\Field_Model;

/**
 * Class Field_Loader
 */
class Field_Loader extends AbstractDataLoader {
	/**
	 * Processes given array keys and loads Model
	 *
	 * @param array $keys array of ninja form field IDs.
	 *
	 * @return array
	 * @throws \Exception When ninja form field data object is empty.
	 */
	public function loadKeys( array $keys ) {
		$loaded_fields = [];

		if ( empty( $keys ) ) {
			return $loaded_fields;
		}

		foreach ( $keys as $id ) {
			$loaded_fields[ $id ] = new Field_Model( \Ninja_Forms()->form()->get_field( $id ) );
		}

		return $loaded_fields;
	}
}

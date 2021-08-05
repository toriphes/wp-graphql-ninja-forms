<?php
/**
 * Map ninja form settings in graphl fields.
 * Useful to convert settings from Ninja Forms entities in the graphql schema
 *
 * @package WPGraphQL\NinjaForms\Utils
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Utils;

/**
 * Class NF_Mapper
 */
class NF_Mapper {
	/**
	 * Return fields from the ninja form settings
	 *
	 * @param array  $data ninja form entity settigns.
	 * @param string $base_type graphql type where the fields belongs to.
	 *
	 * @return array
	 */
	public static function get_fields( array $data, $base_type ) {
		$fields        = [];
		$type_registry = \WPGraphQL::get_type_registry();

		foreach ( $data as $setting ) {
			$field_name = graphql_format_field_name( $setting['name'] );

			if ( empty( $field_name ) ) {
				continue;
			}

			if ( 'option-repeater' === $setting['type'] ) {
				$fields[ $field_name ] = [
					'type'        => [ 'list_of' => 'FieldOption' ],
					'description' => $setting['name'],
				];
			} elseif ( 'fieldset' === $setting['type'] ) {
				$type = $base_type . ucfirst( $field_name );

				if ( $type_registry->get_type( $type ) === null ) {
					// register nested types.
					register_graphql_object_type(
						$type,
						[
							'description' => $setting['label'],
							'fields'      => self::get_fields( $setting['settings'], $type ),
						]
					);
				}

				$fields[ $field_name ] = [
					'type'        => $type,
					'description' => $setting['label'],
				];
			} else {
				switch ( $setting['type'] ) {
					case 'toggle':
						$type = 'Boolean';
						break;
					case 'number':
						$type = 'Int';
						break;
					default:
						$type = 'String';
				}

				$fields[ $field_name ] = [
					'type'        => $type,
					'description' => isset( $setting['label'] ) ? $setting['label'] : $setting['name'],
				];
			}
		}

		return $fields;
	}

	/**
	 * Return field resolvers from the ninja form settings
	 *
	 * @param array $data ninja form entity settings.
	 * @param array $all_settings all ninja form entity settings.
	 *
	 * @return array
	 */
	public static function get_resolvers( array $data, &$all_settings ) {
		$fields = [];

		foreach ( $data as $field_settings ) {
			$name       = $field_settings['name'];
			$field_name = graphql_format_field_name( $name );
			$value      = null;

			if ( isset( $all_settings[ $name ] ) ) {
				$value = $all_settings[ $name ];
				unset( $all_settings[ $name ] );
			}

			switch ( $field_settings['type'] ) {
				case 'option-repeater':
					$return_value = function () use ( $value ) {
						$options = [];
						foreach ( $value as $option ) {
							$options[] = [
								'label'    => $option['label'],
								'order'    => (int) $option['order'],
								'selected' => 1 === $option['selected'],
								'calc'     => $option['valc'],
								'value'    => $option['value'],
							];
						}

						return $options;
					};
					break;
				case 'fieldset':
					$return_value = function () use ( $field_settings, &$all_settings ) {
						return self::get_resolvers( $field_settings['settings'], $all_settings );
					};
					break;
				case 'toggle':
					$return_value = $value && (int) 1 === (int) $value;
					break;
				case 'number':
					$return_value = $value ? (int) $value : null;
					break;
				default:
					$return_value = $value;
			}

			if ( $field_name ) {
				$fields[ $field_name ] = function () use ( $return_value ) {
					return $return_value;
				};
			}
		}

		return $fields;
	}
}

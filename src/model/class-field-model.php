<?php
/**
 * Model Field
 *
 * Resolves form field object model
 *
 * @package WPGraphQL\NinjaForms\Model
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Model;

use GraphQLRelay\Relay;
use WPGraphQL\Model\Model as WPGQLModel;

use WPGraphQL\NinjaForms\Utils\NF_Mapper;

/**
 * Class Field_Model
 */
class Field_Model extends WPGQLModel {
	/**
	 * Stores the incoming form data
	 *
	 * @var \NF_Database_Models_Field $data
	 */
	protected $data;

	/**
	 * Field_Model constructor.
	 *
	 * @param \NF_Database_Models_Field $field_model Original Ninja Forms Field Model Class.
	 *
	 * @throws \Exception When $data object is empty.
	 */
	public function __construct( \NF_Database_Models_Field $field_model ) {
		$this->data = $field_model;

		parent::__construct();
	}

	/**
	 * Initializes the field resolvers
	 *
	 * @return void
	 */
	protected function init() {
		if ( empty( $this->fields ) ) {
			$this->fields = [
				'ID'         => function () {
					return $this->data->get_id();
				},
				'fieldId'    => function () {
					return $this->data->get_id();
				},
				'databaseId' => function () {
					return $this->data->get_id();
				},
				'id'         => function () {
					return ! empty( $this->data->get_id() )
						? Relay::toGlobalId( 'FormField', $this->data->get_id() )
						: null;
				},
			];

			$all_settings = $this->data->get_settings();

			/**
			 * Ninja form
			 *
			 * @var $nf_field_class \NF_Abstracts_Field Ninja form abstract field class.
			 */
			$nf_field_class = \Ninja_Forms()->fields[ $this->data->get_setting( 'type' ) ];

			if ( isset( $all_settings['id'] ) ) {
				unset( $all_settings['id'] );
			}

			$this->fields += NF_Mapper::get_resolvers( $nf_field_class->get_settings(), $all_settings );

			foreach ( $all_settings as $key => $value ) {
				$field_name                  = graphql_format_field_name( $key );
				$this->fields[ $field_name ] = $value;
			}
		}
	}
}

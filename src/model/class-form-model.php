<?php
/**
 * Model Form
 *
 * Resolves form object model
 *
 * @package WPGraphQL\NinjaForms\Model
 * @since 0.1.0
 */

namespace WPGraphQL\NinjaForms\Model;

use GraphQLRelay\Relay;
use WPGraphQL\Model\Model as WPGQLModel;

use WPGraphQL\NinjaForms\Utils\NF_Mapper;

/**
 * Class Form_Model
 */
class Form_Model extends WPGQLModel {

	/**
	 * Stores the incoming form data
	 *
	 * @var \NF_Database_Models_Form $data
	 */
	protected $data;

	/**
	 * Form_Model constructor.
	 *
	 * @param \NF_Database_Models_Form $form_model Original Ninja Forms Model Class.
	 *
	 * @throws \Exception When $data object is empty.
	 */
	public function __construct( \NF_Database_Models_Form $form_model ) {
		$this->data = $form_model;

		parent::__construct();
	}

	/**
	 * Initializes the Form field resolvers
	 *
	 * @return void
	 */
	protected function init() {
		if ( empty( $this->fields ) ) {
			$this->fields = [
				'ID'         => function () {
					return $this->data->get_id();
				},
				'formId'     => function () {
					return $this->data->get_id();
				},
				'databaseId' => function () {
					return $this->data->get_id();
				},
				'id'         => function () {
					return ! empty( $this->data->get_id() )
						? Relay::toGlobalId( 'NinjaForm', $this->data->get_id() )
						: null;
				},
			];

			$all_settings = $this->data->get_settings();

			// id is already handled.
			if ( isset( $all_settings['id'] ) ) {
				unset( $all_settings['id'] );
			}

			$this->fields += NF_Mapper::get_resolvers( \Ninja_Forms::config( 'FormDisplaySettings' ), $all_settings );
			$this->fields += NF_Mapper::get_resolvers( \Ninja_Forms::config( 'FormRestrictionSettings' ), $all_settings );

			foreach ( $all_settings as $key => $value ) {
				$field_name                  = graphql_format_field_name( $key );
				$this->fields[ $field_name ] = $value;
			}
		}
	}
}

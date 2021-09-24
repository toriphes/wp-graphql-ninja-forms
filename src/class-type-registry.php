<?php
/**
 * Registers NinjaForms types to the schema.
 *
 * @package WPGraphQL\NinjaForms
 * @since   0.1.0
 */

namespace WPGraphQL\NinjaForms;

use WPGraphQL\Registry\TypeRegistry;

use WPGraphQL\NinjaForms\Enum\Id_Type_Enums;
use WPGraphQL\NinjaForms\Enum\Field_Label_Pos_Enums;
use WPGraphQL\NinjaForms\Type\WPInterface\Form_Field_Interface;
use WPGraphQL\NinjaForms\Type\WPObject\Form_Type;
use WPGraphQL\NinjaForms\Type\WPObject\Field_Type;
use WPGraphQL\NinjaForms\Type\WPObject\Field_Error_Type;
use WPGraphQL\NinjaForms\Type\Input\Field_Input;
use WPGraphQL\NinjaForms\Connection\Form_Connection;
use WPGraphQL\NinjaForms\Connection\Field_Connection;
use WPGraphQL\NinjaForms\Mutation\Submit_Mutation;

/**
 * Class Type_Registry
 */
class Type_Registry {
	/**
	 * Registers WooGraphQL types, connections, unions, and mutations to GraphQL schema
	 *
	 * @param TypeRegistry $type_registry Instance of the WPGraphQL TypeRegistry.
	 */
	public function init( TypeRegistry $type_registry ) {
		// enums.
		Id_Type_Enums::register();
		Field_Label_Pos_Enums::register();

		// interfaces.
		Form_Field_Interface::register( $type_registry );

		// types.
		Form_Type::register();
		Field_Type::register();
		Field_Error_Type::register();

		// inputs.
		Field_Input::register();

		// connections.
		Form_Connection::register();
		Field_Connection::register();

		// mutations.
		Submit_Mutation::register();
	}
}

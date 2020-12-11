<?php
/**
 * Initializes a singleton instance of NinjaForms
 *
 * @package WPGraphQL\NinjaForms
 * @since   0.1.0
 */

namespace WPGraphQL\NinjaForms;

/**
 * Class NinjaForms
 */
class NinjaForms {

	/**
	 * Stores the instance of the NinjaForms class
	 *
	 * @var NinjaForms The one true NinjaForms
	 */
	private static $instance;

	/**
	 * Returns a NinjaForms Instance.
	 *
	 * @return NinjaForms
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->includes();
			self::$instance->setup();
		}

		/**
		 * Fire off init action.
		 *
		 * @param NinjaForms $instance The instance of the NinjaForms class
		 */
		do_action( 'graphql_ninja_forms_init', self::$instance );

		return self::$instance;
	}

	/**
	 * Returns Ninja Forms action types to be exposed to GraphQL schema.
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public static function get_aviable_actions() {
		return apply_filters(
			'graphql_ninja_forms_action_types',
			array_map(
				function ( $action ) {
						return ucfirst( $action->get_name() ) . 'FormAction';
				},
				\Ninja_Forms::instance()->actions
			)
		);
	}

	/**
	 * Returns Ninja Forms field types to be exposed to GraphQL schema.
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public static function get_aviable_fields() {
		return apply_filters(
			'graphql_ninja_forms_field_types',
			array_map(
				function ( $field ) {
					return ucfirst( $field->get_name() ) . 'Field';
				},
				\Ninja_Forms::instance()->fields
			)
		);
	}

	/**
	 * Include required files. Uses composer's autoload.
	 *
	 * @since 0.1.0
	 */
	private function includes() {
		/**
		 * WPGRAPHQL_NINJA_FORMS_AUTOLOAD can be set to "false" to prevent the autoloader from running.
		 * In most cases, this is not something that should be disabled, but some environments
		 * may bootstrap their dependencies in a global autoloader that will autoload files
		 * before we get to this point, and requiring the autoloader again can trigger fatal errors.
		 *
		 * The codeception tests are an example of an environment where adding the autoloader again causes issues
		 * so this is set to false for tests.
		 */
		if ( defined( 'WPGRAPHQL_NINJA_FORMS_AUTOLOAD' ) && true === WPGRAPHQL_NINJA_FORMS_AUTOLOAD ) {
			require_once WPGRAPHQL_NINJA_FORMS_PLUGIN_DIR . 'vendor/autoload.php';
		}
	}

	/**
	 * Sets up WooGraphQL schema.
	 *
	 * @since 0.1.0
	 */
	public function setup() {
		// Register WPGraphQL core filters.
		Core_Schema_Filters::add_filters();

		// Initialize WPGraphQL Ninja Forms TypeRegistry.
		$registry = new Type_Registry();
		add_action( 'graphql_register_types', [ $registry, 'init' ], 10, 1 );
	}

	/**
	 * Throw error on object clone.
	 * The whole idea of the singleton design pattern is that there is a single object
	 * therefore, we don't want the object to be cloned.
	 *
	 * @since  0.1.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'NinjaForms class should not be cloned.', 'wp-graphql-ninja-forms' ), '0.0.1' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since  0.1.0
	 */
	public function __wakeup() {
		// De-serializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'De-serializing instances of the NinjaForms class is not allowed', 'wp-graphql-ninja-forms' ), '0.0.1' );
	}
}

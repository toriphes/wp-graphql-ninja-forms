<?php
/**
 * WPGraphQL Ninja Forms
 *
 * @package           WPGraphQL\NinjaForms
 * @author            Giulio Ganci
 * @license           GPL-3
 *
 * @wordpress-plugin
 * Plugin Name:       WPGraphQL Ninja Forms
 * Plugin URI:        https://github.com/toriphes/wp-graphql-ninja-forms
 * Description:       Adds Ninja Forms Functionality to WPGraphQL schema.
 * Version:           0.1.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Giulio Ganci
 * Author URI:        https://github.com/toriphes
 * Text Domain:       wp-graphql-ninja-forms
 * Domain Path:       /languages
 * License:           GPL-3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * WPGraphQL requires at least: 0.8.0+
 * Ninja Forms requires at least: 3.0+
 */

// direct access not allowed.
defined( 'ABSPATH' ) || exit;

/**
 * Setups WPGraphQL Ninja Forms constants
 */
function nfgraphql_constants() {
	// Plugin version.
	if ( ! defined( 'WPGRAPHQL_NINJA_FORMS_VERSION' ) ) {
		define( 'WPGRAPHQL_NINJA_FORMS_VERSION', '0.1.2' );
	}
	// Plugin Folder Path.
	if ( ! defined( 'WPGRAPHQL_NINJA_FORMS_PLUGIN_DIR' ) ) {
		define( 'WPGRAPHQL_NINJA_FORMS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}
	// Plugin Folder URL.
	if ( ! defined( 'WPGRAPHQL_NINJA_FORMS_PLUGIN_URL' ) ) {
		define( 'WPGRAPHQL_NINJA_FORMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}
	// Plugin Root File.
	if ( ! defined( 'WPGRAPHQL_NINJA_FORMS_PLUGIN_FILE' ) ) {
		define( 'WPGRAPHQL_NINJA_FORMS_PLUGIN_FILE', __FILE__ );
	}
	// Whether to autoload the files or not.
	if ( ! defined( 'WPGRAPHQL_NINJA_FORMS_AUTOLOAD' ) ) {
		define( 'WPGRAPHQL_NINJA_FORMS_AUTOLOAD', true );
	}
}

/**
 * Returns an array of missing plugin dependencies
 *
 * @return string[]
 */
function nfgraphql_missing_deps() {
	$deps = array();
	if ( ! class_exists( '\WPGraphQL' ) ) {
		$deps[] = 'WPGraphQL';
	}
	if ( ! class_exists( '\Ninja_Forms' ) ) {
		$deps[] = 'Ninja Forms';
	}

	return $deps;
}

/**
 * Initialize WPGraphQL Ninja Forms
 */
function nfgraphql_init() {
	nfgraphql_constants();
	$deps = nfgraphql_missing_deps();

	if ( empty( $deps ) ) {
		require_once WPGRAPHQL_NINJA_FORMS_PLUGIN_DIR . 'src/class-ninjaforms.php';
		return \WPGraphQL\NinjaForms\NinjaForms::instance();
	}

	add_action(
		'admin_notices',
		function () use ( $deps ) {
			?>
			<div class="error notice">
				<p>
					<?php esc_attr_e( 'WPGraphQL Ninja Forms needs the following plugins to work:', 'wp-graphql-ninja-forms' ); ?>
				</p>
				<ul>
					<?php foreach ( $deps as $dep ) : ?>
						<li><?php echo esc_html( $dep ); ?></li>
					<?php endforeach ?>
				</ul>
			</div>
			<?php
		}
	);
}

add_action( 'plugins_loaded', 'nfgraphql_init' );

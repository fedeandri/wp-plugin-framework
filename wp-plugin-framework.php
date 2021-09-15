<?php
/**
 * Plugin Name:       WP Plugin Framework
 * Description:       WordPress Plugin Framework for Developers
 * Version:           1.0.0
 * Author:            Federico Andrioli
 * Author URI:        https://it.linkedin.com/in/fedeandri
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-plugin-framework
 * Domain Path:       /languages
 *
 * @version           1.0.0
 * @package           WpPluginFramework
 */

namespace WpPluginFramework;

use Exception;

defined( 'ABSPATH' ) || die( 'Access Denied' );

/**
 * Retrieve all the plugin arguments.
 *
 * @since    1.0.0
 * @param    string $group    Optional argument group to retrieve a specific argument group.
 * @param    string $label    Optional label to retrieve a specific argument from an argument group.
 * @throws Exception          Throws different execpions based on missing arguments.
 */
function get_args( $group = null, $label = null ) {

	global $wpdb;

	$paths    = array();
	$plugin   = array();
	$database = array();
	$options  = array();
	$args     = array();

	/* plugin paths */
	$paths['root']            = plugin_dir_path( __FILE__ );
	$paths['assets']          = $paths['root'] . 'assets/';
	$paths['classes']         = $paths['root'] . 'classes/';
	$paths['languages']       = $paths['root'] . 'languages/';
	$paths['app']             = $paths['root'] . 'app/';
	$paths['app_classes']     = $paths['app'] . 'classes/';
	$paths['app_controllers'] = $paths['app'] . 'controllers/';
	$paths['app_models']      = $paths['app'] . 'models/';
	$paths['app_views']       = $paths['app'] . 'views/';

	/* plugin arguments */
	$plugin['prefix']     = 'wppf';
	$plugin['name']       = 'WP Plugin Framework';
	$plugin['slug']       = 'wp-plugin-framework';
	$plugin['textdomain'] = $plugin['slug'];
	$plugin['url']        = plugin_dir_url( __FILE__ ); // plugin directory URL.
	$plugin['basename']   = plugin_basename( __FILE__ ); // plugin_dirname/filename.php.
	$plugin['version']    = '1.0.0';

	/* database arguments */
	$database['version']  = '1.0.0';
	$database['table_01'] = $wpdb->prefix . $plugin['prefix'] . '_main';

	/* option labels */
	$options['version']             = $plugin['prefix'] . '_version';
	$options['db_version']          = $plugin['prefix'] . '_db_version';
	$options['delete_on_uninstall'] = $plugin['prefix'] . '_delete_on_uninstall';
	$options['settings']            = $plugin['prefix'] . '_settings';

	/* main arguments array */
	$args['paths']    = $paths;
	$args['plugin']   = $plugin;
	$args['database'] = $database;
	$args['options']  = $options;

	if ( true === empty( $group ) && true === empty( $label ) ) {

		return $args;

	} elseif ( ! empty( $group ) && ! empty( $label ) ) {

		if ( ! isset( $args[ $group ] ) ) {
			throw new Exception( 'argument group ' . $group . ' doesn\'t exist' );
		}

		if ( ! isset( $args[ $group ][ $label ] ) ) {
			throw new Exception( 'argument label ' . $label . ' for argument group ' . $group . ' does\'t exist' );
		}

		return $args[ $group ][ $label ];

	} elseif ( ! empty( $group ) ) {

		if ( ! isset( $args[ $group ] ) ) {
			throw new Exception( 'argument group ' . $group . ' doesn\'t exist' );
		}

		return $args[ $group ];

	} elseif ( ! empty( $label ) ) {

		throw new Exception( 'Missing argument group for label ' . $label );

	}
}

register_activation_hook(
	__FILE__,
	'\\WpPluginFramework\\activation_callback'
);

register_deactivation_hook(
	__FILE__,
	'\\WpPluginFramework\\deactivation_callback'
);

register_uninstall_hook(
	__FILE__,
	'\\WpPluginFramework\\uninstall_callback'
);

/**
 * Activation callback.
 *
 * @since    1.0.0
 */
function activation_callback() {
	\WpPluginFramework\main_hooks_callback( 'activation' );
}

/**
 * Deactivation callback.
 *
 * @since    1.0.0
 */
function deactivation_callback() {
	\WpPluginFramework\main_hooks_callback( 'deactivation' );
}

/**
 * Uninstall callback.
 *
 * @since    1.0.0
 */
function uninstall_callback() {
	\WpPluginFramework\main_hooks_callback( 'uninstall' );
}

/**
 * Main hooks callback.
 *
 * @param    string $hook    Hook name.
 * @since    1.0.0
 */
function main_hooks_callback( $hook ) {

	$class_file  = '';
	$class_name  = '';
	$method_name = '';

	switch ( $hook ) {
		case 'activation':
			$class_file  = 'activator';
			$class_name  = 'Activator';
			$method_name = 'activate';
			break;
		case 'deactivation':
			$class_file  = 'deactivator';
			$class_name  = 'Deactivator';
			$method_name = 'deactivate';
			break;
		case 'uninstall':
			$class_file  = 'uninstaller';
			$class_name  = 'Uninstaller';
			$method_name = 'uninstall';
			break;
		default:
			$hook = '';
	}

	if ( ! empty( $hook ) ) {

		require_once \WpPluginFramework\get_args( 'paths', 'classes' ) . 'class-model.php';
		require_once \WpPluginFramework\get_args( 'paths', 'app_classes' ) . 'class-' . $class_file . '.php';

		$class_name = '\\WpPluginFramework\\' . $class_name;

		$class_name::$method_name();
	}
}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run() {

	/**
	 * The core plugin class
	 */
	require_once get_args( 'paths', 'classes' ) . 'class-base.php';
	require_once get_args( 'paths', 'app' ) . 'class-main.php';

	$plugin = new Main();
	$plugin->run();

}
run();

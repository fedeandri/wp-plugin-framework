<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Activator {

	/**
	 * Fired during plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$args = \WpPluginFramework\get_args();

		$field_structure = array();

		$field_structure['field_name'] = 'VARCHAR(199) NOT NULL';

		Model::create_table(
			$args['database']['table_01'],
			$field_structure
		);

		update_option( $args['options']['version'], $args['plugin']['version'] );
		update_option( $args['options']['db_version'], $args['database']['version'] );
		update_option( $args['options']['settings'], '' );

		if ( true === add_option( $args['options']['delete_on_uninstall'] ) ) {
			update_option( $args['options']['delete_on_uninstall'], 0 );
		}
	}

}

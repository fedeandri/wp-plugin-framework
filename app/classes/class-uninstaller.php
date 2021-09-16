<?php
/**
 * Fired during plugin uninstall
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * Fired during plugin uninstall
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Uninstaller {

	/**
	 * Fired during plugin uninstall
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {

		$args = \WpPluginFramework\get_args();

		if ( '1' === get_option( $args['options']['delete_on_uninstall'] ) ) {

			Model::drop_table( $args['database']['table_01'] );

			delete_option( $args['options']['version'] );
			delete_option( $args['options']['db_version'] );
			delete_option( $args['options']['delete_on_uninstall'] );
			delete_option( $args['options']['settings'] );
		}

	}

}

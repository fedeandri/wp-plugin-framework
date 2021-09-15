<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Settings extends Controller {

	/**
	 * Admin menu initialization.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu_init() {

		add_menu_page(
			$this->get_args( 'plugin', 'name' ),
			$this->get_args( 'plugin', 'name' ),
			'manage_options',
			$this->get_args( 'plugin', 'slug' ),
			array( &$this, 'add_settings_page' )
		);

	}

	/**
	 * Settings link initialization.
	 *
	 * @since    1.0.0
	 * @param    array $links    Plugins page links.
	 * @return   string
	 */
	public function add_settings_link( $links ) {

		$links[] = '<a href="' . network_admin_url( 'admin.php?page=' . $this->get_args( 'plugin', 'slug' ) ) . '">' . __( 'Settings' ) . '</a>';

		return $links;
	}

	/**
	 * Settings page initialization.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_page() {

		if ( current_user_can( 'manage_options' ) ) {

			require_once $this->get_args( 'paths', 'app_views' ) . 'admin/settings.php';

		}

	}

}

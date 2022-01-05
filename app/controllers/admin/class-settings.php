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

			$this->save_settings_form();

			$enqueue_list = array();
			$data         = array();
			$settings     = $this->get_settings_data( true );

			$prefix = $this->get_args( 'plugin', 'prefix' );

			$enqueue_list[] = 'settings-admin-all.css';
			$enqueue_list[] = 'settings-admin-all.js';

			$this->enqueue_files( $enqueue_list );

			$data['prefix']      = $prefix;
			$data['form_id']     = $prefix . '_form';
			$data['ajax_params'] = $prefix . '_ajax_params';

			$data['field_1']['key'] = $prefix . '_field_1';
			$data['field_1']['val'] = ! empty( $settings[ $data['field_1']['key'] ] ) ? $settings[ $data['field_1']['key'] ] : '';

			$data['field_2']['key'] = $prefix . '_field_2';
			$data['field_2']['val'] = ! empty( $settings[ $data['field_2']['key'] ] ) ? $settings[ $data['field_2']['key'] ] : '';

			$data['nonce_field']  = $prefix . '_nonce';
			$data['nonce_action'] = $prefix - '_submit';

			require_once $this->get_args( 'paths', 'app_views' ) . 'admin/settings.php';

		}

	}

	/**
	 * Settings form processing.
	 *
	 * @since    1.0.0
	 */
	public function save_settings_form() {

		$settings = array();
		$prefix   = $this->get_args( 'plugin', 'prefix' );

		$nonce_field  = $prefix . '_nonce';
		$nonce_action = $prefix - '_submit';

		$field_1 = $prefix . '_field_1';
		$field_2 = $prefix . '_field_2';

		if ( isset( $_POST[ $nonce_field ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), $nonce_action ) ) {

			$settings[ $field_1 ] = isset( $_POST[ $field_1 ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field_1 ] ) ) : '';
			$settings[ $field_2 ] = isset( $_POST[ $field_2 ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field_2 ] ) ) : '';

			$this->set_settings_value( $field_1, $settings[ $field_1 ] );
			$this->set_settings_value( $field_2, $settings[ $field_2 ] );

			$this->save_settings_data();
		}

		return true;
	}
}

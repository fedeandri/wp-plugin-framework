<?php
/**
 * The base controller to be extended.
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * The base controller to be extended.
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Controller extends Base {

	/**
	 * The settings associative array of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $settings_data    Settings options array.
	 */
	private $settings_data;

	/**
	 * Return the settings data associative array.
	 *
	 * @since    1.0.0
	 * @param    string $label            Optional settings label to retrieve a specific setting.
	 * @param    bool   $force_refresh    Optional flag to force reading settings from the database.
	 * @return   mixed
	 */
	public function get_settings_value( $label, $force_refresh = false ) {

		if ( $force_refresh || empty( $this->settings_data ) ) {
			$this->settings_data = Model::read_settings();
		}

		if ( ! empty( $label ) ) {
			return $this->settings_data[ $label ];
		}

		return false;

	}

	/**
	 * Return the settings data associative array.
	 *
	 * @since    1.0.0
	 * @param    bool $force_refresh    Optional flag to force reading settings from the database.
	 * @return   array
	 */
	public function get_settings_data( $force_refresh = false ) {

		if ( $force_refresh ) {

			$this->settings_data = Model::read_settings();
		}

		return $this->settings_data;

	}

	/**
	 * Flush the settings data stored inside the settings_data property.
	 *
	 * @since    1.0.0
	 */
	public function flush_settings_data() {

		$this->settings_data = null;

	}

	/**
	 * Set the settings value for the specified label.
	 *
	 * @param    string $label    Settings label.
	 * @param    string $value    Settings value.
	 * @since    1.0.0
	 */
	public function set_settings_value( $label, $value ) {

		if ( ! empty( $label ) ) {

			$this->settings_data[ $label ] = $value;
		}

	}

	/**
	 * Set the settings value for the specified label.
	 *
	 * @since    1.0.0
	 */
	public function save_settings_data() {

		Model::update_settings( $this->settings_data );

	}

	/**
	 * Register and enqueue styles and scripts.
	 *
	 * @since    1.0.0
	 * @param    array $register_enqueue_list    Optional list of files to register and enqueue.
	 */
	public function register_enqueue_files( $register_enqueue_list = array() ) {

		$enqueuer = new Enqueuer();

		$version         = $this->get_args( 'plugin', 'version' );
		$prevent_caching = false;
		$enqueue         = true;

		if ( true === empty( $register_enqueue_list ) ) {
			$register_enqueue_list = $this->get_args( 'to_enqueue', 'register_enqueue' );
		}

		foreach ( $register_enqueue_list as $file ) {
			$enqueuer->register_file( $file, $enqueue, $version, $prevent_caching );
		}

	}

	/**
	 * Register styles and scripts.
	 *
	 * @since    1.0.0
	 * @param    array $register_list    Optional list of files to register.
	 */
	public function register_files( $register_list = array() ) {

		$enqueuer = new Enqueuer();

		$version         = $this->get_args( 'plugin', 'version' );
		$prevent_caching = false;
		$enqueue         = false;

		if ( true === empty( $register_list ) ) {
			$register_list = $this->get_args( 'to_enqueue', 'register' );
		}

		foreach ( $register_list as $file ) {
			$enqueuer->register_file( $file, $enqueue, $version, $prevent_caching );
		}

	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since    1.0.0
	 * @param    array $enqueue_list    Optional list of files to register.
	 */
	public function enqueue_files( $enqueue_list = array() ) {

		$enqueuer = new Enqueuer();

		if ( true === empty( $enqueue_list ) ) {
			$enqueue_list = $this->get_args( 'to_enqueue', 'enqueue' );
		}

		foreach ( $enqueue_list as $file ) {
			$enqueuer->enqueue_file( $file );
		}

	}

	/**
	 * Print ajax params.
	 *
	 * @since    1.0.0
	 * @param    string $ajax_params_name     Optional name for ajax params constant.
	 */
	public function print_ajax_params( $ajax_params_name = '' ) {

		$params = array();

		$params['ajaxUrl'] = admin_url( 'admin-ajax.php' );
		$params['nonce']   = wp_create_nonce( $this->get_args( 'plugin', 'prefix' ) . '_ajax_nonce' );

		if ( empty( $ajax_params_name ) ) {
			$ajax_params_name = $this->get_args( 'plugin', 'prefix' ) . '_ajax_params';
		}

		wp_print_inline_script_tag( 'const ' . $ajax_params_name . ' = ' . wp_json_encode( $params ) );

	}

}

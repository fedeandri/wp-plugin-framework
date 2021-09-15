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

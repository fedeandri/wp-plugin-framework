<?php
/**
 * Enqueue all files for the plugin
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * Enqueue all files for the plugin
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Enqueuer extends Base {

	/**
	 * Utility function to register styles and scripts
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string $file               Filename without path.
	 * @param    bool   $enqueue            Immediately enqueue the file if set to true.
	 * @param    string $version            Version of the enqueued file.
	 * @param    bool   $prevent_caching    Force random version to prevent caching, only used in development.
	 * @return   bool
	 */
	public function register_file( $file, $enqueue = false, $version = '1.0', $prevent_caching = false ) {

		$return     = false;
		$file_parts = array();
		$file_parts = $this->get_file_parts( $file );

		if ( 'css' !== $file_parts['file_extension'] && 'js' !== $file_parts['file_extension'] ) {
			return false;
		}

		if ( true === $prevent_caching ) {
			$version = time();
		}

		if ( ! file_exists( $file_parts['file_path'] ) ) {
			return false;
		}

		switch ( $file_parts['file_extension'] ) {
			case 'css':
				$return = wp_register_style(
					$file_parts['enqueue_name'],
					$file_parts['file_url'],
					array(),
					$version
				);

				if ( true === $enqueue ) {
					wp_enqueue_style(
						$file_parts['enqueue_name']
					);
				}
				break;
			case 'js':
				$return = wp_register_script(
					$file_parts['enqueue_name'],
					$file_parts['file_url'],
					array( 'jquery' ),
					$version,
					true
				);

				if ( true === $enqueue ) {
					wp_enqueue_script(
						$file_parts['enqueue_name']
					);
				}
				break;
		}

		return $return;

	}

	/**
	 * Utility function to enqueue registered styles and scripts
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string $file    Filename without path.
	 * @return   bool
	 */
	public function enqueue_file( $file ) {

		$file_parts = array();
		$file_parts = $this->get_file_parts( $file );

		switch ( $file_parts['file_extension'] ) {
			case 'css':
				wp_enqueue_style(
					$file_parts['enqueue_name']
				);
				break;
			case 'js':
				wp_enqueue_script(
					$file_parts['enqueue_name']
				);
				break;
			default:
				return false;
		}

		return true;

	}

	/**
	 * Utility function to get all the different file parts
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string $file           Filename without path.
	 * @return   array
	 */
	public function get_file_parts( $file ) {

		$file_parts = array();
		$matches    = array();
		$args       = array();

		$args = $this->get_args();

		preg_match( '/^(.+)\.(js|css)$/', $file, $matches );

		$file_parts['file_name']      = isset( $matches[1] ) ? $matches[1] : '';
		$file_parts['file_extension'] = isset( $matches[2] ) ? $matches[2] : null;

		switch ( $file_parts['file_extension'] ) {
			case 'css':
				$file_parts['enqueue_name'] = $args['plugin']['prefix'] . '-' . $file_parts['file_name'];
				$file_parts['file_path']    = $args['paths']['root'] . 'assets/css/' . $file;
				$file_parts['file_url']     = $args['plugin']['url'] . 'assets/css/' . $file;
				break;
			case 'js':
				$file_parts['enqueue_name'] = $args['plugin']['prefix'] . '-' . $file_parts['file_name'];
				$file_parts['file_path']    = $args['paths']['root'] . 'assets/js/' . $file;
				$file_parts['file_url']     = $args['plugin']['url'] . 'assets/js/' . $file;
				break;
		}

		return $file_parts;
	}
}

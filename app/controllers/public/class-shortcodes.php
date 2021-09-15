<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Shortcodes extends Controller {

	/**
	 * Controller initialization.
	 *
	 * @since    1.0.0
	 */
	public function init() {
		add_shortcode( $this->get_args( 'plugin', 'slug' ), array( $this, 'shortcode' ) );
	}

	/**
	 * Choose which view to show based on the main attribute.
	 *
	 * @since    1.0.0
	 * @param    array $atts    Shortcode attributes.
	 */
	public function shortcode( $atts ) {

		switch ( $atts[0] ) {
			case 'form':
				$this->form_shortcode_view();
				break;
			case 'feedbacks':
				$this->feedbacks_shortcode_view();
				break;
		}
	}

	/**
	 * Display form view.
	 *
	 * @since    1.0.0
	 */
	public function form_shortcode_view() {

		$enqueue_list   = array();
		$file_part_list = array();
		$data           = array();

		$enqueue_list[] = 'shortcode-form-public-all.css';
		$enqueue_list[] = 'shortcode-form-public-all.js';

		$this->enqueue_files( $enqueue_list );

		$data['prefix']      = $this->get_args( 'plugin', 'prefix' );
		$data['form_id']     = $this->get_args( 'plugin', 'prefix' ) . '_form';
		$data['ajax_params'] = $this->get_args( 'plugin', 'prefix' ) . '_ajax_params';

		$data['field_name'] = '';

		require_once $this->get_args( 'paths', 'app_views' ) . 'public/shortcode-form.php';
	}

	/**
	 * Display the feedback list view.
	 *
	 * @since    1.0.0
	 */
	public function feedbacks_shortcode_view() {

		if ( ! current_user_can( 'administrator' ) ) {

			require_once $this->get_args( 'paths', 'app_views' ) . 'public/shortcode-unauthorized.php';

		} else {

			$enqueue_list = array();
			$data         = array();

			$enqueue_list[] = 'shortcode-feedbacks-public-all.css';
			$enqueue_list[] = 'shortcode-feedbacks-public-all.js';

			$this->enqueue_files( $enqueue_list );

			$data['prefix']           = $this->get_args( 'plugin', 'prefix' );
			$data['results_id']       = $this->get_args( 'plugin', 'prefix' ) . '_results';
			$data['modal_id']         = $this->get_args( 'plugin', 'prefix' ) . '_modal';
			$data['pagination_class'] = $this->get_args( 'plugin', 'prefix' ) . '_pagination';
			$data['ajax_params']      = $this->get_args( 'plugin', 'prefix' ) . '_ajax_params';

			require_once $this->get_args( 'paths', 'app_views' ) . 'public/shortcode-feedbacks.php';
		}

	}


}

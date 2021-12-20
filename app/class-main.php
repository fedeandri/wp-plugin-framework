<?php
/**
 * The file that defines the core plugin class
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Main extends Base {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	protected function init() {

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/* default dependencies */
		require_once $this->get_args( 'paths', 'classes' ) . 'class-model.php';
		require_once $this->get_args( 'paths', 'classes' ) . 'class-controller.php';
		require_once $this->get_args( 'paths', 'classes' ) . 'class-loader.php';
		require_once $this->get_args( 'paths', 'classes' ) . 'class-enqueuer.php';
		require_once $this->get_args( 'paths', 'classes' ) . 'class-internationalizer.php';

		/* custom dependencies */
		require_once $this->get_args( 'paths', 'app_models' ) . 'class-data.php';
		require_once $this->get_args( 'paths', 'app_controllers' ) . 'admin/class-settings.php';
		require_once $this->get_args( 'paths', 'app_controllers' ) . 'public/class-shortcodes.php';

		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the QuickForms_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$args = array();
		$args = $this->get_args();

		$internationalizer = new Internationalizer( $args );

		$this->loader->add_action( 'plugins_loaded', $internationalizer, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$args = array();
		$args = $this->get_args();

		$args['to_enqueue']['register_enqueue'] = array();

		$args['to_enqueue']['register_enqueue'][] = 'settings-admin-all.css';
		$args['to_enqueue']['register_enqueue'][] = 'settings-admin-all.js';

		$settings_controller = new Settings( $args );

		$this->loader->add_action( 'admin_enqueue_scripts', $settings_controller, 'register_enqueue_files' );
		$this->loader->add_action( 'admin_menu', $settings_controller, 'admin_menu_init' );

		$this->loader->add_filter( 'plugin_action_links_' . $this->get_args( 'plugin', 'basename' ), $settings_controller, 'add_settings_link', 11, 1 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$args = array();
		$args = $this->get_args();

		$args['to_enqueue']['register'] = array();

		$args['to_enqueue']['register'][] = 'shortcode-form-public-all.css';
		$args['to_enqueue']['register'][] = 'shortcode-form-public-all.js';

		$shortcodes_controller = new Shortcodes( $args );

		$this->loader->add_action( 'wp_head', $shortcodes_controller, 'print_ajax_params' );
		$this->loader->add_action( 'wp_enqueue_scripts', $shortcodes_controller, 'register_files' );

		$data_model = new Data();

		$submit_method = 'submit_form';

		$this->loader->add_action( 'wp_ajax_nopriv_' . $submit_method, $data_model, $submit_method );
		$this->loader->add_action( 'wp_ajax_' . $submit_method, $data_model, $submit_method );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

}

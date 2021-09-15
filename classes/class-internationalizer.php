<?php
/**
 * Define the internationalization functionality
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * Define the internationalization functionality
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Internationalizer extends Base {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$result = load_plugin_textdomain(
			$this->get_args( 'plugin', 'textdomain' ),
			false,
			$this->get_args( 'paths', 'languages' )
		);

	}

}

<?php
/**
 * The base class to be extended.
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

use Exception;

/**
 * The base class to be extended.
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Base {

	/**
	 * The args array of this class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $args    Class arguments.
	 */
	private $args;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    array $args     Optional arguments array.
	 * @throws   Exception       Throws different execpions based on missing arguments.
	 */
	public function __construct( $args = array() ) {

		if ( empty( $args ) ) {
			$args = \WpPluginFramework\get_args();
		}

		$this->args = $args;

		$this->init();
	}

	/**
	 * Initializer to be overridden.
	 *
	 * @since    1.0.0
	 */
	protected function init() {}

	/**
	 * Args getter.
	 *
	 * @since    1.0.0
	 * @param    string $group    Optional argument group.
	 * @param    string $label    Optional argument label.
	 * @throws   Exception        Throws different execpions based on missing arguments.
	 */
	protected function get_args( $group = null, $label = null ) {

		if ( true === empty( $group ) && true === empty( $label ) ) {

			return $this->args;

		} elseif ( ! empty( $group ) && ! empty( $label ) ) {

			if ( ! isset( $this->args[ $group ] ) ) {
				throw new Exception( 'argument group ' . $group . ' doesn\'t exist' );
			}

			if ( ! isset( $this->args[ $group ][ $label ] ) ) {
				throw new Exception( 'argument label ' . $label . ' for argument group ' . $group . ' does\'t exist' );
			}

			return $this->args[ $group ][ $label ];

		} elseif ( ! empty( $group ) ) {

			if ( ! isset( $this->args[ $group ] ) ) {
				throw new Exception( 'argument group ' . $group . ' doesn\'t exist' );
			}

			return $this->args[ $group ];

		} elseif ( ! empty( $label ) ) {

			throw new Exception( 'Missing argument group for label ' . $label );

		}

	}

	/**
	 * Args setter.
	 *
	 * @since    1.0.0
	 * @param    string $group    Group of the arguments to set.
	 * @param    array  $args     Arguments array.
	 * @return   bool
	 */
	protected function set_args( $group, $args ) {

		$all_set = true;

		if ( ! empty( $group ) && is_array( $args ) ) {

			foreach ( $args as $label => $value ) {

				if ( false === $this->set_arg( $group, $label, $value ) ) {

					$all_set = false;
				}
			}
		} else {

			$all_set = false;
		}

		return $all_set;
	}

	/**
	 * Arg setter.
	 *
	 * @since    1.0.0
	 * @param    string $group    Group of the argument to set.
	 * @param    string $label    Name of the argument to set.
	 * @param    mixed  $value    Value of the argument to set.
	 * @return   bool
	 */
	protected function set_arg( $group, $label, $value ) {

		if ( ! empty( $group ) && ! empty( $label ) ) {

			$this->args[ $group ][ $label ] = $value;
			return true;
		}

		return false;
	}

}

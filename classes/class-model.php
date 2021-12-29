<?php
/**
 * The base model to be extended.
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

namespace WpPluginFramework;

/**
 * The base model to be extended.
 *
 * @since      1.0.0
 * @author     Federico Andrioli <analogmatter.com@gmail.com>
 */
class Model extends Base {

	/**
	 * Create a database table.
	 *
	 * @since    1.0.0
	 * @param    string $table              DB table name.
	 * @param    array  $field_structure    DB table fields in the form of field_name => field_definition array.
	 */
	public static function create_table( $table, $field_structure ) {

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS `$table` (
		`id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
		";

		foreach ( $field_structure as $field_name => $field_definition ) {
			$sql .= '`' . $field_name . '` ' . $field_definition . ",\n";
		}

		$sql .= "
		`modified` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		`created` DATETIME DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`),
		KEY `modified` (`modified`),
		KEY `created` (`created`)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );

	}

	/**
	 * Create a database table.
	 *
	 * @since    1.0.0
	 * @param    string $table    DB table name.
	 */
	public static function drop_table( $table ) {

		global $wpdb;

		$sql = "DROP TABLE IF EXISTS `$table`;";

		$wpdb->query( $sql ); // PHPCS:ignore.

	}

	/**
	 * Read the settings option from the options table.
	 *
	 * @since    1.0.0
	 * @return   mixed
	 */
	public static function read_settings() {

		$settings_option = \WpPluginFramework\get_args( 'options', 'settings' );

		$settings = json_decode( get_option( $settings_option ), true );

		return $settings;
	}

	/**
	 * Update the settings option from the options table.
	 *
	 * @since    1.0.0
	 * @param    array $settings    Settings associative array.
	 * @return   bool
	 */
	public static function update_settings( $settings ) {

		$settings_option = \WpPluginFramework\get_args( 'options', 'settings' );

		$result = false;
		$result = update_option( $settings_option, wp_json_encode( $settings ) );

		return $result;
	}

	/**
	 * Delete the settings option from the options table.
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	public static function delete_settings() {

		$settings_option = \WpPluginFramework\get_args( 'options', 'settings' );

		$result = false;
		$result = delete_option( $settings_option );

		return $result;
	}
	/**
	 * Save a record into the specified database table.
	 *
	 * @since    1.0.0
	 * @param    string $table    DB table name.
	 * @param    array  $data     DB data fields in the form of field_name => field_value array.
	 * @return   int/bool
	 */
	public function save_record( $table, $data ) {

		global $wpdb;

		$result = $wpdb->insert( $table, $data ); // PHPCS:ignore;

		return $result;
	}

	/**
	 * Read records from the database table.
	 *
	 * @since    1.0.0
	 * @param    string $table    DB table name.
	 * @param    array  $fields    List of fields to select.
	 * @param    int    $id        ID of the record to read.
	 * @return   array
	 */
	public function read_record( $table, $fields, $id ) {

		$result = null;

		if ( is_int( $id ) ) {
			$result = $this->read_db( $table, $fields, array( 'id' => $id ) );
		}

		return $result;
	}

	/**
	 * Read records from the database table.
	 *
	 * @since    1.0.0
	 * @param    string $table        DB table name.
	 * @param    array  $fields       List of fields to select.
	 * @param    array  $where_or     Optional where clause in the form of field_name => value (OR matched).
	 * @param    array  $where_and    Optional where clause in the form of field_name => value (AND matched).
	 * @param    array  $order_by     Optional order by clause in the form of field_name => ASC|DESC array.
	 * @param    int    $limit        Optional amount of records to select.
	 * @param    int    $offset       Optional offset amount to paginate records.
	 * @return   array
	 */
	public function read_db( $table, $fields, $where_or = array(), $where_and = array(), $order_by = array(), $limit = null, $offset = null ) {

		global $wpdb;

		$results            = array();
		$query_values_count = array();
		$query_values       = array();

		$sql_count = 'SELECT COUNT(*)';
		$sql       = "SELECT\n";

		foreach ( $fields as $field ) {
			$sql .= "`$field`,";
		}

		$sql = rtrim( $sql, ',' );

		$sql_count .= "\nFROM `$table`";
		$sql       .= "\nFROM `$table`";

		if ( ! is_array( $where_or ) ) {
			$where_or = array();
		}

		if ( ! is_array( $where_and ) ) {
			$where_and = array();
		}

		if ( ! empty( $where_or ) || ! empty( $where_and ) ) {
			$sql_count .= "\nWHERE\n";
			$sql       .= "\nWHERE\n";
		}

		if ( ! empty( $where_or ) ) {

			$sql_count .= "(\n";
			$sql       .= "(\n";

			foreach ( $where_or as $field => $value ) {

				if ( is_string( $value ) ) {
					$sql_count .= "`$field` = %s OR ";
					$sql       .= "`$field` = %s OR ";
				} else {
					$sql_count .= "`$field` = %d OR ";
					$sql       .= "`$field` = %d OR ";
				}

				$query_values_count[] = $value;
				$query_values[]       = $value;
			}

			$sql_count = rtrim( $sql, ' OR ' );
			$sql       = rtrim( $sql, ' OR ' );

			$sql_count .= "\n)\n";
			$sql       .= "\n)\n";
		}

		if ( ! empty( $where_and ) ) {

			if ( ! empty( $where_or ) ) {
				$sql_count .= "AND\n";
				$sql       .= "AND\n";
			}

			$sql_count .= "(\n";
			$sql       .= "(\n";

			foreach ( $where_and as $field => $value ) {

				if ( is_string( $value ) ) {
					$sql_count .= "`$field` = %s AND ";
					$sql       .= "`$field` = %s AND ";
				} else {
					$sql_count .= "`$field` = %d AND ";
					$sql       .= "`$field` = %d AND ";
				}

				$query_values_count[] = $value;
				$query_values[]       = $value;
			}

			$sql_count = rtrim( $sql, ' AND ' );
			$sql       = rtrim( $sql, ' AND ' );

			$sql_count .= "\n)";
			$sql       .= "\n)";
		}

		if ( ! is_array( $order_by ) ) {
			$order_by = array();
		}

		if ( ! empty( $order_by ) ) {

			$sql .= "\nORDER BY\n";

			foreach ( $order_by as $field => $order ) {
				if ( 'ASC' === $order || 'DESC' === $order ) {
					$sql .= "`$field` $order, ";
				}
			}
			$sql = rtrim( $sql, ', ' );
		}

		if ( ! empty( $limit ) ) {
			$sql .= "\nLIMIT %d\n";

			$query_values[] = $limit;

			if ( ! empty( $offset ) ) {
				$sql .= "\nOFFSET %d\n";

				$query_values[] = $offset;
			}
		}

		$sql_count .= ';';
		$sql       .= ';';

		if ( ! isset( $where_or['id'] ) && ! isset( $where_and['id'] ) ) {
			$sql_count = $wpdb->prepare( $sql_count, $query_values_count ); // PHPCS:ignore;

			$results['total'] = $wpdb->get_var( $sql_count ); // PHPCS:ignore;
		} else {
			$results['total'] = 1;
		}

		$sql = $wpdb->prepare( $sql, $query_values ); // PHPCS:ignore;

		$results['results'] = $wpdb->get_results( $sql, ARRAY_A ); // PHPCS:ignore;

		return $results;
	}

}

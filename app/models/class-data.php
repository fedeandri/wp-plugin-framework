<?php
/**
 * Main model to store feedbacks.
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
class Data extends Model {

	/**
	 * Submit form data.
	 *
	 * @since    1.0.0
	 */
	public function submit_form() {

		$table    = $this->get_args( 'database', 'table_01' );
		$fields   = array();
		$data     = array();
		$result   = false;
		$response = array();

		$fields['field_name'] = 'text';

		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $this->get_args( 'plugin', 'prefix' ) . '_ajax_nonce' ) ) {

			foreach ( $fields as $field => $type ) {

				switch ( $type ) {
					case 'text':
						$data[ $field ] = ! empty( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
						break;
					case 'email':
						$data[ $field ] = ! empty( $_POST[ $field ] ) ? sanitize_email( wp_unslash( $_POST[ $field ] ) ) : '';
						break;
					case 'textarea':
						$data[ $field ] = ! empty( $_POST[ $field ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : '';
						break;
				}
			}

			$result = $this->save_form_data( $table, $data );
		}

		$response['saved'] = 1;
		if ( false !== $result ) {
			wp_send_json_success( $response );
		} else {
			$response['saved'] = 0;
			wp_send_json_error( $response );
		}
	}

	/**
	 * Read feedbacks from the database table.
	 *
	 * @since    1.0.0
	 * @param    string $table    DB table name.
	 * @param    array  $data     DB data fields in the form of field_name => field_value array.
	 * @return   int/bool
	 */
	public function save_form_data( $table, $data ) {

		$fields = array();

		$fields[] = 'field_name';

		foreach ( $fields as $field ) {
			if ( true === empty( $data[ $field ] ) ) {
				return false;
			}
		}

		$result = $this->save_record( $table, $data );

		return $result;
	}

	/**
	 * Read feedbacks from the database table.
	 *
	 * @since    1.0.0
	 * @param    int $id    ID of the record to read.
	 */
	public function read_db_record( $id = null ) {

		$table  = $this->get_args( 'database', 'table_01' );
		$result = null;
		$fields = array();

		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $this->get_args( 'plugin', 'prefix' ) . '_ajax_nonce' ) ) {

			$id = isset( $_POST['id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['id'] ) ) ) : null;

		}

		$fields[] = 'field_name';
		$fields[] = 'created';

		if ( null !== $id ) {
			$result = $this->read_record( $table, $fields, $id );
		}

		if ( 0 < $result['total'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}

	/**
	 * Read feedbacks from the database table.
	 *
	 * @since    1.0.0
	 * @param    int $offset    Optional offset amount to paginate records.
	 * @param    int $limit     Optional limit value, default 10.
	 */
	public function read_all_records( $offset = null, $limit = 10 ) {

		$fields   = array();
		$order_by = array();

		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $this->get_args( 'plugin', 'prefix' ) . '_ajax_nonce' ) ) {

			$offset = isset( $_POST['page'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['page'] ) ) ) : null;
			$offset = $limit * $offset;

		}

		$fields[] = 'id';
		$fields[] = 'field_data';
		$fields[] = 'created';

		$order_by['created'] = 'DESC';

		if ( null === $offset ) {
			$limit = null;
		}

		$result = $this->read_db( $fields, array(), array(), $order_by, $limit, $offset );

		if ( 0 < $result['total'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}
}

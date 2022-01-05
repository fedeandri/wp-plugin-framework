<?php
/**
 * Plugin settings view
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

?>

<h1><?php esc_html_e( 'WP Plugin Framework', 'wp-plugin-framework' ); ?></h1>
<p><?php esc_html_e( 'Plugin settings page', 'wp-plugin-framework' ); ?></p>

<form
	method="post" id="<?php echo esc_attr( $data['form_id'] ); ?>"
	action=""
>
<?php wp_nonce_field( $data['nonce_action'], $data['nonce_field'] ); ?>
<p>
	<label
		for="<?php echo esc_attr( $data['field_1']['key'] ); ?>"
	>
		<?php esc_html_e( 'Field 1', 'wp-plugin-framework' ); ?>
	</label>*<br>
	<input
		type="text"
		id="<?php echo esc_attr( $data['field_1']['key'] ); ?>"
		name="<?php echo esc_attr( $data['field_1']['key'] ); ?>"
		value="<?php echo esc_attr( $data['field_1']['val'] ); ?>"
	/>
</p>
<p>
	<label
		for="<?php echo esc_attr( $data['field_2']['key'] ); ?>"
	>
		<?php esc_html_e( 'Field 2', 'wp-plugin-framework' ); ?>
	</label>*<br>
	<input
		type="text"
		id="<?php echo esc_attr( $data['field_2']['key'] ); ?>"
		name="<?php echo esc_attr( $data['field_2']['key'] ); ?>"
		value="<?php echo esc_attr( $data['field_2']['val'] ); ?>"
	/>
</p>
<p>
	<span>* <?php esc_html_e( 'mandatory fields', 'wp-plugin-framework' ); ?></span><br>

	<input
		type="submit"
		name="submit"
		id="submit"
		class="button button-primary"
		value="<?php esc_html_e( 'Submit', 'wp-plugin-framework' ); ?>"
	/>
</p>

</form>


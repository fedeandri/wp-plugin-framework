<?php
/**
 * Form shortcode view
 *
 * @since      1.0.0
 * @package    WpPluginFramework
 */

?>

<div id="<?php echo esc_attr( $data['form_id'] ); ?>">
	<div class="form_content">
		<label
			for="<?php echo esc_attr( $data['field_1'] ); ?>"
		>
			<?php esc_html_e( 'Field 1', 'wp-plugin-framework' ); ?>
		</label>*<br>
		<input
			type="text"
			id="<?php echo esc_attr( $data['field_1']['key'] ); ?>"
			name="<?php echo esc_attr( $data['field_1']['key'] ); ?>"
			value="<?php echo esc_attr( $data['field_1']['val'] ); ?>"
		><br>
		<span>* <?php esc_html_e( 'mandatory fields', 'wp-plugin-framework' ); ?></span><br>

		<button
			type="button"
			onclick="WpPluginFramework_submit(<?php echo esc_attr( $data['ajax_params'] ); ?>)"
		>
			<?php esc_html_e( 'Submit', 'wp-plugin-framework' ); ?>
		</button>
	</div>
</div>

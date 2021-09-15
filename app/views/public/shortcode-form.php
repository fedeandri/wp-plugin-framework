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
		<label for="<?php echo esc_attr( $data['prefix'] ); ?>_field_name"><?php esc_html_e( 'Field Name', 'wp-plugin-framework' ); ?></label>*<br>
		<input type="text" id="<?php echo esc_attr( $data['prefix'] ); ?>_field_name" name="<?php echo esc_attr( $data['prefix'] ); ?>_field_name" value="<?php echo esc_attr( $data['field_name'] ); ?>"><br>
		<span>* <?php esc_html_e( 'mandatory fields', 'wp-plugin-framework' ); ?></span><br>
		<button type="button" onclick="WpPluginFramework_submit(<?php echo esc_attr( $data['ajax_params'] ); ?>)"><?php esc_html_e( 'Submit', 'wp-plugin-framework' ); ?></button>
	</div>
</div>

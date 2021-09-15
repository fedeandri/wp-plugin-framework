var WpPluginFramework_submit;

(function( $ ) {
	'use strict';

	WpPluginFramework_submit = function(prefix, ajax_params) {

		var validated = true;
		
		var $field_name = $('#' + prefix + '_field_name');

		var field_name = $field_name.val().trim();

		if(!field_name) {
			$field_name.addClass('error');
			validated = false;
		} else {
			$field_name.removeClass('error');
		}

		var data = {
			action: 'submit_form',
			nonce: ajax_params.nonce,
			field_name: field_name
		}

		$.ajax({
			url: ajax_params.ajaxUrl,
			type: 'post',
			data: data,
			success: function(response) {

				console.log('success');
				// console.log(response);
				
			},
			error: function(response){

				console.log('error');
				// console.log(response);
			} 
		});
	}

})( jQuery );


(function($) {

	 jQuery( document ).ready( function( $ ) {
    	jQuery('#wp-admin-bar-quick_menu-default li a').click(function(){

			theme = jQuery(this).text();  
			var $this = $(this);			
			var data = {
			action: 'att_action',
			security: ajax_object.ajax_nonce,
			theme: theme,
			};

			$.ajax({
				url: ajax_object.ajaxurl,
				type: 'post',
				data: data,
				beforeSend: function() {
					// disable double click
					$this.attr('disabled', true)
				},
				success: function( response ) {
					$this.attr('disabled', false);
					//alert( 'Response: ' + response );
					location.reload();
				},
				complete:function(data2) {
					
				}
			})
    })
})
})(jQuery);
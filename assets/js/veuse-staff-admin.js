jQuery(function($) {

	$('.post-type-staff .wp-list-table > tbody').sortable({
		axis: 'y',
		handle: '.order-staff',
		placeholder: 'ui-state-highlight',
		forcePlaceholderSize: false,
		update: function(event, ui) {
			var theOrder = $(this).sortable('toArray');

			var data = {
				action: 'veuse_staff_update_post_order',
				postType: $(this).attr('data-post-type'),
				order: theOrder
			};
			

			$.post(ajaxurl, data);
		}
	}).disableSelection();

});
jQuery(document).ready(function($){
 
	$('#veuse-staff-image-upload').click(function(e) {
 	
 		e.preventDefault();
		
		frame = wp.media({
		 	title : 'Select portrait',
		 	frame: 'post',
		 	multiple : false, // set to false if you want only one image
		 	library : { type : 'image'},
		 	button : { text : 'Insert portrait' },
		});
		
		frame.on('close',function(data) {
		 	var imageArray = [];
		 	images = frame.state().get('selection');
		 	images.each(function(image) {
				imageArray.push(image.attributes.url);
				imageID = image.attributes.id; // want other attributes? Check the available ones with console.log(image.attributes);
				$('#veuse-staff-image-upload').hide();
				$('#veuse-staff-image-remove').show();
			 });
 
			 jQuery('#portrait').val(imageID); // Adds all image URL's comma seperated to a text input
		 
			 jQuery('#portrait-container').append('<img src="'+ imageArray.join(",") + '"/>');
		
		});
		
		frame.open();

	});
	
	$(document).on('click','#veuse-staff-image-remove', function(e) {
		
		e.preventDefault();
		
		$('#veuse-staff-image-remove').hide();
		$('#veuse-staff-image-upload').show();
		$('#portrait-container img').remove();
		$('#portrait').val('');
		
	});
	
});
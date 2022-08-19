(function( $ ) {
	'use strict';
	 $(function() {

	 	// Open review popup
	 	$('.list-row').on('click', '.vplayclient-inventory-review', function(e){
	 		var id = $(this).data('id');
	 		var btn_id = $(this).attr('id');
	 		$('#product_id').val(id);
	 		$('#'+btn_id).html('Please wait..');
	 		jQuery.ajax({
				type: "POST",
				url: vplayclient_listing_obj.ajaxurl,
				dataType: "json",
				data: {
					action:'vplayclient-check-review',
					product_id:id,
				},
				success:function(response){
					$('#'+btn_id).html('Review');
	 				if(response.status=="success"){
	 					// review exists
	 					for(var i=1; i<=response.rating; i++){
				 			var star = 'staro_'+i;
				 			$('#reviewModalWrapper #'+star).addClass('rating_checked');
				 		}
				 		$('#old_rating_text').val(response.review_text);
	 					$('#reviewModalWrapper').modal('show');
	 				}else if(response.status=="failed"){
	 					alert(response.msg);
	 				}else{
	 					$('#review_success').hide();
						$('#review_error').hide();
						$('#reviewModal').modal('show');
	 				}
				},
			});
			
	 	});

	 	// Change star class
	 	$('body').on('click', '.review-image .rating-form-star', function(e){
	 		e.preventDefault();
	 		$('.rating-form-star').removeClass('rating_checked');
	 		var star_num = $(this).data('star');
	 		$('#rating_stars').val(star_num);
	 		for(var i=1; i<=star_num; i++){
	 			var star = 'star_'+i;
	 			$('#'+star).addClass('rating_checked');
	 		}
	 	});

	 	// close review modal
	 	$('#reviewModal').on('hidden.bs.modal', function (e) {
	 		e.preventDefault();
	 		$('#rating_stars').val(0);
	 		$('.fa-star').removeClass('rating_checked');
	 		$('#rating_review').val('');
	 	});
	 	
	 	/* Load product modal */
	 	$('.list-row').on('click', '.load-btn', function(e){
	 		var id = $(this).data('id');
	 		$('#loadProductContent').html(vplayclient_listing_obj.loader_content);
			$('#loadProductModal').modal('show');

			jQuery.ajax({
				type: "POST",
				url: vplayclient_listing_obj.ajaxurl,
				data: {
					action:'vplayclient-load-product',
					product_id:id,
				},
				success:function(response){
	 				$('#loadProductContent').html(response);
				},
				error:function(){
					$('#loadProductContent').html('<p style="color:red"> Something went wrong</p>');	
				},
			});
	 	});

	 	/* Load product modal */
	 	$('.list-row').on('click', '.vplayclient-inventory-video', function(e){
	 		var id = $(this).data('id');
	 		$('#loadVideoContent').html(vplayclient_listing_obj.loader_content);
			$('#videoModal').modal('show');

			jQuery.ajax({
				type: "POST",
				url: vplayclient_listing_obj.ajaxurl,
				data: {
					action:'vplayclient-load-video',
					product_id:id,
				},
				success:function(response){
					if(response=="failed")
						$('#loadVideoContent').html('<p style="color:red"> Something went wrong</p>');	
	 				else
	 					$('#loadVideoContent').html(response);

				},
				error:function(){
					$('#loadVideoContent').html('<p style="color:red"> Something went wrong</p>');	
				},
			});
	 	});

	 	// submit review form

	 	$('#review-form').submit(function(e){
	 		e.preventDefault();
	 		$('#review_error').hide();
			$('#review_success').hide()
	 		$('#review_error .fusion-alert-content').html('');
	 		$('#review_success .fusion-alert-content').html('');

	 		var rating_stars = parseInt($('#rating_stars').val());
	 		var rating_review = $('#rating_review').val();
	 		var product_id = $('#product_id').val();
	 		var error = false;

	 		if(rating_stars==0 || rating_stars=="" || rating_stars>5){
	 			error = true;
	 			$('#review_error .fusion-alert-content').html(vplayclient_listing_obj.rating_error_msg);
	 		}
	 		if(rating_review=="" && !error){
	 			error = true;
	 			$('#review_error .fusion-alert-content').html(vplayclient_listing_obj.review_error_msg);
	 		}
	 		if(!error){
	 			$('#review-form-submit').val('Please wait..');
	 			jQuery.ajax({
					type: "POST",
					url: vplayclient_listing_obj.ajaxurl,
					dataType: 'json',
					data: {
						action:'vplayclient-submit-review',
						id:product_id,
						rating:rating_stars,
						review:rating_review,
					},
					success:function(response){
						if(response.status=="success"){
							$('#review_success .fusion-alert-content').html(response.msg);	
		 					$('#review_success').show();
						}else{
							$('#review_error .fusion-alert-content').html(response.msg);	
		 					$('#review_error').show();
						}
						$('#rating_stars').val(0);
						$('#rating_review').val('');
						$('.rating-form-star').removeClass('rating_checked');
		 				$('#review-form-submit').val('Submit');
					},
				});	
	 		}else{
	 			$('#review_error').show();
	 		}
	 	});

	 	/* Unlock product */
	 	$('.list-row').on('click', '.unlock-btn', function(e){
	 		var id = $(this).data('id');
	 		var conf = confirm('Are you sure you want to unlock this product?');
	 		var btn_id = $(this).attr('id');
	 		if(conf){
	 			$('#'+btn_id).html('Please wait..');
	 			jQuery.ajax({
					type: "POST",
					url: vplayclient_listing_obj.ajaxurl,
					data: {
						action:'vplayclient-unlock-product',
						product_id:id,
					},
					dataType: "json",
					success:function(response){
						$('#'+btn_id).html('Unlock');
						if(response.status=="success"){
							$('#unlock-btn-wrapper-'+id).html(response.content);
							alert('Congratulations! You have successfully unlocked product');
						}else if(response.status=="already_exist"){
							$('#unlock-btn-wrapper-'+id).html(response.content);
						}else if(response.status=="not_enough_coins"){
							alert(response.content);
						}
					},
					error:function(){
						$('#'+btn_id).html('Unlock');
						alert('Something went wrong. Please try again.');
					},
				});
	 		}
	 	});

	 	$('body').on('click', '.extra-button', function(e){
	 		e.preventDefault();
	 		var product_id = $(this).data('id');
	 		var btn_number = $(this).data('btn');
	 		var caption = $(this).data('caption');
	 		var btn_id = $(this).attr('id');
	 		$('#'+btn_id).html('Please wait..');
	 		$.ajax({
				type: "POST",
				url: vplayclient_listing_obj.ajaxurl,
				data: {
					action:'vplayclient-extra-button',
					product_id:product_id,
					btn:btn_number,
				},
				dataType: "json",
				success:function(response){
					$('#'+btn_id).html(caption);

					if(response.status=="success"){
						alert('Success');
					}else if(response.status=="limit_reached"){
						alert('Limit Reached.');
					}else if(response.status=="failed"){
						alert('Something went wrong. Please try again.');
					}
				},
				error:function(){
					$('#'+btn_id).html(caption);
					alert('Something went wrong. Please try again.');
				},
			});
	 	});

	});
	

})( jQuery );


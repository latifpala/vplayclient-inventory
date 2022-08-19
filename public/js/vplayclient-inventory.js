(function( $ ) {
	'use strict';
	 $(function(){
	 	$('#vplayclient-register').submit(function(e){
	 		e.preventDefault();			
	 		$('#vplayclient-register-submit').html('Please wait..');
	 		$('#register_error').hide();
	 		$('#register_error .fusion-alert-content').html('');
	 		var data = $('#vplayclient-register').serialize();
	 		jQuery.ajax({
				type: "POST",
				dataType: 'json',
				url: vplayclient_obj.ajaxurl,
				data: data,
				success:function(response){
					$('#vplayclient-register-submit').html('Register');
					if(response.status=="empty"){
						$('#register_error .fusion-alert-content').html(vplayclient_obj.blank_fields_error);
						$('#register_error').show();
					}else if(response.status=="user_login_invalid"){
						$('#register_error .fusion-alert-content').html(vplayclient_obj.user_login_invalid_error);
						$('#register_error').show();
					}else if(response.status=="user_login_exists"){
						$('#register_error .fusion-alert-content').html(vplayclient_obj.user_login_exists_error);
						$('#register_error').show();
					}else if(response.status=="email_invalid"){
						$('#register_error .fusion-alert-content').html(vplayclient_obj.email_invalid_error);
						$('#register_error').show();
					}else if(response.status=="email_exists"){
						$('#register_error .fusion-alert-content').html(vplayclient_obj.email_exists_error);
						$('#register_error').show();
					}else if(response.status=="success"){
						$('#register_error').hide();
						window.location.href = response.redirect_url;
					}else{
						$('#register_error .fusion-alert-content').html(vplayclient_obj.register_failed_error);
						$('#register_error').show();
					}
				},
				error:function(response){
					$('#vplayclient-register-submit').html('Register');
				},
			});
	 	});
		
		$('#vplayclient-change-password').submit(function(e){
	 		e.preventDefault();
	 		$('#change-psw-submit').html('Please wait..');
	 		$('#change_password_error').hide();
	 		$('#change_password_error .fusion-alert-content').html('');

			var data = $('#vplayclient-change-password').serialize();
	 		jQuery.ajax({
				type: "POST",
				dataType: 'json',
				url: vplayclient_obj.ajaxurl,
				data: data,
				success:function(response){
	 				$('#change-psw-submit').html('Submit');
					if(response.status=="empty"){
						$('#change_password_error .fusion-alert-content').html(vplayclient_obj.blank_fields_error);
						$('#change_password_error').show();
					}else if(response.status=="pwd_cpwd_not_matched"){
						$('#change_password_error .fusion-alert-content').html(vplayclient_obj.password_cpassword_error);
						$('#change_password_error').show();
					}else if(response.status=="success"){
						$('#change_password_error').hide();
						alert('Your password is changed. Please login to continue.');
						window.location.href = response.redirect_url;
					}else{
						$('#change_password_error .fusion-alert-content').html(vplayclient_obj.change_password_error);
						$('#change_password_error').show();
					}
				},
				error:function(response){
	 				$('#change-psw-submit').html('Submit');
				},
			});
	 	});
		
	 	$('#vplayclient_subscribe_free').click(function(e){
	 		e.preventDefault();
	 		$('#membership_error .fusion-alert-content').html('');
			$('#membership_error').hide();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			jQuery.ajax({
					type: "POST",
					dataType: 'json',
					url: vplayclient_obj.ajaxurl,
					data: {
						action:'vplayclient-register-free',
						user_id:vplayclient_obj.uid,
					},
					success:function(response){
						if(response.status=="success"){
							setTimeout(function () {
								$('#vplayclient_subscribe_free').html('Select');
							}, 500);
							window.location.href = vplayclient_obj.dashboard_url;
						}else if(response.status=="membership_active"){
							$('#membership_error .fusion-alert-content').html(response.error_message);
							$('#membership_error').show();
							setTimeout(function () {
								$('#vplayclient_subscribe_free').html('Select');
							}, 500);
						}
					},
					error:function(response){
						$('#vplayclient_subscribe_free').html('Select');
					},
				});
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
			
	 	});

	 	$('#vplayclient_subscribe_basic').click(function(e){
	 		e.preventDefault();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			var mighty_url = "https://mightygiftcards.com/amazon-basic-membership.php?ref="+vplayclient_obj.ref+"_basic&email="+vplayclient_obj.u+"&u="+vplayclient_obj.uid;
	 			window.location.href = mighty_url;
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
			setTimeout(function () {
				$('#vplayclient_subscribe_basic').html('Select');
			}, 500);
	 	});
	 	$('#vplayclient_subscribe_standard').click(function(e){
	 		e.preventDefault();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			var mighty_url = "https://mightygiftcards.com/amazon-standard-membership.php?ref="+vplayclient_obj.ref+"_standard&email="+vplayclient_obj.u+"&u="+vplayclient_obj.uid;
	 			window.location.href = mighty_url;
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
	 		setTimeout(function () {
				$('#vplayclient_subscribe_standard').html('Select');
			}, 500);
	 	});
	 	$('#vplayclient_subscribe_ultimate').click(function(e){
	 		e.preventDefault();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			var mighty_url = "https://mightygiftcards.com/amazon-ultimate-membership.php?ref="+vplayclient_obj.ref+"_ultimate&email="+vplayclient_obj.u+"&u="+vplayclient_obj.uid;
	 			window.location.href = mighty_url;
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
	 		setTimeout(function () {
				$('#vplayclient_subscribe_ultimate').html('Select');
			}, 500);
	 	});
		
		$('#vplayclient_coins_plan1').click(function(e){
	 		e.preventDefault();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			var mighty_url = "https://mightygiftcards.com/nintendo-gift-cards.php?ref="+vplayclient_obj.ref+"&u="+vplayclient_obj.uid+"&plan="+vplayclient_obj.coins_plan1;
	 			window.location.href = mighty_url;
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
	 		setTimeout(function () {
				$('#vplayclient_coins_plan1').html('Select');
			}, 500);
	 	});
		
		$('#vplayclient_coins_plan2').click(function(e){
	 		e.preventDefault();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			var mighty_url = "https://mightygiftcards.com/nintendo-gift-cards.php?ref="+vplayclient_obj.ref+"&u="+vplayclient_obj.uid+"&plan="+vplayclient_obj.coins_plan2;
	 			window.location.href = mighty_url;
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
	 		setTimeout(function () {
				$('#vplayclient_coins_plan2').html('Select');
			}, 500);
	 	});
		
		$('#vplayclient_coins_plan3').click(function(e){
	 		e.preventDefault();
	 		$(this).html('Please wait..');
	 		if(vplayclient_obj.uid!='' && vplayclient_obj.u!=''){
	 			var mighty_url = "https://mightygiftcards.com/nintendo-gift-cards.php?ref="+vplayclient_obj.ref+"&u="+vplayclient_obj.uid+"&plan="+vplayclient_obj.coins_plan3;
	 			window.location.href = mighty_url;
	 		}else{
	 			window.location.href = vplayclient_obj.login_url;
	 		}
	 		setTimeout(function () {
				$('#vplayclient_coins_plan3').html('Select');
			}, 500);
	 	});
	});
	
	if(vplayclient_obj.invalid=="yes"){
		$('#membership_error .fusion-alert-content').html(vplayclient_obj.invalid_msg);
		$('#membership_error').show();
	}

	if(vplayclient_obj.cancel=="yes"){
		$('#membership_error .fusion-alert-content').html(vplayclient_obj.cancel_msg);
		$('#membership_error').show();
	}

	if(vplayclient_obj.failed=="yes"){
		$('#membership_error .fusion-alert-content').html(vplayclient_obj.failed_msg);
		$('#membership_error').show();
	}
	
})( jQuery );

/*// Open the Modal
function openModal() {
  document.getElementById("myModal").style.display = "block";
}
// Close the Modal
function closeModal() {
  document.getElementById("myModal").style.display = "none";
}
function openVideoModal() {
	  	document.getElementById("myVideoModal").style.display = "block";
		var $videoSrc;  
		$('.video-btn').click(function() {
			$videoSrc = $(this).data( "src" );
			console.log('hi');
		});
		console.log($videoSrc);
	  
		// when the modal is opened autoplay it  
		$('#myVideoModal').on('shown.bs.modal', function (e) {
			// set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
			$("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" ); 
		});
	  
		// stop playing the youtube video when I close the modal
		$('#myVideoModal').on('hide.bs.modal', function (e) {
		    // a poor man's stop video
		    $("#video").attr('src',$videoSrc); 
		}); 
	}*/

jQuery(document).ready(function(){
	jQuery('.list-menu-icon a').click(function(){
		jQuery('.v-play-list .nav.nav-pills').toggle('slow');
	});
	jQuery('.left-bar h3').click(function(){
		jQuery('.left-bar ul').toggle('slow');
	});
});
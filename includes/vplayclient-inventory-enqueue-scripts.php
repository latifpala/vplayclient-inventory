<?php
add_action( 'admin_enqueue_scripts', 'vplayclient_inventory_admin_enqueue' );
function vplayclient_inventory_admin_enqueue(){
	wp_enqueue_style( 'vplayclient-inventory-admin', VCI_URL . 'admin/css/vplayclient-inventory-admin.css', array(), VPLAYCLIENT_INVENTORY_VERSION );
	
	wp_enqueue_script( 'vplayclient-inventory-admin', VCI_URL . 'admin/js/vplayclient-inventory-admin.js', array( 'jquery' ), 1.2, true );
}

add_action( 'wp_enqueue_scripts', 'vplayclient_inventory_public_enqueue' );
function vplayclient_inventory_public_enqueue(){
	wp_enqueue_style( 'vplayclient-inventory', VCI_URL . 'public/css/vplayclient-inventory.css', array(), 1.89 );
	
	wp_enqueue_script( 'vplayclient-inventory-bootstrap-modal', VCI_URL . 'public/js/bootstrap.modal.js', array( 'jquery' ), 1.0, true );
	wp_enqueue_script( 'vplayclient-inventory', VCI_URL . 'public/js/vplayclient-inventory.js', array( 'jquery' ), 1.116, true );

	$user_email = $user_id = "";
	$invalid_set = $cancel_set = $failed_set = "no";
	if(isset($_REQUEST['invalid'])){
		$invalid_set = "yes";
	}elseif(isset($_REQUEST['cancel'])){
		$cancel_set = "yes";
	}elseif(isset($_REQUEST['failed'])){
		$failed_set = "yes";
	}

	if(is_user_logged_in()){
		$user = wp_get_current_user();
		$user_email = $user->user_email;
		$user_id = $user->ID;
	}else{
		$user = false;
	}

	$coins_plan1 		= base64_encode("plan1_".get_option('vplayclient_coin_plan1_price'));
	$coins_plan2 		= base64_encode("plan2_".get_option('vplayclient_coin_plan2_price'));
	$coins_plan3 		= base64_encode("plan3_".get_option('vplayclient_coin_plan3_price'));
	wp_localize_script('vplayclient-inventory', 'vplayclient_obj', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'email_invalid_error' => __('This is not a valid email address', VCI_DOMAIN),
		'email_exists_error' => __('This email address has already been used', VCI_DOMAIN),
		'user_login_exists_error' => __('The username is already in use', VCI_DOMAIN),
		'user_login_invalid_error' => __('Sorry, the username you entered is not valid', VCI_DOMAIN),
		'blank_fields_error' => __('All fields are required', VCI_DOMAIN),
		'register_failed_error' => __('Something went wrong. Please try again', VCI_DOMAIN),
		'password_cpassword_error' => __('Please enter same password again', VCI_DOMAIN),
		'change_password_error' => __('Something went wrong. Please try again', VCI_DOMAIN),
		'u' => $user_email,
		'coins_plan1'=>$coins_plan1,
		'coins_plan2'=>$coins_plan2,
		'coins_plan3'=>$coins_plan3,
		'uid' => $user_id,
		'login_url' => site_url('login'),
		'ref' => md5(site_url()),
		'ref_code' => site_url(),
		'dashboard_url' => site_url('dashboard'),
		'invalid' => $invalid_set,
		'cancel' => $cancel_set,
		'failed' => $failed_set,
		'invalid_msg' => __('Invalid Input Provided. Please try again', VCI_DOMAIN),
		'cancel_msg' => __('Payment was Cancelled. Please try again.', VCI_DOMAIN),
		'failed_msg' => __('Payment Failed. Please try again.', VCI_DOMAIN)
	));

	if(is_page('inventory')):
		$loader_content = '<div class="loader-img"><h4>Please wait..</h4><img src="'.VCI_URL.'/public/images/loader.gif" /></div>';
		wp_enqueue_script( 'vplayclient-inventory-listing', VCI_URL . 'public/js/vplayclient-inventory-listing.js', array( 'jquery' ), 1.45, true );
		wp_localize_script('vplayclient-inventory-listing', 'vplayclient_listing_obj', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'loader_content' => $loader_content,
			'rating_error_msg' => __('Please select at least 1 star review', VCI_DOMAIN),
			'review_error_msg' => __('Please enter review', VCI_DOMAIN),
		));
	endif;
}
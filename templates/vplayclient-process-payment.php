<?php
/* 
Template Name: Process Payment
*/
//token=57V28580BU1374356&tx=62L929462J419582H&st=Pending&amt=0.01&cc=USD&cm=&item_number=%23basic&sig=ENorEm5sjXUluucl2yNrR282R6oLWfNry2NGLP60WqoxPnK%2B%2FYkynFkWO9K0bql6l10QcDJz3hKAdibDCWRb1Dd02Bdbz%2BQ1y8ICOUVd7EzVMQ32B0026TzMj9EYv1JqLND4g8IRpxLgdpx9Q9kql4UaXC7y3z6iKLApKIqYRrw%3D
if(!isset($_GET['token']) || !isset($_GET['cm']) || !isset($_GET['tx']) || !isset($_GET['item_number'])){
	wp_redirect(site_url('subscribe/?invalid=1'));
	die;
}
global $wpdb;

$token = sanitize_text_field($_GET['token']);
$transaction_id = sanitize_text_field($_GET['tx']);
$status = sanitize_text_field($_GET['st']);
$amount = sanitize_text_field($_GET['amt']);
$user_id = sanitize_text_field($_GET['cm']);
$item_number = sanitize_text_field($_GET['item_number']);
$signature = sanitize_text_field($_GET['sig']);
$table = $wpdb->prefix."subscription_payments";
$check_token_status = vplayclient_check_token($token, $transaction_id);
$coins_available = get_user_meta($user_id, 'coins', true);
if($coins_available=="" || $coins_available<0){
	$coins_available = 0;
}

$new_coins = 0;
if($item_number=="#basic"){
	$new_coins = get_option('vplayclient_basic_plan_coins');
}elseif($item_number=="#Standard"){
	$new_coins = get_option('vplayclient_standard_plan_coins');
}elseif($item_number=="#ultimate"){
	$new_coins = get_option('vplayclient_ultimate_plan_coins');
}

if($check_token_status){
	$payment_data = array(
		'user_id' => $user_id,
		'token' => $token,
		'transaction_id' => $transaction_id,
		'status' => $status,
		'amount' => $amount,
		'item_number' => $item_number,
		'signature' => $signature,
		'start_date' => date('Y-m-d'),
		'end_date' => date('Y-m-d',strtotime('+29 days'))
	);
	$res = $wpdb->insert( $table, $payment_data);
	if($res){
		$payment_id = md5($wpdb->insert_id)."@".$wpdb->insert_id;
		$final_coins = $coins_available + $new_coins;

		update_user_meta($user_id, 'membership_status', 'Active');
		update_user_meta($user_id, 'last_payment_id', $wpdb->insert_id);
		update_user_meta($user_id, 'membership_package', $item_number);
		update_user_meta($user_id, 'start_date', date('Y-m-d'));
		update_user_meta($user_id, 'end_date', date('Y-m-d', strtotime('+29 days')));
		update_user_meta($user_id, 'coins', $final_coins);

		vplayclient_membership_success_mail($user_id, $item_number, date('d-m-Y'), date('d-m-Y', strtotime('+29 days'), $final_coins);
			

		wp_redirect(site_url('thank-you/?res='.$payment_id));
		die;
	}else{
		wp_redirect(site_url('subscribe/?failed=1'));
		die;	
	}
}else{
	wp_redirect(site_url('subscribe/?invalid=1'));
	die;
}
?>
<html><head>
<title>Please wait.. Processing payment</title>
</head>
<body style="">
	Processing Payment. Please wait.
</body>
</html>
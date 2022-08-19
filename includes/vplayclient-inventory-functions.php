<?php
function vplayclient_check_token($token, $transaction_id){
	global $wpdb;
	$table = $wpdb->prefix."subscription_payments";
	$count = $wpdb->get_var("SELECT COUNT(*) FROM ".$table ." WHERE token='$token' or transaction_id='$transaction_id'");
	if($count==0)
		return true;
	else
		return false;
}

function vplayclient_get_avg_reviews($product_id){
	global $wpdb;
	$table = $wpdb->prefix."vplayclient_reviews";
	$qry = "SELECT rating FROM {$table} WHERE status='1' AND product_id={$product_id}";
	$res = $wpdb->get_results($qry);
	$avg_review = $total = $count = 0;
	if(!empty($res)){
		foreach ($res as $key => $value) {
			$count++;
			$total = $total + $value->rating;
		}
		if($total>0){
			$avg_review = $total/$count;
		}
	}
	return ceil($avg_review);
}
function vplayclient_get_reviews($product_id, $limit){
	global $wpdb;
	$table = $wpdb->prefix."vplayclient_reviews";
	$qry = "SELECT * FROM {$table} WHERE status='1' AND product_id={$product_id}";
	$res = $wpdb->get_results($qry);
	return $res;
}

function vplayclient_mail_header(){
	$header_content = '<div style="border: 2px solid #c1c1c1;"><div style="text-align:center; border-bottom: 2px solid #c1c1c1; font-size:20px; background:#222; "><img style="margin: 10px 0; width: 125px;" src="logo-75x75-1.png" title="VPlayClient"  alt="VPlayClient" /></div>
			<div style="border-color: #c1c1c1; border-style: solid none; border-width: 1px 0; padding: 20px; color: #555;">';
	return $header_content;
}
function vplayclient_mail_footer(){
	$footer_content = '<table style="margin-top: 20px; color: #555;"><tr><td>Best regards,</td></tr><tr><td>VPlayClient Support</td></tr></table>
				</div>
			</div>';
	return $footer_content;
}
function vplayclient_membership_success_mail($user_id, $membership_package, $start_date, $end_date, $coins){
	$adminEmail = get_option('admin_email');
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers[] = 'From: VplayClient <no-reply@vplayclient.com>';

	$user = get_user_by('id', $user_id);
	$name = $user->first_name." ".$user->last_name;
	$email = $user->user_email;
	$subject = 'Congratulations! Your membership is successful.';

	$main_content = vplayclient_mail_header();
	$main_content .= '<p style="font-weight: bold;">Hi '.$name.'</p>';
	$main_content .= '<p>You have successfully subscribed to <b>'.$membership_package.'</b>. Please check details about membership below.</p>';
	$main_content .= '<table style="margin-top: 20px; color: #555;">';
	$main_content .= '<tr><th>Package</th><td>:</td><td>'.$membership_package.'</td></tr>';
	$main_content .= '<tr><th>Start Date</th><td>:</td><td>'.$start_date.'</td></tr>';
	$main_content .= '<tr><th>Renew Date</th><td>:</td><td>'.$end_date.'</td></tr>';
	$main_content .= '<tr><th>Total Coins</th><td>:</td><td>'.$coins.'</td></tr>';
	$main_content .= '</table>';
	$main_content .= vplayclient_mail_footer();

	wp_mail( $email, $subject, $main_content, $headers);
}

function vplayclient_log($input) {
	global $wpdb;
	$date = date('m/d/Y H:i:s', current_time('timestamp', 0));

	// setup new post data
	$my_post = array(
		'post_title'   => 'wpeppsub_logs',
		'post_content' => $date,
	);
	// update
	wp_update_post( $my_post );
}
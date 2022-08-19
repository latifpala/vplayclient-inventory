<?php
add_action('wp_ajax_vplayclient-register','vplayclient_register_func');
add_action('wp_ajax_nopriv_vplayclient-register','vplayclient_register_func');
function vplayclient_register_func(){
	$retrieved_nonce = sanitize_text_field($_POST['_wpnonce']);
	if (!wp_verify_nonce($retrieved_nonce, 'vplayclient-registration' ) ){
		echo json_encode(array('status' => 'nonce_failed'));
		die;
	}
	$user_login = sanitize_text_field($_POST['user_login']);
	$user_email = sanitize_text_field($_POST['user_email']);
	$user_pass = sanitize_text_field($_POST['user_pass']);
	
	if(trim($user_email)=="" || trim($user_login)=="" || trim($user_pass)==""){
		echo json_encode(array('status' => 'empty'));
		die;
	}
	if (!validate_username($user_login)) {
		echo json_encode(array('status' => 'user_login_invalid'));
		die;
	}
	if(username_exists($user_login)){
		echo json_encode(array('status' => 'user_login_exists'));
		die;
	}
	if (!is_email($user_email)) {
		echo json_encode(array('status' => 'email_invalid'));
		die;
	}
	if(email_exists($user_email)){
		echo json_encode(array('status' => 'email_exists'));
		die;
	}
	$user_arr = array(
		'user_login' => $user_login,
		'user_email' => $user_email,
		'user_pass' => $user_pass,
	);
	$user_id = wp_insert_user($user_arr);
	if($user_id){
		$info = array();
		$info['user_login'] = $user_login;
		$info['user_password'] = $user_pass;
		$info['remember'] = true;
		$user_signon = wp_signon( $info, true );
		$redirect_url = site_url('subscribe');
		echo json_encode(array('status' => 'success', 'redirect_url' => $redirect_url));
	}else{
		echo json_encode(array('status' => 'failed'));
	}
	die;
}

add_action('wp_ajax_vplayclient-change-password','vplayclient_change_password_func');
add_action('wp_ajax_nopriv_vplayclient-change-password','vplayclient_change_password_func');
function vplayclient_change_password_func(){
	$retrieved_nonce = sanitize_text_field($_POST['_wpnonce']);
	if (!wp_verify_nonce($retrieved_nonce, 'vplayclient-change-password' ) ){
		echo json_encode(array('status' => 'nonce_failed'));
		die;
	}
	$user_cpass = sanitize_text_field($_POST['user_cpass']);
	$user_pass = sanitize_text_field($_POST['user_pass']);
	
	if(trim($user_cpass)=="" || trim($user_pass)==""){
		echo json_encode(array('status' => 'empty'));
		die;
	}
	if(trim($user_cpass) != trim($user_pass)){
		echo json_encode(array('status' => 'pwd_cpwd_not_matched'));
		die;
	}
	
	wp_set_password($user_pass,get_current_user_id());
	$redirect_url = site_url('login');
	wp_logout();
	echo json_encode(array('status' => 'success', 'redirect_url' => $redirect_url));	
	die;
}

add_action('wp_ajax_vplayclient-register-free','vplayclient_register_free_func');
add_action('wp_ajax_nopriv_vplayclient-register-free','vplayclient_register_free_func');
function vplayclient_register_free_func(){
	$user_id = sanitize_text_field($_POST['user_id']);
	$membership_package = get_user_meta($user_id, 'membership_package', true);
	$today = date("Y-m-d");
	if($membership_package==""){
		update_user_meta($user_id, 'membership_package', "free");
		update_user_meta($user_id, 'start_date', date('Y-m-d'));
		update_user_meta($user_id, 'end_date', date('Y-m-d', strtotime('+29 days')));
		echo json_encode(array('status' => 'success'));
		die;
	}else{
		$end_date = get_user_meta($user_id, 'end_date', true);
		if(strtotime($today) > strtotime($end_date)){
			update_user_meta($user_id, 'membership_package', "free");
			update_user_meta($user_id, 'start_date', date('Y-m-d'));
			update_user_meta($user_id, 'end_date', date('Y-m-d', strtotime('+29 days')));
			echo json_encode(array('status' => 'success'));
			die;
		}else{
			$error_message = "You already have an active <b>".$membership_package."</b> membership till ".date('dS F, Y', strtotime($end_date));
			echo json_encode(array('status' => 'membership_active', 'package' => $membership_package, 'error_message' => $error_message));
			die;
		}
	}
}

add_action('wp_ajax_vplayclient-load-product','vplayclient_load_product_func');
add_action('wp_ajax_nopriv_vplayclient-load-product','vplayclient_load_product_func');

function vplayclient_load_product_func(){
  $product_id = sanitize_text_field($_POST['product_id']);
  $avg_review = vplayclient_get_avg_reviews($product_id);
  $blank_stars = 5-$avg_review;
  $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium', true );
  $featured_image_src = ( !empty($featured_image) ) ? $featured_image[0] : VCI_URL.'public/images/product-img.png';
  $product = get_post($product_id);
  $product_title = $product->post_title;
  $product_content = $product->post_content;
  $reviews = vplayclient_get_reviews($product_id, 3);
	?>
	<div class="md-prd-detail">
      <div class="md-prd-image">
        <img src="<?php echo $featured_image_src; ?>" alt="<?php echo $product_title; ?>">
      </div>
      <div class="md-prd-discription">
        <h3><?php echo $product_title; ?></h3>
        <div class="review-image">
          <?php
          for($i=1; $i<=$avg_review; $i++){
            echo '<span class="fa fa-star rating_checked"></span>';
          }
          for($i=1; $i<=$blank_stars; $i++){
            echo '<span class="fa fa-star"></span>';
          }
          ?>
        </div>
        <div class="review-discription">
          <p><?php echo $product_content; ?></p>
        </div>
        <div class="review-btns-wrap">
          <?php 
          if(get_field('button1_caption', $product_id) && get_field('button1_code', $product_id)): ?>
            <a href="javascript:;" id="<?php echo $product_id.'_btn1'; ?>" class="fusion-login-button fusion-button button-default button-medium extra-button" data-caption="<?php the_field('button1_caption', $product_id); ?>" data-id="<?php echo $product_id; ?>"  data-btn="1"><?php the_field('button1_caption', $product_id); ?></a>
          <?php endif; ?>

          <?php 
          if(get_field('button2_caption', $product_id) && get_field('button2_code', $product_id)): ?>
            <a href="javascript:;" id="<?php echo $product_id.'_btn2'; ?>" class="fusion-login-button fusion-button button-default button-medium extra-button" data-caption="<?php the_field('button2_caption', $product_id); ?>" data-id="<?php echo $product_id; ?>"  data-btn="2" ><?php the_field('button2_caption', $product_id); ?></a>
          <?php endif; ?>

          <?php 
          if(get_field('button3_caption', $product_id) && get_field('button3_code', $product_id)): ?>
            <a href="javascript:;" id="<?php echo $product_id.'_btn3'; ?>" class="fusion-login-button fusion-button button-default button-medium extra-button" data-caption="<?php the_field('button3_caption', $product_id); ?>" data-id="<?php echo $product_id; ?>"  data-btn="3"><?php the_field('button3_caption', $product_id); ?></a>
          <?php endif; ?>

          <?php 
          if(get_field('button4_caption', $product_id) && get_field('button4_code', $product_id)): ?>
            <a href="javascript:;" id="<?php echo $product_id.'_btn4'; ?>" class="fusion-login-button fusion-button button-default button-medium extra-button" data-caption="<?php the_field('button4_caption', $product_id); ?>" data-id="<?php echo $product_id; ?>"  data-btn="4"><?php the_field('button4_caption', $product_id); ?></a>
          <?php endif; ?>

          <?php 
          if(get_field('button5_caption', $product_id) && get_field('button5_code', $product_id)): ?>
            <a href="javascript:;" id="<?php echo $product_id.'_btn5'; ?>" class="fusion-login-button fusion-button button-default button-medium extra-button" data-caption="<?php the_field('button5_caption', $product_id); ?>" data-id="<?php echo $product_id; ?>"  data-btn="5" ><?php the_field('button5_caption', $product_id); ?></a>
          <?php endif; ?>

          <?php 
          if(get_field('button6_caption', $product_id) && get_field('button6_code', $product_id)): ?>
            <a href="javascript:;" id="<?php echo $product_id.'_btn6'; ?>" class="fusion-login-button fusion-button button-default button-medium extra-button" data-caption="<?php the_field('button6_caption', $product_id); ?>" data-id="<?php echo $product_id; ?>" data-btn="6"><?php the_field('button6_caption', $product_id); ?></a>
          <?php endif; ?>
          
        </div> 
      </div>
    </div>

    <?php
    if(!empty($reviews)):
      ?>
    <div class="section-title">
        <h3>All Rating and Reviews</h3>
    </div>
    <?php
      foreach ($reviews as $review):
        $user = get_user_by('ID',$review->user_id);
        $user_login = $user->user_login;
        $rating = $review->rating;
        $blank_stars = 5-$rating;
        ?>
        <div class="md-prd-content">
          <!-- <div class="auther-img">
            <div class="img-circle"><img src="https://test.vplayclient.com/wp-content/uploads/2015/02/image8-1.jpg" alt=""></div>
          </div> -->
          <div class="md-review-detail">
            <h4><?php echo $user_login; ?></h4>
            <h5><?php echo date('d M, Y', strtotime($review->date)); ?></h5>
            <form method="post">
              <div class="review-image">
                <label for="rating">Rating</label>
                <?php
                for($i=1; $i<=$rating; $i++){
                  echo '<span class="fa fa-star rating_checked"></span>';
                }
                for($i=1; $i<=$blank_stars; $i++){
                  echo '<span class="fa fa-star"></span>';
                }
                ?>
              </div>
            </form>
            <div class="review-discription">
              <p><?php echo $review->review_text; ?></p>
            </div>
          </div>
        </div>
        <?php
      endforeach;
    endif;
    ?>
	<?php
  die;
}

add_action('wp_ajax_vplayclient-submit-review','vplayclient_submit_review_func');
add_action('wp_ajax_nopriv_vplayclient-submit-review','vplayclient_submit_review_func');
function vplayclient_submit_review_func(){
  global $wpdb;
  if(!is_user_logged_in()){
    echo json_encode(array('status' => 'failed', 'msg' => __('Please login to add review.', VCI_DOMAIN)));
    die;
  }
  $user_id = get_current_user_id();
  $product_id = (isset($_POST['id']))?sanitize_text_field($_POST['id']):'';
  $rating = (isset($_POST['rating']))?sanitize_text_field($_POST['rating']):'';
  $review = (isset($_POST['review']))?sanitize_text_field($_POST['review']):'';
  $table = $wpdb->prefix."vplayclient_reviews";
  $check_review = $wpdb->get_var("SELECT COUNT(*) FROM ".$table ." WHERE user_id='$user_id' AND product_id='$product_id'");

  if($check_review==0){
    $insert_review_array = array(
      'product_id' => $product_id,
      'user_id' => $user_id,
      'rating' => $rating,
      'review_text' => $review,
      'status' => '0',
      'date' => date('Y-m-d')
    );
    $wpdb->insert($table, $insert_review_array);
    echo json_encode(array('status' => 'success', 'msg' => __('Thank you! Your review has been recorded.', VCI_DOMAIN)));
  }else{
    echo json_encode(array('status' => 'failed', 'msg' => __('You have already submitted review for this product.', VCI_DOMAIN)));
  }
  die;
}

add_action('wp_ajax_vplayclient-check-review','vplayclient_check_review_func');
add_action('wp_ajax_nopriv_vplayclient-check-review','vplayclient_check_review_func');
function vplayclient_check_review_func(){
  global $wpdb;
  $user_id = get_current_user_id();
  $product_id = (isset($_POST['product_id']))?sanitize_text_field($_POST['product_id']):'';

  if(!is_user_logged_in()){
    echo json_encode(array('status' => 'failed', 'msg' => __('Please login to add review.', VCI_DOMAIN)));
    die;
  }
  $table = $wpdb->prefix."vplayclient_reviews";
  $old_review = $wpdb->get_results("SELECT * FROM ".$table ." WHERE user_id='$user_id' AND product_id='$product_id'");
  if(!empty($old_review)){
    $rating = $old_review[0]->rating;
    $review_text = $old_review[0]->review_text;
    echo json_encode(array('status' => 'success', 'rating' => $rating, 'review_text' => $review_text));
  }else{
    echo json_encode(array('status' => 'not_exist'));
  }
  die;
}


add_action('wp_ajax_vplayclient-load-video','vplayclient_load_video_func');
add_action('wp_ajax_nopriv_vplayclient-load-video','vplayclient_load_video_func');
function vplayclient_load_video_func(){
  $product_id = (isset($_POST['product_id']))?sanitize_text_field($_POST['product_id']):'';
  if(get_field('video', $product_id)):
    echo do_shortcode('[video src="'.get_field('video', $product_id).'"]'); 
  else:
    echo "failed";
  endif;
  die;
}

add_action('wp_ajax_vplayclient-unlock-product','vplayclient_unlock_product_func');
add_action('wp_ajax_nopriv_vplayclient-unlock-product','vplayclient_unlock_product_func');
function vplayclient_unlock_product_func(){
  $product_id = (isset($_POST['product_id']))?sanitize_text_field($_POST['product_id']):'';
  $user_id = get_current_user_id();
  $unlocked_products = get_user_meta($user_id, 'unlocked_products', true);
  $coins_available = get_user_meta($user_id, 'coins', true);
  $required_coins_to_unlock = get_field('coins_to_unlock_product', $product_id);
  
  $content = $status = "";
  
  if($coins_available=="")
    $coins_available = 0;

  if($required_coins_to_unlock == "")
    $required_coins_to_unlock = 0;

  if($unlocked_products=="")
    $unlocked_products = array();


  if(in_array($product_id, $unlocked_products)):
    $content = '<p style="color: red; margin-bottom:0;">'.__("Product is already unlocked", VCI_DOMAIN).'</p>';
    $content .= '<button class="fusion-login-button fusion-button button-medium unlock-btn already-exist-margin" data-id="'.$product_id.'" id="unlock-btn-'.$product_id.'">'.__("Unlock", VCI_DOMAIN).'</button>';
    $status = 'already_exist';
  elseif($coins_available<$required_coins_to_unlock):
    $content = "You don't have enough coins to unlock this product";
    $status = 'not_enough_coins';
  else:
    $unlocked_products[] = $product_id;
    $remaining_coins = $coins_available - $required_coins_to_unlock;

    update_user_meta($user_id, 'unlocked_products', $unlocked_products);
    update_user_meta($user_id, 'coins', $remaining_coins);
    
    $status = "success";
    $content = '<button class="fusion-login-button fusion-button button-default button-medium load-btn" data-id="'.$product_id.'">Load Product</button><button class="fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth vplayclient-inventory-review" id="vplayclient-inventory-review-'.$product_id.'" data-id="'.$product_id.'">'.__("Review", VCI_DOMAIN).'</button>';

    if(get_field('video', $product_id)):
      $content .= '<button type="button" class="video-btn fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth vplayclient-inventory-video" data-id="'.$product_id.'">'.__('Demo', VCI_DOMAIN).'</button>';
    endif;
  endif;

  echo json_encode(array('status' => $status, 'content' => $content));
  die;
}

add_action('wp_ajax_vplayclient-extra-button','vplayclient_extra_button_func');
add_action('wp_ajax_nopriv_vplayclient-extra-button','vplayclient_extra_button_func');
function vplayclient_extra_button_func(){
  $user_id = get_current_user_id();
  $product_id = (isset($_POST['product_id']))?sanitize_text_field($_POST['product_id']):'';
  $btn = (isset($_POST['btn']))?sanitize_text_field($_POST['btn']):'';
  $user_key = $product_id.'_btn'.$btn;
  $maximum_clicks_key = 'maximum_clicks'.$btn;
  $btn_code_key = 'button'.$btn.'_code';

  $btn_clicks_by_user = get_user_meta($user_id, $user_key, true);
  $maximum_clicks_by_user = get_field($maximum_clicks_key, $product_id);
  $btn_code = get_field($btn_code_key, $product_id);

  $limit_reached = $success = false;
  if($btn_clicks_by_user=="" || $btn_clicks_by_user<=0){
    $btn_clicks_by_user = 1;
    update_user_meta($user_id, $user_key, 1);
    $success = true;
  }else{
    if($maximum_clicks_by_user > $btn_clicks_by_user){
      $btn_clicks_by_user = $btn_clicks_by_user+1;
      update_user_meta($user_id, $user_key, $btn_clicks_by_user);
      $success = true;
    }else{
      $limit_reached = true;
    }
  }

  if($limit_reached)
    echo json_encode(array('status' => 'limit_reached', 'code' => $btn_code));
  elseif($success)
    echo json_encode(array('status' => 'success', 'code' => $btn_code));
  else
    echo json_encode(array('status' => 'failed', 'code' => $btn_code));
  die;
}
<?php
add_shortcode('vplayclient-inventory', 'vplayclient_inventory_shortcode');
function vplayclient_inventory_shortcode(){
	ob_start();
	$user_id = get_current_user_id();
	$user_membership = get_user_meta($user_id, 'membership_package', true);
	$user_membership = str_replace('#', '', $user_membership);
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$req_product_category = (isset($_REQUEST['product-category']))?sanitize_text_field($_REQUEST['product-category']):'';
	$unlocked_products = get_user_meta($user_id, 'unlocked_products', true);
	if(empty($unlocked_products))
		$unlocked_products = array();

	$product_categories = get_terms( array(
		'taxonomy' => 'product-category',
		'hide_empty' => false,
		'orderby' => 'name',
		'order' => 'ASC',
	) );
	$tax_query = array();
	if($req_product_category!=""){
		$tax_query = array(
			array(
			'taxonomy' => 'product-category',
			'field' => 'slug',
			'terms' => $req_product_category,       
			'operator' => 'IN'
			),
		);
	}

	$membership_products_qry = array(
		'post_type' => 'vplayclient-product',
		'posts_per_page' => -1,
		'orderby' => 'ID',
		'order' => 'DESC',
		'meta_query' => array(
		  array(
		    'key' => 'membership_options',
		    'value' => $user_membership,
		    'compare' => 'LIKE'
		  ),
		),
		'tax_query' => $tax_query,
		'fields' => 'ids',
		);
		$other_products_qry = array(
		'post_type' => 'vplayclient-product',
		'posts_per_page' => -1,
		'orderby' => 'ID',
		'order' => 'DESC',
		'post__not_in' => $unlocked_products,
		'meta_query' => array(
		  array(
		    'key' => 'membership_options',
		    'value' => $user_membership,
		    'compare' => 'NOT LIKE'
		  ),
		),
		'tax_query' => $tax_query,
		'fields' => 'ids',
	);

	$membership_products = get_posts($membership_products_qry);
	$other_products = get_posts($other_products_qry);
	if(!empty($unlocked_products))
		$merged_ids = array_merge($membership_products, $unlocked_products, $other_products);
	else
		$merged_ids = array_merge($membership_products, $other_products);

	if(empty($merged_ids))
	$merged_ids = array(0);

	$args_final = array(
	'post_type' => 'vplayclient-product',
	'post__in' => $merged_ids,
	'orderby' => 'post__in',
	'posts_per_page' => 9,
	'paged' => $paged
	);
	$products = new WP_Query($args_final);
	?>
  <?php
    if(!empty($product_categories)):  ?>
      <ul class="nav nav-pills">
        <li class="<?php echo ($req_product_category=="")?'active':''; ?>"><a href="<?php echo site_url('inventory'); ?>">All Products</a></li>
      <?php
        foreach($product_categories as $product_category):
        ?>
          <li class="<?php echo ($req_product_category==$product_category->slug)?'active':''; ?>"><a href="<?php echo site_url('inventory'); ?>/?product-category=<?php echo $product_category->slug; ?>"><?php echo $product_category->name; ?></a></li>
       <?php endforeach; ?> 
      </ul>
  <?php endif; ?>
  <?php
    if($products->have_posts()): ?>
      <div class="row list-row">
        <?php
        while($products->have_posts()):
          $products->the_post();
          $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium', true );
          $featured_image_src = ( !empty($featured_image) ) ? $featured_image[0] : VCI_URL.'public/images/product-img.png';
          $membership_options = get_post_meta(get_the_ID(), 'membership_options', true);
          if(in_array($user_membership, $membership_options) || in_array(get_the_ID(), $unlocked_products)){
            $disabled = false;
          }else{
            $disabled = true;
          }
        ?>
          <div class="column">
            <img src="<?php echo $featured_image_src; ?>" class="hover-shadow" alt="<?php the_title(); ?>">
            <div class="btn-wrap">
                <?php
                if($disabled):
                ?>
                	<div class="unlock-btn-wrapper" id="unlock-btn-wrapper-<?php the_ID(); ?>">
						<button class="fusion-login-button fusion-button button-medium unlock-btn" data-id="<?php the_ID(); ?>" id="unlock-btn-<?php the_ID(); ?>">Unlock</button>
					</div>
                <?php
                else: 
                ?>
                  <button class="fusion-login-button fusion-button button-default button-medium load-btn" data-id="<?php the_ID(); ?>">Load Product</button>
                  <button class="fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth vplayclient-inventory-review" id="vplayclient-inventory-review-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>"><?php _e('Review', VCI_DOMAIN); ?></button>
                  <?php 
                  if(get_field('video')): ?>
                  	<button type="button" class="video-btn fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth vplayclient-inventory-video" data-id="<?php the_ID(); ?>"><?php _e('Demo', VCI_DOMAIN); ?></button>
                <?php
                	endif;
                endif; ?>
                
            </div>
          </div>
        <?php
        endwhile; wp_reset_postdata(); wp_reset_query(); ?>
      </div>
  <?php
  endif; ?>
  <div class="row">
    <div class="col-sm-12">
      <?php
      $big = 999999999;
      $pages = paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $products->max_num_pages,
        'type'  => 'array',
      ) );
      
      if( is_array( $pages ) ) {
        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        echo '<ul class="pagination">';
        foreach ( $pages as $page ) {
            if($paged==$page)
              echo "<li class='active'>$page</li>";
            else
              echo "<li>$page</li>";
        }
        echo '</ul>';
      }
?>
    </div>
  </div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'vplayclient-register', 'vplayclient_render_register' );
function vplayclient_render_register(){
  ob_start();
  ?>
  <div class="fusion-login-box fusion-login-box-1 fusion-login-box-register fusion-login-align-center fusion-login-field-layout-stacked">
     <h3 class="fusion-login-heading fusion-responsive-typography-calculated" style="--fontSize:22; line-height: 1.91;" data-fontsize="22" data-lineheight="42.0167px">Register</h3>
     <!-- <div class="fusion-login-caption">Register Caption</div> -->

     <form class="fusion-login-form" style="background-color:rgba(249,249,251,0);padding:0;" name="registerform" id="vplayclient-register" method="post" action="">
      
      <div class="fusion-alert alert error alert-danger fusion-alert-center fusion-alert-capitalize alert-dismissable" style="background-color:#f2dede;color:rgba(166,66,66,1);border-color:rgba(166,66,66,1);border-width:1px; display: none;" id="register_error">
          <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
          <div class="fusion-alert-content-wrapper">
            <span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle" aria-hidden="true"></i></span>
            <span class="fusion-alert-content"></span>
          </div>
      </div>


      <input type="hidden" name="action" value="vplayclient-register" />
        <div class="fusion-login-fields">
          <div class="fusion-login-input-wrapper">
            <label class="fusion-hidden-content" for="user_login">Username</label>
            <input type="text" name="user_login" placeholder="Username" value="" size="20" class="fusion-login-username input-text" id="user_login" required="" />
          </div>
          <div class="fusion-login-input-wrapper">
            <label class="fusion-hidden-content" for="user_email">Email</label>
            <input type="text" name="user_email" placeholder="Email" value="" size="20" class="fusion-login-email input-text" id="user_email" required="" />
          </div>
          <div class="fusion-login-input-wrapper">
            <label class="fusion-hidden-content" for="user_pass">Password</label>
            <input type="password" name="user_pass" placeholder="Password" value="" class="fusion-login-password input-text" id="user_pass" required="" />
          </div>
        </div>
        <div class="fusion-login-additional-content">
          <p class="fusion-login-registration-confirm fusion-login-input-wrapper">Registration confirmation will be emailed to you.</p>
          <div class="fusion-login-submit-wrapper">
            <button class="fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth" type="submit" name="wp-submit" id="vplayclient-register-submit">Register</button>
            <?php wp_nonce_field( 'vplayclient-registration' ); ?>
          </div>
        </div>
     </form>
  </div>
  <?php
  return ob_get_clean();
}


add_shortcode('vplayclient-dashboard', 'vplayclient_dashboard_shortcode');
function vplayclient_dashboard_shortcode(){
	ob_start(); 
  $user_id = get_current_user_id();
  $membership_package = get_user_meta($user_id, 'membership_package', true);
  $start_date = get_user_meta($user_id, 'start_date', true);
  $end_date = get_user_meta($user_id, 'end_date', true);
  $coins = get_user_meta($user_id, 'coins', true);
  $membership_status = get_user_meta($user_id, 'membership_status', true);

  $membership_package = str_replace('#', '', $membership_package);

  ?>
	  <div class="main-right-wrap">
     <div class="white-card">
       <div class="card-inner">
         <h3>Current Plan</h3>
         <h2>
          <?php 
          if($membership_package=="")
            echo "Free";
          else
            echo ucfirst($membership_package); 
          ?>
          </h2>
       </div>
     </div>
     <div class="white-card">
       <div class="card-inner">
          <h3>Coins</h3>
          <h2>
          <?php 
          if($coins=="")
            echo "0";
          else
            echo $coins; 
          ?>
          </h2>
       </div>
     </div>
     <div class="white-card">
       <div class="card-inner">
          <h3>Start Date</h3>
          <?php
          if($membership_status=="Active" || $membership_package=="free")
            echo '<h2>'.date('d-M-Y', strtotime($start_date)).'</h2>';
          else
            echo '<h2>-</h2>';
          ?>
       </div>
     </div>
     <div class="white-card">
       <div class="card-inner">
         <h3>End Date</h3>
         <?php
          if($membership_status=="Active"  || $membership_package=="free")
            echo '<h2>'.date('d-M-Y', strtotime($end_date)).'</h2>';
          else
            echo '<h2>-</h2>';
          ?>
       </div>
     </div> 
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode('vplayclient-coins', 'vplayclient_coins_shortcode');
function vplayclient_coins_shortcode(){
  $vplayclient_coin_plan1_name = get_option('vplayclient_coin_plan1_name');
  $vplayclient_coin_plan1_price = get_option('vplayclient_coin_plan1_price');
  $vplayclient_coin_plan1_coins = get_option('vplayclient_coin_plan1_coins');

  $vplayclient_coin_plan2_name = get_option('vplayclient_coin_plan2_name');
  $vplayclient_coin_plan2_price = get_option('vplayclient_coin_plan2_price');
  $vplayclient_coin_plan2_coins = get_option('vplayclient_coin_plan2_coins');

  $vplayclient_coin_plan3_name = get_option('vplayclient_coin_plan3_name');
  $vplayclient_coin_plan3_price = get_option('vplayclient_coin_plan3_price');
  $vplayclient_coin_plan3_coins = get_option('vplayclient_coin_plan3_coins');

	ob_start(); 

  ?>
  <?php
  if(isset($_REQUEST['cancel-coins'])): ?>
    <div class="fusion-alert alert error alert-danger fusion-alert-center fusion-alert-capitalize alert-dismissable" style="background-color:#f2dede;color:rgba(166,66,66,1);border-color:rgba(166,66,66,1);border-width:1px; margin:0 50px 20px 50px; " id="cancel-coins-error">
        <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
        <div class="fusion-alert-content-wrapper">
          <span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle" aria-hidden="true"></i></span>
          <span class="fusion-alert-content">Please complete payment to purchase coins</span>
        </div>
    </div>
  <?php
  endif; ?>
	<div class="main-right-wrap coins">
    
     <div class="white-card">
       <div class="card-inner">
         <h3><?php echo $vplayclient_coin_plan1_coins; ?> Coins</h3>
         <h2>$<?php echo $vplayclient_coin_plan1_price; ?></h2>
         <a class="fusion-button button-default button-medium" href="javascript:;" id="vplayclient_coins_plan1">Buy Now</a>
       </div>
     </div>
     <div class="white-card">
       <div class="card-inner">
         <h3><?php echo $vplayclient_coin_plan2_coins; ?> Coins</h3>
         <h2>$<?php echo $vplayclient_coin_plan2_price; ?></h2>
         <a class="fusion-button button-default button-medium" href="javascript:;" id="vplayclient_coins_plan2">Buy Now</a>
       </div>
     </div>
     <div class="white-card">
       <div class="card-inner">
         <h3><?php echo $vplayclient_coin_plan3_coins; ?> Coins</h3>
         <h2>$<?php echo $vplayclient_coin_plan3_price; ?></h2>
         <a class="fusion-button button-default button-medium" href="javascript:;" id="vplayclient_coins_plan3">Buy Now</a>
       </div>
     </div>
    </div>
	<?php
	return ob_get_clean();
}

add_shortcode('vplayclient-change-password', 'vplayclient_change_password_shortcode');
function vplayclient_change_password_shortcode(){
	ob_start(); ?>
	<form name="changepasswordform" id="vplayclient-change-password" method="post" action="">
		<div class="fusion-login-box change-password">
			<h3 class="fusion-login-heading fusion-responsive-typography-calculated" style="--fontSize:22; line-height: 1.91;" data-fontsize="22" data-lineheight="42.0167px">Change Your Password</h3>
			
			<div class="fusion-alert alert error alert-danger fusion-alert-center fusion-alert-capitalize alert-dismissable" style="background-color:#f2dede;color:rgba(166,66,66,1);border-color:rgba(166,66,66,1);border-width:1px; display: none;" id="change_password_error">
			  <button type="button" class="close toggle-alert" data-dismiss="alert" aria-hidden="true">×</button>
			  <div class="fusion-alert-content-wrapper">
				<span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle" aria-hidden="true"></i></span>
				<span class="fusion-alert-content"></span>
			  </div>
			</div>
			<input type="hidden" name="action" value="vplayclient-change-password" />
			<div class="fusion-login-fields">
				<div class="fusion-login-input-wrapper">
					<label class="fusion-hidden-content" for="user_pass">New password</label>
					<input type="password" name="user_pass" placeholder="Enter Your New password" value="" class="fusion-login-password input-text" id="user_new_pass" required="">
				</div>
				<div class="fusion-login-input-wrapper">
					<label class="fusion-hidden-content" for="user_pass">Confirm Your Password</label>
					<input type="password" name="user_cpass" placeholder="Confirm Password" value="" class="fusion-login-password input-text" id="user_con_pass" required="">
				</div>
				<div class="fusion-login-submit-wrapper">
					<button class="fusion-login-button fusion-button button-default button-medium fusion-login-button-no-fullwidth" type="submit" name="wp-submit" id="change-psw-submit" >Submit</button>
					<?php wp_nonce_field( 'vplayclient-change-password' ); ?>
				</div>
			</div>
		</div>
	</form>
	<?php
	return ob_get_clean();
}


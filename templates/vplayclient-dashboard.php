<?php
/* 
Template Name: Dashboard
*/
if(!is_user_logged_in()):
	wp_redirect(site_url('login'));
	die;
endif;
get_header();
$dashboard_active = $inventory_active = $upgrade_active = $coins_active = $cp_active = false;
if(is_page('inventory')):
	$inventory_active = true;
endif;

if(is_page('dashboard')):
	$dashboard_active = true;
endif;

if(is_page('upgrade-plan')):
	$upgrade_active = true;
endif; 

if(is_page('purchase-coins')):
	$coins_active = true;
endif;

if(is_page('change-password')):
	$cp_active = true;
endif;

?>
<section id="content" class="full-width">
	<?php
	while(have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="v-play-list">
				<div class="list-menu-icon">
					<a href="#"></a>
				</div>
				<div class="flex-row">
					<div class="left-bar">
						<h3>Hi User</h3>
						<ul class="v-play-list-dashboard-links">
							<li class="<?php echo ($dashboard_active)?'active':''; ?>"><a href="<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
							<li class="<?php echo ($inventory_active)?'active':''; ?>"><a href="<?php echo site_url('inventory'); ?>">Inventory</a></li>
							<li class="<?php echo ($upgrade_active)?'active':''; ?>"><a href="<?php echo site_url('upgrade-plan'); ?>">Upgrade Plan</a></li>
							<li class="<?php echo ($coins_active)?'active':''; ?>"><a href="<?php echo site_url('purchase-coins'); ?>">Coins</a></li>
							<li class="<?php echo ($cp_active)?'active':''; ?>"><a href="<?php echo site_url('change-password'); ?>">Change Password</a></li>
							<li><a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
						</ul>
					</div>
					<div class="right-content">
						<div class="post-content">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php 
	endwhile;
	?>
</section>
<?php
get_footer();
?>
<?php
function vplayclient_add_plugin_menu_item() {
	add_submenu_page(
		'edit.php?post_type=vplayclient-product',
		'Settings',
		'Settings',
		'manage_options',
		'vplayclient_settings',
		'vplayclient_plugin_settings_page',
		20
	);
}
add_action("admin_menu", "vplayclient_add_plugin_menu_item");

function vplayclient_plugin_settings_page(){
	?>
	<div class="wrap">
		<h1>Settings</h1>
		<form method="post" action="options.php">
		<?php
			settings_fields("vplayclient-section");
			do_settings_sections("vplayclient-options");

			submit_button(); 
		?>
		</form>
	</div>
	<?php
}
function vplayclient_free_plan_coins_func(){
?>
	<input type="text" name="vplayclient_free_plan_coins" id="vplayclient_free_plan_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_free_plan_coins'); ?>" />
    <?php
}
function vplayclient_basic_plan_coins_func(){
?>
	<input type="text" name="vplayclient_basic_plan_coins" id="vplayclient_basic_plan_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_basic_plan_coins'); ?>" />
    <?php
}
function vplayclient_standard_plan_coins_func(){
?>
	<input type="text" name="vplayclient_standard_plan_coins" id="vplayclient_standard_plan_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_standard_plan_coins'); ?>" />
    <?php
}
function vplayclient_ultimate_plan_coins_func(){
?>
	<input type="text" name="vplayclient_ultimate_plan_coins" id="vplayclient_ultimate_plan_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_ultimate_plan_coins'); ?>" />
	<hr />
    <?php
}


function vplayclient_coin_plan1_name_func(){
?>
	<input type="text" name="vplayclient_coin_plan1_name" id="vplayclient_coin_plan1_name" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan1_name'); ?>" />
    <?php
}
function vplayclient_coin_plan1_price_func(){
?>
	<input type="text" name="vplayclient_coin_plan1_price" id="vplayclient_coin_plan1_price" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan1_price'); ?>" />
    <?php
}
function vplayclient_coin_plan1_coins_func(){
?>
	<input type="text" name="vplayclient_coin_plan1_coins" id="vplayclient_coin_plan1_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan1_coins'); ?>" />
	<hr />
    <?php
}


function vplayclient_coin_plan2_name_func(){
?>
	<input type="text" name="vplayclient_coin_plan2_name" id="vplayclient_coin_plan2_name" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan2_name'); ?>" />
    <?php
}
function vplayclient_coin_plan2_price_func(){
?>
	<input type="text" name="vplayclient_coin_plan2_price" id="vplayclient_coin_plan2_price" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan2_price'); ?>" />
    <?php
}
function vplayclient_coin_plan2_coins_func(){
?>
	<input type="text" name="vplayclient_coin_plan2_coins" id="vplayclient_coin_plan2_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan2_coins'); ?>" />
	<hr />
    <?php
}


function vplayclient_coin_plan3_name_func(){
?>
	<input type="text" name="vplayclient_coin_plan3_name" id="vplayclient_coin_plan3_name" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan3_name'); ?>" />
    <?php
}
function vplayclient_coin_plan3_price_func(){
?>
	<input type="text" name="vplayclient_coin_plan3_price" id="vplayclient_coin_plan3_price" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan3_price'); ?>" />
    <?php
}
function vplayclient_coin_plan3_coins_func(){
?>
	<input type="text" name="vplayclient_coin_plan3_coins" id="vplayclient_coin_plan3_coins" class="theme_options_input" value="<?php echo get_option('vplayclient_coin_plan3_coins'); ?>" />
	<hr />
    <?php
}

function vplayclient_panel_fields() {
	add_settings_section("vplayclient-section", "Coins Per Plan", null, "vplayclient-options");
	
	add_settings_field("vplayclient_free_plan_coins", "Free Plan Coins", "vplayclient_free_plan_coins_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_basic_plan_coins", "Basic Plan Coins", "vplayclient_basic_plan_coins_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_standard_plan_coins", "Standard Plan Coins", "vplayclient_standard_plan_coins_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_ultimate_plan_coins", "Ultimate Plan Coins", "vplayclient_ultimate_plan_coins_func", "vplayclient-options", "vplayclient-section");
	
	
	//add_settings_field("vplayclient_coin_plan1_name", "Plan 1 Name", "vplayclient_coin_plan1_name_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_coin_plan1_price", "Plan 1 Price", "vplayclient_coin_plan1_price_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_coin_plan1_coins", "Plan 1 Coins", "vplayclient_coin_plan1_coins_func", "vplayclient-options", "vplayclient-section");

	//add_settings_field("vplayclient_coin_plan2_name", "Plan 2 Name", "vplayclient_coin_plan2_name_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_coin_plan2_price", "Plan 2 Price", "vplayclient_coin_plan2_price_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_coin_plan2_coins", "Plan 2 Coins", "vplayclient_coin_plan2_coins_func", "vplayclient-options", "vplayclient-section");

	//add_settings_field("vplayclient_coin_plan3_name", "Plan 3 Name", "vplayclient_coin_plan3_name_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_coin_plan3_price", "Plan 3 Price", "vplayclient_coin_plan3_price_func", "vplayclient-options", "vplayclient-section");
	add_settings_field("vplayclient_coin_plan3_coins", "Plan 3 Coins", "vplayclient_coin_plan3_coins_func", "vplayclient-options", "vplayclient-section");

    register_setting("vplayclient-section", "vplayclient_free_plan_coins");
    register_setting("vplayclient-section", "vplayclient_basic_plan_coins");
    register_setting("vplayclient-section", "vplayclient_standard_plan_coins");
    register_setting("vplayclient-section", "vplayclient_ultimate_plan_coins");
    
    //register_setting("vplayclient-section", "vplayclient_coin_plan1_name");
    register_setting("vplayclient-section", "vplayclient_coin_plan1_price");
    register_setting("vplayclient-section", "vplayclient_coin_plan1_coins");

    //register_setting("vplayclient-section", "vplayclient_coin_plan2_name");
    register_setting("vplayclient-section", "vplayclient_coin_plan2_price");
    register_setting("vplayclient-section", "vplayclient_coin_plan2_coins");

    //register_setting("vplayclient-section", "vplayclient_coin_plan3_name");
    register_setting("vplayclient-section", "vplayclient_coin_plan3_price");
    register_setting("vplayclient-section", "vplayclient_coin_plan3_coins");
}
add_action("admin_init", "vplayclient_panel_fields");
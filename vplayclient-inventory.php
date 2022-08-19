<?php
/*
Plugin Name: VPlayClient Inventory
Description: This plugin is used to handle inventory
Version:           1.0
Text Domain:       vplayclient-inventory
Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'VPLAYCLIENT_INVENTORY_VERSION', '1.0' );
define( 'VCI_DOMAIN', 'vplayclient-inventory' );
define( 'VCI_URL', plugin_dir_url( __FILE__ ));

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(is_admin()){
	include plugin_dir_path( __FILE__ ) . 'includes/admin/vplayclient-inventory-transactions-list.php';
	include plugin_dir_path( __FILE__ ) . 'includes/admin/vplayclient-inventory-settings.php';
	include plugin_dir_path( __FILE__ ) . 'includes/admin/vplayclient-inventory-reviews-list.php';
}


require plugin_dir_path( __FILE__ ) . 'includes/vplayclient-inventory-enqueue-scripts.php';
require plugin_dir_path( __FILE__ ) . 'includes/vplayclient-inventory-CPT.php';
require plugin_dir_path( __FILE__ ) . 'includes/vplayclient-inventory-functions.php';
require plugin_dir_path( __FILE__ ) . 'includes/vplayclient-inventory-shortcodes.php';
require plugin_dir_path( __FILE__ ) . 'includes/vplayclient-inventory-ajax-requests.php';



function activate_vplayclient_inventory() {
	// Trigger our function that registers the custom post type plugin.
	vplayclient_inventory_setup_post_type(); 
	// Clear the permalinks after the post type has been registered.
	flush_rewrite_rules(); 
}

function deactivate_vplayclient_inventory(){
	// Unregister the post type, so the rules are no longer in memory.
	unregister_post_type( 'book' );
	// Clear the permalinks to remove our post type's rules from the database.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'activate_vplayclient_inventory' );
register_deactivation_hook( __FILE__, 'deactivate_vplayclient_inventory' );

add_action('after_setup_theme', 'vplayclient_remove_admin_bar');
function vplayclient_remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
function vplayclient_add_page_template ($templates) {
	$templates['vplayclient-process-payment.php'] = 'Process Payment';
	$templates['vplayclient-process-coins-payment'] = 'Process Coin Payment';
	$templates['vplayclient-dashboard.php'] = 'Dashboard';
	return $templates;
}
add_filter ('theme_page_templates', 'vplayclient_add_page_template');
function vplayclient_redirect_page_template ($template) {
    if( is_page_template('vplayclient-process-payment.php') ){
        $template = WP_PLUGIN_DIR . '/vplayclient-inventory/templates/vplayclient-process-payment.php';
    }

    if( is_page_template('vplayclient-dashboard.php') ){
        $template = WP_PLUGIN_DIR . '/vplayclient-inventory/templates/vplayclient-dashboard.php';
    }

    if( is_page_template('vplayclient-process-coins-payment.php') ){
        $template = WP_PLUGIN_DIR . '/vplayclient-inventory/templates/vplayclient-process-coins-payment.php';
    }

    // Return
    return $template;
}
add_filter ('page_template', 'vplayclient_redirect_page_template');

function vplayclient_load_modals(){
	if(is_page('inventory')):
		require plugin_dir_path( __FILE__ ) . 'includes/vplayclient-inventory-modals.php';
	endif;
}
add_action( 'wp_footer', 'vplayclient_load_modals' );
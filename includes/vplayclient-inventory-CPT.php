<?php
function vplayclient_inventory_setup_post_type() {
    $labels = array(
        'name'                  => _x( 'Products', 'Post type general name', VCI_DOMAIN ),
        'singular_name'         => _x( 'Product', 'Post type singular name', VCI_DOMAIN ),
        'menu_name'             => _x( 'Products', 'Admin Menu text', VCI_DOMAIN ),
        'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', VCI_DOMAIN ),
        'add_new'               => __( 'Add New', VCI_DOMAIN ),
        'add_new_item'          => __( 'Add New Product', VCI_DOMAIN ),
        'new_item'              => __( 'New Product', VCI_DOMAIN ),
        'edit_item'             => __( 'Edit Product', VCI_DOMAIN ),
        'view_item'             => __( 'View Product', VCI_DOMAIN ),
        'all_items'             => __( 'All Products', VCI_DOMAIN ),
        'search_items'          => __( 'Search Products', VCI_DOMAIN ),
        'parent_item_colon'     => __( 'Parent Products:', VCI_DOMAIN ),
        'not_found'             => __( 'No Products found.', VCI_DOMAIN ),
        'not_found_in_trash'    => __( 'No Products found in Trash.', VCI_DOMAIN ),
        'featured_image'        => _x( 'Product Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', VCI_DOMAIN ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', VCI_DOMAIN ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', VCI_DOMAIN ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', VCI_DOMAIN ),
        'archives'              => _x( 'Product archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', VCI_DOMAIN ),
        'insert_into_item'      => _x( 'Insert into Product', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', VCI_DOMAIN ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Product', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', VCI_DOMAIN ),
        'filter_items_list'     => _x( 'Filter Product list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', VCI_DOMAIN ),
        'items_list_navigation' => _x( 'Product list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', VCI_DOMAIN ),
        'items_list'            => _x( 'Product list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', VCI_DOMAIN ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'vplayclient-product' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 26,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt'),
    );
    register_post_type( 'vplayclient-product', $args );

	$labels = array(
		'name'              => _x( 'Product Categories', 'taxonomy general name', VCI_DOMAIN ),
		'singular_name'     => _x( 'Product Category', 'taxonomy singular name', VCI_DOMAIN ),
		'search_items'      => __( 'Search Product Categories', VCI_DOMAIN ),
		'all_items'         => __( 'All Product Categories', VCI_DOMAIN ),
		'parent_item'       => __( 'Parent Product Category', VCI_DOMAIN ),
		'parent_item_colon' => __( 'Parent Product Category:', VCI_DOMAIN ),
		'edit_item'         => __( 'Edit Product Category', VCI_DOMAIN ),
		'update_item'       => __( 'Update Product Category', VCI_DOMAIN ),
		'add_new_item'      => __( 'Add New Product Category', VCI_DOMAIN ),
		'new_item_name'     => __( 'New Product Category Name', VCI_DOMAIN ),
		'menu_name'         => __( 'Product Category', VCI_DOMAIN ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'product-category' ),
	);
	register_taxonomy( 'product-category', array( 'vplayclient-product' ), $args );
}
 
add_action( 'init', 'vplayclient_inventory_setup_post_type' );
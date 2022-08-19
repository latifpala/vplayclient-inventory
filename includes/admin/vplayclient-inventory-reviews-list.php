<?php
class Reviews_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Review', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Reviews', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	protected function get_views() { 
		$all_records = self::record_count_by_status();
		$approved_records = self::record_count_by_status(1);
		$pending_records = self::record_count(true);
		$declined_records = self::record_count_by_status(2);
		$page_url = admin_url( 'edit.php?post_type=vplayclient-product&page=vplayclient_reviews', 'https' ); 
		$all_active = $approved_active = $declined_active = $pending_active = "";
		if(isset($_REQUEST['status'])){
			if($_REQUEST['status']==1)
				$approved_active = "current";
			elseif($_REQUEST['status']==2)
				$declined_active = "current";
			elseif($_REQUEST['status']==0)
				$pending_active = "current";
		}else{
			$all_active = "current";
		}
		$status_links = array(
			"all"       => __("<a href='{$page_url}' class='{$all_active}'>All ({$all_records})</a>",'my-plugin-slug'),
			"approved" => __("<a href='{$page_url}&status=1' class='{$approved_active}'>Approved ({$approved_records})</a>",'my-plugin-slug'),
			"declined"   => __("<a href='{$page_url}&status=2' class='{$declined_active}'>Declined ({$declined_records})</a>",'my-plugin-slug'),
			"pending" => __("<a href='{$page_url}&status=0' class='{$pending_active}'>Pending ({$pending_records})</a>",'my-plugin-slug'),
		);
		return $status_links;
	}

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_reviews( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}vplayclient_reviews";

		if(isset($_REQUEST['status'])){
			$sql .= ' WHERE status='.esc_sql( $_REQUEST['status'] );
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}


		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_review( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}vplayclient_reviews",
			[ 'review_id' => $id ],
			[ '%d' ]
		);
	}

	public static function approve_review( $id ){
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}vplayclient_reviews",
			[ 'status' => 1 ],
			[ 'review_id' => $id ],
			[ '%d' ]
		);
	}

	public static function reject_review( $id ){
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}vplayclient_reviews",
			[ 'status' => 2 ],
			[ 'review_id' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count($is_pending = false) {
		global $wpdb;

		if($is_pending)
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}vplayclient_reviews WHERE status='0'";
		else
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}vplayclient_reviews";

		return $wpdb->get_var( $sql );
	}

	public static function record_count_by_status($status = "") {
		global $wpdb;

		if($status=="")
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}vplayclient_reviews";
		else
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}vplayclient_reviews WHERE status='{$status}'";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No reviews avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'review_id':
			case 'name':
			case 'product_name':
				return get_the_title($item['product_id']);
			case 'rating':
				return $item[ $column_name ];
			case 'review_text':
				return $item[ $column_name ];
			case 'status':
				if($item['status']==0)
					return "Pending";
				elseif($item['status']==1)
					return "Approve";
				elseif($item['status']==2)
					return "Declined";
			case 'date':
				return date('d M,Y', strtotime($item[$column_name]));
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['review_id']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'vplayclient_delete_review' );
		$approve_nonce = wp_create_nonce( 'vplayclient_approve_review' );
		$reject_nonce = wp_create_nonce( 'vplayclient_reject_review' );

		$user = get_user_by('ID', $item['user_id']);
		if(!empty($user)){
			$title = '<strong>' . $user->user_login . '</strong>';
		}else{
			$title = '<strong>' . $item['user_id'] . '</strong>';
		}

		$status = "";
		if(isset($_REQUEST['status'])){
			$status = esc_attr($_REQUEST['status']);
		}
		if($status == "" || $status == 0){
			$actions = [
				'approve_review' => sprintf( '<a href="?post_type=vplayclient-product&page=%s&action=%s&review_id=%s&_wpnonce=%s">Approve</a>', esc_attr( $_REQUEST['page'] ), 'approve', absint( $item['review_id'] ), $approve_nonce ), 
				'reject' => sprintf( '<a href="?post_type=vplayclient-product&page=%s&action=%s&review_id=%s&_wpnonce=%s">Reject</a>', esc_attr( $_REQUEST['page'] ), 'reject', absint( $item['review_id'] ), $reject_nonce ), 
				'delete' => sprintf( '<a href="?post_type=vplayclient-product&page=%s&action=%s&review_id=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['review_id'] ), $delete_nonce )
			];
		}else{
			$actions = [
				'delete' => sprintf( '<a href="?post_type=vplayclient-product&page=%s&action=%s&review_id=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['review_id'] ), $delete_nonce )
			];
		}

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'name'    => __( 'User Name', 'sp' ),
			'product_name' => __( 'Product Name', 'sp' ),
			'rating' => __( 'Rating', 'sp' ),
			'review_text' => __( 'Review', 'sp' ),
			'status' => __( 'Status', 'sp' ),
			'date' => __( 'Date', 'sp' ),
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array( 'name', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete',
			'bulk-approve' => 'Approve',
			'bulk-reject' => 'Reject',
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'reviews_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_reviews( $per_page, $current_page );
	}

	

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'vplayclient_delete_review' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_review( absint( $_GET['review_id'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                /*wp_redirect( esc_url_raw(add_query_arg()) );
				exit;*/
			}

		}

		//Detect when a bulk action is being triggered...
		if ( 'approve' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'vplayclient_approve_review' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::approve_review( absint( $_GET['review_id'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                /*wp_redirect( esc_url_raw(add_query_arg()) );
				exit;*/
			}

		}

		//Detect when a bulk action is being triggered...
		if ( 'reject' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'vplayclient_reject_review' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::reject_review( absint( $_GET['review_id'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                /*wp_redirect( esc_url_raw(add_query_arg()) );
				exit;*/
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_review( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        /*wp_redirect( esc_url_raw(add_query_arg()) );
			exit;*/
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-approve' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-approve' )
		) {

			$approve_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $approve_ids as $id ) {
				self::approve_review( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        /*wp_redirect( esc_url_raw(add_query_arg()) );
			exit;*/
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-reject' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-reject' )
		) {

			$reject_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $reject_ids as $id ) {
				self::reject_review( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        /*wp_redirect( esc_url_raw(add_query_arg()) );
			exit;*/
		}
	}

}
class Review_Call {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $review_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {
		$pending_review_count = Reviews_List::record_count(true);

	 	$pending_review_title = $pending_review_count ? sprintf('Reviews <span class="awaiting-mod">%d</span>', $pending_review_count) : 'Reviews';

		$hook = add_submenu_page(
			'edit.php?post_type=vplayclient-product',
			'Reviews',
			$pending_review_title,
			'manage_options',
			'vplayclient_reviews',
			[ $this, 'plugin_settings_page' ]
		);
		add_action( "load-$hook", [ $this, 'screen_option' ] );
	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2>Reviews</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php $this->review_obj->views(); ?>
							<form method="post">
								<?php
								$this->review_obj->prepare_items();
								$this->review_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Reviews',
			'default' => 10,
			'option'  => 'reviews_per_page'
		];

		add_screen_option( $option, $args );

		$this->review_obj = new Reviews_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	Review_Call::get_instance();
} );

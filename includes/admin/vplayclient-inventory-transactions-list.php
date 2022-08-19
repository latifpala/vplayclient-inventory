<?php
class Transaction_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Transaction', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Transactions', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_transactions( $per_page = 10, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}subscription_payments WHERE display_status='publish'";

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
	public static function delete_transaction( $id ) {
		global $wpdb;

		$wpdb->update(
			"{$wpdb->prefix}subscription_payments",
			[ 'display_status' => 'trash' ],
			[ 'payment_id' => $id ],
			[ '%s' ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}subscription_payments WHERE display_status='publish'";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No transactions avaliable.', 'sp' );
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
			case 'transaction_id':
			case 'amount':
				return '<a href="#">'.$item[ $column_name ].'</a>';
			case 'item_number':
				$item_name = str_replace('#', '', $item['item_number']);
				return ucwords($item_name);
			case 'start_date':
				return date("d-m-Y", strtotime($item[ $column_name ]));
			case 'end_date':
				return date("d-m-Y", strtotime($item[ $column_name ]));
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
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['payment_id']
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

		$delete_nonce = wp_create_nonce( 'vplayclient_delete_transaction' );
		$user = get_user_by('ID', $item['user_id']);
		if(!empty($user)){
			$title = '<strong><a href="#">' . $user->user_login . '</a></strong>';
		}else{
			$title = '<strong><a href="#">' . $item['user_id'] . '</a></strong>';
		}

		$actions = [
			'delete' => sprintf( '<a href="?post_type=vplayclient-product&page=%s&action=%s&payment_id=%s&_wpnonce=%s" class="delete_transaction">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['payment_id'] ), $delete_nonce )
		];

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
			'transaction_id' => __( 'Transaction ID', 'sp' ),
			'item_number' => __( 'Plan Name', 'sp' ),
			'amount' => __( 'Amount', 'sp' ),
			'start_date' => __( 'Start Date', 'sp' ),
			'end_date' => __( 'End Date', 'sp' ),
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
			'bulk-delete' => 'Delete'
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

		$per_page     = $this->get_items_per_page( 'transactions_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_transactions( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'vplayclient_delete_transaction' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_transaction( absint( $_GET['payment_id'] ) );

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
				self::delete_transaction( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        /*wp_redirect( esc_url_raw(add_query_arg()) );
			exit;*/
		}
	}

}
class Transactions_Call {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $transactions_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_submenu_page(
			'edit.php?post_type=vplayclient-product',
			'Transactions',
			'Transactions',
			'manage_options',
			'vplayclient_transactions',
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
			<h2>Transactions</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->transactions_obj->prepare_items();
								$this->transactions_obj->display(); ?>
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
			'label'   => 'Transactions',
			'default' => 10,
			'option'  => 'transactions_per_page'
		];

		add_screen_option( $option, $args );

		$this->transactions_obj = new Transaction_List();
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
	Transactions_Call::get_instance();
} );

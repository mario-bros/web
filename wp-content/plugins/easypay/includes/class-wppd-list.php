<?php 
//Checking the WP_List_Table class, so we need to make sure that it's there
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/template.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Payment_Log_Table extends WP_List_Table {

	/**
	 * Constructor, we override the parent to pass our own arguments
	 * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	 */
	function __construct() {
		parent::__construct( array(
				'singular'	=> 'wp_payment_log_link', 	//Singular label
				'plural' 	=> 'wp_payment_log_links', 	//plural label, also this well be one of the table css class
				'ajax'		=> false, 					//We won't support Ajax for this table
				'screen' 	=> 'paymentlog-list'        //hook suffix
		) );
	}
	
	
	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			//The code that goes before the table is here
			
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
			
		}
	}
	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
            
        //currency select by admin option

         return $columns= array(
				'email'=>__('Email'),
				'txnid'=>__('Transaction ID'),
				'payment_amount'=>__('Amount '),
				'payment_status'=>__('Payment Status'),
				'createdtime'=>__('Time'),
				'view'	=> __('View Details')
				
		);
	}
	
	
	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		return $sortable = array(
				'payment_amount'=>'payment_amount',
				'payment_status'=>'payment_status',
				'createdtime'=>'createdtime'
		);
	}
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();
	
		/* -- Preparing your query -- */
		$query = "SELECT * FROM ". $wpdb->prefix . "easypay_payment_log";
	
		
		
			
		$orderby 	= 	!empty( $_GET["orderby"] ) 	? ( $_GET["orderby"] ) 	: 'createdtime';
		$order 		= 	!empty( $_GET["order"] ) 	? ( $_GET["order"] ) 	: 'DESC';
		
		if( !empty( $orderby ) & !empty( $order ) ){
			$query.=' ORDER BY '.$orderby.' '.$order;
		}
	
		/* -- Pagination parameters -- */
		//Number of elements in your table?
		
		
		$totalitems = $wpdb->get_var( "Select COUNT(*) from {$wpdb->prefix}easypay_payment_log" ); //return the total number of affected rows
		//How many to display per page?
		$perpage = 20;
		//Which page is this?
		
		$paged = !empty( $_GET["paged"] ) ? ( $_GET["paged"] ) : '';
		//Page Number
		if(empty($paged) || !is_numeric($paged) || $paged<=0 ){
			$paged=1;
		}
		//How many pages do we have in total?
		$totalpages = ceil( $totalitems / $perpage );
		
		//adjust the query to take pagination into account
		if( !empty( $paged ) && !empty( $perpage ) ){
			$offset = ( $paged-1 )* $perpage;
			$query .=' LIMIT '.(int)$offset.','.(int)$perpage;
		}
	
		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
				"total_items" => $totalitems,
				"total_pages" => $totalpages,
				"per_page" => $perpage,
		) );
		
		//The pagination links are automatically built according to those parameters
		/* -- Register the Columns -- */
		
		$columns = $this->get_columns();
		
	
		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results($query);
	}
	
	
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows() {
                global $wppd_email;
		//Get the records registered in the prepare_items method
		$records = $this->items;

	
		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();
		
		$list_record = ''; 
		//Loop for each record
		if(!empty($records)){
			foreach($records as $rec){

			$custom 		= 	stripslashes( $rec->custom );
			$custom_data_args 	= 	unserialize ( $custom );
                                
         //if currency code
         $currency_code = ( isset($custom_data_args['currency_code']) ) ? $custom_data_args['currency_code'] : 'USD';
        
	
				//Open the line
				$list_record .= '<tr id="record_'.$rec->id.'" class="wppd-view-more">';
				foreach ( $columns as $column_name => $column_display_name ) {
	
					//Style attributes for each col
					$class = "class='$column_name column-$column_name'";
					$style = "";
					if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
					$attributes = $class . $style;
	
					//edit link
					$editlink  = '/wp-admin/link.php?action=edit&id='.(int)$rec->id;
	
                                        //(TransactionID=="") NA
                                        $rec_txnid=($rec->txnid !== '' )? $rec->txnid: 'N/A';
                                        
					//Display the cell
					switch ( $column_name ) {
						case "email": $list_record .= '<td '.$attributes.'>'.$rec->email.'</td>'; break;
						case "txnid":	$list_record .= '<td '.$attributes.'>'.stripslashes($rec_txnid).'</td>';	break;
						case "payment_amount": $list_record .= '<td '.$attributes.'>'.stripslashes($rec->payment_amount).' '.$currency_code.'</td>'; break;
						case "payment_status": $list_record .= '<td '.$attributes.'>'.stripslashes($rec->payment_status).'</td>'; break;
						case "createdtime": $list_record .= '<td '.$attributes.'>'.date( 'd-m-Y  h:i:s', strtotime($rec->createdtime)).'</td>'; break;
						case "view": $list_record .= '<td '.$attributes.'><a class="wppd-viewmore" id="view-'.$rec->id.'" href="javascript:;">view more</a></td>'; break;
					}
				}
	
				//Close the line
				$list_record .= '</tr>';
				$list_record .= '<tr class="wp-list-table-row view-'.$rec->id.'  wppd-add-info"><td colspan="6">';
				$list_record .='<table class="wp-list-table-sub-list" cellspacing="0" width="100%" border="0">';
				
				$custom 		= 	stripslashes( $rec->custom );
				$custom_data_args 	= 	unserialize ( $custom );
                                
                                $row_data_args = array(
                                    'Email'             => '<a href="mailto:'.$rec->email.'"> '.$rec->email.' </a>',
                                    'Transaction ID'    => stripslashes($rec_txnid),
                                    'Amount '   => stripslashes($rec->payment_amount).' '.$currency_code,
                                    'Payment Status'    => stripslashes($rec->payment_status),
                                    'Time'              => date( 'd-m-Y  h:i:s', strtotime($rec->createdtime))
                                            
                                );
                                
                                $custom_data_array = $row_data_args + $custom_data_args;
                                
                                
				$list_record .= '<th colspan="2"><b>'.__('Transaction Detail','easypay').'</b> ('.$rec->email.') <small class="wppd-close"></small></th>';
				
				foreach ($custom_data_array as $data_key => $data_val ){
                                    
                                        $data_val = is_array($data_val)?implode(', ', $data_val):$data_val;
                                    
					if( $data_key == 'currency_code' ) continue;
					 
					//$data_key = str_replace( "-", " ", $data_key );
					//$data_key = ucwords( $data_key );
					$data_label = $wppd_email->wppd_field_label($data_key);
                                        $data_key = $data_label?$data_label:ucwords(preg_replace( '/[_-]/', " ", $data_key ));
					$list_record .= '<tr>';
					$list_record .= '<td border="0">' . $data_key . '</td>';
					$list_record .= '<td border="0">' . stripslashes( $data_val ) . '</td>';
					$list_record .= '</tr>';
					
				}
				
				
				$list_record .='</table>';
				$list_record .= '</td></tr>';
			}
		}
		echo $list_record;
	}


}

global $pay_log_table;

$pay_log_table = new Payment_Log_Table();
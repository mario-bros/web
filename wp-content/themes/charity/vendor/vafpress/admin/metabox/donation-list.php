<?php
/**
 * Charity donation list
 * @package charity
 * @version     v.1.0
 */

if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/template.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CharityDonationList extends WP_List_Table {


    function __construct() {
        global $status, $page;

        parent::__construct(array(
            'singular' => __('causes-donation', 'charity'), 
            'plural' => __('causes-donations', 'charity'), 
            'ajax' => false,        
            'screen' 	=> 'causes-donation-payment' 
        ));

        //add_action('admin_head', array(&$this, 'admin_header'));
    }

    function admin_header() {
        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;
        if ('my_list_test' != $page)
            return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 5%; }';
        echo '.wp-list-table .column-booktitle { width: 40%; }';
        echo '.wp-list-table .column-author { width: 35%; }';
        echo '.wp-list-table .column-isbn { width: 20%;}';
        echo '</style>';
    }

    function no_items() {
        _e('No donation found, dude.', "charity");
    }
    

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'email':
            case 'txnid':
            case 'payment_amount':
            case 'payment_status':
            case 'createdtime':
            case 'view':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function get_sortable_columns() {
		return $sortable = array(
				'payment_amount'=>'payment_amount',
				'payment_status'=>'payment_status',
				'createdtime'=>'createdtime'
		);
    }

    function get_columns() {
         return $columns= array(
				'email'=>__('Email', "charity"),
				'txnid'=>__('Transaction ID', "charity"),
				'payment_amount'=>__('Amount ', "charity"),
				'payment_status'=>__('Payment Status', "charity"),
				'createdtime'=>__('Time', "charity"),
				'view'	=> __('View Details', "charity")
				
		);
    }

    function usort_reorder($a, $b) {
        // If no sort, default to title
        $orderby = (!empty($_GET['orderby']) ) ? $_GET['orderby'] : 'email';
        // If no order, default to asc
        $order = (!empty($_GET['order']) ) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }


    function prepare_items() {
		global $wpdb;
		//$screen = get_current_screen();
		
		/* -- Preparing your query -- */
		$query = "SELECT * FROM ". $wpdb->prefix . "easypay_payment_log";
		$where = " where itemname = '%s' && payment_status = 'Completed'" ;
		
		
			
		$orderby 	= 	!empty( $_GET["orderby"] ) 	? ( $_GET["orderby"] ) 	: 'createdtime';
		$order 		= 	!empty( $_GET["order"] ) 	? ( $_GET["order"] ) 	: 'DESC';
		
		if( !empty( $orderby ) & !empty( $order ) ){
			$query.=$where.' ORDER BY '.$orderby.' '.$order;
		}
		//The pagination links are automatically built according to those parameters
		/* -- Register the Columns -- */
		
		$columns = $this->get_columns();
		
	
		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results($wpdb->prepare($query, $_REQUEST['post']));
		
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
    public function display() {
		$singular = $this->_args['singular'];

		//$this->display_tablenav( 'top' );

?>
<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
	<thead>
	<tr>
		<?php $this->print_column_headers(); ?>
	</tr>
	</thead>

	<tfoot>
	<tr>
		<?php //$this->print_column_headers( false ); ?>
	</tr>
	</tfoot>

	<tbody id="the-list"<?php
		if ( $singular ) {
			echo " data-wp-lists='list:$singular'";
		} ?>>
		<?php $this->display_rows_or_placeholder(); ?>
	</tbody>
</table>
<?php
		//$this->display_tablenav( 'bottom' );
	}


}

//class


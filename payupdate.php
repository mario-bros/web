<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . 'wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . 'wp-includes/wp-db.php' );


$db_username="c1peduliuser"; //database user name
$db_password="s_3c5RqDX";//database password
$db_database="c1pedulidb"; //database name
$db_host="localhost";

mysql_connect($db_host,$db_username,$db_password);
@mysql_select_db($db_database) or die ('Error: '.mysql_error ());



	
	
	
 $qrytwee = "SELECT post_id, meta_key, meta_value FROM `wp_postmeta` WHERE post_id not in (select post_id from wp_postmeta where meta_key = '_trans_date') and meta_key = '_paid_date' ";
$resultblock=mysql_query($qrytwee);
	while($rowtwee=mysql_fetch_array($resultblock)){
		//print_r($rowtwee);
		
		$meta_value = $rowtwee['meta_value'];
		$post_id = $rowtwee['post_id'];
		//echo "TEST" . $userid . " - " . $wc_orderid . "<br/>";
		
		  
				
				// update status and last update date.
				update_post_meta( $post_id, '_trans_date', $meta_value );
					
	}
	
 $qrytwee = "SELECT pp.post_date, pp.ID, pm.post_id, pm.meta_key, pm.meta_value FROM wp_posts as pp, wp_postmeta as pm WHERE pm.post_id not in (select post_id from wp_postmeta where meta_key = '_trans_date') and pm.post_id not in (select post_id from wp_postmeta where meta_key = '_paid_date') and pm.meta_key = '_is_donation' and pm.post_id = pp.ID ";
$resultblock=mysql_query($qrytwee);
	while($rowtwee=mysql_fetch_array($resultblock)){
		//print_r($rowtwee);
		
		$meta_value = $rowtwee['post_date'];
		$post_id = $rowtwee['post_id'];
		//echo "TEST" . $userid . " - " . $wc_orderid . "<br/>";
		
		  
				
				// update status and last update date.
				update_post_meta( $post_id, '_trans_date', $meta_value ); 
					
	}	
	
	
	
	

?>
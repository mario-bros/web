
	<div class="um-form">
		<div class="containersponsor">
		<div class="leftsponsor">
		
		<div class="sponsordetails">
			<div class="sponserheadinfo"><strong>My Transactions</strong></div>
			
			
<?php 
  global $wpdb;
  $count = 0;
  $userid = get_current_user_id();
  //echo $userid;
  
  // find list of states in DB
  $qry = "SELECT ID, post_status FROM wp_posts where post_type = 'shop_order' and ID in (select post_id from wp_postmeta where meta_key = '_customer_user' and meta_value = '$userid')  ORDER BY ID desc";
  $states = $wpdb->get_results( $qry );
  foreach( $states as $row ) {
    $count++;
		
  }
  
  if ($count > 0) {
?>

<table class="transactionstable">
<tr class="head"><td>Date</td><td>Donation Type</td><td>Amount</td><td>Status</td></tr>
<?php	  
foreach( $states as $row ) {
$OrderId = $row->ID;
$status = str_replace("wc-","",$row->post_status);

if ($status == "completed") {
	$status = "Succes";
	$class = "complete";
}
if ($status == "failed") {
	$status = "Failed";
	$class = "failed";	
}
if ($status == "processing") {
	$status = "Pending";
	$class = "pending";	
}
if ($status == "cancelled") {
	$status = "Cancelled";
	$class = "failed";	
}

$_order_total = get_post_meta( $OrderId, '_order_total', true );
$_order_currency = get_post_meta( $OrderId, '_order_currency', true );

$_payment_method_title = get_post_meta( $OrderId, '_payment_method_title', true );
$_paid_date = get_post_meta( $OrderId, '_paid_date', true );
$_paid_date = explode(" ", $_paid_date);
$_paid_date = $_paid_date[0];

$_paid_date = new DateTime($_paid_date);
$_paid_date = $_paid_date->format('M d, Y');


$_recurring_profile = get_post_meta( $OrderId, '_recurring_profile', true );
if (empty($_recurring_profile)) {
	$_recurring_profile = "N";
} else {
	$_recurring_profile = "Y (" . $_recurring_profile .")";	
}
$_transaction_id = get_post_meta( $OrderId, '_transaction_id', true );


  $qrytwee = "SELECT meta_value FROM wp_woocommerce_order_itemmeta where meta_key = 'order_item_name' and order_item_id = (select order_item_id from wp_woocommerce_order_items where order_id = '$OrderId')";
  //echo $qrytwee;
  $statestwee = $wpdb->get_results( $qrytwee );
	foreach( $statestwee as $rowtwee ) {
		$donationfor = "Donation for " . $rowtwee->meta_value;
	}
	


?>
<tr><td><?php echo $_paid_date; ?></td><td class="donation"><?php echo $donationfor; ?></td><td class="amount">&euro;<?php echo $_order_total; ?></td><td class="<?php echo $class; ?>"><?php echo $status; ?></td></tr>
<?php
}
?>
</table>

<?php

  } else { 
  echo '<p class="nosponsor">There are no transactions recorded yet.</p>';
  }
  
?>			
			
			
		</div>		
		
		</div>
		
			
		
		<div class="rightsponsorcontainer">
		<div class="rightsponsor">
		<div class="sponsoredkids">
		<p class="centersponsor"><strong>Sponsor a Child</strong></p>
		<div class="childsponsoredcontainer"> 
    <div id="carousel_sliderkids" class="owl-carousel sponsorachildcaroussel">

    


<?php
$args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 48);
query_posts ( $args );
if (have_posts ()) :
?>
<?php while ( have_posts () ) : the_post ();?>
							<?php $dob = get_post_meta( get_the_ID(), '_give_date_of_birth', true ); ?>
							<?php $gender = get_post_meta( get_the_ID(), '_give_gender', true ); ?>
							<?php $intro = get_post_meta( get_the_ID(), '_give_intro', true ); ?>
							<?php $name = get_post_meta( get_the_ID(), '_give_childname', true ); ?>
							<?php $liv_sponsor = get_post_meta( get_the_ID(), '_give_liv_sponsor', true ); ?>
							<?php $edu_sponsor = get_post_meta( get_the_ID(), '_give_edu_sponsor', true ); ?>	
							
							<?php
			$livstatus = "active";
			if ($liv_sponsor == "") {
				$livstatus = "inactive";
			}
			$edustatus = "active";
			if ($edu_sponsor == "") {
				$edustatus = "inactive";
			}							
							
							 if ($livstatus == "active" && $edustatus == "active") { continue; } ?>
							
																
						
						<?php 
						$intro = (strlen($intro) > 70) ? substr($intro, 0, 70) . '...' : $intro;
						?> 
						
<div class="carousel_img sponsoredimgkids">
        <a target="_blank" href="<?php the_permalink();?>">
            <?php echo get_the_post_thumbnail( get_the_ID(), 'medium'); ?></a>
			<p class="name"><strong><?php echo $name; ?></strong></p>
			<p class="intro"><?php echo $intro; ?></p>
			<p class="button">
			
			<input type="submit" class="btn btn-donatie breed" id="give-purchase-button" onclick="location.href='<?php the_permalink();?>';" name="give-purchase" value="Sponsor now" />						
			</p>			
			
</div>						
						
	
						 
						

					
		<?php
		endwhile;
		?>
		</div>
<?php 
endif;
wp_reset_query();
?>		
    
 

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#carousel_sliderkids").owlCarousel({
                 // Most important owl features
                items : 1,
                itemsDesktop : [1200,1],
                itemsDesktopSmall : [980,1],
                itemsTablet: [768,1],
                itemsMobile : [479,1],
                singleItem : true,
                 
                //Basic Speeds
                slideSpeed : 0,
                paginationSpeed : 800,
                rewindSpeed : 1000,
                 
                //Autoplay
                autoPlay : false,
                stopOnHover : true,
                 
                // Navigation
                navigation : true,
                navigationText : ["&#10094;","&#10095;"],
                rewindNav : true,
                scrollPerPage : false,
                 
                //Pagination
                pagination : false,
                paginationNumbers: false,
                 
                //Auto height
                autoHeight : false,
                                 
            });
			$(".owl-pagination").css("left", function(){
			    return ($(".owl-carousel.owl-theme").width() - $(this).width()) / 2;
			});
        })
    </script>	
	</div>
		
	
		</div>
		
		</div>
		
		<div class="rightsponsortwee">
		<div class="sponsoredkids">
		<p class="centersponsor"><strong>Latest News</strong></p>
		<ul>
		
		<?php $the_query = new WP_Query( 'posts_per_page=1' ); ?>
		
		
		<?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
		
		
		<li><a href="<?php the_permalink() ?>"><strong><?php the_title(); ?></strong></a></li>
		<p><?php echo get_the_date( get_option('date_format') ); ?></p>
		 <?php $text = the_excerpt(__('(more…)')); $text = strip_tags($text);
                     if (strlen($text) > 70) :
                          $text = substr($text, 0, 70).'...';
                     endif;
                     echo $text;
					 ?>
		
		<?php ; ?>
		
		
		<?php 
		endwhile;
		wp_reset_postdata();
		?>
		</ul>
		
		
		</div>
		<div class="give-submit-button-wrap give-clearfix buttonmiddle">
		<input type="submit" class="btn btn-donatie" id="give-purchase-button" onclick="location.href='/blog';" name="give-purchase" value="VIEW ALL" />
		</div>
		</div>		
		
		
		</div>
		</div>



	

	
	</div>
	

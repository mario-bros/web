
	<div class="um-form">
		<div class="containersponsor">
		<div class="leftsponsor">
		
		
			<div class="sponsorimage">
			
				<a href="<?php echo um_get_core_page('user'); ?>"><?php echo um_user('profile_photo', 80); ?></a>
			
			</div>
			<div class="sponsordetails">
			<div><strong><?php echo um_user('display_name'); ?></strong></div>
			<p><?php echo um_user('user_email'); ?></p>
			<p>
			<?php echo um_user('billing_address_1'); ?><br/>
			<?php echo um_user('billing_city'); ?> <?php echo um_user('billing_state'); ?><br/>
			<?php echo um_user('billing_postcode'); ?> (<?php echo um_user('billing_country'); ?>) <br/>
			</p>
			
			<?php do_action('um_logout_after_user_welcome', $args ); ?>
			</div>
			<div class="give-submit-button-wrap give-clearfix buttonmiddle middlebuttonsponsor">
	
			<input type="submit" class="btn btn-donatie" id="give-purchase-button" name="give-purchase" onclick="location.href='/edit-account';" value="Edit Account" />

			</div>
		</div>
		
<?php
  $userid = get_current_user_id();
  // find list of states in DB
  $sponsorcount = 0;
  global $wpdb;
  
  
  $qry = "SELECT meta_id FROM wp_postmeta where meta_key in ('_give_liv_sponsor','_give_edu_sponsor') and meta_value = '$userid'";
  $states = $wpdb->get_results( $qry );
  foreach( $states as $row ) {
    $sponsorcount++;
		
  }
?>			
		
		<div class="rightsponsorcontainer">
		<div class="rightsponsor">
		<div class="sponsoredkids">
		<p class="centersponsor"><strong>My Sponsoring <?php echo "(".$sponsorcount.")"; ?></strong></p>
		<div class="childsponsoredcontainer"> 
<?php
		  $qry = "SELECT post_id, meta_key FROM wp_postmeta where meta_key in ('_give_liv_sponsor','_give_edu_sponsor') and meta_value = '$userid'";
		  $states = $wpdb->get_results( $qry );
		  foreach( $states as $row ) {	
		  $postid = $row->post_id;	
		  $metakey = $row->meta_key;	
		  $edustatus = "inactive";
		  $livstatus = "inactive";		  
		  if ($metakey == "_give_liv_sponsor") {
		   $livstatus = "active";  
		  }
		  if ($metakey == "_give_edu_sponsor") {
			$edustatus = "active";  
		  }		  
			
							 $dob = get_post_meta( $postid, '_give_date_of_birth', true ); 
							 $gender = get_post_meta( $postid, '_give_gender', true ); 
							 $intro = get_post_meta( $postid, '_give_intro', true ); 
							 $name = get_post_meta( $postid, '_give_childname', true );
	
							
							
							
			?>
							
																
						
						<?php 
						$intro = (strlen($intro) > 120) ? substr($intro, 0, 120) . '...' : $intro;
						?> 
						<div class="childsponsoreddetailcontainer">
						<div class="childimagesponsor"><?php echo get_the_post_thumbnail( $postid, 'thumbnail'); ?></div>
						<div class="childdetailsponsor">
						<p><strong><?php echo $name; ?></strong><br/>
						<?php echo $dob; ?></p>
						</div>
						</div>
						 
						

					
		<?php
		  }
		?>
		</div>
<?php 


?>		
		
		<div class="give-submit-button-wrap give-clearfix buttonmiddle">
		<input type="submit" class="btn btn-donatie" id="give-purchase-button" onclick="location.href='/my-sponsoring';" name="give-purchase" value="VIEW ALL" />
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
	

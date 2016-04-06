<?php
  $userid = get_current_user_id();
  // find list of states in DB
  $sponsorcount = 0;
  $childid = "";
  if (isset($_GET['id'])) {
  $childid = $_GET['id'];
  }
  
  
  

  
  
  
  
  $qry = "SELECT meta_id FROM wp_postmeta where meta_key in ('_give_liv_sponsor','_give_edu_sponsor') and meta_value = '$userid'";
  $states = $wpdb->get_results( $qry );
  foreach( $states as $row ) {
    $sponsorcount++;
		
  }
  
  ?>



	<div class="um-form">
		<div class="containersponsor">
		
		<?php 
  if (!empty($childid)) {
  ?>
  		<div class="leftsponsor">
		<div class="containerchild-detail">
		<div class="leftchild-detail">
		<div class="img-thumb">
		<?php echo get_the_post_thumbnail( $childid, 'medium'); ?>
		</div>
		</div>
		<div class="middlechild-detail">
							<?php $name = get_post_meta( $childid, '_give_childname', true ); ?>
							<h3 style="margin-bottom:5px;"><?php echo $name;?></h3>
							<?php $dob = get_post_meta( $childid, '_give_date_of_birth', true ); ?>
							<?php $gender = get_post_meta( $childid, '_give_gender', true ); ?>
							<?php $intro = get_post_meta( $childid, '_give_intro', true ); ?>
							<?php $fullinfo = get_post_meta( $childid, '_give_fullinfo', true ); ?>
							<?php $liv_sponsor = get_post_meta( $childid, '_give_liv_sponsor', true ); ?>
							<?php $edu_sponsor = get_post_meta( $childid, '_give_edu_sponsor', true ); ?>		
							<?php $monthly_report = get_post_meta( $childid, '_give_monthly_report', true ); ?>									
							
							<?php $url = get_post_permalink( $childid); ?>
												
							
			<?php 
			
			
			
			$livstatus = "active";
			if ($liv_sponsor == "") {
				$livstatus = "inactive";
			}
			$edustatus = "active";
			if ($edu_sponsor == "") {
				$edustatus = "inactive";
			}	
			$reportstatus = "active";
			if ($reportstatus == "") {
				$reportstatus = "inactive";	
			}
			?>							
							
							
							
							<?php $fullinfo = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<p><object width=\"560%\" height=\"315\"><param name=\"movie\" value=\"http://www.youtube.com/v/$1&hl=en&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/$1&hl=en&fs=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"560\" height=\"315\"></embed></object></p>",$fullinfo); ?>
														
														
	
							
							<ul class="sponsoredul">
							<li>Gender: <span><?php echo $gender; ?></span></li>
							<li>ID:<span><?php echo $childid; ?></span></li>
							<li>Born: <span><?php echo $dob; ?></span></li>							
							</ul>	
							
							<p class="youtube"><?php echo $fullinfo; ?></p>	
							
							<p>&nbsp;</p>
							<p class="sponsoredicon educationbutton"><span class="dashicons sponsor-<?php echo $edustatus; ?>-education"></span><span class="sponsor-item">Education Sponsorship</span><?php if ($edustatus == "inactive") { ?><span class="sponsorbuttonnow"><a class="btn btn-default charity-donation-button sponsornowbtn" href="<?php echo $url; ?>">Sponsor now</a></span><?php } ?></p>
							<?php if ($edustatus == "active") { ?>
							<p class="sponsored">Sponsored</p>														
							<?php } else { ?>
							<p class="notsponsored fullmobile">Sponsor Needed.</p>
							<?php } ?>
							<p class="sponsoredicon livelihoodbutton"><span class="dashicons sponsor-<?php echo $livstatus; ?>-hearth"></span><span class="sponsor-item">Livelihood Sponsorship</span><?php if ($livstatus == "inactive") { ?><span class="sponsorbuttonnow"><a class="btn btn-default charity-donation-button sponsornowbtn" href="<?php echo $url; ?>">Sponsor now</a></span><?php } ?></p>
							<?php if ($livstatus == "active") { ?>
							<p class="sponsored">Sponsored</p>														
							<?php } else { ?>
							<p class="notsponsored fullmobile">Sponsor Needed.</p>
							<?php } ?>	
							
							<p class="sponsoredicon reporticon"><span class="dashicons sponsor-<?php echo $reportstatus; ?>-report"></span><span class="sponsor-item">Latest Report</span><span class="sponsorbuttonnow reportdownload"><a class="btn btn-default charity-donation-button sponsornowbtn " href="<?php echo $monthly_report; ?>">Download report</a></span></p>
							<p class="notsponsored hidemobile">Read latest report</p>
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							
							
													
							
		</div>  
		</div>
		</div>
		
		</div>
  
  
  
  <?php	  
  } else { 
  ?>		
		
		<div class="leftsponsor">
		
		<div class="sponsoreddetails">
			<div class="sponserheadinfo"><strong>My Sponsoring <?php echo "(".$sponsorcount.")"; ?></strong></div>
			<?php if ($sponsorcount == 0) { ?>
			<p class="nosponsor">You have not yet sponsored any of our childs</p>
			<?php } else { ?>
			<table class="sponsortable">
			<tr class="head">
			<td>Child</td><td class="center">Education Support</td><td class="center">Livelihood Support</td>
			</tr>
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
							 $liv_sponsor = get_post_meta( $postid, '_give_liv_sponsor', true ); 
							 $edu_sponsor = get_post_meta( $postid, '_give_edu_sponsor', true ); 
							 $url = get_post_permalink( $postid);
							 
			$livstatus = "active";
			if ($liv_sponsor == "") {
				$livstatus = "inactive";
			}
			$edustatus = "active";
			if ($edu_sponsor == "") {
				$edustatus = "inactive";
			}							 
							 
			
			?>
			<tr>
			<td class="left"><div class="sponsoredchildcontainer"><div class="sponsoredchildimg"><a href="#" onclick="gotodetail(<?php echo $postid;?>); return false;"><?php echo get_the_post_thumbnail($postid, 'thumbnail'); ?></a></div><div class="sponsoredchildname"><a href="#" onclick="gotodetail(<?php echo $postid;?>); return false;"><strong><?php echo $name; ?></strong></a><br/>
						<?php echo $dob; ?></div></div></td><td>
						<?php if ($edustatus == "active") { ?>
						<a href="#" onclick="gotodetail(<?php echo $postid;?>); return false;">
						<?php } else { ?>
						<a href="<?php echo $url; ?>">
						<?php } ?>				
						<span class="dashicons sponsor-<?php echo $edustatus; ?>-education"></span>
						</a></td>
						<td>
						<?php if ($livstatus == "active") { ?>
						<a href="#" onclick="gotodetail(<?php echo $postid;?>); return false;">
						<?php } else { ?>
						<a href="<?php echo $url; ?>">
						<?php } ?>							
						<span class="dashicons sponsor-<?php echo $livstatus; ?>-hearth"></span></a></td>
			</tr>
			
			<form action="<?php $_SERVER['self'];?>" id="<?php echo $postid; ?>" style="display:none;">
			<input type="hidden" id="id" name="id" value="<?php echo $postid;?>" />
			</form>
			
		  <?php } ?>	
			</table>
			<?php } ?>
			
			
		</div>		
		
		</div>
		<?php } ?>
		
			
		
		<div class="rightsponsorcontainer">
		<div class="rightsponsor">
		<div class="sponsoredkids">
		<p class="centersponsor"><strong>Sponsor a Child</strong></p>
		<div class="childsponsoredcontainer"> 
    <div id="carousel_sliderkids" class="owl-carousel sponsorachildcaroussel">
	<div class="slider center" style="margin-left: -215px">

    


<?php
$args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 120, "orderby" => "rand");
/* $args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 48); */
query_posts ( $args );
echo "apa";
if (have_posts ()) :
?>
<?php while ( have_posts () ) : the_post ();?>
<?php $dob = get_post_meta( $childid, '_give_date_of_birth', true ); ?>
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
					
	if ($livstatus == "active" && $edustatus == "active") { continue; } 
?>												
						
<?php 
	$intro = (strlen($intro) > 70) ? substr($intro, 0, 70) . '...' : $intro;
?> 

<div class="kindje" style="width:140px; margin-right:10px;"> 
<?php 
$intro = (strlen($intro) > 120) ? substr($intro, 0, 120) . '...' : $intro;
?> 

<div class="carousel_img sponsoredimgkids">
        <a target="_blank" href="<?php the_permalink();?>">
            <?php echo get_the_post_thumbnail( get_the_ID(), 'medium'); ?>
        </a>
		<p class="name"><strong><?php echo $name; ?></strong></p>
		<p class="intro"><?php echo $intro; ?></p>
		<p class="button">
		
		<input type="submit" class="btn btn-donatie breed" id="give-purchase-button" onclick="location.href='<?php the_permalink();?>';" name="give-purchase" value="Sponsor now" />						
		</p>			
		
</div>	
</div>	
		<?php
		endwhile;
		?>	
<?php while ( have_posts () ) : the_post ();?>
<?php $dob = get_post_meta( $childid, '_give_date_of_birth', true ); ?>
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
							
							 if ($livstatus == "active" && $edustatus == "active") {   ?>
							
<div class="carousel_img sponsoredimgkids">
        <a target="_blank" href="<?php the_permalink();?>">
            <?php echo get_the_post_thumbnail( get_the_ID(), 'medium'); ?>
        </a>
		<p class="name"><strong><?php echo $name; ?></strong></p>
		<p class="intro"><?php echo $intro; ?></p>
		<p class="button">
		
		<input type="submit" class="btn btn-donatie breed" id="give-purchase-button" name="give-purchase" value="Already Sponsored" />						
		</p>			
		
</div>	
																
						 <?php } else { continue; } ?>
						

					
		<?php
		endwhile;
		?>
		</div>
		</div>
<?php 
endif;
wp_reset_query();
?>		
    
 

    <script type="text/javascript">
	
	function gotodetail(id) {
	jQuery('#'+id).submit();	
	}
	
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
	
	

	

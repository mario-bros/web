<?php
/**
 * Charity - Our works in programs story template 
 * 
 * @package     charity
 * @version     v.1.0
 */
global $more;
$args = array ('post_type' => 'give_forms', "post_status"=> "publish", "posts_per_page" => 48);
query_posts ( $args );
if (have_posts ()) :

?>

 <link rel="stylesheet" type="text/css" href="/slick/slick.css"/>
 <link rel="stylesheet" type="text/css" href="/slick/slick-theme.css"/> 
 <section class="how-to-help header-one-help">
	<div class="container homeslideblock">
		<div class="row">
			<div class="col-xs-12 ">
				<header class="page-header section-header">
					<h2><strong>Sponsor Me ?</strong></h2>
					<p>Our children need your support. Please sponsor one of children below.</p>
				</header>
				<div class="row help-list ">
	 			
				
					<div class="col-xs-12 homeslider">
		<div id="pijltjes">
		<ul>
		<li class="previous_pijltje"></li>		
		<li class="next_pijltje"></li>
		</ul>
		</div>		




		<div class="slider center">
		
		
		
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
							
																
						<div class="kindje" style="width:140px; margin-right:10px;"> 
						<?php 
						$intro = (strlen($intro) > 120) ? substr($intro, 0, 120) . '...' : $intro;
						?> 

						<?php echo get_the_post_thumbnail( get_the_ID(), 'medium'); ?>
						<h3><?php echo $name; ?></h3>  
						 <p class="intro"><?php echo $intro; ?></p>					
						 <p class="knop">
						 <div class="buttonmiddle">
						 <a class="btn btn-default charity-donation-button"  href="<?php the_permalink();?>">Sponsor now</a>						
						</div>
						</p>
						 </div>
						

					
		<?php
		endwhile;
		?>
		
	
	</div>
	</div>
</div>
</div>
</div>
</div>	
	
</section>
<?php 
endif;
wp_reset_query();
?>


<!--<div class="col-md-3">
    <div id="" class="row">
    <div id="carousel_slider1" class="owl-carousel">

    
    <div class="carousel_img">
        <a target="_blank" href="http://www.forumheerhugowaard.nl/">
            <img width="159" height="87" src="http://peduli.laucaconsultancy.nl/wp-content/uploads/2015/08/pjkoper.png" class="attachment-full wp-post-image" alt="P.J. Koper vastgoed BV" />        </a>
    </div>

    
    </div>
    </div>-->
	
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
 <script type="text/javascript" src="/slick/slick.js"></script>

 
 <script type="text/javascript">

jQuery(document).ready(function(){

jQuery('.center').slick({
  centerMode: true,
  centerPadding: '60px',
  slidesToShow: 3,
  variableWidth: true, 
  slidesToScroll: 1,
  infinite: true,
  autoplay: false,
  arrows: true,
  autoplaySpeed: 2000, 
   waitForAnimate: false,
   zIndex: 20000,  
  focusOnSelect: true,
  nextArrow: '.next_pijltje',
  prevArrow: '.previous_pijltje',
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});
});


</script>

 <script type="text/javascript">
jQuery(document).ready(function () {

    jQuery("#owl-demo").owlCarousel({

        autoPlay: 3000, //Set AutoPlay to 3 seconds
        responsive: true,
        addClassActive: true,
        items: 4,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 3],
        stopOnHover:true,
        afterMove:function(){
            //console.log(1);
            jQuery(".owl-item").css({
                transform:"none"
            })
            jQuery(".active").eq(1).css({
                transform:"scale(1.4)",
                zIndex:3000,
				padding:"10px",
                
            })
            
        },
        afterInit:function(){
            jQuery(".active").eq(1).css({
                transform:"scale(1.4)",
                zIndex:3000,
				padding:"10px",
                
            })
        }

    });

});
</script>
	
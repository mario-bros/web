<?php
/**
 * Charity - Our team in our story template 
 * 
 * @package     charity
 * @version     v.1.0
 */
$args = array(
		'orderby' => 'ID',
		'order' => 'ASC',
		'count_total' => false,
		'fields' => 'all',
		'who' => ''
	);
$teams = get_users($args);
if(count($teams)> 0):

?>
<div class="container">
	<div class="our-team text-center row">
		<div class="col-xs-12">
			<header class="team-info section-header">
				<h2><?php echo vp_option('vpt_option.ch_our_team_section'); ?></h2>
			</header>
			<div class="row">
		<?php 
			foreach($teams as $team):
                            
                            if(in_array("customer", $team->roles)){ continue; }
                            if(in_array("shop_manager", $team->roles)){ continue; }
                            //print_r($team);
				$profileImage = (get_the_author_meta("image_medium", $team->ID) != "") ? get_the_author_meta("image_medium", $team->ID) : false;		
				//$firstName = (get_the_author_meta("first_name", $team->ID) != "") ? get_the_author_meta("first_name", $team->ID) : get_the_author_meta("display_name", $team->ID);
			   ?>
				<div class="col-xs-12 col-sm-3 anim-section">
					<div class="thumbnail">
						<?php 
						if(!empty($profileImage)):
						?>
						<figure>
							<img src="<?php echo esc_url(charity_resize($profileImage, 263, 272));?>" alt="<?php echo esc_attr($team->display_name);?>">
						</figure>
						<?php endif;?>
						<div class="caption">
							<h3><?php echo esc_attr($team->display_name);?></h3>
							<?php charity_author_social_link($team->ID); ?>
						</div>
					</div>
				</div>
				<?php
				endforeach;
			?>			
			</div>
		</div>
	</div>
</div>
<?php 
endif;

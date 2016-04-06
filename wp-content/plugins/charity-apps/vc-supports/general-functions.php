<?php

function getDropdownPage(){

$vcPages=get_posts(array("post_type"=>"page", "posts_per_page"=> -1, "post_status"=> "publish"));
		$args=array();
		if(is_array($vcPages)){
				foreach($vcPages as $vcPage){
					$args[$vcPage->ID]=$vcPage->post_title;	
				}
		}
		
		//print_r($args);
		return $args;
		
}

<?php 

return array(
      "name"              => __("Donate to save Lives", "charity"),
      "base"              => "our_mission",
      "class"             => "",
      "icon"              => "icon-charity",
      "description"       => "Place donate to save Lives",
      "category"          => __('Charity Shortcodes', "charity"),
      "params"            => array( 
			array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => __("Select page", "charity"),
				"param_name"  => "our_mission",
				"value"       => getDropdownPage(),
				"description" => __("Select page.","charity")
			 )
		)
   );

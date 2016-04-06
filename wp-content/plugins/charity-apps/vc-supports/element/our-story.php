<?php 

return array(
      "name"              => __("Helping Child", "charity"),
      "base"              => "our_story",
      "class"             => "",
      "icon"              => "icon-charity",
      "description"       => "Place our story",
      "category"          => __('Charity Shortcodes', "charity"),
      "params"            => array(
                             array(
				"type"        => "dropdown",
				"class"       => "",
				"heading"     => __("Select page", "charity"),
				"param_name"  => "our_story",
				"value"       => getDropdownPage(),
				"description" => __("Select page.","charity")
			 )
                             )
   );

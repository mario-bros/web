<?php 

return array(
      "name"              => __("See our causes", "charity"),
      "base"              => "see_causes",
      "class"             => "",
      "icon"              => "icon-charity",
      "description"       => "Place see our causes crausel",
      "category"          => __('Charity Shortcodes', "charity"),
      "params"            => array(
        array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => __("Select style", "charity"),
            "param_name"  => "causes",
            "value"       => array("home1","home2","home3"),
            "description" => __("Select style of the see our causes.","charity")
         ))
   );

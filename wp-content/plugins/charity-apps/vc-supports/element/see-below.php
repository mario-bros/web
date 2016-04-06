<?php 

return array(
      "name"              => __("See below", "charity"),
      "base"              => "see_below",
      "class"             => "",
      "icon"              => "icon-charity",
      "description"       => "Place see below",
      "category"          => __('Charity Shortcodes', "charity"),
      "params"            => array(
       array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => __("Select style", "charity"),
            "param_name"  => "help",
            "value"       => array("home1","home2","home3"),
            "description" => __("Select style of the see below.","charity")
         ))
   );

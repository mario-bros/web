<?php 

return array(
      "name"              => __("Latest News", "charity"),
      "base"              => "latest_news",
      "class"             => "",
      "icon"              => "icon-charity",
      "description"       => "Place latest news",
      "category"          => __('Charity Shortcodes', "charity"),
      "params"            => array(
         array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => __("Select style", "charity"),
            "param_name"  => "news",
            "value"       => array("home1","home2","home3"),
            "description" => __("Select style of the latest news.","charity")
         )
        
      )
   );

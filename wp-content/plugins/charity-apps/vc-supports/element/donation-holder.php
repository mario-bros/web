<?php 

return array(
      "name"              => __("Donation Holder Say", "charity"),
      "base"              => "donation_holder_say",
      "class"             => "",
      "icon"              => "icon-charity",
      "description"       => "Place donation holder say crausel",
      "category"          => __('Charity Shortcodes', "charity"),
      "params"            => array(
        array(
            "type"        => "dropdown",
            "class"       => "",
            "heading"     => __("Select style", "charity"),
            "param_name"  => "testimonial",
            "value"       => array("home1","home2","home3"),
            "description" => __("Select style of the testimonial.","charity")
         )
      )
   );

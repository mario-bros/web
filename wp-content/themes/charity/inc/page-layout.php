<?php
/**
 * Page Layout settings 
 *
 * @package     charity
 * @version     v.1.0
 */

class CharityPageLayout{
    
    public function __construct() {
        $this->action();
    }
    
    function action(){
        add_action("charity_header_layout", array(&$this, "header"));
        add_action("charity_footer_layout", array(&$this, "footer"));
    }
    
    function header(){
    	$layoutOption=vp_option("vpt_option.header_layout");
    	$slug= "content/header/one";
    	 
    	switch($layoutOption){
    		case "two":
    			$slug= "content/header/two";
    			 break;
    		case "three":
    		 	$slug= "content/header/three";
    		 	break;
    		case "shop-header":
    		 	$slug= "content/header/shop-header";
    		 	break;
    		default:
    		 	$slug= "content/header/one";
    		 	break;
    	}
        get_template_part($slug);
    }
    function footer(){
        	$layoutOption=vp_option("vpt_option.footer_layout");
    	$slug= "content/footer/one";
    	 
    	switch($layoutOption){
    		case "two":
    			$slug= "content/footer/two";
    			 break;
    		case "three":
    		 	$slug= "content/footer/three";
    		 	break;
    		default:
    		 	$slug= "content/footer/one";
    		 	break;
    	}
        get_template_part($slug);
    }
}

new CharityPageLayout();
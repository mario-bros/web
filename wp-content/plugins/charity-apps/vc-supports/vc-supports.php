<?php

/**
 * Charity - Visual Composer
 *
 * @package     charity
 * @version     v.1.0
 */
 
 
class Charity_VC_Support {

	public $support;

	public function __construct() {
		include_once "general-functions.php";
		$this -> support();
		$this -> shortcodes();
		$this -> action();
	}

	function action() {
		add_action("init", array(&$this, "check_vc"));
		add_action('vc_after_init', array(&$this, "elements"));

	}

	function scripts() {
		$vc_url = plugins_url() . "/charity-apps/";
		wp_enqueue_style("vc", $vc_url . "/vc-supports/assets/vc.css", array(), '1.0.0', "all");

	}

	function support() {
		$this -> support = array("donate_now" => "donate-now.php", //1
		"see_causes" => "see-causes.php", //3 - number of style
		"see_below" => "see-below.php", //3
		"latest_news" => "latest-news.php", //3
		"donation_holder_say" => "donation-holder.php", //3
		"meet_team" => "meet-team.php", //1
		"our_story" => "our-story.php", //1
		"our_mission" => "our-mission.php", //1
		"become_volunteer" => "volunteer.php", //1
		);

	}

	function elements() {
		foreach ($this->support as $support) :
			vc_map(
			include "element/" . $support);
		endforeach;
	}

	function shortcodes() {
		foreach ($this->support as $code => $support) :
			include "shortcode/" . $support;
			add_shortcode($code, "charity_vc_" . $code);
		endforeach;

	}

	function check_vc() {
		// Check if Visual Composer is installed
		if (defined('WPB_VC_VERSION')) {
			add_action('wp_enqueue_scripts', array(&$this, "scripts"));
		}

	}

}

if (defined('WPB_VC_VERSION')) {
	new Charity_VC_Support();
}

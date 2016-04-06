<div class="vp-field charity-oneclick-wrap">
    <?php if(!empty($_REQUEST['oneclk']) && $_REQUEST['oneclk']=='succes'): ?>
        
    <b><i class="fa fa-hand-o-right"></i> <?php _e("Oneclick data", "charity"); ?></b>
    <p><?php _e("Oneclick data successfull import.", "charity"); ?></p>
    <p><?php _e("Please ", "charity"); ?> <a href="<?php echo site_url(); ?>"><?php _e("Click here", "charity"); ?></a> <?php _e("for visit site.", "charity"); ?></p>
       <?php else: ?>
    
    <b><i class="fa fa-hand-o-right"></i> <?php _e("System Settings", "charity"); ?></b>
    <?php $systemHealth = charity_system_health(); ?>
    <ul class="charity-oneclick-ul">
        <?php
        foreach ($systemHealth as $healthVal):
            $status = (!empty($healthVal['status']) && $healthVal['status'] == "ok") ? "fa fa-check" : "fa fa-exclamation-triangle text-danger";
            ?>
            <li><span class="fa fa-paperclip"></span> <?php echo $healthVal['title']; ?> <i original-title="<?php echo $healthVal['info']; ?>" class="description vp-js-tipsy chy-oc-check <?php echo $status; ?>"></i></li>
        <?php endforeach; ?>
    </ul>
    <b><i class="fa fa-hand-o-right"></i><?php _e("Plugin Status", "charity"); ?> </b>
    <?php $pluginStatus = charity_plugins_status(); ?>
    <ul class="charity-oneclick-ul">
        <?php
        foreach ($pluginStatus as $pluginVal):
            $status = (!empty($pluginVal['status']) && $pluginVal['status'] == "ok") ? "fa fa-check" : "fa fa-exclamation-triangle text-danger";
            ?>
            <li><span class="fa fa-paperclip"></span> <?php echo $pluginVal['title']; ?> <i original-title="<?php echo $pluginVal['info']; ?>" class="description vp-js-tipsy chy-oc-check <?php echo $status; ?>"></i></li>
        <?php endforeach; ?>
    </ul>

    <div id="charity-oneclick-wrapper">
        <b><i class="fa fa-hand-o-right"></i> <?php _e("OneClick Installation", "charity"); ?></b>
        <div id="charity-progressbar" class="tm-btn-hide">
            <div class="charity-progressbar-value" style="width: 0%;"> <div class="charity-progress-label"><?php _e("Loading...", "charity"); ?></div></div>
        </div>
        <button type="button" class="btn btn-primary button button-primary" id="installationStart"> <i class="fa fa-th-list"></i> <?php _e("OneClick Installation Start", "charity"); ?> </button>
        <button type="button" class="btn btn-primary tm-btn-hide button button-primary" id="installationLog"><i class="fa fa-info-circle" ></i> <?php _e("Import Log", "charity"); ?></button>  

        <div id="charity-progressbar-status">
            <ul>
                <li class="tm-s-0 tm-e-5 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Import Start", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-5 tm-e-10 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Author Mapping", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-10 tm-e-20 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Categories Process", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-20 tm-e-35 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Media Process", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-35 tm-e-60 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Posts Process", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-60 tm-e-80 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Posts Mapping", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-80 tm-e-90 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Attachment Mapping", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-90 tm-e-100 tm-hide"><span class="fa fa-paperclip"></span><?php _e("Featured Images Mapping", "charity"); ?>  <i class="fa fa-spinner"></i></li>
                <li class="tm-s-100 tm-finish tm-hide"><span class="fa fa-paperclip"></span><?php _e("Import Finished", "charity"); ?>  <i class="fa fa-spinner"></i></li>
            </ul>


        </div>
        <div id="dialogInstallationAlert" title="<?php _e("Alert! Plugin Installation", "charity"); ?>">
            <i class="fa fa-info-circle"></i>  <?php _e("Please install all recommended plugin ?", "charity"); ?>
        </div>

        <div id="dialogInstallationStart" title="<?php _e("OneClick Installation Start", "charity"); ?>">
            <i class="fa fa-info-circle"></i>  <?php _e("Are you want to import demo content ?", "charity"); ?>
        </div>
        <div id="dialogInstallationLog" title="<?php _e("OneClick Installation Log", "charity"); ?>">
        </div>
    </div>
    <?php endif; ?>
    
</div>
<?php

function charity_system_health() {
    $args = array();
//PHP Version
    $php_version = function_exists('phpversion') ? phpversion() : '';
    $args['php_version']['title'] = __('PHP Version', "charity");

    if ($php_version != "" && intval($php_version) >= 5) {
        $args['php_version']['info'] = __('PHP version is ', "charity") . $php_version;
        $args['php_version']['status'] = 'ok';
    } else {
        $args['php_version']['info'] = __('Currently you are using PHP version is ', "charity") . $php_version . __(". You need to update at least PHP version >= 5. ", "charity");
        $args['php_version']['status'] = 'error';
    }


//WordPress Version
    $args['wp_version']['title'] = __('WordPress Version', "charity");
    if (version_compare($GLOBALS['wp_version'], '3.6', '>')) {
        $args['wp_version']['info'] = __('WordPress version is ' , "charity"). $GLOBALS['wp_version'];
        $args['wp_version']['status'] = 'ok';
    } else {
        $args['wp_version']['info'] = __('Currently you are using WordPress version is ' , "charity"). $GLOBALS['wp_version'] . __(". You need to update at least WordPress version >= 3.6 ", "charity");
        $args['wp_version']['status'] = 'error';
    }

//PHP INI
    if (function_exists('ini_get')) {
//Memory Limit
        $args['php_memory_limit']['title'] = __('PHP Memory Limit', "charity");
        $args['php_memory_limit']['info'] = __('PHP memory limit is ', "charity") . size_format(charity_ini_to_num(ini_get('memory_limit')));
        $args['php_memory_limit']['status'] = 'ok';

//Post max size
        $args['php_post_max_size']['title'] = __('PHP Post Max Size', "charity");
        $args['php_post_max_size']['info'] = __('PHP post max size is ', "charity") . size_format(charity_ini_to_num(ini_get('post_max_size')));
        $args['php_post_max_size']['status'] = 'ok';


//Max execution time
        $args['php_max_execution_time']['title'] = __('PHP Execution Time', "charity");
        $args['php_max_execution_time']['info'] = __('PHP max execution time is ', "charity") . ini_get('max_execution_time');
        $args['php_max_execution_time']['status'] = 'ok';

//Max input vars
        $args['php_max_input_vars']['title'] = __('PHP Max Input Vars', "charity");
        $args['php_max_input_vars']['info'] = __('PHP max input vars is ', "charity") . ini_get('max_input_vars');
        $args['php_max_input_vars']['status'] = 'ok';
    }

//PHP cURL
//function_exists('curl_init')
    $args['php_curl']['title'] = __('PHP cURL', "charity");

    if (function_exists('curl_init')) {
        $args['php_curl']['info'] = __('PHP cURL enable ', "charity");
        $args['php_curl']['status'] = 'ok';
    } else {
        $args['php_curl']['info'] = __('PHP cURL disable which communicate with other servers will not work. Contact your hosting provider', "charity");
        $args['php_curl']['status'] = 'error';
    }

//fsockopen
    $args['php_fsockopen']['title'] = __('PHP fsockopen', "charity");

    if (function_exists('fsockopen')) {
        $args['php_fsockopen']['info'] = __('PHP fsockopen enable ', "charity");
        $args['php_fsockopen']['status'] = 'ok';
    } else {
        $args['php_fsockopen']['info'] = __('PHP fsockopen disable which communicate with other servers will not work. Contact your hosting provider', "charity");
        $args['php_fsockopen']['status'] = 'error';
    }

//WordPress Memory Limit
    $args['wp_memory_limit']['title'] = __('WordPress Memory Limit', "charity");
    $memory = charity_ini_to_num(WP_MEMORY_LIMIT);
    if ($memory < 100663296) {
        $args['wp_memory_limit']['info'] = sprintf(__('%s - We recommend setting memory to at least 96MB.', 'charity'), size_format($memory));
        $args['wp_memory_limit']['status'] = 'error';
    } else {
        $args['wp_memory_limit']['info'] = __('WordPress memory limit is ', "charity") . size_format($memory);
        $args['wp_memory_limit']['status'] = 'ok';
    }

    $args['wp_max_upload_size']['title'] = __('WordPress Max Upload Size', "charity");
    $args['wp_max_upload_size']['info'] = __('WordPress max upload size is ', "charity") . size_format(wp_max_upload_size());
    $args['wp_max_upload_size']['status'] = 'ok';

    return $args;
}

function charity_ini_to_num($size) {
    $l = substr($size, -1);
    $ret = substr($size, 0, -1);
    switch (strtoupper($l)) {
        case 'P':
            $ret *= 1024;
        case 'T':
            $ret *= 1024;
        case 'G':
            $ret *= 1024;
        case 'M':
            $ret *= 1024;
        case 'K':
            $ret *= 1024;
    }
    return $ret;
}

function charity_plugins_status() {
    $args = array();
    if (!function_exists('is_plugin_active')):
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $plugins = array(
        'charity-apps' => array('name' => 'Charity Apps', 'path' => 'charity-apps/charity-apps.php'),
        'easypay' => array('name' => 'EasyPay', 'path' => 'easypay/easypay.php'),
    	'revslider' => array('name' => 'Revslider', 'path' => 'revslider/revslider.php'),
    	'cf7' => array('name' => 'Contact Form 7', 'path' => 'contact-form-7/wp-contact-form-7.php'),
        'mailchimp' => array('name' => 'Mailchimp', 'path' => 'mailchimp-for-wp/mailchimp-for-wp.php'),
    	'woocommerce' => array('name' => 'Woocommerce', 'path' => 'woocommerce/woocommerce.php'),
    	'contact-form-7-to-database-extension' => array('name' => 'Contact Form DB', 'path' => 'contact-form-7-to-database-extension/contact-form-7-db.php'),
    );




    foreach ($plugins as $key => $plugin) {
        $args[$key]['title'] = $plugin['name'];

        if (!is_plugin_active($plugin['path'])) {
            $args[$key]['info'] = "Plugin is not install.";
            $args[$key]['status'] = "error";
        } else {
            $args[$key]['info'] = "Plugin is install.";
            $args[$key]['status'] = "ok";
        }
    }

    return $args;
}

/*
<div class="vp-field">
	<div class="label">
		<label>
			<?php _e('Restore Default Options', 'vp_textdomain') ?>
		</label>
		<div class="description">
			<p><?php _e('Restore options to initial default values.', 'vp_textdomain') ?></p>
		</div>
	</div>
	<div class="field">
		<div class="input">
			<!--div class="buttons">
				<input class="vp-js-restore vp-button button button-primary" type="button" value="<?php _e('Restore Default', 'vp_textdomain') ?>" />
				<p><?php _e('** Please make sure you have already make a backup data of your current settings. Once you click this button, your current settings will be gone.', 'vp_textdomain'); ?></p>
				<span style="margin-left: 10px;">
					<span class="vp-field-loader vp-js-loader" style="display: none;"><img src="<?php VP_Util_Res::img_out('ajax-loader.gif', ''); ?>" style="vertical-align: middle;"></span>
					<span class="vp-js-status" style="display: none;"></span>
				</span>
			</div-->
		</div>
	</div>
</div>

*/

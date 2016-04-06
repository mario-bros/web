<?php
/**
 * @author      Olga Banasinska
 * @copyright	Omines
 * @since       21-6-11 15:58
 * @package		System
 */
 
define('ROOT_PATH',		str_replace('/system/cron', '', dirname(__FILE__)).'/');
define('SYS_PATH',		ROOT_PATH.'system/');
define('SITE_PATH',		SYS_PATH.'site/');
define('STATIC_PATH',	ROOT_PATH.'static/');
define('CRON_PATH', 	SYS_PATH.'cron/');
define('CACHE_PATH', 	ROOT_PATH.'cache/');

if(!defined('SITE_ROOT'))
	define('SITE_ROOT', '');


function CronVerbose($text)		{ CronLog('[CRON] '.$text); }
function CronInformation($text)	{ CronLog('[CRON] '.$text); }
function CronWarning($text)		{ CronLog('[CRON][Warning] '.$text); }
function CronError($text)		{ CronLog('[CRON][ERROR] '.$text); }
function CronLog($text)			{ print $text."\n"; }

if(!file_exists(SYS_PATH.'config.php'))
	CronError('There is no valid config.php file in the system folder. Please generate one from the supplied config-sample.php');
require_once SYS_PATH.'config.php';
require_once SYS_PATH.'settings.php';

require_once SYS_PATH.'init-logging.php';
require_once SYS_PATH.'init-functions.php';
require_once SYS_PATH.'init-classes.php';
$db				= DatabaseFactory::Create(DB_TYPE);
$db->Connect(DB_SERVER, DB_CATALOG, DB_USERNAME, DB_PASSWORD);

require_once SYS_PATH.'init-logging.php';
<?php
/**
 * @author      Bas Scholts
 * @copyright	Omines
 * @since       26-jan-2011 14:08:15
 * @package		System
 */

define('PAYMENT_LOGS', PAYMENT_ROOT.'/logs/');

require_once PAYMENT_ROOT.'classes/class-buckaroo.php';
require_once PAYMENT_ROOT.'classes/class-buckaroo-v3.php';

$path	= explode('/', trim($request->URI(), '/'));
if (!is_array($path) || count($path) == 0)
	$path	= array('error');

switch ($path[0])
{
	case 'buckaroo':
        if (isset($path[1]))
        {
            switch ($path[1])
            {
                case 'push':
                    require_once PAYMENT_ROOT.'postbacks/buckaroo-push.php';
                    break;
                case 'batch':
                    require_once PAYMENT_ROOT.'postbacks/buckaroo-batch.php';
                    break;
                default:
                    require_once PAYMENT_ROOT.'postbacks/buckaroo.php';
                    break;
            }
        }
        else
            require_once PAYMENT_ROOT.'postbacks/buckaroo.php';
		break;
	default:
		header('HTTP/1.0 404 Not Found');
		header('Status: 404 Not Found');
		die('Invalid provider');
}
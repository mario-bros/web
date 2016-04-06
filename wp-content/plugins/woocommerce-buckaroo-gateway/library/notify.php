<?php
require dirname(__FILE__) . '../../../../init.php';

if (!defined('BOOTSTRAP')) { die('Access denied'); }

require(dirname(__FILE__) . '/common.php');

fn_buckaroo_process_response();
exit;
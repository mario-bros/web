<?php
/**
 * Description of Logger
 *
 * @author s.cigankovs
 */
if (!class_exists('Logger'))
{
    require_once dirname(__FILE__).'/api/corelogger.php';

    class Logger extends CoreLogger {

        private $logtype = 'plugin';

    }
};
?>

<?php
/**
 * @author      Bas Scholts
 * @copyright	Omines
 * @since       26-jan-2011 14:07:11
 * @package		System
 */
 
class PayLog
{
	private $filename;
	private $messages;

	public function __construct($filename = null)
	{
		$this->messages	= array();
		$this->SetFileName(is_null($filename) ? date('Ymd_H') : $filename);
		$this->messages[]	= 'New entry at '.date('Y-m-d H:i:s');
	}
	public function SetFileName($filename)
	{
		$this->filename	= $filename . '.txt';
	}

	public function Log($message)
	{
		$this->messages[]	= $message;
	}

	private function flushLog()
	{
		if (count($this->messages) == 0)
			return;
		$handle	= fopen(PAYMENT_LOGS.$this->filename, 'a+');
        fwrite($handle, "\xEF\xBB\xBF");
		fwrite($handle, implode("\r\n", $this->messages)."\r\n".str_repeat('-', 60)."\r\n\r\n");
		fclose($handle);

        $this->messages = array();
	}

	public function __destruct()
	{
		$this->flushLog();
	}
    public function close()
    {
        $this->flushLog();
    }
}
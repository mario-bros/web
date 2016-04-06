<?php
class BSOAP_Service
{
	public $RequestParameter;
	public $Name;
	public $Action;
	public $Version;
	
	public function __construct($Name, $Action, $Version) {
		$this->Name = $Name;
		$this->Action = $Action;
		$this->Version = $Version;
	}
}


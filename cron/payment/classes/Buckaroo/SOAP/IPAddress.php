<?php
class BSOAP_IPAddress
{
 	public $_;
 	public $Type;
	
	public function __construct($address, $Type = 'IPv4') {
		$this->_ = $address;
		$this->Type = $Type;
	}
}

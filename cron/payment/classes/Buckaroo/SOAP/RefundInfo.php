<?php
class BSOAP_RefundInfo
{
	public $TransactionKey;
	
	public function __construct($Transactionkey) {
		$this->TransactionKey = $Transactionkey;
	}
}


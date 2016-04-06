<?php
/**
 * @author      Bas Scholts
 * @copyright	Omines
 * @since       26-jan-2011 12:37:44
 * @package		System
 */

require_once dirname(__FILE__).'/class-buckaroo-v3.php';
/*
class BuckarooValidationException extends Exception
{
	public function __construct($message = null, $code = null) { parent::__construct($message, $code); }
}

abstract class BuckarooBase
{
	/**
	 * @var MySQLDatabase
	 *
	private $db;
	/**
	 * @var Transaction
	 *
	private $transaction;
	private $id;
	protected $data;

	public function __construct(Transaction $transaction)
	{
		global $db, $session;
		$this->transaction	= $transaction;
		$this->db			= $db;
		$this->data			= array(
			'BPE_Mode'					=> BPE_MODE,
			'BPE_Currency'				=> BPE_CURRENCY,
			'BPE_Merchant'				=> BPE_MERCHANT_KEY,
			'BPE_Return_Method'			=> 'POST',
			'BPE_PaymentMethodsAllowed'	=> BPE_METHODS,
			'BPE_Amount'				=> round($this->Transaction()->Amount() * 100),
			'BPE_Description'			=> 'Peduli Anak Foundation Invoice '.$this->transaction()->InvoiceNumber(),
			'BPE_Reference'				=> $this->transaction()->InvoiceNumber(),
			'BPE_Invoice'				=> $this->transaction()->InvoiceNumber()
		);

		$sql = 'SELECT * FROM BuckarooTransaction WHERE TransactionId = '.$this->Transaction()->TransactionId();
		$result	= $this->db->Query($sql);
		if ($result->Count() > 0)
		{
			$data	= $result->FetchObject();
			$this->id						= (int)$data->BuckarooTransactionId;
			$this->data['BPE_Amount']		= (int)$data->BpeAmount;
			$this->data['BPE_Mode']			= (int)$data->BpeMode;
			$this->data['BPE_Result']		= (int)$data->BpeResult;
			$this->data['BPE_Currency']		= $data->BpeCurrency;
			$this->data['BPE_Reference']	= $data->BpeReference;
			$this->data['BPE_Trx']			= $data->BpeTrx;
			$this->data['BPE_Description']	= $data->BpeDescription;
			$this->data['BPE_ResponseSig']	= $data->BpeResponseSignature;
		}
		//register_shutdown_function(array($this, 'Save'));
	}

	public function Db()			{ return $this->db; }
	public function BuckarooTrId()	{ return $this->id; }
	public function Transaction()	{ return $this->transaction; }

	public function FormVariables()	{ return $this->data; }
	public function Data($key)		{ return $this->data[$key]; }

	public function Amount()		{ return $this->data['BPE_Amount']; }
	public function CustomerId()	{ return $this->Transaction()->UserId(); }
	public function Currency()		{ return $this->data['BPE_Currency']; }
	public function Description()	{ return $this->data['BPE_Description']; }
	public function Invoice()		{ return $this->data['BPE_Invoice']; }
	public function Language()		{ return $this->data['BPE_Language']; }
	public function Mode()			{ return $this->data['BPE_Mode']; }
	public function MerchantKey()	{ return $this->data['BPE_Merchant']; }
	public function Reference()		{ return $this->data['BPE_Reference']; }
	public function Result()		{ return $this->data['BPE_Result']; }
	public function SecretKey()		{ return BPE_SECRET_KEY; }
	public function Methods()		{ return $this->data['BPE_PaymentMethodsAllowed']; }

	public function SetCurrency()
	{
		$this->data['BPE_Currency']	= 'EUR';
	}
	public function SetAmount($amountInCent)
	{
		if (!is_int($amountInCent))
			throw new Exception('Argument passed to '.__METHOD__.' should be an integer (price in cent), but received '.gettype($amountInCent));
		$this->data['BPE_Amount']		= $amountInCent;
	}
	public function SetDescription($description)
	{
		$this->data['BPE_Description']	= $description;
	}
	public function SetReference($reference)
	{
		$this->data['BPE_Reference']	= $reference;
	}
	public function SetLanguage($language = 'NL')
	{
		$language	= strtoupper($language);
		if (!in_array($language, array('NL','EN','DE','FR')))
			$language	= 'NL';
		$this->data['BPE_Language']		= strtoupper($language);
	}

	public function Save()
	{
		$dbArg	= array(
			'id'			=> $this->id,
			'transactionId'	=> $this->transaction->TransactionId(),
			'reference'		=> $this->Reference(),
			'currency'		=> $this->Currency(),
			'amount'		=> $this->Amount(),
			'result'		=> $this->Result(),
			'mode'			=> $this->Mode(),
			'description'	=> $this->Description(),
			'reqSignature'	=> $this->Data('BPE_Signature2'),
			'resSignature'	=> $this->Data('BPE_ResponseSig'),
		);

		if ($this->id == 0)
			$sql = 'INSERT INTO BuckarooTransaction
					(TransactionId, BpeReference, BpeCurrency, BpeAmount, BpeResult, BpeMode, BpeDescription, BpeRequestSignature)
					VALUES
					(%transactionId, %reference, %currency, %amount, %result, %mode, %description, %reqSignature)';
		else
			$sql = 'UPDATE	BuckarooTransaction
					SET		TransactionId			= %transactionId,
							BpeTrx					= %trxId,
							Reference				= %reference,
							BpeRequestSignature		= %reqSignature
					WHERE	BuckarooTransactionId	= %id';
		$this->db->Query($sql, $dbArg);
		if (!$this->id) {
			$this->id = (int)$this->db->LastInsertId();
		}
	}

	public function Log($message)
	{
		global $payLog;
		if (!isset($payLog) || !($payLog instanceof PayLog))
			return;
		$payLog->Log($message);
	}
}

class BuckarooRequest extends BuckarooBase
{
	
	public function __construct(Transaction $transaction)
	{
		parent::__construct($transaction);
		$this->RequestSignature();
	}

	/**
	 * Returns the digital signature of a transaction for the Buckaroo Payment Engine.
	 * Signature is created using the following: MerchantKey + InvoiceNumber + Amount + Currency + Mode + SecretKey
	 *
	 * @return string
	 *
	public function RequestSignature()
	{
		if (!in_array('BPE_Signature2', array_keys($this->data)))
			$this->data['BPE_Signature2']	= md5(
				$this->MerchantKey() .
				$this->Transaction()->InvoiceNumber() .
				$this->Amount() .
				$this->Currency() .
				$this->Mode() .
				$this->CustomerId() .
				$this->SecretKey()
			);
		return $this->data['BPE_Signature2'];
	}
	public function Signature()	{ return null; }
}

class BuckarooResponse extends BuckarooBase
{
	public function __construct(Transaction $transaction)
	{
		parent::__construct($transaction);
		$this->SetAmount(intval($transaction->Amount() * 100));
	}

	private	$signature;
	public function Signature()	{ return $this->signature; }
	public function ResponseSignature($bpeTrxId, $bpeTimestamp, $bpeInvoice, $bpeReference, $bpeResult)
	{
		$this->signature	= md5(
			$bpeTrxId .
			$bpeTimestamp .
			$this->MerchantKey() .
			$bpeInvoice .
			$bpeReference .
			$this->Currency() .
			$this->Amount() .
			$bpeResult .
			$this->Mode() .
			$this->SecretKey()
		);
		return $this->Signature();
	}

	public function ResponseUpdate($bpeTrx, $bpeResult, $bpeResponseSig) {
		$data['BPE_Trx']		 = $bpeTrx;
		$data['BPE_Result']		 = $bpeResult;
		$data['BPE_ResponseSig'] = $bpeResponseSig;

		$sql = 'UPDATE	BuckarooTransaction
				SET		BpeTrx		= %BPE_Trx,
						BpeResult	= %BPE_Result,
						BpeResponseSignature = %BPE_ResponseSig
				WHERE	BuckarooTransactionId	= '.$this->BuckarooTrId();
		$this->Db()->Query($sql, $data);

		$sql	= 'SELECT * FROM BuckarooStatus WHERE BuckarooStatusId = %BPE_Result';
		$result = $this->Db()->Query($sql, $data);
		$status	= $result->FetchObject();
		switch ($status->StatusType)
		{
			case BPE_STATUS_SUCCESS:
				$this->Transaction()->Complete($bpeResult);
				break;
			case BPE_STATUS_CANCELLED:
				$this->Transaction()->Cancel($bpeResult);
				break;
			case BPE_STATUS_REJECTED:
				$this->Transaction()->Reject($bpeResult);
				break;
			default:
				$this->Log('Unknown BPE result code received: '.$bpeResult);
				break;
		}
	}

	public function BatchResponseUpdate($bpeTrx, $bpeResult) {
		$data['BPE_Trx']		 = $bpeTrx;
		$data['BPE_Result']		 = $bpeResult;

		$sql = 'UPDATE	BuckarooTransaction
				SET		BpeTrx		= %BPE_Trx,
						BpeResult	= %BPE_Result,
				WHERE	BuckarooTransactionId	= '.$this->BuckarooTrId();
		$this->Db()->Query($sql, $data);

		$sql	= 'SELECT * FROM BuckarooStatus WHERE BuckarooStatusId = %BPE_Result';
		$result = $this->Db()->Query($sql, $data);
		$status	= $result->FetchObject();
		switch ($status->StatusType)
		{
			case BPE_STATUS_SUCCESS:
				$this->Transaction()->Complete($bpeResult);
				break;
			case BPE_STATUS_CANCELLED:
				$this->Transaction()->Cancel($bpeResult);
				break;
			case BPE_STATUS_REJECTED:
				$this->Transaction()->Reject($bpeResult);
				break;
			default:
				$this->Log('Unknown BPE result code received: '.$bpeResult);
				break;
		}
	}
}
*/
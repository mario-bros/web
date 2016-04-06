<?php
/**
 * @author      Bas Scholts
 * @copyright	Omines
 * @since       25-jan-2011 13:57:25
 * @package		System
 */

class TransactionException extends Exception
{
	public function __construct($message = null, $code = null) { parent::__construct($message, $code); }
}

class Transaction
{
	/**
	 * @var MySQLDatabase
	 */
	private $db;
	private $id			= 0;
	private $data;
	private $lines		= array();

	static public function FindByInvoice($invoice)
	{
		
		$sql = "SELECT TransactionId FROM Transaction WHERE InvoiceNumber = '$invoice'";
		$result     = mysql_query($sql);
		$teller = mysql_num_rows($result);		
		if ($teller == 0)
			throw new TransactionException('Transaction with invoice reference "'.$invoice.'" not found');
		while ($row = mysql_fetch_array($result))
		{
			$transactionid = $row['TransactionId'];
		}
		return new Transaction($transactionid);
	}

	public function __construct($id = null)
	{
		
		if (!is_null($id))
			$this->load($id);
	}
	private function load($id)
	{
		$this->id	= (int)$id;
		$sql		= 'SELECT * FROM Transaction WHERE TransactionId = ' . $this->TransactionId();
		$result     = mysql_query($sql);
		$teller = mysql_num_rows($result);				
		if ($teller == 0)
			throw new TransactionException('Transaction with ID reference '.$id.' not found');
		$this->data	= mysql_fetch_object($result);

		$sql		= 'SELECT * FROM TransactionLine WHERE TransactionId = ' . $this->TransactionId();
		$result     = mysql_query($sql);		
		
		if ($teller > 0)
		{
			while ($row = mysql_fetch_object($result))
				$this->AddLine(new TransactionLine((int)$row->TransactionLineId));
		}
	}

	public function TransactionId()				{ return $this->id; }
	public function UserId()					{ return $this->data->UserId; }
	public function Amount()					{ return $this->data->Amount; }
	public function InvoiceNumber()				{ return $this->data->InvoiceNumber; }
	public function BPEStatus()					{ return $this->data->BPEStatus; }
	public function GetData($column)			{ return $this->data->$column; }
    /** @return TransactionLine[] */
	public function Lines()						{ return $this->lines; }

	public function SetUserId($id)				{ $this->data->UserId = $id; }

	/**
	 * @return bool
	 */
	public function IsCompleted()				{ return $this->data->IsCompleted == 1; }
	/**
	 * @param bool $u Return UNIX timestamp if true, string if false
	 * @return mixed
	 */
	public function TransactionDate($u = false)	{ return $u ? strtotime($this->data->TransactionDate) : $this->data->TransactionDate; }
	/**
	 * @param bool $u Return UNIX timestamp if true, string if false
	 * @return mixed
	 */
	public function CompletedDate($u = false)	{ return $u ? strtotime($this->data->CompletedDate) : $this->data->CompletedDate; }

    public function HasRecurringPayment()
    {
        foreach ($this->Lines() as $line)
        {
            if ($line->hasRecurringPayment())
                return true;
        }
        return false;
    }

	/**
	 * @param TransactionLine $line
	 * @param null $identifier Optional, line ID will be used if none given
	 * @return void
	 */
	public function AddLine(TransactionLine $line, $identifier = null)
	{
		if (is_null($identifier)) {
			//$identifier	= $line->LineId();
			$this->lines[]	= $line;
		}
		else {
			$this->lines[$identifier]	= $line;
		}
		$this->recalculate();
	}

	private function recalculate()
	{
		$amount	= 0;
		foreach ($this->lines as $line)
		{
			$amount	+= $line->Amount();
		}
		$this->data->Amount	= $amount;
	}

	public function Save($doBuckaroo = true)
	{
		$dbArgs	= array(
			'UserId'				=> $this->data->UserId,
			'Amount'				=> $this->Amount(),
			'BPEStatus'				=> $this->BPEStatus()
		);
		
		$UserId = $this->data->UserId;
		$Amount = $this->Amount();
		$BPEStatus = $this->BPEStatus();				
		
		if ($this->id) {
			$tmp = array();
			foreach($dbArgs as $column => $value)
				$tmp[] = $column.' = %'.$column;

			$sql = 'UPDATE	Transaction
					SET		'.implode(', ', $tmp).'
					WHERE	TransactionId = '.$this->TransactionId();
		}
		else {
			$sql = 'INSERT	INTO Transaction ('.implode(', ', array_keys($dbArgs)).')
					VALUES	('.implode(', ', array_values($dbArgs)).')';
		}
		
		//echo $sql;
		//exit();
		
		mysql_query($sql);

		$updateTrId = false;
		if (!$this->id) {
			$this->id = (int)mysql_insert_id();
			$updateTrId = true;
			$InvoiceNumber = $this->data->InvoiceNumber;
			//construct invoice number
			$this->data->InvoiceNumber = date('Ymd').str_pad($this->data->UserId, 6, '0', STR_PAD_LEFT);
			$sql = 'UPDATE	Transaction
					SET		InvoiceNumber = '.$InvoiceNumber.'
					WHERE	TransactionId = '.$this->TransactionId();
			mysql_query($sql);		
			//$this->db->Query($sql, array('InvoiceNumber' => $this->data->InvoiceNumber));
		}

		foreach ($this->lines as $line) {
			if ($updateTrId) 
				$line->SetTransactionId($this->id);
			$line->Save();
		}

		if ($doBuckaroo) {
			$buckaroo = new BuckarooV3Request($this);
		}
	}

	public function Log($message)
	{
		global $payLog;
		if (!isset($payLog) || !($payLog instanceof PayLog))
			return;
		$payLog->Log($message);
	}

	public function Complete($bpeResult)
	{
		$data = array();
		$this->data->CompletedDate	= date('Y-m-d H:i:s');
		$this->data->BPEStatus		= $bpeResult;
		$data['BPEStatus']			= $bpeResult;
		$data['transactionId']		= $this->TransactionId();

		//check if result code stands for SUCCESS
		$BPEStatus = $data['BPEStatus'];
		$transactionId = $data['transactionId'];
		$sql	= "SELECT * FROM BuckarooV3Status WHERE BuckarooStatusId = '$BPEStatus'";
		//$result = $this->db->Query($sql, $data);
		$result     = mysql_query($sql);
		$teller = mysql_num_rows($result);	
		$status	= mysql_fetch_object($result);
		$this->Log('BPE Result: '.$bpeResult);
		$this->Log('BPE Type: '.($status && $status->StatusType ? $status->StatusType : '--'));

		$data['IsCompleted']	= $this->data->IsCompleted	= ($status && $status->StatusType == BuckarooV3Base::StatusSuccess) ? 1 : 0;
		$data['status']			= $status->StatusType;

		$isCompleted = $data['IsCompleted'];
		$status = $data['status'];

		$sql = "UPDATE	Transaction
				SET		IsCompleted = '$isCompleted',
						Status = '$status',
						CompletedDate = NOW(),
						BPEStatus = '$BPEStatus'
				WHERE TransactionId = '$transactionId'";
		mysql_query($sql);

		if ($status && $status->StatusType == BuckarooV3Base::StatusSuccess)
		{
			$this->Log('Transaction has a success status');
			//check transaction lines and update
		
		}
		$this->Log('Finished transaction processing');
	}



	public function DumpLines() { var_dump($this->lines); }

}

class TransactionLine
{
	/**
	 * @var MySQLDatabase
	 */
	private $db;
	private $id			= null;
	private $data;
	private $recurringPayment = null;
	
	public function __construct($id = null)
	{
		global $db;
		$this->db	= $db;
		if (!is_null($id))
			$this->load($id);
	}
	private function load($id)
	{
		$this->id	= (int)$id;

		
		$sql		= 'SELECT * FROM TransactionLine WHERE TransactionLineId = ' . $this->LineId();
		$result     = mysql_query($sql);		
		
		$teller = mysql_num_rows($result);				
		if ($teller == 0)		
		
		
			throw new TransactionException('TransactionLine with ID reference '.$id.' not found');
		$this->data	= mysql_fetch_object($result);
	}

	public function LineId()					{ return $this->id; }
	public function TransactionId()				{ return $this->data->TransactionId; }
	public function Amount()					{ return $this->data->Amount; }
	public function Description()				{ return $this->data->Description; }
	public function ProjectId()					{ return $this->data->ProjectId; }
	public function ChildId()					{ return $this->data->ChildId; }
	public function DonationPlanId()			{ return $this->data->DonationPlanId; }
	public function DonationPlanType()			{ return $this->data->DonationPlanType; }
	public function RecurringPaymentId()		{ return $this->data->RecurringPaymentId; }

	public function SetTransactionId($id)		{ $this->data->TransactionId	= $id; }
	public function SetAmount($amount)			{ $this->data->Amount			= $amount; }
	public function SetDescription($text)		{ $this->data->Description		= $text; }

	public function SetProjectId($id)			{ $this->data->ProjectId		= $id; }
	public function SetChildId($id)				{ $this->data->ChildId			= $id; }
	public function SetDonationPlanId($id)		{ $this->data->DonationPlanId	= $id; }
	public function SetDonationPlanType($type)	{ $this->data->DonationPlanType	= $type; }

	public function Set($column, $value)		{ $this->data->$column = $value; }

    /**
     * @return bool
     */
    public function hasRecurringPayment()
    {
        return ((int)$this->data->RecurringPaymentId > 0);
    }

	public function LinkRecurringPayment(RecurringPayment $rPayment, $updateLineData = false) {
		$this->recurringPayment			= $rPayment;
		$this->data->RecurringPaymentId	= $this->recurringPayment->Id();

		if ($updateLineData) {
			$this->data->Amount				= $this->recurringPayment->Amount();
			$this->data->Description		= $this->recurringPayment->Get('Description');
			$this->data->DonationPlanId		= $this->recurringPayment->Get('DonationPlanId');
			$this->data->DonationPlanType	= $this->recurringPayment->Get('DonationPlanType');
			if ($this->recurringPayment->Get('ChildId') > 0)
				$this->data->ChildId		= $this->recurringPayment->Get('ChildId');
		}
	}

	public function Save()
	{
		if (!isset($this->data->TransactionId) || !isset($this->data->Amount) || !is_numeric($this->data->Amount))
			throw new Exception('Cant save transaction without TrId or Amount');

		$dbArgs	= array(
			'Description'	=> $this->Description(),
			'TransactionId'	=> $this->TransactionId(),
			'Amount'		=> $this->Amount(),
		);
		//get additional fields
		if (property_exists($this->data, 'ProjectId') && $this->data->ProjectId)
			$dbArgs['ProjectId'] = $this->data->ProjectId;
		if (property_exists($this->data, 'ChildId') && $this->data->ChildId)
			$dbArgs['ChildId'] = $this->data->ChildId;
		if (property_exists($this->data, 'DonationPlanId') && $this->data->DonationPlanId)
			$dbArgs['DonationPlanId'] = $this->data->DonationPlanId;
		if (property_exists($this->data, 'DonationPlanType') && $this->data->DonationPlanType)
			$dbArgs['DonationPlanType'] = $this->data->DonationPlanType;
		if (property_exists($this->data, 'RecurringPaymentId') && $this->data->RecurringPaymentId)
			$dbArgs['RecurringPaymentId'] = $this->data->RecurringPaymentId;

		if (!is_null($this->id))
		{
			$tmp = array();
			foreach ($dbArgs as $field => $value) {
				$tmp[] = $field.' = %'.$field;
			}
			$sql = 'UPDATE	TransactionLine
					SET		'.implode(', ', $tmp).'
					WHERE	TransactionLineId = '.$this->LineId();
					
			
					
			$this->db->Query($sql, $dbArgs);
		}
		else
		{
			$dbArgs['TransactionId'] = $this->TransactionId();
			$sql = 'INSERT INTO TransactionLine ('.implode(', ', array_keys($dbArgs)).')
					VALUES ('.implode(', ', array_values($dbArgs)).')';
					//echo $sql; exit();		
			mysql_query($sql);
			

            //$this->db()->Query($sql, $data);
            $this->id = mysql_insert_id();
			
			if ( ($this->recurringPayment !== null) && ($this->recurringPayment instanceof RecurringPayment)) {
				$this->recurringPayment->RecurringUpdate($this->id);
			}
		}
	}
}
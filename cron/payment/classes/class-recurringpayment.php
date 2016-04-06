<?php
/**
 * @author      Bas Scholts
 * @copyright	Omines
 * @since       07/04/11 16:42
 * @package		System
 */
 
class RecurringPayment
{
	private $id;
	private $DataSet = false;

	/**
	 * @var MySQLDatabase
	 */
	private $db;

	static public function FindByDate($month = null, $day = null, $year = null)
	{
		if (is_null($year))		$year	= (int)date('Y');
		if (is_null($month))	$month	= (int)date('m');
		if (is_null($day))		$day	= (int)date('d');


		$date = $year.'-'.$month.'-'.$day;
		$sql = "SELECT RecurringPaymentId FROM RecurringPayment WHERE NextPaymentDate = '$date'";
		$result     = mysql_query($sql);
		$teller = mysql_num_rows($result);		
		
		
		$set	= array();
		while ($row = mysql_fetch_object($result))
			$set[$row->RecurringPaymentId]	= new RecurringPayment($row->RecurringPaymentId);
		return $set;
	}

	public function __construct($id = null)
	{

		if (!is_null($id))
			$this->load($id);
	}

	private function load($id)
	{

		
		$sql = "SELECT	rp.*, dp.AmountMonth, dp.AmountYear
				FROM	RecurringPayment rp
				LEFT	JOIN DonationPlan dp ON rp.DonationPlanId = dp.DonationPlanId
				WHERE	rp.RecurringPaymentId = '$id'";
		$result     = mysql_query($sql);
		$teller = mysql_num_rows($result);		
		
		if ($teller == 0)
			throw new Exception('RecurringPayment not found');
		$this->id	= (int)$id;
		$this->data	= mysql_fetch_object($result);
	}

	public function SetPaymentInfo($userId, $startDate, $dPlanId, $dPlanType, $childId, $desc, $amount = null ) {
		$this->data->UserId				= $userId;
		$this->data->StartDate			= $startDate;
		if ($dPlanId)
			$this->data->DonationPlanId	= $dPlanId;
		$this->data->DonationPlanType	= $dPlanType;
		if ($childId > 0)
			$this->data->ChildId		= $childId;
		$this->data->Description		= $desc;
		if ($amount)
			$this->data->DonationAmount	= $amount;
		$this->DataSet = true;
	}

	public function RecurringUpdate($transactionLineId) {
		if ($this->id > 0) {
			$nextTime = strtotime('+1 '.strtolower($this->data->DonationPlanType));
			//we attempt to avoid Jan 30 +1 month => Mar 02 problem
			if (date('j') > 28)
				$nextDate = date('Y-m-\2\8 00:00:00', $nextTime);
			else {
				$nextDate = date('Y-m-d 00:00:00', $nextTime);
			}
			$dbArgs = array(
				'Status'				=> 1,
				'LastTransactionLineId'	=> (int)$transactionLineId,
				'LastTransactionDate'	=> date('Y-m-d H:i:s'),
				'NextTransactionDate'	=> $nextDate
			);

			$tmp = array();
			foreach ($dbArgs as $column => $value)
				$tmp[] = '`'.$column.'` = '.$value;

			$sql = 'UPDATE	`RecurringPayment`
					SET		'.implode(', ', $tmp).'
					WHERE	`RecurringPaymentId` = '.$this->id;
			//$this->db->Query($sql, $dbArgs);
			mysql_query($sql);
		}
	}

	public function Save() {
		if ($this->id > 0) {
			$dbArgs = array(
				'Status'				=> $this->data->Status,
				'LastTransactionLineId'	=> $this->data->LastTransactionLineId,
				'LastTransactionDate'	=> $this->data->LastTransactionDate,
				'NextTransactionDate'	=> $this->data->NextTransactionDate
			);
			$tmp = array();
			foreach ($dbArgs as $column => $value)
				$tmp[] = '`'.$column.'` = '.$value;

			$sql = 'UPDATE	`RecurringPayment`
					SET		'.implode(', ', $tmp).'
					WHERE	`RecurringPaymentId` = '.$this->id;
			mysql_query($sql);
		}
		else if ($this->DataSet) {
			//insert
			$dbArgs = array(
				'UserId'			=> $this->data->UserId,
				'StartDate'			=> $this->data->StartDate,
				'DonationPlanType'	=> $this->data->DonationPlanType,
				'ChildId'			=> $this->data->ChildId,
				'Description'		=> $this->data->Description
			);
			if (isset($this->data->DonationPlanId) && $this->data->DonationPlanId)
				$dbArgs['DonationPlanId']	= $this->data->DonationPlanId;
			if (isset($this->data->DonationAmount) && ($this->data->DonationAmount > 0) )
				$dbArgs['DonationAmount']	= $this->data->DonationAmount;

			$dbColumns = array_keys($dbArgs);
			$dbValues = array_values($dbArgs);
			$sql = 'INSERT	INTO `RecurringPayment`
							('.implode(', ', $dbColumns).')
					VALUES	('.implode(', ', $dbValues).')';
			mysql_query($sql);
			$this->id = mysql_insert_id();
		}
		return false;
	}

	public function Amount()	{
		if ($this->data->DonationPlanId)
			$column	= 'Amount'.$this->data->DonationPlanType;
		else
			$column = 'DonationAmount';
		return	$this->data->$column;
	}
	public function Get($column)	{ return $this->data->$column; }

	public function Id()		{ return $this->id; }
}
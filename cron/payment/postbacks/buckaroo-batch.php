<?php
/**
 * @author      Olga Banasinska
 * @copyright	Omines
 * @since       23-9-11 13:08
 * @package		System
 */

$session->AutoStore	= false;

$fileName = SYS_PATH.'payment/logs/'.date('Ymd_His').'_buckaroo-notify.xml';
$payLog->Log('Received notification for Buckaroo batch handler, saved in '.$fileName);

// We use php://input to get the raw $_POST results.
$xml_post = file_get_contents('php://input');
file_put_contents($fileName, $xml_post);

$mail = new Email();
$mail->SetFrom('cron@pedulianak.org', 'Peduli Cron');
$mail->AddAddress('bas@omines.nl', 'Bas Scholts');
$mail->Subject = 'Buckaroo batch';
$mail->AddAttachment($fileName, 'buckaroo_notify.xml');
$mail->Body = 'New Buckaroo batch notification';
$mail->AltBody = 'New Buckaroo batch notification';
$mail->Send();

//TEST
/*$xml_post = '
<PayMessage Channel="[Channel]" VersionID="1.0">
	<Control Language="[Language]" Test="[Test]">
		<SenderSessionID>[SenderSessionID]</SenderSessionID>
		<Date>[Date]</Date> <Time>[Time]</Time>
		<MerchantID>[MerchantID]</MerchantID>
		<BatchID>[BatchID]</BatchID>
		<Signature>[Signature]</Signature>
		<MessageID>BatchProcessingResponse</MessageID>
		<ResponseURL SSL="[ResponseSSL]">[ResponseURL]</ResponseURL>
		<Notify>[Notify]</Notify>
	</Control>
	<Content>
		<BatchResult>
			<BatchStatus>[BatchStatus]</BatchStatus>
			<BatchStatusDescription>[BatchStatusDescription]</BatchStatusDescription>
			<BatchKey>[BatchKey]</BatchKey>
			<AdditionalInfo>[AdditionalInfo]</AdditionalInfo>
		</BatchResult>
		<Transaction>
			<CustomerID>[CustomerID]</CustomerID >
			<Invoice>[Invoice]</Invoice>
			<Amount Currency="[Currency]">[Amount]</Amount>
			<Description>[Description]</Description>
			<TransactionKey>[TransactionKey]</TransactionKey>
			<ResponseStatus>[ResponseStatus]</ResponseStatus>
			<ResponseStatusDescription>[ResponseStatusDescription]</ResponseStatusDescription>
			<AdditionalMessage>[Message]</AdditionalMessage>
		</Transaction>
		<Transaction>
			<CustomerID>[CustomerID2]</CustomerID >
			<Invoice>[Invoice2]</Invoice>
			<Amount Currency="[Currency2]">[Amount2]</Amount>
			<Description>[Description2]</Description>
			<TransactionKey>[TransactionKey2]</TransactionKey>
			<ResponseStatus>[ResponseStatus2]</ResponseStatus>
			<ResponseStatusDescription>[ResponseStatusDescription2]</ResponseStatusDescription>
			<AdditionalMessage>[Message2]</AdditionalMessage>
		</Transaction>
	</Content>
</PayMessage>';
//TEST

//get the actual stuff done
try {
	$xml	= new SimpleXMLElement($xml_post);
}
catch (Exception $e) {
	$payLog->Log('[Exception] '.$e->getMessage());
	die($e->getMessage());
}
$errors = 0;

$batchId = (integer)$xml->Control->BatchID;
if ($batchId < 1) {
	$payLog->Log('Batch number missing: '.$batchId);
	$errors++;
}
else {
	$batchId = $db->QueryScalar('SELECT BuckarooBatchId FROM BuckarooBatch WHERE BuckarooBatchId = '.(integer)$xml->Control->BatchID);
	if (!$batchId) {
		$payLog->Log('Batch number '.(string)$xml->Control->BatchID.' not found in database');
		$errors++;
	}
}

if ((string)$xml->Control->MerchantID != BPE_MERCHANT_KEY) {
	$payLog->Log('MerchantID doesn\'t match, expected '.BPE_MERCHANT_KEY.', received '.(string)$xml->Control->MerchantID);
	$errors++;
}

if (!$errors) {
	//we only check signature if Batch and Merchant ids are correct
	$controlAttrib		= $xml->Control->attributes();
	$signatureBuilder	= array(
		'MerchantID'	=> (string)$xml->Control->MerchantID,
		'BatchID'		=> (string)$batchId,
		'BatchKey'		=> (string)$xml->Content->BatchResult->BatchKey,
		'BatchStatus'	=> (string)$xml->Content->BatchResult->BatchStatus,
		'Test'			=> (string)$controlAttrib['Test'],
		'Date'			=> (string)$xml->Control->Date,
		'Time'			=> (string)$xml->Control->Time,
		'SecretKey'		=> BPE_SECRET_KEY
	);
	$signatureCheck = md5(implode('', $signatureBuilder));
	$received		= (string)$xml->Control->Signature;
	if ($signatureCheck != $received) {
		$payLog->Log('Received signature is not as expected.');
		$errors++;
	}
}

if (!$errors) {
	//we only really process anything if its about transactions
	if (isset($xml->Content->Transaction)) {
		foreach ($xml->Content->Transaction as $tr) {
			$trError	= 0;
			$attr		= $tr->Amount->attributes();
			$currency	= (string)$attr['Currency'];
			try {
				//get transaction and stuff
				$trx	= Transaction::FindByInvoice((string)$tr->Invoice);
				$bpe	= new BuckarooResponse($trx);

				if ((integer)$tr->CustomerID != (integer)$trx->UserId()) {
					$payLog->Log('Transaction '.(string)$tr->Invoice.': expected UserId '.$trx->UserId().', got CustomerID '.(integer)$tr->CustomerID);
					$trError++;
				}
				if ($currency != $bpe->Currency()) {
					$payLog->Log('Transaction '.(string)$tr->Invoice.': expected Currency '.$bpe->Currency().', got Currency '.$currency);
					$trError++;
				}
				if ((integer)$tr->Amount != $bpe->Amount()) {
					$payLog->Log('Transaction '.(string)$tr->Invoice.': expected Amount '.$bpe->Amount().', got Amount '.(integer)$tr->Amount);
					$trError++;
				}

				if (!$trError) {
					$bpe->BatchResponseUpdate((string)$tr->TransactionKey, (string)$tr->ResponseStatus);
				}
			}
			catch (BuckarooValidationException $e) {
				$payLog->Log('[BuckarooValidationException] '.$e->getMessage());
			}
			catch (TransactionException $e) {
				$payLog->Log('[TransactionException] '.$e->getMessage());
			}
			catch (Exception $e) {
				$payLog->Log('[Exception] '.$e->getMessage());
			}
		}
	}
}
*/
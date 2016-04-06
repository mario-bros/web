<?php
/**
 * @author      Bas Scholts
 * @copyright	Omines
 * @since       26-jan-2011 13:09:11
 * @package		System
 */

$payLog->Log('Received postback for Buckaroo handler');

try
{
    $trx = Transaction::FindByInvoice($request->PostParameter('brq_invoicenumber'));
    $payLog->Log('Transaction found via invoice, ID '.$trx->TransactionId());

    $bpe = new BuckarooV3Response($trx);
    $payLog->Log('BuckarooV3Response initialized');
    $bpe->ResponseUpdate($request->PostParameters());

    $payLog->Log('All done :)');
}
catch (BuckarooV3ValidationException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[BuckarooV3ValidationException] '.$e->getMessage());
	print $e->getMessage();
}
catch (BuckarooValidationException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[BuckarooValidationException] '.$e->getMessage());
	print $e->getMessage();
}
catch (TransactionException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[TransactionException] '.$e->getMessage());
	print $e->getMessage();
}
catch (Exception $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[Exception] '.$e->getMessage());
	print $e->getMessage();
}

/*
// Old code below:

$bpeInvoice		= $request->PostParameter('bpe_invoice');
$bpeTrxId		= $request->PostParameter('bpe_trx');
$bpeTimestamp	= $request->PostParameter('bpe_timestamp');
$bpeReference	= $request->PostParameter('bpe_reference');
$bpeCurrency	= $request->PostParameter('bpe_currency');
$bpeAmount		= $request->PostParameter('bpe_amount');
$bpeResult		= $request->PostParameter('bpe_result');
$bpeMode		= $request->PostParameter('bpe_mode');
$bpeSignature	= $request->PostParameter('bpe_signature2');

$payLog->Log('POST variables received');
foreach ($request->PostParameters() as $key => $value)
	$payLog->Log("\t".str_pad($key, 20).': '.$value);

try
{
	$trx		= Transaction::FindByInvoice($bpeInvoice);
	$payLog->Log('Transaction found via invoice, ID '.$trx->TransactionId());
	$bpe		= new BuckarooResponse($trx);
	$payLog->Log('BuckarooResponse initialized');

	// Verify currency
	if ($bpeCurrency != $bpe->Currency())
		throw new BuckarooValidationException('Submitted transaction currency does not match record in database');
	// Verify purchase amount
	if ($bpeAmount != $bpe->Amount())
		throw new BuckarooValidationException('Submitted transaction amount does not match record in database');
	// Verify signature
	if ($bpeSignature != $bpe->ResponseSignature($bpeTrxId, $bpeTimestamp, $bpeInvoice, $bpeReference, $bpeResult))
	{
		$payLog->Log('Signature received: '.$bpeSignature);
		$payLog->Log('Signature expected: '.$bpe->ResponseSignature($bpeTrxId, $bpeTimestamp, $bpeInvoice, $bpeReference, $bpeResult));
		throw new BuckarooValidationException('Transaction signature invalid');
	}

	$payLog->Log('Passed validation stage, proceeding to completion stage');
	$bpe->ResponseUpdate($bpeTrxId, $bpeResult, $bpeSignature);
	try {$trx->SendUpdated(); }
	catch (Exception $e) { $payLog->Log('Problem with sending email: '.$e->getMessage()); }
}
catch (BuckarooValidationException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[BuckarooValidationException] '.$e->getMessage());
	print $e->getMessage();
}
catch (TransactionException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[TransactionException] '.$e->getMessage());
	print $e->getMessage();
}
catch (Exception $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[Exception] '.$e->getMessage());
	print $e->getMessage();
}
*/
<?php
/**
* Peduli Anak
* (c) Omines Internetbureau B.V.
*
 * User: Bas Scholts
 * Date: 23-1-14
 * Time: 13:17
*/

$payLog->Log('Received postback for Buckaroo PUSH handler');

try
{
    $trx = Transaction::FindByInvoice($request->PostParameter('brq_invoicenumber'));
    $payLog->Log('Transaction found via invoice, ID '.$trx->TransactionId());

    $bpe = new BuckarooV3Response($trx);
    $bpe->setAmount((int)($trx->Amount() * 100));
    $bpe->ResponseUpdate($request->PostParameters());

    $payLog->Log('All done :)');
}
catch (BuckarooV3ValidationException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[BuckarooV3ValidationException] '.$e->getMessage());
	print $e->getMessage()."\n";
}
catch (BuckarooValidationException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[BuckarooValidationException] '.$e->getMessage());
	print $e->getMessage()."\n";
}
catch (TransactionException $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[TransactionException] '.$e->getMessage());
	print $e->getMessage()."\n";
}
catch (Exception $e)
{
	header('HTTP/1.0 404 Not Found', true, 404);
	header('Status: Not Found', true, 404);
	$payLog->Log('[Exception] '.$e->getMessage());
	print $e->getMessage()."\n";
}
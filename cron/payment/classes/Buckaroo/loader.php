<?php
/**
* Peduli Anak
* (c) Omines Internetbureau B.V.
*
 * User: Bas Scholts
 * Date: 23-1-14
 * Time: 17:11
*/

define('BUCKAROO_SOAP_CLIENT_PATH', dirname(__FILE__).'/');
define('BUCKAROO_PRIVATE_KEY', '/var/www/clients/client1/web1/web/wp-content/plugins/woocommerce-buckaroo-gateway/library/certificate/BuckarooPrivateKey.pem');
define('BUCKAROO_PRIVATE_THUMB', 'A49FF9F79781A85C73763BA49BCD6E61916C464C');


$db_username="c1peduliuser"; //database user name
$db_password="s_3c5RqDX";//database password
$db_database="c1pedulidb"; //database name
$db_host="localhost";

mysql_connect($db_host,$db_username,$db_password);
@mysql_select_db($db_database) or die ('Error: '.mysql_error ());

class SoapClientWSSEC extends SoapClient
{
	public function __doRequest ($request , $location , $action , $version , $one_way = 0 )
	{
		// Add code to inspect/dissect/debug/adjust the XML given in $request here
		$domDOC = new DOMDocument();
		$domDOC->loadXML($request);

		//Sign the document
		SignDomDocument($domDOC);

		// Uncomment the following line, if you actually want to do the request
		return parent::__doRequest($domDOC->saveXML($domDOC->documentElement), $location, $action, $version, $one_way);
	}
}

class Header
{
	public $MessageControlBlock;
}

class SecurityType
{
	public $Signature;
}

class SignatureType
{
	public $SignedInfo;
	public $SignatureValue;
	public $KeyInfo;
}

class SignedInfoType
{
	public $CanonicalizationMethod;
	public $SignatureMethod;
	public $Reference;
}

class ReferenceType
{
	public $Transforms;
	public $DigestMethod;
	public $DigestValue;
	public $URI;
	public $Id;
}


class TransformType
{
	public $Algorithm;
}

class DigestMethodType
{
	public $Algorithm;
}


class SignatureMethodType
{
	public $Algorithm;
}

class CanonicalizationMethodType
{
	public $Algorithm;
}

class MessageControlBlock
{
	public $Id;
	public $WebsiteKey;
	public $Culture;
	public $TimeStamp;
	public $Channel;
}

class Body
{
	public $Currency;
	public $AmountDebit;
 	public $AmountCredit;
 	public $Invoice;
 	public $Order;
 	public $Description;
 	public $ClientIP;
 	public $ReturnURL;
 	public $ReturnURLCancel;
 	public $ReturnURLError;
 	public $ReturnURLReject;
 	public $OriginalTransactionKey;
 	public $StartRecurrent;
 	public $Services;
 	public $CustomParameters;
 	public $AdditionalParameters;
}

class Services
{
 	public $Global;
 	public $Service;
}

class Service
{
	public $RequestParameter;
	public $Name;
	public $Action;
	public $Version;
}

class AdditionalParameter
{
    public $_;
    public $Name;

    public function __construct($key, $value)
    {
        $this->Name = $key;
        $this->_ = $value;
    }
}

class RequestParameter
{
 	public $_;
 	public $Name;
 	public $Group;
}

class IPAddress
{
 	public $_;
 	public $Type;
}

/**
 * @param $domDocument
 */
function SignDomDocument($domDocument)
{
	//create xPath
	$xPath = new DOMXPath($domDocument);

	//register namespaces to use in xpath query's
	$xPath->registerNamespace('wsse','http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd');
	$xPath->registerNamespace('sig','http://www.w3.org/2000/09/xmldsig#');
	$xPath->registerNamespace('soap','http://schemas.xmlsoap.org/soap/envelope/');

	//Set id on soap body to easily extract the body later.
	$bodyNodeList = $xPath->query('/soap:Envelope/soap:Body');
	$bodyNode = $bodyNodeList->item(0);
	$bodyNode->setAttribute('Id','_body');

	//Get the digest values
	$controlHash = CalculateDigestValue(GetCanonical(GetReference('_control', $xPath)));
	$bodyHash = CalculateDigestValue(GetCanonical(GetReference('_body', $xPath)));

	//Set the digest value for the control reference
	$Control = '#_control';
	$controlHashQuery = $query = '//*[@URI="'.$Control.'"]/sig:DigestValue';
	$controlHashQueryNodeset = $xPath->query($controlHashQuery);
	$controlHashNode = $controlHashQueryNodeset->item(0);
	$controlHashNode->nodeValue = $controlHash;

	//Set the digest value for the body reference
	$Body = '#_body';
	$bodyHashQuery = $query = '//*[@URI="'.$Body.'"]/sig:DigestValue';
	$bodyHashQueryNodeset = $xPath->query($bodyHashQuery);
	$bodyHashNode = $bodyHashQueryNodeset->item(0);
	$bodyHashNode->nodeValue = $bodyHash;

	//Get the SignedInfo nodeset
	$SignedInfoQuery = '//wsse:Security/sig:Signature/sig:SignedInfo';
	$SignedInfoQueryNodeSet = $xPath->query($SignedInfoQuery);
	$SignedInfoNodeSet = $SignedInfoQueryNodeSet->item(0);

	//Canonicalize nodeset
	$signedINFO = GetCanonical($SignedInfoNodeSet);

	//Get privatekey certificate
	$fp = fopen(BUCKAROO_PRIVATE_KEY, "r");
	$priv_key = fread($fp, 8192);
	fclose($fp);
	$pkeyid = openssl_get_privatekey($priv_key, '');

	//Sign signedinfo with privatekey
	$signature2 = null;
	openssl_sign($signedINFO, $signature2, $pkeyid);

	//Add signature value to xml document
	$sigValQuery = '//wsse:Security/sig:Signature/sig:SignatureValue';
	$sigValQueryNodeset = $xPath->query($sigValQuery);
	$sigValNodeSet = $sigValQueryNodeset->item(0);
	$sigValNodeSet->nodeValue = base64_encode($signature2);

	//Get signature node
	$sigQuery = '//wsse:Security/sig:Signature';
	$sigQueryNodeset = $xPath->query($sigQuery);
	$sigNodeSet = $sigQueryNodeset->item(0);

	//Create keyinfo element and Add public key to KeyIdentifier element
	$KeyTypeNode = $domDocument->createElementNS("http://www.w3.org/2000/09/xmldsig#","KeyInfo");
	$SecurityTokenReference = $domDocument->createElementNS('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd','SecurityTokenReference');
	$KeyIdentifier = $domDocument->createElement("KeyIdentifier");
	$KeyIdentifier->nodeValue = BUCKAROO_PRIVATE_THUMB;
	$KeyIdentifier->setAttribute('ValueType','http://docs.oasis-open.org/wss/oasis-wss-soap-message-security-1.1#ThumbPrintSHA1');
	$SecurityTokenReference->appendChild($KeyIdentifier);
	$KeyTypeNode->appendChild($SecurityTokenReference);
	$sigNodeSet->appendChild($KeyTypeNode);
}

//Get nodeset based on xpath and ID
function GetReference($ID, $xPath)
{
	$query = '//*[@Id="'.$ID.'"]';
	$nodeset = $xPath->query($query);
	$Object = $nodeset->item(0);

	return $Object;
}

//Canonicalize nodeset
function GetCanonical($Object)
{
	$output = $Object->C14N(true, false);
	return $output;
}

//Calculate digest value (sha1 hash)
function CalculateDigestValue($input)
{
	$digValueControl = base64_encode(pack("H*",sha1($input)));

	return $digValueControl;
}
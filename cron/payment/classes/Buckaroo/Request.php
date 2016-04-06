<?php
class BSOAP_Request
{

    private $soapClient = null;
    private $websiteKey = null;
    private $culture = 'nl-NL';
    private $testMode = false;

    public function __construct($websiteKey = null, $testMode = false)
    {
        
        $this->websiteKey = $websiteKey;
        $this->testMode = $testMode;

        $wsdl_url = "https://checkout.buckaroo.nl/soap/soap.svc?wsdl";
        $this->soapClient = new SoapClientWSSEC($wsdl_url, array('trace'=>1));
    }

    public function loadPem($filename)
    {
        $this->soapClient->loadPem($filename);
    }

    public function sendRequest($TransactionRequest, $type)
    {

        if (!$this->websiteKey) {
            throw new InvalidArgumentException('websiteKey not defined');
        }

        // Envelope and wrapper stuff
        $Header = new BSOAP_Header();
        $Header->MessageControlBlock = new BSOAP_MessageControlBlock();
        $Header->MessageControlBlock->Id = '_control';
        $Header->MessageControlBlock->WebsiteKey = $this->websiteKey;
        $Header->MessageControlBlock->Culture = $this->culture;

        $Header->MessageControlBlock->TimeStamp = time();
        $Header->MessageControlBlock->Channel = 'Web';
        $Header->Security = new BSOAP_SecurityType();
        $Header->Security->Signature = new BSOAP_SignatureType();
        $Header->Security->Signature->SignedInfo = new BSOAP_SignedInfoType();

        $Reference = new BSOAP_ReferenceType();
        $Reference->URI = '#_body';
        $Transform = new BSOAP_TransformType();
        $Transform->Algorithm = 'http://www.w3.org/2001/10/xml-exc-c14n#';
        $Reference->Transforms=array($Transform);

        $Reference->DigestMethod = new BSOAP_DigestMethodType();
        $Reference->DigestMethod->Algorithm = 'http://www.w3.org/2000/09/xmldsig#sha1';
        $Reference->DigestValue = '';

        $Transform2 = new BSOAP_TransformType();
        $Transform2->Algorithm = 'http://www.w3.org/2001/10/xml-exc-c14n#';
        $ReferenceControl = new BSOAP_ReferenceType();
        $ReferenceControl->URI = '#_control';
        $ReferenceControl->DigestMethod = new BSOAP_DigestMethodType();
        $ReferenceControl->DigestMethod->Algorithm = 'http://www.w3.org/2000/09/xmldsig#sha1';
        $ReferenceControl->DigestValue = '';
        $ReferenceControl->Transforms=array($Transform2);

        $Header->Security->Signature->SignedInfo->Reference = array($Reference,$ReferenceControl);
        $Header->Security->Signature->SignatureValue = '';

        $soapHeaders[] = new SOAPHeader('https://checkout.buckaroo.nl/PaymentEngine/', 'MessageControlBlock', $Header->MessageControlBlock);
        $soapHeaders[] = new SOAPHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', $Header->Security);
        $this->soapClient->__setSoapHeaders($soapHeaders);

        if ($this->testMode) {
            $this->soapClient->__SetLocation('https://testcheckout.buckaroo.nl/soap/');
        } else {
            $this->soapClient->__SetLocation('https://checkout.buckaroo.nl/soap/');
        }
        try
        {
            switch($type) {
                case 'invoiceinfo':
                    $this->soapClient->InvoiceInfo($TransactionRequest);
                    break;
                case 'transaction':
                    $this->soapClient->TransactionRequest($TransactionRequest);
                    break;
                case 'refundinfo':
                    $this->soapClient->RefundInfo($TransactionRequest);
                    break;
            }
        }
        catch (SoapFault $e)
        {
            print 'SOAPFault: '.$e->getMessage();
            print '<pre>';
            $dom = new DOMDocument();
            $dom->loadXML($this->soapClient->__getLastRequest());
            $dom->formatOutput = true;
            print htmlspecialchars($dom->saveXML());
//            var_dump(htmlspecialchars($this->soapClient->__getLastRequest()));
            print '</pre>';
            exit;
        }

        $return['response'] = $this->soapClient->__getLastResponse();
        $return['request']  = $this->soapClient->__getLastRequest();
        return $return;
    }

    /**
     * @param boolean $testMode
     * @return Request
     */
    public function setTestMode($testMode)
    {
        $this->testMode = $testMode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->testMode;
    }

    /**
     * @param string $culture
     * @return Request
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
        return $this;
    }

    /**
     * @return string
     */
    public function getCulture()
    {
        return $this->culture;
    }
}

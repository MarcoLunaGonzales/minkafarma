<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

class SoapMessage extends Message
{
	protected	$action;
	protected	$endpoint;
	
	public	$header = [];
	public	$body = [];
	
	public function __construct($endpoint)
	{
		$this->endpoint = $endpoint;
	}
	public function getHeaders()
	{
		return [
			"Content-type: text/xml;charset=\"utf-8\"",
			'Accept: text/xml',
			'Cache-Control: no-cache',
			'Pragma: no-cache',
			"SOAPAction: {$this->endpoint}{$this->action}",
			//'Content-Length: 0',
		];
	}
	public function validate()
	{
		
	}
	public function setAction($action, $data)
	{
		$this->body = ['soap:soap:' . $action => $data];
		$this->action = '/' . trim($action, '/');
	}
	public function setBody($data)
	{
		$this->body = $data; 
	}
	public function toXml($tag = null)
	{
		$this->namespaces = [
			//['name' => 'xmlns:soap', 'value' => 'http://schemas.xmlsoap.org/soap/envelope/', 'ns' => 'http://www.w3.org/2001/XMLSchema']
		];
		$this->xmlAllFields = false;
		$xml = parent::toXml('soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"');
		$body = $xml->addChild('soap:body', ''); 
		//$xml = new \SimpleXMLElement('<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" />');
		$this->buildChilds($body, $this->body, '');
		return $xml;
	}
}
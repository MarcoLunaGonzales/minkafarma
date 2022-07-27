<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionFactura;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudRecepcion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

require_once __DIR__ . SB_DS . 'functions.php';

class Connection
{
	const MOD_ELECTRONICA_ENLINEA = 1;
	const MOD_COMPUTARIZADA_ENLINEA = 2;
	const MOD_PORTAL_WEB = 3;
	
	const TIPO_EMISION_ONLINE = 1;
	const TIPO_EMISION_OFFLINE = 1;
	const TIPO_EMISION_MASIVA = 1;
	
	const TIPO_FACTURA_CREDITO_FISCAL = 1;
	const TIPO_FACTURA_SIN_CREDITO_FISCAL = 2;
	const TIPO_FACTURA_AJUSTE = 3;
	
	
	/**
	 * Código Único de Inicio de Sistemas
	 * @var string
	 */
	public	$cuis;
	/**
	 * Código Único de Facturación Diario
	 * @var string
	 */
	public	$cufd;
	/**
	 * Token delegado
	 * @var string
	 */
	protected	$token;
	// protected	$endpoint 	= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada';
	// protected	$wsdl 		= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	public $endpoint=conexionSiatUrl::endpoint;
	public $wsdl=conexionSiatUrl::wsdl;
	
	public		$debug = false;
	public		$modalidad 	= 'computarizada';
	public		$ambiente	= 'pruebas';
	
	public function __construct($cuis = null, $cufd = null, $token = null, $endpoint = null)
	{
		$this->cuis 	= $cuis;
		$this->cufd 	= $cufd;
		$this->token	= $token;
		if( $endpoint )
			$this->endpoint = $endpoint;
	}
	public function getCUIS()
	{
		$method = 'solicitudCuis';
		
	}
	public function getCUFD()
	{
		$method = 'solicitudCufd';
	}
	public function syncCatalog()
	{
		
	}
	public function sendDocument(Message $message)
	{
		if( empty($this->token) )
			throw new Exception('Invalid "Token delegado"');
		if( empty($this->cuis) )
			throw new Exception('Invalid "Codigo Unico de Inicio de Sistemas"');
		if( empty($this->cufd) )
			throw new Exception('Invalid "Codigo Unico de Facturacion Diario"');
		
		$xml 		= $message->toXml(null);
		$rawXml 	= $xml->asXML();
		$headers 	= $message->getHeaders();
		$headers[]	= 'Content-Length: ' . strlen($rawXml);
		$this->debug($headers, false);
		$this->debug($rawXml, true);
		//$request = new Request();
		//$response = $request->request($this->endpoint, $rawXml, 'POST', $headers);
		//print_r($response);
		
	}
	
	public function debug($str, $isXml = true)
	{
		if( !$this->debug )
			return true;
		
		sb_siat_debug($str, $isXml);
	}
}
<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;

use Exception;

class ServicioSiat
{
	const MOD_ELECTRONICA_ENLINEA = 1;
	const MOD_COMPUTARIZADA_ENLINEA = 2;
	const MOD_PORTAL_WEB = 3;
	
	const TIPO_EMISION_ONLINE = 1;
	const TIPO_EMISION_OFFLINE = 2;
	const TIPO_EMISION_MASIVA = 3;
	
	const TIPO_FACTURA_CREDITO_FISCAL = 1;
	const TIPO_FACTURA_SIN_CREDITO_FISCAL = 2;
	const TIPO_FACTURA_AJUSTE = 3;
	
	const AMBIENTE_PRODUCCION = 1;
	const AMBIENTE_PRUEBAS = 2;
	
	protected 	$wsdl;
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
	public	$codigoControl;
	/**
	 * Token delegado
	 * @var string
	 */
	protected	$token;
	
	public		$debug 			= false;
	public		$modalidad		= null;
	public		$ambiente		= self::AMBIENTE_PRUEBAS;
	public		$codigoSistema 	= null;
	public		$nit			= null;
	public 		$razonSocial	= null;
	
	public function __construct($cuis = null, $cufd = null, $token = null, $endpoint = null)
	{
		$this->cuis 	= $cuis;
		$this->cufd 	= $cufd;
		$this->token	= $token;
		if( $endpoint )
			$this->endpoint = $endpoint;
	}
	public function setConfig(array $data)
	{
		$this->codigoSistema 	= isset($data['codigoSistema']) ? $data['codigoSistema'] : null;
		$this->ambiente			= isset($data['ambiente']) ? $data['ambiente'] : null;
		$this->modalidad		= isset($data['modalidad']) ? $data['modalidad'] : null;
		$this->nit				= isset($data['nit']) ? $data['nit'] : null;
		$this->razonSocial		= isset($data['razonSocial']) ? $data['razonSocial'] : null;
		$this->token			= isset($data['tokenDelegado']) ? $data['tokenDelegado'] : $this->token;
	}
	/**
	 * Assign tokenDelegado
	 * 
	 * @param string $token
	 */
	public function setToken($token)
	{
		$this->token = $token;
	}
	public function validate()
	{
		if( empty($this->codigoSistema) )
			throw new Exception('Invalid config:codigoSistema');
		if( empty($this->ambiente) )
			throw new Exception('Invalid config:ambiente');
		if( $this->modalidad == null || empty($this->modalidad) )
			throw new Exception('Invalid config:modalidad');
		if( empty($this->nit) )
			throw new Exception('Invalid config:nit');
	}
	public function autenticar()
	{
		
	}
	protected function callAction($action, $data, $soapHeaders = [], $httpHeaders = [])
	{
		//var_dump(__METHOD__);
		
		$headers = array_merge([
			'apikey: TokenApi ' . $this->token,
		], $httpHeaders);
		//print_r($headers);
		$context =[
			'http' => [
				'header' => implode("\r\n", $headers),
			]
		];
		$stream_context = stream_context_create($context);		
		ini_set("default_socket_timeout", 7);
		if($action=='sincronizarListaProductosServicios'){//si son varios productos tarda mucho
			ini_set("default_socket_timeout",30);
		}
		$client = new \SoapClient($this->wsdl, ['trace' => 1, 'stream_context' => $stream_context]);
		try
		{
			/*
			$client->__setSoapHeaders([
				new \SoapHeader('soap', 'apikey', $tokenDelegado),
			]);
			*/
			
			$res = $client->__soapCall($action, $data);
			// print_r($client->__getLastRequestHeaders());
			// print_r($client->__getLastRequest());
			return $res;
		}
		catch(\SoapFault $e)
		{
			print_r($client->__getLastRequestHeaders());
			print_r($client->__getLastRequest());
			
			echo "($action)ERROR: ", $e->getMessage(), "\n\n";
		}
	}
	public function debug($str, $isXml = true)
	{
		if( !$this->debug )
			return true;
			
		sb_siat_debug($str, $isXml);
	}
}
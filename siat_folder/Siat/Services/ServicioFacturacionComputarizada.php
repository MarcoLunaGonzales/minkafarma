<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class ServicioFacturacionComputarizada extends ServicioFacturacion
{
	// protected	$wsdl 		= 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	public $wsdl=conexionSiatUrl::wsdlCodigo;
	
	public function __construct($cuis = null, $cufd = null, $token = null, $endpoint = null)
	{
		parent::__construct($cuis, $cufd, $token, $endpoint);
		//$this->modalidad = self::MOD_COMPUTARIZADA_ENLINEA;
	}
	
}
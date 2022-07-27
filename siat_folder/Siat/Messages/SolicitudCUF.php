<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;

use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;

class SolicitudCUF extends Message
{
	public		$codigoAmbiente;
	public		$codigoSistema;
	public		$nit;
	public		$codigoModalidad;
	public		$cuis;
	public		$codigoSucursal;
	public		$codigoPuntoVenta = 0;
	
	public function __construct()
	{
		
	}
	public function validate()
	{
		
	}
	public function toXml($rootTagName = '')
	{
		
	}
}
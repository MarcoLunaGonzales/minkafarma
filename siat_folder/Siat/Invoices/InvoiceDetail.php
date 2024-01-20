<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;

class InvoiceDetail extends Message
{
	public	$actividadEconomica;
	public	$codigoProductoSin;
	public	$codigoProducto;
	public	$descripcion;
	public	$cantidad;
	public	$unidadMedida;
	public	$precioUnitario;
	public	$montoDescuento;
	public	$subTotal;
	public	$numeroSerie=null;//se quito esto para educacion
	public	$numeroImei=null;//se quito esto para educacion
	public function __construct()
	{
		
		
		 		$this->unidadMedida	= 57;
		 
	}
	public function validate()
	{
		
	}
}


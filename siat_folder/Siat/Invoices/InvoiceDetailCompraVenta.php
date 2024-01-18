<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;


//este archivo se queda
class InvoiceDetailCompraVenta extends Message
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
	
	public	$numeroSerie;//se quito esto para educacion
	public	$numeroImei;//se quito esto para educacion

	public function __construct()
	{
		$this->unidadMedida	= 57;
	}
	public function validate()
	{
		
	}
}
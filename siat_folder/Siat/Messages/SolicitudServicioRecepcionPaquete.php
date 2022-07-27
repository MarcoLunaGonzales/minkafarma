<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use Exception;

class SolicitudServicioRecepcionPaquete extends SolicitudServicioRecepcionFactura
{
	public	$cafc;
	public	$cantidadFacturas;
	public	$codigoEvento;
	
	public function validate()
	{

		// echo $this->cantidadFacturas."*******";
		parent::validate();
		if( $this->cantidadFacturas <= 0 )
			throw new Exception('Invalid data "cantidadFacturas"');
		if( $this->cantidadFacturas > 500 )
			throw new Exception('Invalid data "cantidadFacturas", quantity is grater than allowed, 500 max');
	}
}
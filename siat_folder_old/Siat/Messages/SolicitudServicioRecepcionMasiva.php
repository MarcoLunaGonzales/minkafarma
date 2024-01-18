<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use Exception;

class SolicitudServicioRecepcionMasiva extends SolicitudServicioRecepcionFactura
{
	public	$cantidadFacturas;
	
	public function validate()
	{
		parent::validate();
		if( !$this->cantidadFacturas <= 0 )
			throw new Exception('Invalid data "cantidadFacturas"');
	}
}
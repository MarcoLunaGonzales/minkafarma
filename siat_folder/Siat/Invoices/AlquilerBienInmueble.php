<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatInvoice;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;

class AlquilerBienInmueble extends SiatInvoice
{
	
	public function __construct()
	{
		$this->nsData = [
			'computarizada' => [
				'name' => 'xsi:noNamespaceSchemaLocation',
				'value' => 'facturaElectronicaAlquilerBienInmueble.xsd',
				'ns' => 'http://www.w3.org/2001/XMLSchema-instance',
				'value' => 'facturaElectronicaAlquilerBienInmueble',
			],
			'enlinea' => [
				'name' => 'xsi:noNamespaceSchemaLocation',
				'value' => 'facturaComputarizadaAlquilerBienInmueble.xsd',
				'ns' => 'http://www.w3.org/2001/XMLSchema-instance',
				'tag' => 'facturaComputarizadaAlquilerBienInmueble',
			]
		];
		$this->codigoDocumentoSector = DocumentTypes::FACTURA_ALQUILER_INMUEBLES;
	}
	public function validate()
	{
		
	}
}

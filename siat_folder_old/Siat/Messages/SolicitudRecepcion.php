<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use Exception;

class SolicitudRecepcion extends Message
{
	/**
	 * @var int
	 */
	public	$codigoAmbiente;
	public	$codigoDocumentoSector;
	public	$codigoEmision = 2;
	public	$codigoModalidad = 1;
	public	$codigoPuntoVenta = 1;
	public	$codigoSistema;
	public	$codigoSucursal = 0;
	public	$cufd;
	public	$cuis;
	public	$nit;
	public	$tipoFacturaDocumento;
	
	public function validate()
	{
		$tag = '['. basename(str_replace('\\', SB_DS, self::class)).'] ERROR: ';
		
		if( !is_int($this->codigoAmbiente) )
			throw new Exception($tag . 'Invalid data, "codigoAmbiente", required integer');
		if( !is_int($this->codigoDocumentoSector) )
			throw new Exception($tag . 'Invalid data, "codigoDocumentoSector", required integer');
		if( !is_int($this->codigoEmision) )
			throw new Exception($tag . 'Invalid data, "codigoEmision", required integer');
		if( !is_int($this->codigoModalidad) )
			throw new Exception($tag . 'Invalid data, "codigoModalidad", required integer');
		if( empty($this->codigoSistema) )
			throw new Exception($tag . 'Invalid data, "codigoSistema", required non empty string');
		if( !is_int($this->codigoSucursal) )
			throw new Exception($tag . 'Invalid data, "codigoSucursal", required integer');
		if( empty($this->cufd) )
			throw new Exception($tag . 'Invalid data, "cufd", required non empty string');
		if( empty($this->cuis) )
			throw new Exception($tag . 'Invalid data, "cuis", required non empty string');
		if( !is_int($this->nit) )
			throw new Exception($tag . 'Invalid data, "nit", required long ', $this->nit);
		if( !is_int($this->tipoFacturaDocumento) )
			throw new Exception($tag . 'Invalid data, "tipoFacturaDocumento", required integer');
	}
}
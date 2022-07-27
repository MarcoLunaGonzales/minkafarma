<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class ServicioFacturacionCodigos extends ServicioSiat
{
	// public $wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl';
	public $wsdl=conexionSiatUrl::wsdlCodigo;
	
	public function cuis($codigoPuntoVenta = 0, $codigoSucursal = 0)
	{
		$data = [
			[
				'SolicitudCuis' => [
					'codigoAmbiente'	=> $this->ambiente,
					'codigoModalidad'	=> $this->modalidad,
					'codigoPuntoVenta'	=> $codigoPuntoVenta,
					'codigoSistema'		=> $this->codigoSistema,
					'codigoSucursal'	=> $codigoSucursal,
					'nit'				=> $this->nit,
				]
			]
		];
		list(,$action) = explode('::', __METHOD__);
		$res = $this->callAction($action, $data);
		// print_r($data);
		return $res;
	}
	public function cufd($codigoPuntoVenta = 0, $codigoSucursal = 0)
	{
		list(,$action) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudCufd' => [
					'codigoAmbiente'	=> $this->ambiente,
					'codigoModalidad'	=> $this->modalidad,
					'codigoPuntoVenta'	=> $codigoPuntoVenta,
					'codigoSistema'		=> $this->codigoSistema,
					'codigoSucursal'	=> $codigoSucursal,
					'cuis'				=> $this->cuis,
					'nit'				=> $this->nit,
				]
			]
		];
		$res = $this->callAction($action, $data);
		
		return $res;
	}
	public function verificarNit($codigoSucursal = 0, $nitCliente = 0)
	{
		list(,$action) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudVerificarNit'  => [
					'codigoAmbiente'	 => $this->ambiente,
					'codigoModalidad'	 => $this->modalidad,
					// 'codigoPuntoVenta'	=> $codigoPuntoVenta,
					'codigoSistema'		 => $this->codigoSistema,
					'codigoSucursal'	 => $codigoSucursal,
					'cuis'				 => $this->cuis,
					'nit'				 => $this->nit,
					'nitParaVerificacion'=> $nitCliente
				]
			]
		];
		$res = $this->callAction($action, $data);
		
		return $res;
	}
}
<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class ServicioOperaciones extends ServicioSiat
{
	// protected $wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl';
	public $wsdl=conexionSiatUrl::wsdlOperaciones;
	protected function buildData($fecha)
	{
		$data = [
			[
				'SolicitudConsultaEvento' => [
					'codigoAmbiente' 	=> $this->ambiente,
					'codigoPuntoVenta'	=> 0,
					'codigoSistema'		=> $this->codigoSistema,
					'codigoSucursal'	=> 0,
					'cufd'				=> $this->cufd,
					'cuis'				=> $this->cuis,
					'nit'				=> $this->nit,
					'fechaEvento'		=> $fecha,
				]
			]
		];
		
		return $data;
	}
	
	public function consultaEventoSignificativo($fecha)
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData($fecha));
		
		return $res;
	}
	public function registroEventoSignificativo($codigoMotivoEvento, $descripcion, $cufdEvento, $fechaInicio, $fechaFin, $codigoSucursal = 0,$codigoPuntoVenta=0)
	{
		list(, $method) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudEventoSignificativo' => [
					'codigoAmbiente' 		=> $this->ambiente,
					'codigoMotivoEvento'	=> $codigoMotivoEvento,
					'codigoPuntoVenta'		=> $codigoPuntoVenta,
					'codigoSistema'			=> $this->codigoSistema,
					'codigoSucursal'		=> $codigoSucursal,
					'cufd'					=> $this->cufd,
					'cufdEvento'			=> $cufdEvento,
					'cuis'					=> $this->cuis,
					'descripcion'			=> $descripcion,
					'fechaHoraInicioEvento'	=> $fechaInicio,
					'fechaHoraFinEvento'	=> $fechaFin,
					'nit'					=> $this->nit,
					
				]
			]
		];
		// print_r($data);
		$res = $this->callAction($method, $data);
		
		return $res;
	}
	public function registroPuntoVenta($codigoSucursal = 0,$tipoPuntoVenta=0,$nombrePuntoVenta='',$descripcion = '')
	{
		list(, $method) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudRegistroPuntoVenta' => [
					'codigoAmbiente' 		=> $this->ambiente,
					'codigoModalidad'		=> $this->modalidad,
					'codigoSistema'			=> $this->codigoSistema,
					'codigoSucursal'		=> $codigoSucursal,
					'codigoTipoPuntoVenta'	=> $tipoPuntoVenta,
					'cuis'					=> $this->cuis,
					'descripcion'			=> $descripcion,
					'nit'					=> $this->nit,
					'nombrePuntoVenta'		=> $nombrePuntoVenta,
				]
			]
		];
		// var_dump($data);
		// echo "<br><br>****";
		$res = $this->callAction($method, $data);
		
		return $res;
	}

	public function cierrePuntoVenta($codigoSucursal = 0,$codigoPuntoVenta=0)
	{
		list(, $method) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudCierrePuntoVenta' => [
					'codigoAmbiente' 		=> $this->ambiente,
					'codigoModalidad'		=> $this->modalidad,
					'codigoSistema'			=> $this->codigoSistema,
					'codigoSucursal'		=> $codigoSucursal,
					'codigoPuntoVenta'		=> $codigoPuntoVenta,
					'cuis'					=> $this->cuis,
					'nit'					=> $this->nit,
				]
			]
		];
		$res = $this->callAction($method, $data);
		
		return $res;
	}

	public function consultaPuntoVenta($codigoSucursal = 0)
	{
		list(, $method) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudConsultaPuntoVenta' => [
					'codigoAmbiente' 		=> $this->ambiente,
					'codigoModalidad'		=> $this->modalidad,
					'codigoSistema'			=> $this->codigoSistema,
					'codigoSucursal'		=> $codigoSucursal,
					'codigoTipoPuntoVenta'	=> $tipoPuntoVenta,
					'cuis'					=> $this->cuis,
					'nit'					=> $this->nit
				]
			]
		];
		$res = $this->callAction($method, $data);
		
		return $res;
	}



}
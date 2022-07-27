<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;


class ServicioFacturacionSincronizacion extends ServicioSiat
{
	// protected $wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl';
	public $wsdl=conexionSiatUrl::wsdlSincronizacion;
	
	protected function buildData()
	{
		$data = [
			[
				'SolicitudSincronizacion' => [
					'codigoAmbiente' 	=> $this->ambiente,
					'codigoPuntoVenta'	=> 0,
					'codigoSistema'		=> $this->codigoSistema,
					'codigoSucursal'	=> 0,
					'cuis'				=> $this->cuis,
					'nit'				=> $this->nit,
				]
			]
		];
		
		return $data;
	}
	public function sincronizarParametricaMotivoAnulacion()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarListaActividadesDocumentoSector()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoDocumentoSector()
	{
		list(, $method) = explode('::', __METHOD__);
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTiposFactura()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarListaMensajesServicios()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function verificarComunicacion()
	{
		list(, $method) = explode('::', __METHOD__);
		$res = $this->callAction($method, [[]]);
		return $res;
	}
	public function sincronizarParametricaEventosSignificativos()
	{
		list(, $method) = explode('::', __METHOD__);
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoPuntoVenta()
	{
		list(, $method) = explode('::', __METHOD__);
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarListaProductosServicios()
	{
		list(, $method) = explode('::', __METHOD__);
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoMoneda()
	{
		/*
		$servCodigos = new ServicioFacturacionCodigos(null, null, Config::$tokenDelegado);
		
		$servCodigos->ambiente		= Config::$ambiente;
		$servCodigos->modalidad		= Config::$modalidad;
		$servCodigos->codigoSistema = Config::$codigoSistema;
		$servCodigos->nit			= Config::$nit;
		
		$res = $servCodigos->cuis();
		//print_r($res);
		$cuis = $res->RespuestaCuis->codigo;
		$servCodigos->cuis = $cuis;
		*/
		list(, $method) = explode('::', __METHOD__);
		$res = $this->callAction($method, $this->buildData());
		return $res->RespuestaListaParametricas;
	}
	public function sincronizarActividades()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoEmision()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoDocumentoIdentidad()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarFechaHora()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarListaLeyendasFactura()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaPaisOrigen()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoHabitacion()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaTipoMetodoPago()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
	public function sincronizarParametricaUnidadMedida()
	{
		list(, $method) = explode('::', __METHOD__);
		
		$res = $this->callAction($method, $this->buildData());
		return $res;
	}
}

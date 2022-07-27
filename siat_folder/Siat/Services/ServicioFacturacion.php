<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionFactura;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\SiatInvoice;
use Exception;
use SoapFault;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionMasiva;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionPaquete;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioValidacionRecepcionPaquete;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioAnulacionFactura;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class ServicioFacturacion extends ServicioSiat
{
	public function buildInvoiceXml(SiatInvoice $invoice)
	{
		return $invoice->toXml(null, true)->asXML();
	}
	public function recepcionFactura(SiatInvoice $factura, $tipoEmision = SiatInvoice::TIPO_EMISION_ONLINE, $tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL)
	{
		//echo "jajaja";
		$factura->cabecera->razonSocialEmisor	= $this->razonSocial;
		$factura->cabecera->nitEmisor 	= $this->nit;
		$factura->cabecera->cufd		= $this->cufd;

		//$sucursalNro, $modalidad, $tipoEmision, $tipoFactura, $codigoControl
		//print_r($factura);
		//echo "<br>CODE:".(int)$factura->cabecera->codigoSucursal." ".$this->modalidad." ".$tipoEmision." ".$tipoFactura." ".$this->codigoControl;
		$factura->buildCuf((int)$factura->cabecera->codigoSucursal, $this->modalidad, $tipoEmision, $tipoFactura, $this->codigoControl);

		//die($factura->cuf);
		$factura->validate();

		$facturaXml = $this->buildInvoiceXml($factura);		
		//print_r($facturaXml);
		$this->debug($facturaXml, 1);
		//print_r($factura);
		// file_put_contents('factura.xml', $facturaXml);
		// file_put_contents('siat_folder/Siat/temp/Facturas-XML/'.$factura->cabecera->cuf.".xml", $facturaXml);
		// var_dump($facturaXml);die;
		
		if($tipoEmision!=2){
			$solicitud = new SolicitudServicioRecepcionFactura();
			$solicitud->cufd 					= $this->cufd;
			$solicitud->cuis					= $this->cuis;
			$solicitud->codigoSistema			= $this->codigoSistema;
			$solicitud->nit						= $this->nit;
			$solicitud->codigoModalidad			= $this->modalidad;
			$solicitud->codigoAmbiente 			= $this->ambiente;
			$solicitud->codigoPuntoVenta 		= $factura->cabecera->codigoPuntoVenta;// PARA COMPLETAR CON LA FACTURACION TIPO VENTA 1 COBOFAR SA		
			$solicitud->codigoDocumentoSector 	= $factura->cabecera->codigoDocumentoSector; //DocumentTypes::FACTURA_COMPRA_VENTA; //ERROR: no acepta 1
			$solicitud->tipoFacturaDocumento	= self::TIPO_FACTURA_CREDITO_FISCAL;
			$solicitud->codigoEmision			= self::TIPO_EMISION_ONLINE;
			$solicitud->fechaEnvio				= date("Y-m-d\TH:i:s.v");//$factura->cabecera->fechaEmision;//


			// se deben setear los parametros
			$solicitud->codigoSucursal=(int)$factura->cabecera->codigoSucursal;


			//print_r($solicitud);
			//die($solicitud->fechaEnvio);
			/*
			$zh = gzopen('factura.xml.zip', 'w9');
			gzwrite($zh, $facturaXml);
			gzclose($zh);
			*/
			$solicitud->setBuffer($facturaXml, true);
			$solicitud->validate();
			
			try
			{
				$data = [
					$solicitud->toArray()
				];
				//$this->debug($factura->toArray(), 0);
				//$this->debug($solicitud->toArray(), 0);
				// $this->wsdl = $factura->getEndpoint($this->modalidad, $this->ambiente);
				$this->wsdl = conexionSiatUrl::wsdlCompraVenta;
				// echo "<br><br>";
				// var_dump($data);
				$res = $this->callAction('recepcionFactura', $data);			
				//print_r($res);
				return array($res,$factura->cabecera->fechaEmision,$factura->cabecera->cuf,$facturaXml,$solicitud);
			}
			catch(\SoapFault $e)
			{	
				//echo "askdjaslkdjasl";
				return array(null,$factura->cabecera->fechaEmision,$factura->cabecera->cuf,$facturaXml);
				//print_r($e->getMessage());
				//throw new Exception($e->getMessage());
			}			
		}else{ // cuando esta en offline no guardar envio
			return array(null,$factura->cabecera->fechaEmision,$factura->cabecera->cuf,$facturaXml);
		}
	}
	/**
	 * 
	 * @param SiatInvoice[] $facturas
	 * @param int $tipoEmision
	 * @param int $tipoFactura
	 */
	public function recepcionMasivaFactura(array $facturas, $tipoEmision = SiatInvoice::TIPO_EMISION_ONLINE, $tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL)
	{
		try
		{
			// if( !count($facturas) )
			// 	throw new Exception('Invalid siat invoices, the service requires atleast one invoice');
			
			$invoiceFiles = [];
			//##validate invoices
			echo "NUMEROS:".count($facturas)."<br>";
			$solicitud = new SolicitudServicioRecepcionMasiva();
			$conta=0;
			foreach($facturas as $factura)
			{
				// $factura->cufd = $this->cufd;
				// $factura->buildCuf($this->nit, 0, $this->modalidad, $tipoEmision, $tipoFactura);
				// $factura->validate();
				// $facturaXml = $this->buildInvoiceXml($factura);
				$factura->cabecera->razonSocialEmisor	= $this->razonSocial;
				$factura->cabecera->nitEmisor 	= $this->nit;
				$factura->cabecera->cufd		= $this->cufd;
				//$factura->buildCuf(0, $this->modalidad, $tipoEmision, $tipoFactura, $this->codigoControl);
				$factura->buildCuf(0, $this->modalidad, 3, $tipoFactura, $this->codigoControl);
				//die($factura->cuf);
				$factura->validate();
				$facturaXml = $this->buildInvoiceXml($factura);
				//$facturaXml = file_get_contents('factura.xml');
				$this->debug($facturaXml, 1);

				$filename = MOD_SIAT_TEMP_DIR . SB_DS . sprintf("factura-%d-%d".$conta.time().".xml", $factura->cabecera->nitEmisor, $factura->cabecera->numeroFactura);
				file_put_contents($filename, $facturaXml);
				$invoiceFiles[$conta] = $filename;
			$solicitud->codigoPuntoVenta 		= $factura->cabecera->codigoPuntoVenta;// PARA COMPLETAR CON LA 

			$solicitud->codigoDocumentoSector 	= $factura->cabecera->codigoDocumentoSector; //DocumentTypes::FACTURA_COMPRA_VENTA; //ERROR: no acepta 1
			// $this->wsdl = $factura->getEndpoint($this->modalidad, $this->ambiente);
			$conta++;
			}

			echo "CONTADORES:".$conta."<br>";

			// $this->wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';
			$this->wsdl = conexionSiatUrl::wsdlCompraVenta;


			$solicitud->codigoPuntoVenta 		= 1;// PARA COMPLETAR CON LA 

			$solicitud->codigoDocumentoSector 	= 1; //
			
			$solicitud->cantidadFacturas		= count($facturas);
			$solicitud->cufd 					= $this->cufd;
			$solicitud->cuis					= $this->cuis;
			$solicitud->codigoSistema			= $this->codigoSistema;
			$solicitud->nit						= $this->nit;
			$solicitud->codigoModalidad			= $this->modalidad;
			$solicitud->codigoAmbiente 			= $this->ambiente;
			$solicitud->tipoFacturaDocumento	= self::TIPO_FACTURA_CREDITO_FISCAL;
			$solicitud->codigoEmision			= 3;//self::TIPO_EMISION_ONLINE;

			// $solicitud->codigoDocumentoSector 	= DocumentTypes::FACTURA_COMPRA_VENTA; //ERROR: no acepta 1
			// $solicitud->tipoFacturaDocumento	= DocumentTypes::FACTURA_COMPRA_VENTA;
			$solicitud->fechaEnvio				= date("Y-m-d\TH:i:s.m0");
			//##build tarball for invoice files
			$solicitud->setBufferFromFiles($invoiceFiles);
			//$solicitud->validate();
			$data = [
				$solicitud->toArray()
			];


			print_r($data);
			$res = $this->callAction('recepcionMasivaFactura', $data);
			return $res;
		}
		catch(SoapFault $e)
		{
			echo $e->getMessage();
		}
	}
	/**
	 * 
	 */
	public function recepcionPaqueteFactura(array $facturas, $codigoEvento, $tipoEmision = SiatInvoice::TIPO_EMISION_OFFLINE, 
		$tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL, $cafc = null)
	{
		try
		{
			if( !count($facturas) )
				throw new Exception('Invalid siat invoices, the service requires atleast one invoice');
			$xmlInvoices = [];
			//##validate invoices
			$cont=0;
			foreach($facturas as $factura)
			{	
				// print_r($factura);
				$factura->validate();
				// $this->wsdl = $factura->getEndpoint($this->modalidad, $this->ambiente);
				$xmlInvoices[$cont] = $this->buildInvoiceXml($factura);
				$cont++;
			}
			$this->wsdl = conexionSiatUrl::wsdlCompraVenta;
			 // print_r($xmlInvoices);
			
			$solicitud = new SolicitudServicioRecepcionPaquete();
			$solicitud->cafc					= $cafc;
			$solicitud->cantidadFacturas		= count($facturas);
			$solicitud->codigoEvento			= $codigoEvento;
			$solicitud->cufd 					= $this->cufd;
			$solicitud->cuis					= $this->cuis;

			$solicitud->codigoSucursal			= $this->codigoSucursal;
			$solicitud->codigoPuntoVenta		= $this->codigoPuntoVenta;

			// $solicitud->codigoEmision			= self::TIPO_EMISION_ONLINE;

			
			$solicitud->codigoSistema			= $this->codigoSistema;

			$solicitud->nit						= $this->nit;
			$solicitud->codigoModalidad			= $this->modalidad;
			$solicitud->codigoAmbiente 			= $this->ambiente;
			$solicitud->codigoDocumentoSector 	= DocumentTypes::FACTURA_COMPRA_VENTA; //ERROR: no acepta 1
			$solicitud->tipoFacturaDocumento	= $tipoFactura;
			// $solicitud->fechaEnvio				= date("Y-m-d\TH:i:s.m0");
			$solicitud->fechaEnvio				= $this->fechaEnvio;
			//$solicitud->setBuffer($xmlInvoices);
			$solicitud->setBufferFromInvoicesXml($xmlInvoices);
			 $solicitud->validate();
			
			
			$data = [
				$solicitud->toArray()
			];
			// print_r($data);
			$res = $this->callAction('recepcionPaqueteFactura', $data);

			 // print_r($res);
			return $res;
		}
		catch(SoapFault $e)
		{
			echo $e->getMessage();
		}
	}

	public function validacionRecepcionPaqueteFactura($codigoRecepcion, $tipoEmision = SiatInvoice::TIPO_EMISION_OFFLINE,$tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL)
	{
		try
		{
			
			$solicitud = new SolicitudServicioValidacionRecepcionPaquete();
			
			$solicitud->codigoAmbiente			= $this->ambiente;
			$solicitud->codigoDocumentoSector	= DocumentTypes::FACTURA_COMPRA_VENTA; //ERROR: no acepta 1
			$solicitud->codigoEmision			= $tipoEmision;
			$solicitud->codigoModalidad 		= $this->modalidad;
			$solicitud->codigoSistema			= $this->codigoSistema;
			$solicitud->cufd					= $this->cufd;
			$solicitud->cuis 					= $this->cuis;
			$solicitud->codigoSucursal			= $this->codigoSucursal;
			$solicitud->codigoPuntoVenta		= $this->codigoPuntoVenta;
			$solicitud->nit 					= $this->nit;
			$solicitud->tipoFacturaDocumento	= $tipoFactura;
			$solicitud->codigoRecepcion			= $codigoRecepcion;
			
			$data = [
				$solicitud->toArray()
			];
			 // print_r($data);
			$res = $this->callAction('validacionRecepcionPaqueteFactura', $data);
			 
			return $res;
		}
		catch(SoapFault $e)
		{
			echo $e->getMessage();
		}
	}

	public function validarFacturaMasiva($codigoRecepcion, $tipoEmision = SiatInvoice::TIPO_EMISION_ONLINE, $tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL)
	{
		try
		{
			
			$solicitud = new SolicitudServicioValidacionRecepcionMasiva();
			// $this->wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';
			$this->wsdl = conexionSiatUrl::wsdlCompraVenta;
			$solicitud->codigoPuntoVenta 		= 1;// PARA COMPLETAR CON LA 

			$solicitud->codigoDocumentoSector 	= 1; //
			
			$solicitud->codigoRecepcion		= $codigoRecepcion;
			$solicitud->cufd 					= $this->cufd;
			$solicitud->cuis					= $this->cuis;
			$solicitud->codigoSistema			= $this->codigoSistema;
			$solicitud->nit						= $this->nit;
			$solicitud->codigoModalidad			= $this->modalidad;
			$solicitud->codigoAmbiente 			= $this->ambiente;
			$solicitud->tipoFacturaDocumento	= self::TIPO_FACTURA_CREDITO_FISCAL;
			$solicitud->codigoEmision			= 3;//self::TIPO_EMISION_ONLINE;

			// $solicitud->codigoDocumentoSector 	= DocumentTypes::FACTURA_COMPRA_VENTA; //ERROR: no acepta 1
			// $solicitud->tipoFacturaDocumento	= DocumentTypes::FACTURA_COMPRA_VENTA;
			//$solicitud->fechaEnvio				= date("Y-m-d\TH:i:s.m0");
			$data = [
				$solicitud->toArray()
			];

			print_r($data);
			$res = $this->callAction('validacionRecepcionMasivaFactura', $data);
			return $res;
		}
		catch(SoapFault $e)
		{
			echo $e->getMessage();
		}
	}
	public function anularFacturaEnviada($cuf,$codigoPuntoVenta,$codigoSucursal)
	{
		try
		{

			// echo "aqui";
			$solicitud = new SolicitudServicioAnulacionFactura();
			// $this->wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';
			$this->wsdl = conexionSiatUrl::wsdlCompraVenta;
			

			$solicitud->codigoPuntoVenta 		= $codigoPuntoVenta;// PARA COMPLETAR CON LA
			 $solicitud->codigoSucursal			=$codigoSucursal;
			$solicitud->codigoDocumentoSector 	= 1; //
			// $solicitud->codigoDocumentoSector 	= DocumentTypes::FACTURA_COMPRA_VENTA; //instanciar
			$solicitud->cuf						= $cuf;
			$solicitud->codigoMotivo			= 1;//motivo anulacion por defecto 1 //FACTURA MAL EMITIDA
			$solicitud->cufd 					= $this->cufd;
			$solicitud->cuis					= $this->cuis;
			$solicitud->codigoSistema			= $this->codigoSistema;
			$solicitud->nit						= $this->nit;
			$solicitud->codigoModalidad			= $this->modalidad;
			$solicitud->codigoAmbiente 			= $this->ambiente;
			$solicitud->tipoFacturaDocumento	= self::TIPO_FACTURA_CREDITO_FISCAL;
			$solicitud->codigoEmision			= self::TIPO_EMISION_ONLINE;
			$data = [
				$solicitud->toArray()
			];

			// print_r($data);
			$res = $this->callAction('anulacionFactura', $data);
			// print_r($res);
			return $res;
		}
		catch(SoapFault $e)
		{
			echo $e->getMessage();
		}
	}


	public function verificacionEstadoFactura($codigoSucursal = 0,$codigoPuntoVenta = 0,$cufd,$cuf)
	{
		 // $this->wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl';
		$this->wsdl = conexionSiatUrl::wsdlCompraVenta;
		list(,$action) = explode('::', __METHOD__);
		$data = [
			[
				'SolicitudServicioVerificacionEstadoFactura'  => [
					'codigoAmbiente'	 => $this->ambiente,
					'codigoModalidad'	 => $this->modalidad,
					'codigoPuntoVenta'	=> $codigoPuntoVenta,
					'codigoSistema'		 => $this->codigoSistema,
					'codigoSucursal'	 => $codigoSucursal,
					'cuis'				 => $this->cuis,
					'nit'				 => $this->nit,					
					'tipoFacturaDocumento'=>self::TIPO_FACTURA_CREDITO_FISCAL,
					'codigoDocumentoSector'=>1,
					'codigoEmision'=>self::TIPO_EMISION_ONLINE,
					'cufd'=>$cufd,
					'cuf'=>$cuf
				]
			]
		];
		$res = $this->callAction($action, $data);
		
		return $res;
	}
}

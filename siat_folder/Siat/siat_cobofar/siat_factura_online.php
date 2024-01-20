<?php
if ( ! defined('BASEPATH')) define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);
//require_once '../autoload.php';
require_once dirname(__DIR__) . SB_DS . 'autoload.php'; // si o si
require_once dirname(__DIR__) . SB_DS . 'functions.php';
//sb_siat_autload();
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SoapMessage;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\CompraVenta;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\SiatInvoice;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionComputarizada;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\InvoiceDetail;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\InvoiceDetailCompraVenta;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioOperaciones;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionElectronica;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\ElectronicaCompraVenta;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class FacturaOnline
{
	// protected $endpoint = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada';
	// protected	$wsdl = 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	public $endpoint = conexionSiatUrl::endpoint;
	public $wsdl = conexionSiatUrl::wsdl;
	/**
	 * 
	 * @return SiatConfig
	 */
	public static function buildConfig()
	{
		include dirname(__DIR__). SB_DS ."conexionSiat.php";	
		
		//echo $siat_codigoSistema;	
		return new SiatConfig([
			'nombreSistema'	=> $siat_nombreSistema,
			'codigoSistema'	=> $siat_codigoSistema,
			'tipo' 			=> $siat_tipo,
			'nit'			=> $siat_nit,
			'razonSocial'	=> $siat_razonSocial,
			'modalidad'     => $siat_modalidad,//1 para electronica en linea y 2 para computarizada
			// 'ambiente'      => ServicioSiat::AMBIENTE_PRUEBAS,
			'ambiente'      => conexionSiatUrl::AMBIENTE_ACTUAL,
			'tokenDelegado'	=> $siat_tokenDelegado,
			'cuis'			=> null,
			'cufd'			=> null
		]);
	}

	public static function buildInvoice($codigoPuntoVenta = 0, $codigoSucursal = 0, $modalidad = 0,$dataFact,$fechaemision)
	{
		$subTotal = 0;
		// $factura = $modalidad == ServicioSiat::MOD_COMPUTARIZADA_ENLINEA ? new ElectronicaCompraVenta() : new CompraVenta();
		if($modalidad==1){//electronica en linea
			$factura = new ElectronicaCompraVenta();
		}else{//computarizada en linea
			$factura = new CompraVenta();//luego instanciar solo a compra venta
		}

		
		//print_r($factura);
		
		$codigoSalida=$dataFact['cod_salida_almacenes'];
		$descuentoVenta=$dataFact['descuento'];
		require dirname(__DIR__). SB_DS ."../../conexionmysqli2.php";

        /*$sqlDetalle="SELECT m.codigo_material, s.orden_detalle,m.descripcion_material, s.observaciones,s.precio_unitario,sum(s.cantidad_unitaria) as cantidad_unitario,
        sum(s.descuento_unitario) as descuento_unitario, sum(s.monto_unitario) as monto_unitario
        from salida_detalle_almacenes s, material_apoyo m 
        where m.codigo_material=s.cod_material and s.cod_salida_almacen=$codigoSalida
        group by m.codigo_material, s.orden_detalle,m.descripcion_material,s.observaciones,s.precio_unitario
        order by s.orden_detalle;";*/
        $sqlDetalle="SELECT s.cod_material as codigo_material, s.orden_detalle, '' as descripcion_material, s.observaciones,s.precio_unitario,sum(s.cantidad_unitaria) as cantidad_unitario,
        sum(s.descuento_unitario) as descuento_unitario, sum(s.monto_unitario) as monto_unitario
        from salida_detalle_almacenes s 
        where s.cod_salida_almacen=$codigoSalida
        group by s.cod_material,s.orden_detalle,descripcion_material,s.observaciones,s.precio_unitario
        order by s.orden_detalle;";

        //print_r($enlaceCon);
		$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
		$montoTotal=0;$descuentoVentaProd=0;$filaIndice=0;
		while($datDetalle=mysqli_fetch_array($respDetalle)){

		    $codInterno=$datDetalle['codigo_material'];
		    $cantUnit=$datDetalle['cantidad_unitario'];
		    $observaciones=$datDetalle['observaciones'];
		    if($datDetalle['observaciones']==null){
		    	$observaciones="";
		    }
		    $nombreMat=$datDetalle['descripcion_material']." ".$observaciones;
		    $nombreMat=str_replace("&","&amp;",$nombreMat);		    
		    $precioUnit=$datDetalle['precio_unitario'];
		    $descUnit=$datDetalle['descuento_unitario'];
		    //$montoUnit=$datDetalle[5];
		    $montoUnit=($cantUnit*$precioUnit)-$descUnit;
		    
		    $precioUnitFactura=($cantUnit*$precioUnit)/$cantUnit;
		    
		    $cantUnit=self::redondear2($cantUnit);
		    $precioUnit=self::redondear2($precioUnit);
		    $montoUnit=self::redondear2($montoUnit);
		    
		    $precioUnitFactura=self::redondear2($precioUnitFactura);

		    // - $descUnit
		    $descUnit=self::redondear2($descUnit);    
		    $descuentoVentaProd+=$descUnit;
		    $montoUnitProd=($cantUnit*$precioUnit);
		    $montoUnitProd=self::redondear2($montoUnitProd-$descUnit);

		    //$detalle = new InvoiceDetail();
		    if($modalidad==2){//computarizada en linea sector compra venta
			 // 	unset($detalle->numeroSerie);
				// unset($detalle->numeroImei);
				$detalle = new InvoiceDetailCompraVenta();
			}else{
				$detalle = new InvoiceDetail();
			}
		  
		    // $detalle = new InvoiceDetail();
			$detalle->cantidad				= $cantUnit;
			$detalle->actividadEconomica	= $dataFact['siat_codigoActividad'];//	'477300';
			$detalle->codigoProducto		= $codInterno;
			$detalle->codigoProductoSin		= $dataFact['siat_codigoProducto'];//62273; //SERVICIOS DE COMERCIO AL POR MENOR DE HILADOS Y TELAS EN TIENDAS NO ESPECIALIZADAS
			$detalle->unidadMedida			= $dataFact['siat_unidadMedida'];
			$detalle->descripcion			= $nombreMat;
			$detalle->precioUnitario		= $precioUnitFactura;
			$detalle->montoDescuento		= $descUnit;
			$detalle->subTotal				= $montoUnitProd;
			
			$subTotal += $detalle->subTotal;


			$factura->detalle[$filaIndice] = $detalle;
			$filaIndice++;
		    $montoTotal=$montoTotal+$montoUnitProd; 
		}

		$descuentoVenta=number_format($descuentoVenta,2,'.','');
		$montoFinal=$montoTotal-$descuentoVenta;//-$descuentoVentaProd;
		$montoTotal=number_format($montoTotal,2,'.','');
		$montoFinal=number_format($montoFinal,2,'.','');

		//DATOS QUE SE CARGAN CON LOS PARAMETROS POR DEFECTO
		$factura->cabecera->razonSocialEmisor	= ''; // NO ES NECESARIO DECLARAR

		$factura->cabecera->municipio			= $dataFact['municipio'];
		$factura->cabecera->telefono			= $dataFact['telefono'];
		$factura->cabecera->numeroFactura		= $dataFact['nro_correlativo'];		
		$factura->cabecera->codigoSucursal		= $codigoSucursal;
		$factura->cabecera->direccion			= $dataFact['direccion'];	
		$factura->cabecera->codigoPuntoVenta	= $codigoPuntoVenta;
		// $factura->cabecera->fechaEmision		= date('Y-m-d\TH:i:s.v'); 		
		$factura->cabecera->fechaEmision		= $fechaemision;	
		// $factura->cabecera->nombreRazonSocial	= $dataFact['razon_social'];
		$factura->cabecera->nombreRazonSocial	= str_replace("&","&amp;",$dataFact['razon_social']);
		$factura->cabecera->codigoTipoDocumentoIdentidad	= $dataFact['siat_codigotipodocumentoidentidad']; //CI - CEDULA DE IDENTIDAD
		$factura->cabecera->numeroDocumento		= $dataFact['nit'];
		$factura->cabecera->codigoCliente		= $dataFact['cod_cliente'];
		


		$factura->cabecera->codigoMetodoPago	= $dataFact['codigoMetodoPago'];


		$factura->cabecera->montoTotal			= $montoFinal; //$montoFinal
		$factura->cabecera->montoTotalMoneda	= $factura->cabecera->montoTotal;
		$factura->cabecera->montoTotalSujetoIva	= $factura->cabecera->montoTotal;
		//if()
		if($dataFact['siat_complemento']!=""){
			$factura->cabecera->complemento=$dataFact['siat_complemento'];	
		}

		if($dataFact['nro_tarjeta']!=""){
			$factura->cabecera->numeroTarjeta=$dataFact['nro_tarjeta'];	
		}
		$factura->cabecera->descuentoAdicional	= $descuentoVenta; //VERIFICAR
		$factura->cabecera->codigoMoneda		= 1; //BOLIVIANO
		$factura->cabecera->tipoCambio			= 1;
		$factura->cabecera->usuario				= $dataFact['usuario'];

		if($modalidad==1){//electronica en linea
			$factura->cabecera->codigoDocumentoSector= 1;//factura compra venta
			unset($factura->cabecera->nombreEstudiante);
			unset($factura->cabecera->periodoFacturado);
		}else{//computarizada en linea
			$factura->cabecera->codigoDocumentoSector= 1;//factura compra venta
			unset($factura->cabecera->nombreEstudiante);
			unset($factura->cabecera->periodoFacturado);
			// $factura->cabecera->nombreEstudiante	= $dataFact['siat_nombreEstudiante'];
		 	// $factura->cabecera->periodoFacturado	= $dataFact['siat_periodoFacturado'];
		}
		// $solicitud->codigoDocumentoSector 	= DocumentTypes::FACTURA_COMPRA_VENTA; //instanciar
		// print_r($factura);
		return $factura;
	}

	public function testRecepcionFacturaElectronica($codSalidaFactura,$tipoEmision=1,$ex=false,$online_siat=1,$nuevo_cuf=0)
	{
		try
		{
			//echo "entra test factura ";
			//datosCompletosFactura
			require dirname(__DIR__). SB_DS ."../../conexionmysqli2.php";

			 error_reporting(E_ALL);
			 ini_set('display_errors', '1');

			$consulta="SELECT s.cod_salida_almacenes,c.cod_ciudad,
			s.siat_codigoPuntoVenta as codigoPuntoVenta,

			(select cufd from siat_cufd where codigo=s.siat_codigocufd)cufd_generado,
			(select codigo_control from siat_cufd where codigo=s.siat_codigocufd)codigoControl_generado,
			(select cuis from siat_cuis where cod_ciudad=c.cod_ciudad and cod_gestion=YEAR(s.fecha) and estado=1 ORDER BY codigo desc limit 1)cuis,
			(select valor from configuracion_facturas where cod_ciudad=c.cod_ciudad and id=5 limit 1)municipio,
			(select valor from configuracion_facturas where cod_ciudad=c.cod_ciudad and id=4 limit 1)telefono,
			s.nro_correlativo,
			c.cod_impuestos,
			(select valor from configuracion_facturas where cod_ciudad=c.cod_ciudad and id=3 limit 1)direccion,
			s.fecha,
			s.hora_salida,
			s.razon_social,
			IFNULL(s.siat_codigotipodocumentoidentidad,1) AS siat_codigotipodocumentoidentidad,
			s.nit,
			s.cod_cliente,s.siat_excepcion,s.cod_tipo_doc,s.siat_codigotipoemision,
			(select codigoClasificador from siat_tipos_pago where cod_tipopago=s.cod_tipopago)codigoMetodoPago,
			s.monto_total as monto_referencial,
			(select  CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') FROM funcionarios where codigo_funcionario=s.cod_chofer)as usuario,s.siat_fechaemision,s.siat_complemento,s.siat_codigoRecepcion,s.siat_cuf,(select nro_tarjeta from tarjetas_salidas where cod_salida_almacen=s.cod_salida_almacenes limit 1)as nro_tarjeta,(select descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,
			(select siat_cafc from dosificaciones d where d.cod_dosificacion=s.cod_dosificacion and d.tipo_dosificacion=2 and d.tipo_descargo=2)as cafc,c.siat_codigoActividad,c.siat_codigoProducto, 
			'' as siat_nombreEstudiante, 
			'' as siat_periodoFacturado,
			c.siat_unidadMedida

			 from salida_almacenes s join almacenes a on a.cod_almacen=s.cod_almacen
			join ciudades c on c.cod_ciudad=a.cod_ciudad
			where s.cod_salida_almacenes=$codSalidaFactura;";
			
			//echo $consulta;
			
			$respFactura = mysqli_query($enlaceCon,$consulta);	
			$dataFact = $respFactura->fetch_array(MYSQLI_ASSOC);
			
			//print_r($dataFact);

			$config = self::buildConfig();
			$config->validate();

			$codigoPuntoVenta = $dataFact['codigoPuntoVenta'];
			$codigoSucursal = $dataFact['cod_impuestos'];
			$modalidad=$config->modalidad;//modificacion para facturacion electronica
			
			//echo "modalidad: ".$modalidad;
			
			if($modalidad==1){//eletronica en linea
				include dirname(__DIR__). SB_DS ."conexioncert.php";
				$privCert = MOD_SIAT_DIR . SB_DS . 'certs' . SB_DS . $privatekey;
				$pubCert = MOD_SIAT_DIR . SB_DS . 'certs' . SB_DS . $publickey;
			}

			//echo "despues de modalidad";
			$serviceCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$serviceCodigos->setConfig((array)$config);
			$serviceCodigos->cuis = $dataFact['cuis'];
			// print_r($serviceCodigos);echo "<br><br>";
			if($tipoEmision==2){//tipo emision OFFLINE
				$tipoFactura=SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL;
				$tipoEmision = SiatInvoice::TIPO_EMISION_OFFLINE;
				$fechaemision=$dataFact['siat_fechaemision'];

				$factura = self::buildInvoice($codigoPuntoVenta, $codigoSucursal, $config->modalidad,$dataFact,$fechaemision);
				$factura->cabecera->razonSocialEmisor	= $config->razonSocial;
				$factura->cabecera->nitEmisor 	= $config->nit;
				$factura->cabecera->cufd		= $dataFact['cufd_generado'];
				// $factura->cabecera->codigoDocumentoSector		= 1;
				
				//echo "***aquui***".$factura->cabecera->cufd;
				
				//$sucursalNro, $modalidad, $tipoEmision, $tipoFactura, $codigoControl
				$factura->cabecera->codigoExcepcion=$dataFact['siat_excepcion'];
				// $factura->cabecera->leyenda=$leyenda;
				$factura->cabecera->leyenda=$dataFact['leyenda'];
				$factura->cabecera->cuf=$dataFact['siat_cuf'];
				
				if($nuevo_cuf==1){
					$factura->buildCuf((int)$factura->cabecera->codigoSucursal, $config->modalidad, $tipoEmision, $tipoFactura, $dataFact['codigoControl_generado']);
				}

				//die($factura->cuf);
				$factura->validate();
				return $factura;
			}elseif($tipoEmision==-1){
				
				//echo "entra descargar XML";
				
				$tipoFactura=SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL;
				if($dataFact['siat_codigoRecepcion']=="1"){
					$tipoEmision = SiatInvoice::TIPO_EMISION_OFFLINE;	
				}else{
					$tipoEmision = SiatInvoice::TIPO_EMISION_ONLINE;
				}
				
				$fechaemision=$dataFact['siat_fechaemision'];
				$factura = self::buildInvoice($codigoPuntoVenta, $codigoSucursal, $config->modalidad,$dataFact,$fechaemision);
				
				//print_r($factura);
				//hasta aqui todo ok el xml

				$factura->cabecera->razonSocialEmisor	= $config->razonSocial;
				$factura->cabecera->nitEmisor 	= $config->nit;
				
				//$factura->cabecera->cufd		= $dataFact['cufd'];
				$factura->cabecera->cufd		= $dataFact['cufd_generado'];
				$factura->cabecera->codigoExcepcion=$dataFact['siat_excepcion'];				

				$cafc=$dataFact['cafc'];
				if($cafc<>null&&$dataFact['cod_tipo_doc']==4){
					$factura->cabecera->cafc=$cafc;
				}

				$factura->cabecera->cuf=$dataFact['siat_cuf'];
				$factura->cabecera->leyenda=$dataFact['leyenda'];
				$factura->validate();
				
				//echo "validadda";

				if($modalidad==1){//facturacion eletronica en linea
					//echo "entro modalidad electronica ronald";
					
					$service = new ServicioFacturacionElectronica($dataFact['cuis'], $dataFact['cufd_generado'], $config->tokenDelegado);
					$service->setConfig((array)$config);
					$service->codigoControl = $dataFact['codigoControl_generado'];
					$service->setPrivateCertificateFile($privCert);
					$service->setPublicCertificateFile($pubCert);
				}else{
					$service = new ServicioFacturacionComputarizada($dataFact['cuis'], $dataFact['cufd_generado'], $config->tokenDelegado);
					$service->setConfig((array)$config);
					$service->codigoControl = $dataFact['codigoControl_generado'];
				}
				$service->debug = true;
				$facturaXml = $service->buildInvoiceXml($factura);
				return $facturaXml;
			}else{
				if($modalidad==1){//facturacion eletronica en linea
					
					//echo "xml modalidad 1";
					
					$service = new ServicioFacturacionElectronica($dataFact['cuis'], $dataFact['cufd_generado'], $config->tokenDelegado);
					$service->wsdl=conexionSiatUrl::wsdlFacturacionElectronica;
					$service->setConfig((array)$config);				
					$service->codigoControl = $dataFact['codigoControl_generado'];
					$service->setPrivateCertificateFile($privCert);
					$service->setPublicCertificateFile($pubCert);
				}else{
					//echo "xml modalidad <> 1";
					$service = new ServicioFacturacionComputarizada($dataFact['cuis'], $dataFact['cufd_generado'], $config->tokenDelegado);	
					$service->wsdl=conexionSiatUrl::wsdlFacturacionComputarizada;	
					$service->setConfig((array)$config);			
					$service->codigoControl = $dataFact['codigoControl_generado'];
				}
				$service->debug = true;
				// Resultado de Solicitud a "Impuesto"
				// var_dump($service);
				// echo "=========================";

				if($dataFact['cod_tipo_doc']==4){
					$fechaemision=$dataFact['siat_fechaemision'];
					$factura = self::buildInvoice($codigoPuntoVenta, $codigoSucursal, $config->modalidad,$dataFact,$fechaemision);
				}else{
					$fechaemision=date('Y-m-d\TH:i:s.v'); 
					$factura = self::buildInvoice($codigoPuntoVenta, $codigoSucursal, $config->modalidad,$dataFact,$fechaemision);
					// print_r($factura);
				}
				// print_r($factura);

				$factura->cabecera->codigoExcepcion=$dataFact['siat_excepcion'];
				// $factura->cabecera->leyenda=$leyenda;
				$factura->cabecera->leyenda=$dataFact['leyenda'];

				// if($ex==true){
				// 	$factura->cabecera->codigoExcepcion=1;	
				// }else{
				// 	$factura->cabecera->codigoExcepcion=0;
				// }
				// echo "<br>FACTURA1:<br>";
				 //print_r($factura);
				
				//print_r($factura);

				if($online_siat==2){
					$res = $service->recepcionFactura($factura,$online_siat);	
				}else{
					$res = $service->recepcionFactura($factura);				
				}
				// echo "<br>FACTURA2<br><br>";
				// print_r($factura);
				// echo "==================================================";
				// echo "<br>Respuetsa:<br><br>";
				// print_r($res);
				// echo "<br><br><br>";

				return $res;
			}
		}
		catch(Exception $e)
		{
			// return $e->getMessage();
			print_r($e->getMessage());
			//echo "\033[0;31m", $e->getMessage(), "\033[0m", "\n\n";
			// print $e->getTraceAsString();
		}
	}


	public function redondear2($valor) { 
   		$float_redondeado=round($valor * 100) / 100; 
   		return $float_redondeado; 
	}


	public static function anularFacturaEnviada($codigoPuntoVenta,$codigoSucursal,$cuis,$cufd,$cuf,$modalidad=1)
	{				
		  // echo "aqui cuf".$cuf;
		$config = self::buildConfig();
		$config->validate();	
		// echo $cuis."->>>>".$cufd;
		
		if($modalidad==1){//electronica en linea
			$service = new ServicioFacturacionElectronica($cuis, $cufd, $config->tokenDelegado);
		}else{//computarizada en linea
			$service = new ServicioFacturacionComputarizada($cuis, $cufd, $config->tokenDelegado);
		}
		// print_r($config);
		$service->setConfig((array)$config);
		// $service->codigoControl = $codigoControl;
		//// $service->setPrivateCertificateFile($privCert);
		//$service->setPublicCertificateFile($pubCert);
		$service->debug = true;
		// print_r($service);

		 $res2 = $service->anularFacturaEnviada($cuf,$codigoPuntoVenta,$codigoSucursal);
		 print_r($res2);
		 if(isset($res2->RespuestaServicioFacturacion->codigoEstado)){
		 	if($res2->RespuestaServicioFacturacion->codigoEstado==905) {
		 		$codigo=1;
			 	$detalle=$res2->RespuestaServicioFacturacion->codigoDescripcion;
			 	return array($codigo,$detalle);	
		 	}else{
		 		$codigo=-1;
			 	$detalle=$res2->RespuestaServicioFacturacion->mensajesList->descripcion;
			 	return array($codigo,$detalle);	
		 	}
			
		 }else{
		 	$codigo=-1;
		 	$detalle=$res2->RespuestaServicioFacturacion->mensajesList->descripcion;
		 	return array($codigo,$detalle);
		 }
		//echo "<br>***Anul:<br>";
		 // print_r($res2);

	}

	public static function verificarNitCliente($nitCliente,$cod_ciduad){
		try
		{
			//datosCompletosFactura
			require dirname(__DIR__). SB_DS ."../../conexionmysqli2.php";			
			//$global_agencia=$_COOKIE["global_agencia"];
			
			$global_agencia=$cod_ciduad;
			$consulta="SELECT s.cuis,c.cod_impuestos from siat_cuis s join ciudades c on c.cod_ciudad=s.cod_ciudad where s.cod_ciudad='$global_agencia' and cod_gestion=YEAR(NOW()) and estado=1 order by s.codigo desc limit 1";		
			$resp = mysqli_query($enlaceCon,$consulta);	
			// echo $consulta;
			$dataList = $resp->fetch_array(MYSQLI_ASSOC);

			$config = self::buildConfig();

			$config->validate();

			$cuis = $dataList['cuis'];
			$codigoSucursal = $dataList['cod_impuestos'];
			$serviceCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$serviceCodigos->setConfig((array)$config);
			$serviceCodigos->cuis = $dataList['cuis'];			
			$res=$serviceCodigos->verificarNit($codigoSucursal,$nitCliente);	
			return $res;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
		 
	}

	public static function verificarEstadoFactura($codVenta,$global_agencia=null)
	{
		require dirname(__DIR__). SB_DS ."../../conexionmysqli2.php";
		if($global_agencia==null){
			$global_agencia=$_COOKIE["global_agencia"];
		}
		$fechaActual=date("Y-m-d");
		$consulta="SELECT s.cuis,c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad limit 1) as punto_venta,(SELECT cufd from siat_cufd where fecha='$fechaActual' and cod_ciudad=c.cod_ciudad and s.cuis=cuis   and estado=1 order by fecha limit 1)as siat_cufd,(select sc.modalidad from siat_credenciales sc where sc.cod_entidad in (select cc.cod_entidad from ciudades cc where cc.cod_ciudad=c.cod_ciudad)) as modalidad 

			from siat_cuis s join ciudades c on c.cod_ciudad=s.cod_ciudad where s.cod_ciudad='$global_agencia' and cod_gestion=YEAR(NOW()) and estado=1 order by s.codigo desc limit 1";		
		
		//echo $consulta;

		$resp = mysqli_query($enlaceCon,$consulta);	
		$dataList = $resp->fetch_array(MYSQLI_ASSOC);
		$cuis = $dataList['cuis'];
		$codigoPuntoVenta = $dataList['punto_venta'];
		$cufd = $dataList['siat_cufd'];
		$modalidad=$dataList['modalidad'];

		$sql="SELECT s.siat_cuf from salida_almacenes s where s.cod_salida_almacenes='$codVenta' ";		
		$resp = mysqli_query($enlaceCon,$sql);	
		$dataCuf = $resp->fetch_array(MYSQLI_ASSOC);
		$cuf = $dataCuf['siat_cuf'];
		//echo "CUFD:".$cufd;
		$codigoSucursal = $dataList['cod_impuestos'];
		$config = self::buildConfig();
		$config->validate();
		if($modalidad==1){//electronica en linea
			$service = new ServicioFacturacionElectronica($cuis, $cufd, $config->tokenDelegado);		
		}else{//computarizada en linea
			//echo "entro 2";
			$service = new ServicioFacturacionComputarizada($cuis, $cufd, $config->tokenDelegado);		
		}
		//echo "salio del ()";
		//print_r($config);
		$service->setConfig((array)$config);
		$service->debug = true;

		$res2 = $service->verificacionEstadoFactura($codigoSucursal,$codigoPuntoVenta,$cufd,$cuf);		 
		
		return $res2;

	}

	public static function verificarConexion($action='verificarComunicacion')
	{				
		
		try
		{
			$config = self::buildConfig();
			$config->validate();
			
			// $servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			// $servCodigos->setConfig((array)$config);
			// $resCuis = $servCodigos->cuis();
			//print_r($resCuis);
			$sync = new ServicioFacturacionSincronizacion(null, null, $config->tokenDelegado);
			$sync->setConfig((array)$config);
			$res = call_user_func([$sync, $action]);
			$codigo=$res->return->mensajesList->codigo;
			$mensaje=$res->return->mensajesList->descripcion;
			
			//echo "<br>*";print_r($res);echo "<br>*";
			
			if($codigo==926){
				return array('1',$mensaje);
			}else{//error
				return array('2',$mensaje);
			}
			
			
		}
		catch(\Exception $e)
		{
			return array('2',"ERROR EN COMUNICACION CON EL PORTAL DEL SIAT :(");
		}		

	}
}

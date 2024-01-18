<?php
define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioOperaciones;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionComputarizada;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionElectronica;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionFactura;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioRecepcionPaquete;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages\SolicitudServicioValidacionRecepcionPaquete;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class FacturacionOffLine
{
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
			'cufd'			=> null,
		]);
	}


	public static function SolicitudCuis($codigoPuntoVenta,$codigoSucursal)
	{				
		
		$config = self::buildConfig();
		$config->validate();
		
		$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
		$serviceCodigos->wsdl = conexionSiatUrl::wsdlCodigo;
		$servCodigos->setConfig((array)$config);
		$resCuis = $servCodigos->cuis($codigoPuntoVenta, $codigoSucursal);

		$cuis=$resCuis->RespuestaCuis->codigo;
		return $cuis;
	}
	public static function RecepcionEvento($codigoClasificador,$descripcion,$codigoPuntoVenta,$codigoSucursal,$cufd,$cufdAntiguo,$fechaInicio,$fechaFin,$cuis)
	{				
		
		$config = self::buildConfig();
		$config->validate();
	
		$serviceOps = new ServicioOperaciones($cuis, $cufd);
		$serviceOps->wsdl = conexionSiatUrl::wsdlOperaciones;
		
		$serviceOps->setConfig((array)$config);	
		$serviceOps->codigoPuntoVenta=$codigoPuntoVenta;
		$serviceOps->codigoSucursal=$codigoSucursal;
		$serviceOps->cufd=$cufd;
		$serviceOps->cuis=$cuis;
		 //echo $fechaInicio."--->".$fechaFin."<br>".$cufdAntiguo;
		$resEvent = $serviceOps->registroEventoSignificativo($codigoClasificador, $descripcion, $cufdAntiguo, $fechaInicio, $fechaFin,$codigoSucursal,$codigoPuntoVenta);
		echo "<br>Evento:<br>";
		print_r($resEvent);
		echo "<br>";

		if(isset($resEvent->RespuestaListaEventos->codigoRecepcionEventoSignificativo)){
			$codigoEvento=$resEvent->RespuestaListaEventos->codigoRecepcionEventoSignificativo;
			// echo $codigoEvento;
			$descripcion="REGISTRADO";
		}else{
			$codigoEvento=-1;
			$descripcion=$resEvent->RespuestaListaEventos->mensajesList->descripcion;
		}
		return array($codigoEvento,$descripcion);	
	}
	public static function RecepcionPaqueteFactura($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis,$codigoEvento,$tipo,$nuevo_cuf=0)
	{
		try
		{	
			
			// $tipoEmision = SiatInvoice::TIPO_EMISION_OFFLINE;
			// $tipoFactura = SiatInvoice::FACTURA_DERECHO_CREDITO_FISCAL;
			// instanciar*****
			$tipoEmision = 2;
			$tipoFactura = 1;
			require_once "siat_factura_online.php";
			$config = self::buildConfig();
			$config->validate();
			
			if($config->modalidad==1){//electronica
				include dirname(__DIR__). SB_DS ."conexioncert.php";
				$privCert = MOD_SIAT_DIR . SB_DS . 'certs' . SB_DS . $privatekey;
				$pubCert = MOD_SIAT_DIR . SB_DS . 'certs' . SB_DS . $publickey;
				$service = new ServicioFacturacionElectronica($cuis, $cufd, $config->tokenDelegado);
				// $service->wsdl=conexionSiatUrl::wsdlFacturacionElectronica;
				$service->setConfig((array)$config);
				//$service->codigoControl = $dataFact['codigoControl_generado'];
				$service->setPrivateCertificateFile($privCert);
				$service->setPublicCertificateFile($pubCert);

			}else{//computarizada
				$service = new ServicioFacturacionComputarizada($cuis, $cufd, $config->tokenDelegado);
				$service->setConfig((array)$config);
			}

			$service->debug = true;
			$service->fechaEnvio = date("Y-m-d\TH:i:s.000");
			$facturas = [];
			$ii = 0;
			$sqlUpdateFacturas="";
			// $sql="SELECT s.cod_salida_almacenes,(select siat_cafc from dosificaciones d where d.cod_dosificacion=s.cod_dosificacion and d.tipo_dosificacion=2 and d.tipo_descargo=2)as cafc,s.cod_tipo_doc,a.cod_ciudad
			//     FROM salida_almacenes s 
			//     WHERE s.cod_salida_almacenes in ($string_codigos) and s.cod_almacen=$cod_almacen and DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d') = '$fecha'";
			$sql="SELECT s.cod_salida_almacenes,(select siat_cafc from dosificaciones d where d.cod_dosificacion=s.cod_dosificacion and d.tipo_dosificacion=2 and d.tipo_descargo=2)as cafc,s.cod_tipo_doc,a.cod_ciudad
			    FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen
			    WHERE s.cod_salida_almacenes in ($string_codigos) and s.cod_almacen=$cod_almacen and DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d') = '$fecha'";
			    //echo  $sql;
		    $valor=0;		    
		    require("../../conexionmysqli2.inc");
		    // echo $sql;
		    $resp=mysqli_query($enlaceCon,$sql);
		    $facturax= new FacturaOnline();
		    $facturax->endpoint=conexionSiatUrl::endpoint;
		    $facturax->wsdl=conexionSiatUrl::wsdl;

		    $cafc=null;
		    $cod_ciudad=null;
		    while($row=mysqli_fetch_array($resp)){ 
		      	$cod_salida_almacenes=$row['cod_salida_almacenes'];
		      	$cod_ciudad=$row['cod_ciudad'];
		      	$cafc=$row['cafc'];

				$factura=$facturax::testRecepcionFacturaElectronica($cod_salida_almacenes,2,false,1,$nuevo_cuf);
				// print_r($factura);
				if($cafc<>null&&$row['cod_tipo_doc']==4){
					$factura->cabecera->cafc=$cafc;
				}

				$codigo_cuf=$factura->cabecera->cuf;
				$codigoTipoDocumentoIdentidad=$factura->cabecera->codigoTipoDocumentoIdentidad;
				$codigoExcepcion=$factura->cabecera->codigoExcepcion;

				$facturas[$ii] = $factura;
				$sqlUpdateFacturas.="UPDATE salida_almacenes set siat_cuf='$codigo_cuf',siat_codigoRecepcion='$codigoEvento',siat_estado_facturacion='1' where cod_salida_almacenes=$cod_salida_almacenes;";
				$ii++;
		    }
		     // print_r($factura);
		    $service->codigoSucursal=$cod_impuestos;
		    $service->codigoPuntoVenta=$codigoPuntoVenta;
		    // $service->cafc=$codigoPuntoVenta;
			 $res = $service->recepcionPaqueteFactura($facturas, $codigoEvento,$tipoEmision,$tipoFactura,$cafc);
			 // print_r($res);
			 $sw_estado=false;
			if(isset($res->RespuestaServicioFacturacion->codigoRecepcion)){//si todo ok, validamos el Paquete
				$codigoRecepcion = $res->RespuestaServicioFacturacion->codigoRecepcion;
				$res2=$service->validacionRecepcionPaqueteFactura($codigoRecepcion);
				if(isset($res2->RespuestaServicioFacturacion->codigoEstado) ){
					if($res2->RespuestaServicioFacturacion->codigoEstado==908 or $res2->RespuestaServicioFacturacion->codigoEstado==901 or $res2->RespuestaServicioFacturacion->codigoEstado==904){//908-VALIDA 901-PENDIENTE 904-OBSERVACIONES
						$sw_estado=true;
						$codigoRecepcion=$res2->RespuestaServicioFacturacion->codigoRecepcion;
						$sqlUpdate="UPDATE siat_eventos set codigoRecepcionPaquete='$codigoRecepcion' where codigoRecepcionEventoSignificativo='$codigoEvento' and codigoRecepcionPaquete is null";
						$resp=mysqli_query($enlaceCon,$sqlUpdate);
						//validamos cada factura en el siat
						$datos_udpate=explode(';', $sqlUpdateFacturas);
						$facturaVerif= new FacturaOnline();
						$facturaVerif->endpoint=conexionSiatUrl::endpoint;
		    			$facturaVerif->wsdl=conexionSiatUrl::wsdl;
						for ($ix=0; $ix <count($datos_udpate); $ix++) {
							$sql_up=$datos_udpate[$ix];
							if($sql_up<>'' and $sql_up<>' '){
								$resp2=mysqli_query($enlaceCon,$sql_up);//actualizamos factura;
								$datos_factura=explode('where cod_salida_almacenes=', $sql_up);
								$cod_venta=$datos_factura[1];
								$respFac=$facturaVerif::verificarEstadoFactura($cod_venta,$cod_ciudad);
								//si existe error en validacion
								if(isset($respFac->RespuestaServicioFacturacion->codigoEstado)){
									if($respFac->RespuestaServicioFacturacion->codigoEstado<>690){
										$sw_estado=false;
								       $slq_error="UPDATE salida_almacenes set siat_codigoRecepcion=null,siat_estado_facturacion='3' where cod_salida_almacenes=$cod_venta";//volvemos al estado anterior
								       $resperr=mysqli_query($enlaceCon,$slq_error);
								    }
								}else{
									$sw_estado=false;
									$slq_error="UPDATE salida_almacenes set siat_codigoRecepcion=null,siat_estado_facturacion='3' where cod_salida_almacenes=$cod_venta";
									$resperr=mysqli_query($enlaceCon,$slq_error);
								}
								
								// echo "<br>verif Factura:<br>";
								// print_r($respFac);
								// echo "<br>";
							}
						}

					}
				}
			}

			//borramos los archivos temporales
			$files = glob('../Siat/temp/*.tar'); //obtenemos todos los nombres de los ficheros
			foreach($files as $file){
			    if(is_file($file))
			    unlink($file); //elimino el fichero
			}

			echo "<br>Envio Paquete:<br>";
			print_r($res);
			echo "<br>";

			echo "<br>Validación Paquete:<br>";
			print_r($res2);
			echo "<br>";

			if(isset($res->RespuestaServicioFacturacion->codigoDescripcion)){
				$descripcionPaquete=$res->RespuestaServicioFacturacion->codigoDescripcion;
			}else{
				$descripcionPaquete="";
			}
			

			if(isset($res2->RespuestaServicioFacturacion->codigoEstado)){
				if($sw_estado){
					$codigoEvento=1;
					if(isset($res2->RespuestaServicioFacturacion->mensajesList->descripcion)){
						$descripcionValidacion=$res2->RespuestaServicioFacturacion->mensajesList->descripcion;
						// $descripcionValidacion=$res2->RespuestaServicioFacturacion->codigoDescripcion;
					}else{
						// $descripcionValidacion="DATOS RECEPCIONADOS CORRECTAMENTE.";
						$descripcionValidacion=$res2->RespuestaServicioFacturacion->codigoDescripcion;
					}
				}else{
					$codigoEvento=-1;
					$descripcionValidacion=$res2->RespuestaServicioFacturacion->mensajesList->descripcion;	
				}

				
			}else{
				$codigoEvento=-1;
				$descripcionValidacion=$res2->RespuestaServicioFacturacion->mensajesList->descripcion;
			}
			return array($codigoEvento,$descripcionPaquete,$descripcionValidacion);
			
		}
		catch(Exception $e)
		{
			echo "\033[0;31m", $e->getMessage(), "\033[0m", "\n\n";
			print $e->getTraceAsString();
		}
	}

	public static function consultaEventoSignificativo($fechaEvento,$global_agencia=null)
	{


		require dirname(__DIR__). SB_DS ."../../conexionmysqli2.inc";			
		if($global_agencia==null){
			$global_agencia=$_COOKIE["global_agencia"];
		}
		
		$fechaActual=date("Y-m-d");
		$consulta="SELECT s.cuis,c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad limit 1) as punto_venta,(SELECT cufd from siat_cufd where fecha='$fechaActual' and cod_ciudad=c.cod_ciudad and s.cuis=cuis and estado=1 order by fecha limit 1)as siat_cufd from siat_cuis s join ciudades c on c.cod_ciudad=s.cod_ciudad where s.cod_ciudad='$global_agencia' and cod_gestion=YEAR(NOW()) and estado=1 order by s.codigo desc limit 1";		
		$resp = mysqli_query($enlaceCon,$consulta);	
		$dataList = $resp->fetch_array(MYSQLI_ASSOC);
		$cuis = $dataList['cuis'];
		$codigoPuntoVenta = $dataList['punto_venta'];
		$cufd = $dataList['siat_cufd'];
		$codigoSucursal = $dataList['cod_impuestos'];

		$config = self::buildConfig();
		$config->validate();
		$serviceOps = new ServicioOperaciones($cuis, $cufd);
		$serviceOps->wsdl = conexionSiatUrl::wsdlOperaciones;
		$serviceOps->setConfig((array)$config);	
		
		$serviceOps->codigoPuntoVenta=$codigoPuntoVenta;
		$serviceOps->codigoSucursal=$codigoSucursal;
		$serviceOps->cufd=$cufd;
		$serviceOps->cuis=$cuis;
		 // echo "*+*";
		 // print_r($serviceOps);
		$res2 = $serviceOps->consultaEventoSignificativo($codigoSucursal,$codigoPuntoVenta,$fechaEvento);
		// print_r($res2);
		 return $res2;

	}

}


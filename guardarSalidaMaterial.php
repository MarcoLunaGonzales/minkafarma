<?php
set_time_limit(0);

$start_time = microtime(true);
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");
require("enviar_correo/php/send-email_anulacion.php");

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

/*SACAMOS EL TIPO DE IMPRESION PDF O HTML*/
$tipoImpresion=obtenerValorConfiguracion($enlaceCon,48);


$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=$_POST['tipoSalida'];

//echo "TIPO SALIDA: ".$tipoSalida;

//ARRASTRAMOS EL CODIGO ALMACEN ORIGEN DEL FORMULARIO ANTERIOR PARA ALMACEN Y PARA LA SUCURSAL
$almacenOrigen=$_POST['almacen_origen'];
$globalSucursal=$_POST['sucursal_origen'];

$tipoDoc=$_POST['tipoDoc'];
if(!isset($_POST['no_venta'])){
   $almacenDestino=2;
}else{
   $almacenDestino=$_POST['almacen'];
}

$cod_tipopreciogeneral=0;
if(isset($_POST['codigoDescuentoGeneral'])){
   $cod_tipopreciogeneral=$_POST['codigoDescuentoGeneral'];
}
$cod_tipoVenta2=1;
if(isset($_POST['tipo_venta2'])){
   $cod_tipoVenta2=$_POST['tipo_venta2'];
}
$cod_tipodelivery=0;
if(isset($_POST['tipo_ventadelivery'])){
   $cod_tipodelivery=$_POST['tipo_ventadelivery'];
}

$monto_bs=0;
if(isset($_POST['efectivoRecibidoUnido'])){
   $monto_bs=$_POST['efectivoRecibidoUnido'];
}

$monto_usd=0;
if(isset($_POST['efectivoRecibidoUnidoUSD'])){
   $monto_usd=$_POST['efectivoRecibidoUnidoUSD'];
}

$tipo_cambio=0;
if(isset($_POST['tipo_cambio_dolar'])){
   $tipo_cambio=$_POST['tipo_cambio_dolar'];
}

if(isset($_POST['cliente'])){	$codCliente=$_POST['cliente']; }else{ $codCliente=0;	}
if(isset($_POST['tipoPrecio'])){	$tipoPrecio=$_POST['tipoPrecio']; }else{ $tipoPrecio=0;	}
if(isset($_POST['razonSocial'])){	$razonSocial=$_POST['razonSocial']; }else{ $razonSocial="";	}
if($razonSocial==""){
	$razonSocial="SN";
}
$razonSocial=addslashes($razonSocial);

if(isset($_POST['nitCliente'])){	$nitCliente=$_POST['nitCliente']; }else{ $nitCliente=0;	}

/*
if((int)$nitCliente==123){
	$razonSocial="SN";
}
*/

$fecha_emision_manual="";
if(isset($_POST['fecha_emision']) and isset($_POST['hora_emision'])){
	$fecha_emision_manual=date("Y-m-d\TH:i:s.v",strtotime($_POST['fecha_emision']." ".$_POST['hora_emision']));
}else{
	if(isset($_POST['fecha_emision'])){
	   $fecha_emision_manual=date("Y-m-d\TH:i:s.v",strtotime($_POST['fecha_emision']." ".date("H:i:s")));
	}
}


if(isset($_POST['tipoVenta'])){	$tipoVenta=$_POST['tipoVenta']; }else{ $tipoVenta=0;	}
if(isset($_POST['observaciones'])){	$observaciones=$_POST['observaciones']; }else{ $observaciones="";	}

$cuf="";

if(isset($_POST['totalVenta'])){	$totalVenta=$_POST['totalVenta']; }else{ $totalVenta=0;	}
if(isset($_POST['descuentoVenta'])){	$descuentoVenta=$_POST['descuentoVenta']; }else{ $descuentoVenta=0;	}
if(isset($_POST['totalFinal'])){	$totalFinal=$_POST['totalFinal']; }else{ $totalFinal=0;	}

$totalEfectivo=0;
$totalCambio=0;
if(isset($_POST['efectivoRecibido'])){	$totalEfectivo=$_POST['efectivoRecibido']; }else{ $totalEfectivo=0;	}
if(isset($_POST['cambioEfectivo'])){	$totalCambio=$_POST['cambioEfectivo']; }else{ $totalCambio=0;	}
//echo "total efectivo ".$totalEfectivo;
//echo "total cambio ".$totalCambio;

if(isset($_POST['complemento'])){	$complemento=$_POST['complemento']; }else{ $complemento=0;	}

$totalFinalRedondeado=round($totalFinal);

//VALIDAMOS QUE NO SEA CERO EL VALOR DEL REDONDEADO PARA EL CODIGO DE ControlCode
if($totalFinalRedondeado==0){
	$totalFinalRedondeado=1;
}

$fecha=$_POST["fecha"];
$cantidad_material=$_POST["cantidad_material"];

if($descuentoVenta=="" || $descuentoVenta==0){
	$descuentoVenta=0;
}

$vehiculo=0;

$fecha=date("Y-m-d");
$hora=date("H:i:s");


//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$tipoDocDefault=obtenerValorConfiguracion($enlaceCon,1);


//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$clienteDefault=obtenerValorConfiguracion($enlaceCon,2);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$facturacionActivada=obtenerValorConfiguracion($enlaceCon,3);

//SACAMOS LA CONFIGURACION PARA CONOCER EL CONTROL DE STOCKS
$banderaValidacionStock=obtenerValorConfiguracion($enlaceCon,4);

//variables para envio de correo
$siat_estado_facturacion="";
//SI TIPO DE DOCUMENTO ES 1 == FACTURA INGRESAMOS A LOS PROCESOS SIAT y 4 facturas de contigencia
if($tipoDoc==1 || $tipoDoc==4){
	//ALEATORIAMENTE SON DOS PORQUE AL PRIMER RAND SIEMPRE RETORNA EL MISMO
	// $sqlConf="SELECT codigo FROM siat_sincronizarlistaleyendasfactura where codigoActividad=$codigoActividadSIAT and estado=1 ORDER BY rand() LIMIT 1;";
	$sqlConf="SELECT codigo FROM siat_sincronizarlistaleyendasfactura where codigoActividad in (SELECT siat_codigoActividad from ciudades where cod_ciudad='$globalSucursal') and estado=1 ORDER BY rand() LIMIT 1;";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	// $cod_leyenda=mysqli_result($respConf,0,0);
	$datConf=mysqli_fetch_array($respConf);
	$cod_leyenda=$datConf[0];
	// $sqlConf="SELECT codigo FROM siat_sincronizarlistaleyendasfactura where codigoActividad=$codigoActividadSIAT and estado=1 ORDER BY rand() LIMIT 1;";
	$sqlConf="SELECT codigo FROM siat_sincronizarlistaleyendasfactura where codigoActividad in (SELECT siat_codigoActividad from ciudades where cod_ciudad='$globalSucursal') and estado=1 ORDER BY rand() LIMIT 1;";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	// $cod_leyenda=mysqli_result($respConf,0,0);
	$datConf=mysqli_fetch_array($respConf);
	$cod_leyenda=$datConf[0];
	$siat_codigotipodocumentoidentidad=$_POST["tipo_documento"];	
}

/*VALIDACION MANUAL CASOS ESPECIALES*/
if((int)$nitCliente=='99001' || (int)$nitCliente=='99002' || (int)$nitCliente=='99003'){
	$siat_codigotipodocumentoidentidad=5;//nit
}
$created_by=$usuarioVendedor;
$contador = 0;
do {
	$anio=date("Y");
	$created_at=date("Y-m-d H:i:s");
	$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
	$resp=mysqli_query($enlaceCon,$sql);
	// $codigo=mysqli_result($resp,0,0);
	$datCodSalida=mysqli_fetch_array($resp);
	$codigo=$datCodSalida[0];

	//PARA CUANDO ES FACTURA Y ACTIVAMOS PROCESOS SIAT
	if($tipoDoc==1 || $tipoDoc==4){
		if(isset($_POST['fecha_emision'])){
			$anio=date("Y",strtotime($_POST['fecha_emision']));	
		}

		$sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalSucursal' and estado=1 and cod_gestion='$anio' LIMIT 1";
		$respCuis=mysqli_query($enlaceCon,$sqlCuis);
		// $cuis=mysqli_result($respCuis,0,0);
		$datConf=mysqli_fetch_array($respCuis);
		$cuis=$datConf[0];

		if(isset($_POST['fecha_emision'])){
			$fechaEmit=$_POST['fecha_emision'];	
			$horaEmit=$_POST['hora_emision'];	

			$sqlCufd="SELECT codigo,cufd,codigo_control from siat_cufd where cod_ciudad='$globalSucursal' and cuis='$cuis' and  created_at between '$fechaEmit 00:00:00' and '$fechaEmit $horaEmit:00' order by created_at desc limit 1";
		}else{
			$sqlCufd="select codigo,cufd,codigo_control FROM siat_cufd where cod_ciudad='$globalSucursal' and estado=1 and fecha='$fecha' and cuis='$cuis' LIMIT 1";	
		}
		$respCufd=mysqli_query($enlaceCon,$sqlCufd);
		$datCufd=mysqli_fetch_array($respCufd);
		$codigoCufd=$datCufd[0];
		$cufd=$datCufd[1];
		$controlCodigo=$datCufd[2];

		$sqlPV="SELECT codigoPuntoVenta FROM siat_puntoventa where cod_ciudad='$globalSucursal' LIMIT 1";	
		$respPV=mysqli_query($enlaceCon,$sqlPV);
		// $codigoPuntoVenta=mysqli_result($respPV,0,0);
		$datPV=mysqli_fetch_array($respPV);
		$codigoPuntoVenta=$datPV[0];
		$vectorNroCorrelativo=numeroCorrelativoCUFD($enlaceCon,$tipoDoc);
		if(!isset($_POST["nro_correlativo"])){
	  			$nro_correlativo=$vectorNroCorrelativo[0];
		}else{
				$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDoc);
	  			$nro_correlativo=$vectorNroCorrelativo[0];
		}
		$cod_dosificacion=$vectorNroCorrelativo[2];	

		$excepcion=0;
		if(($_POST['siat_error_valor']==0&&$_POST['tipo_documento']==5)||$tipoDoc==4){
			$excepcion=1;
		}
		if($_POST['tipo_documento']==5){
			$complemento="";
		}
		$sql_insert="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
			`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
			`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
			`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion, monto_efectivo,
			monto_cambio,cod_tipopago,created_by,created_at,cod_tipopreciogeneral,cod_tipoventa2,monto_cancelado_bs,monto_cancelado_usd,tipo_cambio,cod_delivery,
			siat_cuis,siat_cuf,siat_codigotipodocumentoidentidad,siat_complemento,siat_codigoPuntoVenta,siat_excepcion,siat_codigocufd,siat_cod_leyenda)
			values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
			'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', 
			'$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$totalEfectivo','$totalCambio','$tipoVenta','$created_by','$created_at','$cod_tipopreciogeneral','$cod_tipoVenta2','$monto_bs','$monto_usd','$tipo_cambio','$cod_tipodelivery','$cuis','$cuf','$siat_codigotipodocumentoidentidad','$complemento','$codigoPuntoVenta',$excepcion,'$codigoCufd','$cod_leyenda')";
		$sql_inserta=mysqli_query($enlaceCon,$sql_insert);
	}else{   //CUANDO ES NR O TRASPASOS U OTROS TIPOS DE DOCS
		$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDoc);
		$nro_correlativo=$vectorNroCorrelativo[0];
		$cod_dosificacion=0;

		$sql_inserta="INSERT INTO salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
 		cod_tipo_doc, fecha, hora_salida, territorio_destino, almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada, 
 		cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago, monto_efectivo, monto_cambio)
 		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
 		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta','$totalEfectivo','$totalCambio')";
 		//echo $sql_inserta;
 		$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);
	}
	$contador++;
} while ($sql_inserta<>1 && $contador <= 100);

if($sql_inserta==1){
	$code="";
	
	//TARJETA INSERTAR
   if(isset($_POST['nro_tarjeta'])&&$_POST['nro_tarjeta']!=""){//&&$tipoVenta==2
       $nro_tarjeta=$_POST['nro_tarjeta'];
       $monto_tarjeta=$_POST['monto_tarjeta'];
       $banco_tarjeta=$_POST['banco_tarjeta'];
       $nro_tarjeta=str_replace("*","0",$nro_tarjeta);
       $sql_tarjeta="INSERT INTO tarjetas_salidas (nro_tarjeta,monto,cod_banco,cod_salida_almacen,estado) VALUES('$nro_tarjeta','$monto_tarjeta','$banco_tarjeta','$codigo',1)";
       $sql_tarjeta=mysqli_query($enlaceCon,$sql_tarjeta);
   }

	//INSERTAMOS RECETA FACTURA
	if(isset($_POST['cod_medico'])){
       $cod_medico=$_POST['cod_medico'];
       $sql_medico="INSERT INTO recetas_salidas (cod_salida_almacen,cod_medico) VALUES('$codigo','$cod_medico')";
       $sql_medico=mysqli_query($enlaceCon,$sql_medico);
    }

	if($facturacionActivada==1 && $tipoDoc==1){
		//insertamos la factura
		$sqlInsertFactura="insert into facturas_venta (cod_dosificacion, cod_sucursal, nro_factura, cod_estado, razon_social, nit, fecha, importe, 
		codigo_control, cod_venta) values ('$cod_dosificacion','$globalSucursal','$nro_correlativo','1','$razonSocial','$nitCliente','$fecha','$totalFinal',
		'$code','$codigo')";
		// echo $sqlInsertFactura;
		$respInsertFactura=mysqli_query($enlaceCon,$sqlInsertFactura);	
	}

	$estado_receta=0;
	$montoTotalVentaDetalle=0;
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		if($codMaterial!=0){
			
			$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
			$precioUnitario=$_POST["precio_unitario$i"];
			$descuentoProducto=$_POST["descuentoProducto$i"];

			if(!isset($_POST["receta_medica$i"])){
          	$estado_receta=0;
			}else{
    			$estado_receta=1;	
			}

			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;			
			
			$banderaErrorCantidadRestante=0;
			$montoTotalVentaDetalle=$montoTotalVentaDetalle+$montoMaterialConDescuento;
			if($banderaValidacionStock==1){
				/* Aca realizamos la validacion de la cantidad restante en caso de que la bandera de validacion sea 1 */
				$cantidadRestanteProducto=cantidadRestanteIngresos($enlaceCon,$almacenOrigen,$codMaterial);
				if($cantidadRestanteProducto>=$cantidadUnitaria){
					$respuesta=descontar_inventarios($enlaceCon,$codigo,$almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i);
				}else{
					$banderaErrorCantidadRestante=1;
				}
			}
			if($banderaValidacionStock==0 || $banderaErrorCantidadRestante==1){
				$respuesta=insertar_detalleSalidaVenta($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
			}
	
			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	}
	
	$montoTotalConDescuento=$montoTotalVentaDetalle-$descuentoVenta;
	//ACTUALIZAMOS EL PRECIO CON EL DETALLE
	$sqlUpdMonto="update salida_almacenes set monto_total=$montoTotalVentaDetalle, monto_final=$montoTotalConDescuento 
				where cod_salida_almacenes=$codigo";
				// echo $sqlUpdMonto;
	$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
	if($facturacionActivada==1){
		$sqlUpdMonto="update facturas_venta set importe=$montoTotalConDescuento 
					where cod_venta=$codigo";
					// echo $sqlUpdMonto;
		$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
	}


	if($tipoSalida==1001){
		//servicios siat
		if($tipoDoc==1){		
			$sqlRecep="select siat_codigoRecepcion from salida_almacenes where cod_salida_almacenes='$codigo'";
			$respRecep=mysqli_query($enlaceCon,$sqlRecep);
			$datPV=mysqli_fetch_array($respRecep);
			$recepcion=$datPV[0];
			$errorFacturaXml=0;
			if($recepcion==""){			
				require_once "siat_folder/funciones_siat.php";
				$errorConexion=verificarConexion()[0];
				if($_POST['siat_error_valor']==0&&$_POST['tipo_documento']==5){
					$facturaImpuestos=generarFacturaVentaImpuestos($codigo,true,$errorConexion);			
				}else{
					$facturaImpuestos=generarFacturaVentaImpuestos($codigo,false,$errorConexion);	
				}
				// exit;
				$fechaEmision=$facturaImpuestos[1];
				$cuf=$facturaImpuestos[2];		
				if(isset($facturaImpuestos[0]->RespuestaServicioFacturacion->codigoRecepcion)){
					$codigoRecepcion=$facturaImpuestos[0]->RespuestaServicioFacturacion->codigoRecepcion;
					$sqlUpdMonto="update salida_almacenes set siat_fechaemision='$fechaEmision',siat_estado_facturacion='1',siat_codigoRecepcion='$codigoRecepcion',siat_cuf='$cuf',siat_codigocufd='$codigoCufd',siat_codigotipoemision='1' 
							where cod_salida_almacenes='$codigo' ";
					$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
					$siat_estado_facturacion=1;
				}else{
					$sqlUpdMonto="update salida_almacenes set siat_codigotipoemision=2,siat_fechaemision='$fechaEmision',siat_codigocufd='$codigoCufd',siat_cuf='$cuf'
						where cod_salida_almacenes='$codigo' ";
					$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
					$errorFacturaXml=1;
					// echo $sqlUpdMonto;
				}			
			}
			if($errorFacturaXml==0){
				if($errorProducto!=""){		
					$cambioFactura=number_format($totalEfectivo-$totalFacturaMonto,2,'.','');
					$sqlUpdCambio="update salida_almacenes set monto_cambio='$cambioFactura',observaciones='REVFACT' 
					where cod_salida_almacenes='$codigo'";
		   			mysqli_query($enlaceCon,$sqlUpdCambio);
						echo "<script type='text/javascript' language='javascript'>	
							location.href='errorDiferenciaFactura.php?codVenta=$codigo';
						</script>";	
				}else{
					$mensaje="Transacción Exitosa :)";	
					$url="puente_impresion.php?codVenta=$codigo&tipodoc=$tipoDoc";
					
				}
			}else{ //ESTO ES CUANDO HAY ERROR FACTURA
				$mensaje="Factura emitida fuera de línea :(";				
				$url="puente_impresion.php?codVenta=$codigo&tipodoc=$tipoDoc";
			}

			//SACAMOS LA VARIABLE PARA ENVIAR EL CORREO O NO SI ES 1 ENVIAMOS CORREO DESPUES DE LA TRANSACCION
			$banderaCorreo=obtenerValorConfiguracion($enlaceCon,10);
			if($banderaCorreo==1){
				$enviar_correo=true;
				$correo_destino=obtenerCorreosListaCliente($codCliente);
				if($correo_destino==null || $correo_destino=="" || $correo_destino==" "){
					$enviar_correo=false;
					$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>EL CLIENTE NO TIENE UN CORREO REGISTRADO</b></span>";
				}
			}else{
				$enviar_correo=false;
				$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>CORREO NO ENVIADO</b></span>";
			}
			if($enviar_correo){
				$sw_correo=true;
				$codigoVenta=$codigo;
				require_once "descargarFacturaXml.php";
				$codigoVenta=$codigo;
				require_once "descargarFacturaPDF.php";

				$estado_envio=envio_factura($codigoVenta,$correo_destino,$enlaceCon);
				if($estado_envio==1){
					$texto_correo="<span style=\"border:1px;font-size:18px;color:#91d167;\"><b>SE ENVIÓ EL CORREO CON EXITO.</b></span>";
				}elseif($estado_envio==0){
					$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>EL CLIENTE NO TIENE UN CORREO REGISTRADO</b></span>";
				}else{
					$texto_correo="<span style=\"border:1px;font-size:18px;color:red;\"><b>Ocurrio un error al enviar el correo, vuelva a intentarlo.</b></span>";
				}

				/*DIRECCIONAMOS A LA URL CORRECTA TIPO DE IMPRESION*/
				$url="puente_impresion.php?codVenta=$codigo&tipodoc=$tipoDoc";
				
				echo "<script language='Javascript'>
					Swal.fire({
				    title: 'SIAT: ".$mensaje."',
				    html: '".$texto_correo."',
				    type: 'success',
				    timer: 500
					}).then(function() {
					   location.href='$url'; 
					});
					</script>";
			}else{
				echo "<script language='Javascript'>
					Swal.fire({
				    title: 'SIAT: ".$mensaje."',
				    html: '".$texto_correo."',
				    type: 'success',
				    timer: 500
					}).then(function() {
						 location.href='$url';
					});
					</script>";
			}
		}else if($tipoDoc==2){			
			$url="puente_impresion.php?codVenta=$codigo&tipodoc=$tipoDoc";
			$banderaCorreo=0;
			if($banderaCorreo==1 || $banderaCorreo==2){
				header("location:sendEmailVenta.php?codigo=$codigo&evento=1&tipodoc=$tipoDoc");
			    $respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
		    }else{
				echo "<script type='text/javascript' language='javascript'>
					location.href='$url';
				</script>";
			}
		}else if($tipoDoc==4){
			$sqlUpdMonto="update salida_almacenes set siat_codigotipoemision=2,siat_fechaemision='$fecha_emision_manual',siat_codigocufd='$codigoCufd'
						where cod_salida_almacenes='$codigo' ";
			mysqli_query($enlaceCon,$sqlUpdMonto);
			require_once "siat_folder/funciones_siat.php";
			$errorConexion=2; //OFFLINE
			$facturaImpuestos=generarFacturaVentaImpuestos($codigo,true,$errorConexion);							
			$cuf=$facturaImpuestos[2];
			$sqlUpdMonto="update salida_almacenes set siat_cuf='$cuf' where cod_salida_almacenes='$codigo' ";
			$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
			$errorFacturaXml=1;
			//SACAMOS LA VARIABLE PARA ENVIAR EL CORREO O NO SI ES 1 ENVIAMOS CORREO DESPUES DE LA TRANSACCION
			$banderaCorreo=obtenerValorConfiguracion($enlaceCon,10);
			if($banderaCorreo==1){
				//para correo solo en caso de offline y online
				$enviar_correo=true;
				$correo_destino=obtenerCorreosListaCliente($codCliente);
				if($correo_destino==null || $correo_destino=="" || $correo_destino==" "){
					$enviar_correo=false;
					$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>EL CLIENTE NO TIENE UN CORREO REGISTRADO</b></span>";
				}
			}else{
				$enviar_correo=false;
				$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>CORREO NO ENVIADO</b></span>";
			}
			if($enviar_correo){
				$sw_correo=true;
				$codigoVenta=$codigo;
				require_once "descargarFacturaXml.php";
				$codigoVenta=$codigo;
				require_once "descargarFacturaPDF.php";

				$estado_envio=envio_factura($codigoVenta,$correo_destino,$enlaceCon);
				if($estado_envio==1){
					$texto_correo="<span style=\"border:1px;font-size:18px;color:#91d167;\"><b>SE ENVIÓ EL CORREO CON EXITO.</b></span>";
				}elseif($estado_envio==0){
					$texto_correo="<span style=\"border:1px;font-size:18px;color:orange;\"><b>EL CLIENTE NO TIENE UN CORREO REGISTRADO</b></span>";
				}else{
					$texto_correo="<span style=\"border:1px;font-size:18px;color:red;\"><b>Ocurrio un error al enviar el correo, vuelva a intentarlo.</b></span>";
				}
				echo "<script language='Javascript'>
					Swal.fire({
				    title: 'FACTURA CON CAFC REGISTRADO CORRECTAMENTE',
				    html: '".$texto_correo."',
				    type: 'success'
					}).then(function() {
					   location.href='navegadorVentas.php'; 
					});
					</script>";
			}else{
				echo "<script language='Javascript'>
					Swal.fire({
				    title: 'FACTURA CON CAFC REGISTRADO CORRECTAMENTE',
				    html: '".$texto_correo."',
				    type: 'success',
				    timer: 1000
					}).then(function() {
					    location.href='navegadorVentas.php';
					});
					</script>";
			}
		}else{
			echo "<script type='text/javascript' language='javascript'>
			location.href='navegador_salidamateriales.php';
			</script>";
		}
	}else{	
		echo "<script type='text/javascript' language='javascript'>
			location.href='navegador_salidamateriales.php';
		</script>";
	}	
}else{
		echo "<script type='text/javascript' language='javascript'>
			alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		</script>";//location.href='navegador_salidamateriales.php';
}

?>




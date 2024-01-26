<?php
function obtenerValorConfiguracion($enlaceCon,$id){
	$estilosVenta=1;
	//require("conexionmysqli2.inc");
	$sql = "SELECT valor_configuracion from configuraciones c where id_configuracion=$id";
	$resp=mysqli_query($enlaceCon,$sql);
	$codigo=0;
	while ($dat = mysqli_fetch_array($resp)) {
	  $codigo=$dat['valor_configuracion'];
	}
	return($codigo);
}

function numeroCorrelativoCUFD($enlaceCon,$tipoDoc){
	//require("conexionmysqli2.inc");
	$globalCiudad=$_COOKIE['global_agencia'];
	//echo "GlobalCiudad".$globalCiudad;
	$globalAlmacen=$_COOKIE['global_almacen'];	 
	//echo "GlobalAlmacen".$globalAlmacen;
  $fechaActual=date("Y-m-d");
  $sqlCufd="select cufd FROM siat_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual' and cufd<>'' and cuis<>''";

  $respCufd=mysqli_query($enlaceCon,$sqlCufd);
  $datCufd=mysqli_fetch_array($respCufd);
  $cufd=$datCufd[0];//$cufd=mysqli_result($respCufd,0,0);
  $nro_correlativo="CUFD INCORRECTO / VENCIDO";
  $bandera=1;

  $anioActual=date("Y");
  $sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual'";  
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $datCuis=mysqli_fetch_array($respCuis);
  $cuis=$datCuis[0];//$cuis=mysqli_result($respCuis,0,0);
  if($cuis==""){
  		$nro_correlativo="CUIS INCORRECTO / VENCIDO";$bandera=1;	
  } 
  //$nro_correlativo.=" CUIS".$cufd; 
  if($cufd!=""&&$cuis!=""){
    $sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and siat_cuis='$cuis' and cod_almacen='$globalAlmacen'";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){  
       $nro_correlativo=$row[0];   
       $bandera=0;
    }
  }
  return array($nro_correlativo,$bandera,'');  
}

function numeroCorrelativoCUFD2($tipoDoc){
	require("conexionmysqli2.inc");
	$globalCiudad=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];	 

  $fechaActual=date("Y-m-d");
  $sqlCufd="select cufd FROM siat_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual'";
	// echo $sqlCufd;
  $respCufd=mysqli_query($enlaceCon,$sqlCufd);
  $cufd=mysqli_result($respCufd,0,0);
  $nro_correlativo="CUFD INCORRECTO / VENCIDO";$bandera=1;

  $anioActual=date("Y");
	$sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anioActual'";
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $cuis=mysqli_result($respCuis,0,0);
  if($cuis==""){
  		$nro_correlativo="CUIS INCORRECTO / VENCIDO";$bandera=1;	
  } 
  //$nro_correlativo.=" CUIS".$cufd; 
  if($cufd!=""&&$cuis!=""){
    $sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and siat_cuis='$cuis' and cod_almacen='$globalAlmacen' ";				
				$resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){  
       $nro_correlativo=$row[0];   
       $bandera=0;
    }
  }
  return array($nro_correlativo,$bandera);  
}

function obtenerCorreosListaCliente($id_proveedor){	
  	require("conexionmysqli2.inc");
  	$sql_detalle="SELECT DISTINCT email_cliente FROM `clientes` where cod_cliente='$id_proveedor'";
  	$correosProveedor="";  
  	$resp=mysqli_query($enlaceCon,$sql_detalle);  
  	while($detalle=mysqli_fetch_array($resp)){  
       $correo=$detalle[0];
       $correosProveedor.=$correo.",";
	} 
	$correosProveedor=trim($correosProveedor,",");
  	mysqli_close($enlaceCon); 
  	return $correosProveedor;
}





function cargarDocumentosPDF($codigoVenta){
	$home=1;
	ob_start();
	require "conexionmysqli2.inc";
	include "dFacturaElectronicaAllPdf.php";
	$html = ob_get_clean();
	//error_reporting(E_ALL);
	$sqlDatosVenta="select s.siat_cuf
	        from `salida_almacenes` s
	        where s.`cod_salida_almacenes`='$codigoVenta'";
	$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
	$cuf="";
	while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	    $cuf=$datDatosVenta['siat_cuf'];
	}
	$nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";
	unlink($nombreFile);	

	guardarPDFArqueoCajaVerticalFactura($cuf,$html,$nombreFile);
	return $cuf.".pdf";

}
function cargarDocumentosXML($codSalida){
	// $codSalida=$_GET['codVenta'];
	require "conexionmysqli2.inc";
	require_once "siat_folder/funciones_siat.php";  
	$facturaImpuestos=generarXMLFacturaVentaImpuestos($codSalida);

	$sqlDatosVenta="select s.siat_cuf
	        from `salida_almacenes` s
	        where s.`cod_salida_almacenes`='$codSalida'";
	$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
	$cuf="";
	while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
	    $cuf=$datDatosVenta['siat_cuf'];

	}
	$nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.xml";
	unlink($nombreFile);	
	$archivo = fopen($nombreFile,'a');    
	fputs($archivo,$facturaImpuestos);
	fclose($archivo);

	// if($email==1){
		// header("Content-Type: application/force-download");
		// header("Content-Disposition: attachment; filename=\"$cuf.xml\"");
		// readfile($nombreFile);	
	// }else{
		return $cuf.".xml";
	// }
}


function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formatNumberInt($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumero($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumeroDec($valor) { 
   $float_redondeado=number_format($valor, 2); 
   return $float_redondeado; 
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo="";
	if($cadena_fecha!=""){
		$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
	}	
	return($cadena_formatonuevo);
}

function formatearFecha2($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[8].$cadena_fecha[9]."/".$cadena_fecha[5].$cadena_fecha[6]."/".$cadena_fecha[0].$cadena_fecha[1].$cadena_fecha[2].$cadena_fecha[3];
	return($cadena_formatonuevo);
}

function UltimoDiaMes($cadena_fecha)
{	
	list($anioX, $mesX, $diaX)=explode("-",$cadena_fecha);
	$fechaNuevaX=$anioX."-".$mesX."-01";
	
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'+1 month'));
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'-1 day'));

	return($fechaNuevaX);
}

function obtenerCodigo($enlaceCon,$sql)
{	//require("conexion.inc");
	$resp=mysqli_query($enlaceCon,$sql);
	$nro_filas_sql = mysqli_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysqli_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}

function stockProducto($enlaceCon,$almacen, $item){
	//
	//require("conexion.inc");
	$fechaActual=date("Y-m-d");
	$fechaInicioSistema="2000-01-01";
		   $sql_ingresos="select IFNULL(sum(id.cantidad_unitaria),0) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicioSistema' and '$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0";		
			$cant_ingresos=0;
			$cant_salidas=0;
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);			
			if($dat_ingresos=mysqli_fetch_array($resp_ingresos)){
				$cant_ingresos=$dat_ingresos[0];	
			}			
			//echo $cant_ingresos." ";
			$sql_salidas="select IFNULL(sum(sd.cantidad_unitaria),0) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicioSistema' and '$fechaActual' and s.cod_almacen='$almacen'
			and sd.cod_material='$item' and s.salida_anulada=0";
			//echo $sql_salidas."<br>";
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			if($dat_salidas=mysqli_fetch_array($resp_salidas)){
				$cant_salidas=$dat_salidas[0];
			}			
			//echo $cant_salidas." ";
			$stock2=$cant_ingresos-$cant_salidas;
			return($stock2);
}

function stockProductoAFecha($enlaceCon, $almacen, $item, $fechaInventario){
	$fechaActual=$fechaInventario;
	$fechaInicioSistema="2000-01-01";
	$sqlAlmacenes="select cod_almacen from almacenes where cod_almacen in ($almacen)";
	$respAlmacenes=mysqli_query($enlaceCon,$sqlAlmacenes);
	$stock2=0;
	while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
			$cant_ingresos=0;
			$cant_salidas=0;
			$codAlmacenX=$datAlmacenes[0];

		   $sql_ingresos="select IFNULL(sum(id.cantidad_unitaria),0) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicioSistema' and '$fechaActual' and i.cod_almacen='$codAlmacenX'
			and id.cod_material='$item' and i.ingreso_anulado=0";
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			if($dat_ingresos=mysqli_fetch_array($resp_ingresos)){
				$cant_ingresos=$dat_ingresos[0];	
			}
			$sql_salidas="select IFNULL(sum(sd.cantidad_unitaria),0) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicioSistema' and '$fechaActual' and s.cod_almacen='$codAlmacenX'
			and sd.cod_material='$item' and s.salida_anulada=0";
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			if($dat_salidas=mysqli_fetch_array($resp_salidas)){
				$cant_salidas=$dat_salidas[0];
			}
			$stock2+=$cant_ingresos-$cant_salidas;		
	}
	return($stock2);
}

function ingresosItemPeriodo($enlaceCon, $almacen, $item, $fechaInicio, $fechaFinal){
	$sqlAlmacenes="select cod_almacen from almacenes where cod_almacen in ($almacen)";
	$respAlmacenes=mysqli_query($enlaceCon,$sqlAlmacenes);
	$cant_ingresos=0;
	while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
		$codAlmacenX=$datAlmacenes[0];
		$sql_ingresos="select IFNULL(sum(id.cantidad_unitaria),0) from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicio' and '$fechaFinal' and i.cod_almacen='$codAlmacenX' and id.cod_material='$item' and i.ingreso_anulado=0";
		$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
		if($dat_ingresos=mysqli_fetch_array($resp_ingresos)){
			$cant_ingresos+=$dat_ingresos[0];	
		}		
	}
	return($cant_ingresos);
}
function ingresosItemPeriodoxCompra($enlaceCon, $almacen, $item, $fechaInicio, $fechaFinal){
	$sql_ingresos="select IFNULL(sum(id.cantidad_unitaria),0) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha between '$fechaInicio' and '$fechaFinal' and i.cod_almacen='$almacen' and i.cod_tipoingreso=1000 and id.cod_material='$item' and i.ingreso_anulado=0";
	$cant_ingresos=0;
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	if($dat_ingresos=mysqli_fetch_array($resp_ingresos)){
		$cant_ingresos=$dat_ingresos[0];	
	}
	return($cant_ingresos);
}
function salidasItemPeriodo($enlaceCon, $almacen, $item, $fechaInicio, $fechaFinal){
	$sqlAlmacenes="select cod_almacen from almacenes where cod_almacen in ($almacen)";
	$respAlmacenes=mysqli_query($enlaceCon,$sqlAlmacenes);
	$cant_salidas=0;
	while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
		$codAlmacenX=$datAlmacenes[0];
		$sql_salidas="select IFNULL(sum(sd.cantidad_unitaria),0) from salida_almacenes s, salida_detalle_almacenes sd
		where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha between '$fechaInicio' and '$fechaFinal' and s.cod_almacen='$codAlmacenX' and sd.cod_material='$item' and s.salida_anulada=0";
		$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
		if($dat_salidas=mysqli_fetch_array($resp_salidas)){
			$cant_salidas+=$dat_salidas[0];
		}
	}
	return($cant_salidas);
}

function cantidadRestanteIngresos($enlaceCon,$almacen, $item){
	$stockRestante="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$stockRestante=$registro[0];
	if($stockRestante=="")
	{   $stockRestante=0;
	}
	$stockRestante=redondear2($stockRestante);
	return($stockRestante);
}
function stockMaterialesEdit($enlaceCon,$almacen, $item, $cantidad){
	//
	//require("conexion.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($enlaceCon,$codigo_registro){
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysqli_query($enlaceCon,$sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysqli_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysqli_query($enlaceCon,$sql_actualiza);			
	}
	return(1);
}
function numeroCorrelativo($enlaceCon,$tipoDoc){
	//require("conexion.inc");
	$banderaErrorFacturacion=0;
	//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$datConf=mysqli_fetch_array($respConf);
	$facturacionActivada=$datConf[0];
	//$facturacionActivada=mysql_result($respConf,0,0);

	$fechaActual=date("Y-m-d");
	$globalAgencia=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];
	
	if($facturacionActivada==1 && $tipoDoc==1){
		//VALIDAMOS QUE LA DOSIFICACION ESTE ACTIVA
		// $sqlValidar="select count(*) from dosificaciones d 
		// where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual'";
		$sqlValidar="select count(*) from dosificaciones d 
		where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual' and d.tipo_dosificacion=1";
		$respValidar=mysqli_query($enlaceCon,$sqlValidar);
		$datValidar=mysqli_fetch_array($respValidar);		
		$numFilasValidar=$datValidar[0];	
		//$numFilasValidar=mysql_result($respValidar,0,0);
		
		if($numFilasValidar==1){
			// $sqlCodDosi="select cod_dosificacion from dosificaciones d 
			// where d.cod_sucursal='$globalAgencia' and d.cod_estado=1";
			$sqlCodDosi="select cod_dosificacion from dosificaciones d 
			where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.tipo_dosificacion=1";
			$respCodDosi=mysqli_query($enlaceCon,$sqlCodDosi);
			$datCodDosi=mysqli_fetch_array($respCodDosi);		
			$codigoDosificacion=$datCodDosi[0];
			//$codigoDosificacion=mysql_result($respCodDosi,0,0);
		
			// if($tipoDoc==1){//validamos la factura para que trabaje con la dosificacion
			// 	$sql="select IFNULL(max(f.nro_factura)+1,1) from facturas_venta f where 
			// 	cod_dosificacion='$codigoDosificacion'";	
			// }else{
			// 	$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen'";
			// }
			if($tipoDoc==1){//validamos la factura para que trabaje con la dosificacion
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and cod_dosificacion='$codigoDosificacion' and cod_almacen='$globalAlmacen' ";	
			}else{
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc'";
			}
			//echo $sql;
			$resp=mysqli_query($enlaceCon,$sql);
			$dat=mysqli_fetch_array($resp);		
			$codigo=$dat[0];
			//$codigo=mysql_result($resp,0,0);
			
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,$codigoDosificacion);
			return $vectorCodigo;
		}else{
			$banderaErrorFacturacion=1;
			$vectorCodigo = array("DOSIFICACION INCORRECTA O VENCIDA",$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
	if($facturacionActivada==1 && $tipoDoc==4){
		//VALIDAMOS QUE LA DOSIFICACION ESTE ACTIVA
		$sqlValidar="select count(*) from dosificaciones d 
		where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual' and d.tipo_dosificacion=2 and d.tipo_descargo=2 and (SELECT IFNULL(max(nro_correlativo),0) FROM `salida_almacenes` where cod_dosificacion=d.cod_dosificacion)<d.nro_fin";
		//echo $sqlValidar;
		$respValidar=mysqli_query($enlaceCon,$sqlValidar);
		// $numFilasValidar=mysqli_result($respValidar,0,0);
		$datVali=mysqli_fetch_array($respValidar);		
		$numFilasValidar=$datVali[0];
		
		if($numFilasValidar==1){			

			$sqlCodDosi="select cod_dosificacion from dosificaciones d 
			where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.tipo_dosificacion=2 and d.tipo_descargo=2 ";
			$respCodDosi=mysqli_query($enlaceCon,$sqlCodDosi);
			// $codigoDosificacion=mysqli_result($respCodDosi,0,0);
			$datVali=mysqli_fetch_array($respCodDosi);		
			$codigoDosificacion=$datVali[0];
			$sqlCodDosi="select nro_inicio from dosificaciones d 
			where d.cod_dosificacion='$codigoDosificacion'";
			$respCodDosi=mysqli_query($enlaceCon,$sqlCodDosi);
			// $nroInicio=mysqli_result($respCodDosi,0,0);
			$datVali=mysqli_fetch_array($respCodDosi);		
			$nroInicio=$datVali[0];
		
			if($tipoDoc==4){//validamos la factura para que trabaje con la dosificacion
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and cod_dosificacion='$codigoDosificacion'  ";	 //and salida_anulada=0
			}else{
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and s.cod_almacen='$globalAlmacen' ";
			}
			//echo $sql;
			$resp=mysqli_query($enlaceCon,$sql);
			// $codigo=mysqli_result($resp,0,0);
			$datVali=mysqli_fetch_array($resp);		
			$codigo=$datVali[0];

			//NUMERO INICIO
			if($codigo==1){
               $codigo=($nroInicio-1)+$codigo;
			}
			
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,$codigoDosificacion);
			return $vectorCodigo;
		}else{
			$banderaErrorFacturacion=1;
			$vectorCodigo = array("DOSIFICACION INCORRECTA O VENCIDA",$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
	if(($facturacionActivada==1 && ($tipoDoc==2 || $tipoDoc==3)) || $facturacionActivada!=1){
		$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen'";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
}


function numeroCorrelativoCotizacion($enlaceCon,$tipoDoc){
	$globalAlmacen=$_COOKIE['global_almacen'];
	$sql="select IFNULL(max(nro_correlativo)+1,1) from cotizaciones where cod_tipo_doc='1' and cod_almacen='$globalAlmacen'";
	$resp=mysqli_query($enlaceCon,$sql);
	$codigo=0;
	while($dat=mysqli_fetch_array($resp)){
		$codigo = $dat[0];
	}
	return $codigo;
}

function unidadMedida($enlaceCon,$codigo){
	
	$consulta="select u.abreviatura from material_apoyo m, unidades_medida u
		where m.cod_unidad=u.codigo and m.codigo_material='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$unidadMedida=$registro[0];

	return $unidadMedida;
}


function nombreTipoDoc($enlaceCon,$codigo){
	$consulta="select u.abreviatura from tipos_docs u
		where u.codigo='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$nombre=$registro[0];

	return $nombre;
}

function precioVenta($enlaceCon,$codigo,$agencia){
	
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and p.cod_ciudad='$agencia'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}
//COSTO 
function costoVentaFalse($enlaceCon,$codigo,$agencia){	
	$consulta="select sd.costo_almacen from salida_almacenes s, salida_detalle_almacenes sd where 
		s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen in  
		(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and s.salida_anulada=0 and 
		sd.cod_material='$codigo' limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}

function costoVenta($enlaceCon,$codigo,$agencia){	
	$consulta="select id.costo_almacen from ingreso_almacenes i, ingreso_detalle_almacenes id where 
	i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen in  
			(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and i.ingreso_anulado=0 
	and id.cod_material='$codigo' and i.cod_tipoingreso in (999,1000) order by i.cod_ingreso_almacen desc limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}

function costoVentaGeneral($enlaceCon,$codigo,$agencia){	
	$consulta="select id.costo_almacen from ingreso_almacenes i, ingreso_detalle_almacenes id where 
	i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 
	and id.cod_material='$codigo' and i.cod_tipoingreso in (999,1000) and id.costo_almacen>0 
	order by i.cod_ingreso_almacen desc limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}


function codigoSalida($enlaceCon,$cod_almacen){	
	$consulta="select IFNULL(max(s.cod_salida_almacenes)+1,1) as codigo from salida_almacenes s";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$codigo=$registro[0];

	return $codigo;
}

function obtieneIdProducto($enlaceCon,$idProducto){
	$sql="select m.codigo_material from material_apoyo m where m.codigo_anterior='$idProducto'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$idProducto=$dat[0];
	return($idProducto);	
}

function obtieneMarcaProducto($enlaceCon,$idMarca){
	$sql="select m.nombre from marcas m where m.codigo='$idMarca'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$nombreMarca=$dat[0];
	return($nombreMarca);	
}
function fechaInicioSistema($enlaceCon){
	//6 FECHA DE INICIO DE OPERACIONES
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=6";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$datConf=mysqli_fetch_array($respConf);
	$fechaInicioOperaciones=$datConf[0];
	//$fechaInicioOperaciones=mysqli_result($respConf,0,0);	
	return($fechaInicioOperaciones);
}

function montoVentaDocumento($enlaceCon,$codVenta){
	$sql="select (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.cod_salida_almacenes=$codVenta";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);

	$totalVenta=0;
	while($datos=mysqli_fetch_array($resp)){	
		
		$montoVenta=$datos[0];
		$cantidad=$datos[1];

		$descuentoVenta=$datos[2];
		$montoNota=$datos[3];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		$totalVenta=$totalVenta+$montoVenta;
	}
	return($totalVenta);	
}


function obtenerEstadoSalida($codSalida){
  	$estilosVenta=1;
  	require("conexionmysqli2.inc");
  	$sql_detalle="SELECT salida_anulada FROM salida_almacenes where cod_salida_almacenes='$codSalida'";
  	$estado=0;	
  	$resp=mysqli_query($enlaceCon,$sql_detalle);
  	while($detalle=mysqli_fetch_array($resp)){	
       $estado=$detalle[0]; 	
  	} 
  	mysqli_close($enlaceCon); 
  	return $estado;
	}

  function guardarPDFArqueoCajaVerticalFactura($nom,$html,$rutaGuardado,$codSalida){
    //aumentamos la memoria  
    ini_set("memory_limit", "128M");
    // Cargamos DOMPDF
    require_once 'assets/libraries/dompdf/dompdf_config.inc.php';
    $mydompdf = new DOMPDF();
    $mydompdf->set_paper('letter', 'portrait');    

    ob_clean();
    $mydompdf->load_html($html);
    $mydompdf->render();
    $canvas = $mydompdf->get_canvas();
    $canvas->page_text(540, 750, "{PAGE_NUM}/{PAGE_COUNT}", Font_Metrics::get_font("sans-serif"), 7, array(0,0,0)); 

    $estado=obtenerEstadoSalida($codSalida);
    if($estado!=0){ //facturas anuladas MARCA DE AGUA ANULADO
      //marca de agua
      $canvas2 = $mydompdf->get_canvas(); 
      $w = $canvas2->get_width(); 
      $h = $canvas2->get_height(); 
      $font = Font_Metrics::get_font("times"); 
      $text = "ANULADO"; 
      $txtHeight = -100; 
      $textWidth = 250; 
      $canvas2->set_opacity(.5); 
      $x = (($w-$textWidth)/2); 
      $y = (($h-$txtHeight)/2); 
      $canvas2->text($x, $y, $text, $font, 100, $color = array(100,0,0), $word_space = 0.0, $char_space = 0.0, $angle = -45);
    //fin marca agua
     }

    $output = $mydompdf->output();    
    file_put_contents($rutaGuardado, $output);
  }


function ubicacionProducto($enlaceCon,$almacen, $item){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");

	$sql_ingresos="select 
	(select u.nombre from ubicaciones_estantes u where u.codigo=id.cod_ubicacionestante)as estante,
	(select u.nombre from ubicaciones_filas u where u.codigo=id.cod_ubicacionfila)as fila
	from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0 and id.cantidad_restante>0 limit 0,1";
	//echo $sql_ingresos;
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	$dat_ingresos=mysqli_fetch_array($resp_ingresos);
	$ubicacion=$dat_ingresos[0]."-".$dat_ingresos[1];
	return($ubicacion);
}
function precioProducto($enlaceCon,$item){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");
	$sql="SELECT p.`precio` from precios p where p.`codigo_material`='$item' and p.`cod_precio`='1'";	
	$resp=mysqli_query($enlaceCon,$sql);
	$precio=0;
 if (mysqli_num_rows($resp)>0){ 
	$dat=mysqli_fetch_array($resp);
	$precio=$dat[0];
	}
	return($precio);
}
function precioProductoSucursal($enlaceCon,$item,$sucursal){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");
	$sql="SELECT p.`precio` from precios p where p.`codigo_material`='$item' and p.`cod_precio`='1' 
				and p.cod_ciudad='$sucursal'";	
	$resp=mysqli_query($enlaceCon,$sql);
	$precio=0;
 if (mysqli_num_rows($resp)>0){ 
	$dat=mysqli_fetch_array($resp);
	$precio=$dat[0];
	}
	return($precio);
}

function precioProductoSucursalMasDescuento($enlaceCon,$item,$sucursal){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");
	$sql="SELECT p.precio, p.descuento_unitario from precios p where p.codigo_material='$item' and p.cod_precio='1' 
				and p.cod_ciudad='$sucursal'";	
	$resp=mysqli_query($enlaceCon,$sql);
 	$arrayPrecios=[0,0];
 	if (mysqli_num_rows($resp)>0){ 
		$dat=mysqli_fetch_array($resp);
		$arrayPrecios[0]=$dat[0];
		$arrayPrecios[1]=$dat[1];
	}
	return($arrayPrecios);
}

function precioProductoSucursalCalculadoSinMayorista($enlaceCon,$item,$sucursal){
	/**** Este es el precio que se utilizara en todo lado es el oficial menos el mayorista  *****/
	$fechaActual=date("Y-m-d");
	$banderaPorcentajeDescuentoPrecio=obtenerValorConfiguracion($enlaceCon, 52);
	$porcentajeVentaProd=obtenerValorConfiguracion($enlaceCon, 53);
	
	$sql="SELECT p.precio, p.descuento_unitario from precios p where p.`codigo_material`='$item' and p.`cod_precio`='1' 
				and p.cod_ciudad='$sucursal'";	
	$resp=mysqli_query($enlaceCon,$sql);
	$precio=0;
	$descuentoUnitario=0;
	$descuentoUnitarioBs=0;
 	if (mysqli_num_rows($resp)>0){ 
		$dat=mysqli_fetch_array($resp);
		$precio=$dat[0];
		$descuentoUnitario=$dat[1];
	}
	if($banderaPorcentajeDescuentoPrecio==1){
		if($descuentoUnitario>0){
			$descuentoUnitario=$descuentoUnitario*($porcentajeVentaProd/100);
		}
		if($descuentoUnitario>0){
			$descuentoUnitarioBs=$precio*($descuentoUnitario/100);
		}		
	}else{
		$descuentoUnitarioBs=0;
	}

	$precio=$precio-$descuentoUnitarioBs;
	return($precio);
}

function precioProductoSucursalCalculado($enlaceCon,$item,$sucursal){
	/***** Este precio es el final calculado para una sucursal incluye el porcentaje mayor y el porcentaje de mayoreo *****/
	$fechaActual=date("Y-m-d");

	$porcentajeVentaProd=obtenerValorConfiguracion($enlaceCon, 53);
 	$porcentajePrecioMayorista=precioMayoristaSucursal($enlaceCon,$sucursal);

	$sql="SELECT p.precio, p.descuento_unitario from precios p where p.`codigo_material`='$item' and p.`cod_precio`='1' 
				and p.cod_ciudad='$sucursal'";	
	$resp=mysqli_query($enlaceCon,$sql);
	$precio=0;
	$descuentoUnitario=0;
	$descuentoUnitarioBs=0;
 	if (mysqli_num_rows($resp)>0){ 
		$dat=mysqli_fetch_array($resp);
		$precio=$dat[0];
		$descuentoUnitario=$dat[1];
	}
	if($descuentoUnitario>0){
		$descuentoUnitario=$descuentoUnitario*($porcentajeVentaProd/100);
	}
	$descuentoUnitario=$descuentoUnitario+$porcentajePrecioMayorista;
	if($descuentoUnitario>0){
		$descuentoUnitarioBs=$precio*($descuentoUnitario/100);
	}
	$precio=$precio-$descuentoUnitarioBs;
	return($precio);
}

function precioProductoSucursalCalculadoArray($enlaceCon,$item,$sucursal){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");

	$porcentajeVentaProd=obtenerValorConfiguracion($enlaceCon, 53);
 	$porcentajePrecioMayorista=precioMayoristaSucursal($enlaceCon,$sucursal);

	$sql="SELECT p.precio, p.descuento_unitario from precios p where p.`codigo_material`='$item' and p.`cod_precio`='1' 
				and p.cod_ciudad='$sucursal'";	
	$resp=mysqli_query($enlaceCon,$sql);
	$precio=0;
	$descuentoUnitario=0;
	$descuentoUnitarioBs=0;
 	if (mysqli_num_rows($resp)>0){ 
		$dat=mysqli_fetch_array($resp);
		$precio=$dat[0];
		$descuentoUnitario=$dat[1];
		$descuentoUnitarioCompra=$descuentoUnitario;
	}
	if($descuentoUnitario>0){
		$descuentoUnitario=$descuentoUnitario*($porcentajeVentaProd/100);
	}
	$descuentoUnitario=$descuentoUnitario+$porcentajePrecioMayorista;
	if($descuentoUnitario>0){
		$descuentoUnitarioBs=$precio*($descuentoUnitario/100);
	}
	$precioFinal=$precio-$descuentoUnitarioBs;
	$vectorPrecios = array($precio, $precioFinal, $descuentoUnitarioCompra, $porcentajeVentaProd, $porcentajePrecioMayorista);
	return($vectorPrecios);
}

function margenLinea($enlaceCon,$item){
	//require("conexionmysqli.php");
	$fechaActual=date("Y-m-d");

	$sql="select p.margen_precio from material_apoyo m, proveedores_lineas p where 
		p.cod_linea_proveedor=m.cod_linea_proveedor and m.codigo_material=$item;";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$margen=0;
	$margen=$dat[0];
	return($margen);
}

function descargarPDFArqueoCajaVertical($nom,$html){
 //aumentamos la memoria  
 ini_set("memory_limit", "128M");
 // Cargamos DOMPDF
 require_once 'assets/libraries/dompdf/dompdf_config.inc.php';
 $mydompdf = new DOMPDF();
 $mydompdf->set_paper('legal', 'portrait');
 ob_clean();
 $mydompdf->load_html($html);
 $mydompdf->render();
 $canvas = $mydompdf->get_canvas();
 $canvas->page_text(500, 970, "Página:  {PAGE_NUM} de {PAGE_COUNT}", Font_Metrics::get_font("sans-serif"), 9, array(0,0,0)); 
 $mydompdf->set_base_path('assets/libraries/plantillaPDFArqueo.css');
 $mydompdf->stream($nom.".pdf", array("Attachment" => false));
}

function actualizarPrecios($enlaceCon, $codProducto, $arrayPrecios, $descuento){
	foreach ( $arrayPrecios as $clave => $valor ){
	    
	    //echo "ciudad: ".$clave." valor: ".$valor."<br>";
	    
	    $sqlVerificaPrecio="select count(*) from precios p where p.cod_precio=1 and p.codigo_material='$codProducto' and p.cod_ciudad='$clave'";
		 $respVerificaPrecio=mysqli_query($enlaceCon, $sqlVerificaPrecio);
	    $bandera=0;
	    if($datVerificaPrecio=mysqli_fetch_array($respVerificaPrecio)){
	    	$bandera=$datVerificaPrecio[0];
	    }

	    if($bandera==0){    //insertamos
	    	$sqlActPrecio="insert into precios (codigo_material, cod_precio, precio, cod_ciudad, descuento_unitario) values 
	    	('$codProducto','1','$valor','$clave','$descuento')";
	    }elseif($bandera>0){
	    	$sqlActPrecio="update precios set precio='$valor', descuento_unitario='$descuento' where codigo_material='$codProducto' and cod_precio=1 and 
	    		cod_ciudad='$clave'";
	    }	    
	    
	    //echo $sqlActPrecio."<br>";	    
	    
	    $respPrecio=mysqli_query($enlaceCon,$sqlActPrecio);
	}
	return(1);
}

function actualizarPreciosConPorcentajes($enlaceCon, $codProducto, $arrayPrecios, $arrayPorcentajes){
	foreach ( $arrayPrecios as $clave => $valor ){
	    $sqlVerificaPrecio="select count(*) from precios p where p.cod_precio=1 and p.codigo_material='$codProducto' and p.cod_ciudad='$clave'";
		 $respVerificaPrecio=mysqli_query($enlaceCon, $sqlVerificaPrecio);
	    $bandera=0;
	    if($datVerificaPrecio=mysqli_fetch_array($respVerificaPrecio)){
	    	$bandera=$datVerificaPrecio[0];
	    }

	    $porcentajePrecio=$arrayPorcentajes[$clave];
	    
	    if($bandera==0){    //insertamos
	    	$sqlActPrecio="insert into precios (codigo_material, cod_precio, precio, cod_ciudad, descuento_unitario) values 
	    	('$codProducto','1','$valor','$clave','$porcentajePrecio')";
	    }elseif($bandera>0){
	    	$sqlActPrecio="update precios set precio='$valor', descuento_unitario='$porcentajePrecio' where codigo_material='$codProducto' and cod_precio=1 and 
	    		cod_ciudad='$clave'";
	    }	    
	    
	    //echo $sqlActPrecio."<br>";	    
	    
	    $respPrecio=mysqli_query($enlaceCon,$sqlActPrecio);
	}
	return(1);
}

function obtenerMontoVentasGeneradas($desde,$hasta,$sucursal,$tipoPago){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
	$sql="select sum(s.monto_final) as monto
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($sucursal))
	and s.`fecha` BETWEEN '$desde' and '$hasta' and 
	s.cod_tipopago in ($tipoPago)";
  //echo $sql;	
  $resp=mysqli_query($enlaceCon,$sql);
  $monto=0;				
  while($detalle=mysqli_fetch_array($resp)){	
       $monto+=$detalle[0];   		
  }  
  mysqli_close($enlaceCon);
  return $monto;
}

function obtenerAlmacenesDeCiudadString($subGrupo){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
	$sql="SELECT GROUP_CONCAT(cod_almacen) from almacenes where cod_ciudad in ($subGrupo) GROUP BY cod_ciudad;";
    $resp=mysqli_query($enlaceCon,$sql);
    $datos=[];$index=0;				
    while($detalle=mysqli_fetch_array($resp)){
       $datos[$index]=$detalle[0];
       $index++;		 		
    }  
    return implode(",", $datos);
}


function obtenerMaterialesStringDeLinea($subGrupo){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
	$sql="SELECT GROUP_CONCAT(codigo_material) from material_apoyo where cod_linea_proveedor in ($subGrupo) GROUP BY cod_linea_proveedor;";
    $resp=mysqli_query($enlaceCon,$sql);
    $datos=[];$index=0;				
    while($detalle=mysqli_fetch_array($resp)){
       $datos[$index]=$detalle[0];
       $index++;		 		
    }  
    return implode(",", $datos);
}

function obtenerMontoVentasGeneradasLineaProducto($desde,$hasta,$almacenes,$tipoPago,$subGrupo,$formato){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
      $sql="select s.cod_salida_almacenes
	from salida_almacenes s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in ($almacenes)
	and s.`fecha` BETWEEN '$desde' and '$hasta' and 
	s.cod_tipopago in ($tipoPago)";
  $resp=mysqli_query($enlaceCon,$sql);
  $datos=[];$index=0;			
  while($detalle=mysqli_fetch_array($resp)){
  	   $datos[$index]=$detalle[0];
       $index++;
  } 
  $codigoSalida=implode(",", $datos);
  $sqlDetalle="SELECT sum(cantidad_unitaria*monto_unitario) FROM salida_detalle_almacenes where cod_salida_almacen in ($codigoSalida) and cod_material in ($subGrupo)";
  $respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
  $monto=0;		
  while($detalleLinea=mysqli_fetch_array($respDetalle)){	
     $monto+=$detalleLinea[0];   		
  } 
  mysqli_close($enlaceCon);
  return $monto;
}

function obtenerVentaClienteCampania($enlaceCon, $codCliente, $fechaVenta){
	$sqlCampanias="SELECT c.codigo, c.nombre, c.abreviatura, c.fecha_inicio, c.fecha_fin from campanias c where 
	c.fecha_inicio<='$fechaVenta' and c.fecha_fin>='$fechaVenta' and c.estado_campania=3";
	$respCampanias=mysqli_query($enlaceCon, $sqlCampanias);
	$codCampaniaHabilitada=0;
	$abrevCampaniaHabilitada="";
	$montoVenta=0;
	if($datCampanias=mysqli_fetch_array($respCampanias)){
		$codCampaniaHabilitada=$datCampanias[0];
		$abrevCampaniaHabilitada=$datCampanias[2];
		$fechaInicioCampania=$datCampanias[3];
		$fechaFinCampania=$datCampanias[4];

		$sql="select (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria)cantidadventa, s.descuento, s.monto_total
			from `salida_almacenes` s, `salida_detalle_almacenes` sd 
			where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fechaInicioCampania' and '$fechaFinCampania'
			and s.cod_cliente='$codCliente' and s.`salida_anulada`=0 and s.`cod_tiposalida`=1001";
		$resp=mysqli_query($enlaceCon, $sql);
		$totalVentaClienteCampania=0;
		$montoVenta=0;
		while($datos=mysqli_fetch_array($resp)){	
			$montoVenta=$datos[0];
			$cantidad=$datos[1];
			$descuentoVenta=$datos[2];
			$montoNota=$datos[3];

			if($descuentoVenta>0){
				$porcentajeVentaProd=($montoVenta/$montoNota);
				$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
				$montoVenta=$montoVenta-$descuentoAdiProducto;
			}			
			$montoPtr=number_format($montoVenta,2,".",",");
			$cantidadFormat=number_format($cantidad,0,".",",");
			$totalVentaClienteCampania=$totalVentaClienteCampania+$montoVenta;
		}
	}
	$arrayCampania = array($abrevCampaniaHabilitada,$montoVenta);
	return($arrayCampania);
}

function precioMayoristaSucursal($enlaceCon, $sucursal){
	$sql="SELECT c.descuento_mayorista from ciudades c where c.cod_ciudad='$sucursal'";
 	$resp=mysqli_query($enlaceCon,$sql);
   $descuento=0;				
   while($detalle=mysqli_fetch_array($resp)){
	   $descuento=$detalle[0];
   }  
   return $descuento;
}

function obtenerFechaVencimiento($enlaceCon, $almacen, $codProducto){
	$sql="SELECT DATE_FORMAT(id.fecha_vencimiento, '%m/%Y')as fecha_vencimiento from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen  
		and i.ingreso_anulado=0 and id.cantidad_restante>0 and i.cod_tipoingreso in (1000,1003) and id.cod_material='$codProducto' 
		and id.fecha_vencimiento not in ('1969-12-30','0000-00-00')
		order by id.fecha_vencimiento asc limit 0,1";
	/*and i.cod_almacen='$almacen'*/	
 	$resp=mysqli_query($enlaceCon,$sql);
   $fechaVencimiento="";				
   if($detalle=mysqli_fetch_array($resp)){
	   $fechaVencimiento=$detalle[0];
   }  
   if($fechaVencimiento==""){
   	$sql="SELECT DATE_FORMAT(id.fecha_vencimiento, '%m/%Y')as fecha_vencimiento from ingreso_almacenes i, ingreso_detalle_almacenes id
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen  
		and i.ingreso_anulado=0 and i.cod_tipoingreso in (1000,1003) and id.cod_material='$codProducto' 
		and id.fecha_vencimiento not in ('1969-12-30','0000-00-00')
		order by id.fecha_vencimiento desc limit 0,1";
	 	$resp=mysqli_query($enlaceCon,$sql);
	   if($detalle=mysqli_fetch_array($resp)){
		   $fechaVencimiento=$detalle[0];
	   }	
   }
   return $fechaVencimiento;
}

function obtenerSaldoKardexAnterior($enlaceCon, $rpt_almacen, $rpt_item, $fecha){
	//desde esta parte viene el reporte en si
	$costoInicialKardex=0;
	$fecha_iniconsulta=$fecha;
	//aqui sacamos las existencias a una fecha
	$sql="select sum(id.cantidad_unitaria), sum(id.cantidad_unitaria*id.costo_almacen)as costo FROM ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha<'$fecha_iniconsulta'";
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon, $sql);
	$dat_existencias_afecha=mysqli_fetch_array($resp);
	$cantidad_ingresada_afecha=$dat_existencias_afecha[0];
	$costoIngresosAnterior=$dat_existencias_afecha[1];
	
	$sql_salidas_afecha="select sum(sd.cantidad_unitaria), sum(sd.cantidad_unitaria*sd.costo_almacen)as costo from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha<'$fecha_iniconsulta'";
	//echo $sql_salidas_afecha;
	$resp_salidas_afecha=mysqli_query($enlaceCon, $sql_salidas_afecha);
	$dat_salidas_afecha=mysqli_fetch_array($resp_salidas_afecha);
	$cantidad_sacada_afecha=$dat_salidas_afecha[0];
	$costoSalidasAnterior=$dat_salidas_afecha[1];
	$cantidad_inicial_kardex=$cantidad_ingresada_afecha-$cantidad_sacada_afecha;
	$costoInicialKardex=$costoIngresosAnterior-$costoSalidasAnterior;

	$vectorKardexAnterior=array($cantidad_inicial_kardex,$costoInicialKardex);
	return($vectorKardexAnterior);
}


function obtenerNombreDesDiasRegistrados($codigo){
  $cantidad=obtenerTotalDias();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_dia from tipos_precio_dias where cod_tipoprecio='$codigo'";
  $i=0;
  $diasArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $diasArray[$i]=obtenerNombreDia($detalle[0]);
       $i++;  		
  }  
  if(count($diasArray)==$cantidad){
  	return "TODOS";
  }else if(count($diasArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$diasArray);
  }
}
function obtenerNombreDesCiudadesRegistrados($codigo){
  $cantidad=obtenerTotalCiudades();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_ciudad from tipos_precio_ciudad where cod_tipoprecio='$codigo'";
  $i=0;
  $ciudadArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $ciudadArray[$i]=obtenerNombreCiudad($detalle[0]);
       $i++;  		
  }  
  if(count($ciudadArray)==$cantidad){
  	return "TODOS";
  }else if(count($ciudadArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$ciudadArray);
  }
}

function obtenerCiudadDesdeAlmacen($codigo){
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_ciudad from almacenes where cod_almacen='$codigo'";
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  $codCiudad=0;
  while($detalle=mysqli_fetch_array($resp)){	
  	   $codCiudad=$detalle[0];
  }  
  return($codCiudad);
}

function obtenerNombreDesCiudadesRegistradosGeneral($codigo){
  $cantidad=obtenerTotalCiudades();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_ciudad from tipos_preciogeneral_ciudad where cod_tipoprecio='$codigo'";
  $i=0;
  $ciudadArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $ciudadArray[$i]=obtenerNombreCiudad($detalle[0]);
       $i++;  		
  }  
  if(count($ciudadArray)==$cantidad){
  	return "TODOS";
  }else if(count($ciudadArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$ciudadArray);
  }
}

function obtenerNombreDesCiudadesRegistradosCampanaGeneral($codigo){
  $cantidad=obtenerTotalCiudades();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_ciudad from campana_general_ciudad where cod_campana='$codigo'";
  $i=0;
  $ciudadArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $ciudadArray[$i]=obtenerNombreCiudad($detalle[0]);
       $i++;  		
  }  
  if(count($ciudadArray)==$cantidad){
  	return "TODOS";
  }else if(count($ciudadArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$ciudadArray);
  }
}



function obtenerTotalDias(){
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT count(*) cantidad from dias where estado=1";
  $cantidad=0;				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $cantidad=$detalle[0];   		
  }  
  return $cantidad;
}
function obtenerTotalCiudades(){
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT count(*) cantidad from ciudades where cod_estadoreferencial=1";
  $cantidad=0;				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $cantidad=$detalle[0];   		
  }  
  return $cantidad;
}
function obtenerNombreDiaCompleto($dia){
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT nombre from dias where codigo='$dia'";
  $abrev="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $abrev=$detalle[0];   		
  }  
  return $abrev;
}

function obtenerNombreDia($dia){
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT abreviatura2 from dias where codigo='$dia'";
  $abrev="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $abrev=$detalle[0];   		
  }  
  return $abrev;
}

function obtenerNombreDesLineasRegistrados($codigo){
  $cantidad=obtenerTotalLineas();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_linea_proveedor from tipos_precio_lineas where cod_tipoprecio='$codigo'";
  $i=0;
  $lineaArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $lineaArray[$i]=obtenerNombreProveedorLinea($detalle[0]);
       $i++;  		
  }  
  if(count($lineaArray)==$cantidad){
  	return "TODOS";
  }else if(count($lineaArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$lineaArray);
  }
}

function obtenerNombreProveedorLinea($codigo){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor='$codigo'";
  $linea="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $linea=$detalle[0];   		
  }  
  mysqli_close($enlaceCon);
  return $linea;
}

function obtenerNombreDesProdRegistrados($codigo){
  $cantidad=obtenerTotalProd();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_material from tipos_precio_productos where cod_tipoprecio='$codigo'";
  $i=0;
  $lineaArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $lineaArray[$i]=obtenerNombreProductoLinea($detalle[0]);
       $i++;  		
  }  
  if(count($lineaArray)==$cantidad){
  	return "TODOS";
  }else if(count($lineaArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$lineaArray);
  }
}
function obtenerNombreDesProdRegistradosCampana($codigo){
  $cantidad=obtenerTotalProd();
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT cod_material from campana_general_productos where cod_campana='$codigo'";
  $i=0;
  $lineaArray=[];				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $lineaArray[$i]=obtenerNombreProductoLinea($detalle[0]);
       $i++;  		
  }  
  if(count($lineaArray)==$cantidad){
  	return "TODOS";
  }else if(count($lineaArray)==0){
  	return "NINGUNO";
  }else{
  	return implode(", ",$lineaArray);
  }
}

function obtenerTotalProd(){
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT count(*) cantidad from material_apoyo where estado=1";
  $cantidad=0;				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $cantidad=$detalle[0];   		
  }  
  return $cantidad;
}

function obtenerNombreProductoLinea($codigo){
	$estilosVenta=1;
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT descripcion_material from material_apoyo where codigo_material='$codigo'";
  $linea="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $linea=$detalle[0];   		
  }  
  mysqli_close($enlaceCon);
  return $linea;
}

function obtenerNombreCiudad($ciudad){
	require("conexionmysqli2.inc");
  $sql_detalle="SELECT descripcion from ciudades where cod_ciudad='$ciudad'";
  $nombre="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $nombre=$detalle[0];   		
  }  
  return $nombre;
}


function obtenerDescripcionMotivo($codigo,$ninguna){
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT descripcion from observaciones_clase where codigo='$codigo'";
  $nombre="";
  if($ninguna==1){
  	$nombre="OBSERVACIÓN ESPECÍFICA";
  }
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $nombre=$detalle[0];   		
  }  
  return $nombre;
}
function obtenerNombreProveedorDeLinea($codigo){
  $estilosVenta=1;
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT p.nombre_proveedor from proveedores p join proveedores_lineas l where l.cod_linea_proveedor='$codigo'";
  $proveedor="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $proveedor=$detalle[0];   		
  } 
  mysqli_close($enlaceCon); 
  return $proveedor;
}
function obtenerNombreProveedor($codigo){
  $estilosVenta=1;
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT nombre_proveedor from proveedores where cod_proveedor='$codigo'";
  $proveedor="";				
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
       $proveedor=$detalle[0];   		
  } 
  mysqli_close($enlaceCon); 
  return $proveedor;
}
function obtenerProveedorLineaInventario($codigo){
  require("conexionmysqli2.inc");
  $sql_detalle="SELECT DISTINCT p.nombre_proveedor 
	FROM inventarios_sucursal_detalle d 
	JOIN material_apoyo m on m.codigo_material=d.cod_material
	JOIN proveedores_lineas l on l.cod_linea_proveedor=m.cod_linea_proveedor
	JOIN proveedores p on p.cod_proveedor=l.cod_proveedor
	where d.cod_inventariosucursal=$codigo";
  $proveedor="";			
  $resp=mysqli_query($enlaceCon,$sql_detalle);
  while($detalle=mysqli_fetch_array($resp)){	
  	   $proveedor=$detalle[0];	
  } 
  mysqli_close($enlaceCon);
  return $proveedor; 
}

/**
 * Verificación de Stock de Producto en todas las Sucursales
 * FALSE: No existe Stock en todas las sucursales
 * TRUE: Existe Stock en alguna sucursal
 */
function verificaStockSucursales($cod_producto){
	require("conexionmysqli2.inc");
	// Realizar la consulta SQL
	$sql = "SELECT cod_almacen, nombre_almacen FROM almacenes";	
	$resp = mysqli_query($enlaceCon, $sql);

	$productoEncontrado = false;
	// Verificar si se obtuvieron resultados
	if ($resp) {
		// Recorrer los resultados con un bucle while
		while ($fila = mysqli_fetch_assoc($resp)) {
			$codAlmacen = $fila['cod_almacen'];
			$stock = stockProducto($enlaceCon,$codAlmacen, $cod_producto);        
			// Verificar si se encontró al menos un producto en stock
			if ($stock > 0) {
				// Cambiar el estado de la bandera y salir del bucle
				$productoEncontrado = true;
				break;
			}
		}
	}
	return $productoEncontrado;
}

/**
 * Obtiene valores de Producto
 */
function obtieneProductoDetalle($cod_material){
	$globalAlmacen	  = $_COOKIE['global_almacen'];
	$codCiudadIngreso = $_COOKIE['global_agencia'];
	require("conexionmysqli2.inc");
	
	$sql="SELECT m.codigo_material, m.descripcion_material, m.cantidad_presentacion, 
	(select concat(p.nombre_proveedor)
	from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)	
	from material_apoyo m, proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor and m.estado=1 
		and m.codigo_material = '$cod_material'";
	// echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		$cont=0;
		while($dat=mysqli_fetch_array($resp)){
			$cont++;
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=str_replace("," ,"", $nombre);
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];
			$linea=$dat[3];
			
			$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
			$precioProducto=precioProductoSucursalCalculadoSinMayorista($enlaceCon,$codigo,$codCiudadIngreso);
			if($precioProducto==""){
				$precioProducto=0;
			}
			$precioProductoF=formatonumeroDec($precioProducto);
			$margenLinea=margenLinea($enlaceCon,$codigo);			
			$datosProd=$codigo."|".$nombre."-".$linea."|".$cantidadPresentacion."|".$precioProducto."|".$margenLinea;
		
			return array(
				$codigo, 
				"$nombre - $linea", 
				$cantidadPresentacion, 
				$precioProducto, 
				$margenLinea
			);
			exit;
	
			// echo "<tr><td><input type='checkbox' id='idchk$cont' name='idchk$cont' value='$datosProd' onchange='ver(this)' ></td>
			// <td>$linea</td><td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre - $linea\", $cantidadPresentacion, $precioProducto, $margenLinea)'>$nombre</a></div></td><td><div class='textograndenegro'>$stockProducto</div></td><td><div class='textograndenegro'>$precioProductoF</div></td></tr>";
		}
	}else{
		return array(
			0,0,0,0,0
		);
	}
}

?>
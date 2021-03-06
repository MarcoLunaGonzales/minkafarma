<?php

function obtenerValorConfiguracion($id){
	require("conexion.inc");
	$sql = "SELECT valor_configuracion from configuraciones c where id_configuracion=$id";
	$resp=mysql_query($sql);
	$codigo=0;
	while ($dat = mysql_fetch_array($resp)) {
	  $codigo=$dat['valor_configuracion'];
	}
	return($codigo);
}

function generarCodigoAprobacion($codigo){
	//
	$nroDigitos = strlen("".$codigo);
	$nroDigitos--;//total digitos
	//
	$cadAux = strrev($codigo);
	$ultimoCar="".$cadAux[0];//ultimo digito
	//
	$cadAux = "".$codigo;
	$primerCar="".$cadAux[0];//primer digito
	//
	$acumulador=0;
	$cadAux="".$codigo;//echo "_$cadAux<br>";
	for($i=0;$i<=$nroDigitos;$i++)
	   {$acumulador+=$cadAux[$i];//echo "_$cadAux[$i]-----$i";
	   }
	$acumulador=$acumulador+100;//suma de digitos mas 100
	//
	//clave generada
	$claveGenerada="".$nroDigitos.$ultimoCar.$primerCar.$acumulador;	
	return $claveGenerada;
}

function formatNumberInt($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatNumberDec($valor) { 
   $float_redondeado=number_format($valor, 2); 
   return $float_redondeado; 
}

function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
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

function obtenerCodigo($sql)
{	require("conexion.inc");
	$resp=mysql_query($sql);
	$nro_filas_sql = mysql_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysql_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}


function margenLinea($item){
	require("conexion.inc");
	$fechaActual=date("Y-m-d");

	$sql="select p.margen_precio from material_apoyo m, proveedores_lineas p where 
		p.cod_linea_proveedor=m.cod_linea_proveedor and m.codigo_material=$item;";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$margen=0;
	$margen=$dat[0];
	return($margen);
}


function precioProducto($item){
	require("conexion.inc");
	$fechaActual=date("Y-m-d");

	$sql="SELECT p.`precio` from precios p where p.`codigo_material`='$item' and p.`cod_precio`='1'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$precio=0;
	$precio=$dat[0];
	return($precio);
}


function ubicacionProducto($almacen, $item){
	//
	require("conexion.inc");
	$fechaActual=date("Y-m-d");

	$sql_ingresos="select 
	(select u.nombre from ubicaciones_estantes u where u.codigo=id.cod_ubicacionestante)as estante,
	(select u.nombre from ubicaciones_filas u where u.codigo=id.cod_ubicacionfila)as fila
	from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0 and id.cantidad_restante>0 limit 0,1";
	//echo $sql_ingresos;
	$resp_ingresos=mysql_query($sql_ingresos);
	$dat_ingresos=mysql_fetch_array($resp_ingresos);
	$ubicacion=$dat_ingresos[0]."-".$dat_ingresos[1];
	return($ubicacion);
	
}

function stockProducto($almacen, $item){
	//
	require("conexion.inc");
	$fechaActual=date("Y-m-d");

	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0";
			$resp_ingresos=mysql_query($sql_ingresos);
			$dat_ingresos=mysql_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$fechaActual' and s.cod_almacen='$almacen'
			and sd.cod_material='$item' and s.salida_anulada=0";
			$resp_salidas=mysql_query($sql_salidas);
			$dat_salidas=mysql_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}

function stockProductoVencido($almacen, $item){
	//
	require("conexion.inc");
	$fechaActual=date("Y-m-d");

	$sql_ingresos="select sum(id.cantidad_restante) from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$almacen' and i.ingreso_anulado=0 and id.fecha_vencimiento<'$fechaActual' and id.cod_material='$item'";
	$resp_ingresos=mysql_query($sql_ingresos);
	$dat_ingresos=mysql_fetch_array($resp_ingresos);
	$cant_ingresos=$dat_ingresos[0];
	$stock2=$cant_ingresos;
	return($stock2);
}

function stockMaterialesEdit($almacen, $item, $cantidad){
	//
	require("conexion.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysql_query($consulta);
	$registro=mysql_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($codigo_registro){
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysql_query($sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysql_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysql_query($sql_actualiza);			
	}
	return(1);
}
function numeroCorrelativo($tipoDoc){
	require("conexion.inc");
	$banderaErrorFacturacion=0;
	//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
	$respConf=mysql_query($sqlConf);
	$facturacionActivada=mysql_result($respConf,0,0);

	$fechaActual=date("Y-m-d");
	$globalAgencia=$_COOKIE['global_agencia'];
	
	if($facturacionActivada==1 && $tipoDoc==1){
		//VALIDAMOS QUE LA DOSIFICACION ESTE ACTIVA
		$sqlValidar="select count(*) from dosificaciones d 
		where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual'";
		$respValidar=mysql_query($sqlValidar);
		$numFilasValidar=mysql_result($respValidar,0,0);
		
		if($numFilasValidar==1){
			$sqlCodDosi="select cod_dosificacion from dosificaciones d 
			where d.cod_sucursal='$globalAgencia' and d.cod_estado=1";
			$respCodDosi=mysql_query($sqlCodDosi);
			$codigoDosificacion=mysql_result($respCodDosi,0,0);
		
			if($tipoDoc==1){//validamos la factura para que trabaje con la dosificacion
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and cod_dosificacion='$codigoDosificacion'";	
			}else{
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc'";
			}
			//echo $sql;
			$resp=mysql_query($sql);
			$codigo=mysql_result($resp,0,0);
			
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,$codigoDosificacion);
			return $vectorCodigo;
		}else{
			$banderaErrorFacturacion=1;
			$vectorCodigo = array("DOSIFICACION INCORRECTA O VENCIDA",$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
	if(($facturacionActivada==1 && $tipoDoc!=1) || $facturacionActivada!=1){
		$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc'";
		//echo $sql;
		$resp=mysql_query($sql);
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
}

?>
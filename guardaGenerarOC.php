<?php

require("conexion.inc");
require("estilos.inc");
require("funciones.php");

$sql = "select cod_orden from orden_compra order by cod_orden desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $codigo = 1;
} else {
    $codigo = $dat[0];
    $codigo++;
}

$sql = "select nro_orden from orden_compra order by 1 desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $nro_correlativo = 1;
} else {
    $nro_correlativo = $dat[0];
    $nro_correlativo++;
}
$hora_sistema = date("H:i:s");

$fechaVencimiento=$_POST['fecha'];
$fechaVencimiento=formateaFechaVista($fechaVencimiento);
$tipoCambio=$_POST['tipoCambio'];
$proveedor=$_POST['proveedor'];
$nro_factura=$_POST['nro_factura'];
$tipo_pago=$_POST['tipo_pago'];
$propiedadOC=$_POST['propiedadOC'];
$observaciones=$_POST['observaciones'];
$totalVenta=$_POST['totalOCBs'];
$cantidad_material=$_POST['numeroItems'];
$descuento=$_POST['descuentoOCBs'];
$tipoFactura=$_POST['tipoFactura'];

$fecha_real=date("Y-m-d");


$consulta="insert into orden_compra 
	values($codigo,$nro_correlativo,$proveedor,'$fechaVencimiento','$observaciones',1,'$tipo_pago','$nro_factura','$totalOCBs', 
	'0', '$propiedadOC', '', '$tipoCambio','$descuento','$tipoFactura')";

	echo $consulta;

	$sql_inserta = mysql_query($consulta);

//////////
if($tipoFactura==1){
/*	$codAlmacen=1003;
	$sql = "select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
	$resp = mysql_query($sql);
	$dat = mysql_fetch_array($resp);
	$num_filas = mysql_num_rows($resp);
	if ($num_filas == 0) {
		$codigoIng = 1;
	} else {
		$codigoIng = $dat[0];
		$codigoIng++;
	}
	$sql = "select nro_correlativo from ingreso_almacenes where cod_almacen='$codAlmacen' order by cod_ingreso_almacen desc";
	$resp = mysql_query($sql);
	$dat = mysql_fetch_array($resp);
	$num_filas = mysql_num_rows($resp);
	if ($num_filas == 0) {
		$nro_correlativo = 1;
	} else {
		$nro_correlativo = $dat[0];
		$nro_correlativo++;
	}
	$hora_sistema = date("H:i:s");
	$consultaIng="insert into ingreso_almacenes 
	values($codigoIng,$codAlmacen,'1','$fechaVencimiento','$hora_sistema','Ingreso Aut.','0','0','$nro_correlativo',0,0,0,0,0,0,'$proveedor')";
	$respIng=mysql_query($consultaIng);*/
}
///////

for ($i = 1; $i <= $cantidad_material; $i++) {
	$cod_material = $_POST["codMaterial$i"];
    $cantidad=$_POST["cantidad$i"];
	$precioBruto=$_POST["precioBs$i"];
	$precioNeto=$_POST["precioBs$i"];
	
    $consulta="insert into orden_compra_detalle values($codigo,'$cod_material',' ', $cantidad,$precioBruto)";
    echo "bbb:$consulta";
    $sql_inserta2 = mysql_query($consulta);
	
	/*if($tipoFactura==1){
		$consultaIng="insert into ingreso_detalle_almacenes values($codigoIng,'$cod_material',$cantidad,$cantidad,0,0,0,0,0,0)";
		$respIng=mysql_query($consultaIng);
	}*/
}
echo "<script language='Javascript'>
    alert('Los datos fueron insertados correctamente.');
    location.href='navegador_ordenCompra.php';
    </script>";
?>
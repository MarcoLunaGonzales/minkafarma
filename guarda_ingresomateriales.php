<?php

require("conexion.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");

$sql = "select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $codigo = 1;
} else {
    $codigo = $dat[0];
    $codigo++;
}
$sql = "select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
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

$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=$_POST['nota_entrega'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$codSalida=$_POST['codSalida'];
$proveedor=$_POST['proveedor'];

$fecha_real=date("Y-m-d");


$consulta="insert into ingreso_almacenes 
	values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones','$codSalida','$nota_entrega','$nro_correlativo',0,0,0,$nro_factura,0,0,'$proveedor')";

$sql_inserta = mysql_query($consulta);
//echo "aaaa:$consulta";

for ($i = 1; $i <= $cantidad_material; $i++) {
	$cod_material = $_POST["material$i"];
    $cantidad=$_POST["cantidad_unitaria$i"];
	$precioBruto=$_POST["precio$i"];
	$precioNeto=$_POST["neto$i"];
	
	//$costo=$precioBruto/$cantidad;
	$costo=$precioBruto;
	
    $consulta="insert into ingreso_detalle_almacenes values($codigo,'$cod_material',$cantidad,$cantidad,$precioBruto,$precioBruto,$costo,$costo,$costo,$costo)";
    //echo "bbb:$consulta";
    $sql_inserta2 = mysql_query($consulta);
	
	$aa=recalculaCostos($cod_material, $global_almacen);
	
}
echo "<script language='Javascript'>
    alert('Los datos fueron insertados correctamente.');
    location.href='navegador_ingresomateriales.php';
    </script>";
?>
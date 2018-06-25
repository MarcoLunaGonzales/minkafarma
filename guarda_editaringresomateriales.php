<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$codIngreso=$_POST["codIngreso"];
$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=$_POST['nota_entrega'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$codSalida=$_POST['codSalida'];
$fecha_real=date("Y-m-d");


//$consulta="insert into ingreso_almacenes values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones',0,'$nota_entrega','$nro_correlativo',0,0,0,$nro_factura)";
$consulta="update ingreso_almacenes set cod_tipoingreso='$tipo_ingreso', nota_entrega='$nota_entrega', nro_factura_proveedor='$nro_factura', 
		observaciones='$observaciones', cod_salida_almacen='$codSalida' where cod_ingreso_almacen='$codIngreso'";
$sql_inserta = mysql_query($consulta);

//echo "aaaa:$consulta";

$sqlDel="delete from ingreso_detalle_almacenes where cod_ingreso_almacen=$codIngreso";
$respDel=mysql_query($sqlDel);

for ($i = 1; $i <= $cantidad_material; $i++) {
	$cod_material = $_POST["material$i"];
    $cantidad=$_POST["cantidad_unitaria$i"];
	$precioBruto=$_POST["precio$i"];
	$precioNeto=$_POST["neto$i"];
	
    $consulta="insert into ingreso_detalle_almacenes values($codIngreso,'$cod_material',$cantidad,$cantidad,$precioNeto,$precioBruto,0,0,0,0)";
    //echo "bbb:$consulta";
    $sql_inserta2 = mysql_query($consulta);
}

echo "<script language='Javascript'>
    alert('Los datos fueron modificados correctamente.');
    location.href='navegador_ingresomateriales.php';
    </script>";

?>
<?php

require("conexion.inc");
require("funciones.php");
require("estilos_almacenes.inc");


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



$tipo_pago=$_POST['tipo_pago'];
$proveedor=$_POST['proveedor'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$totalVenta=$_POST['montoOC'];
$tipoCambio=$_POST['tipoCambio'];
$fecha=$_POST['fecha'];
$fecha=formateaFechaVista($fecha);

//$fecha_real=date("Y-m-d");


$consulta="insert into orden_compra 
	values($codigo,$nro_correlativo,$proveedor,'$fecha','$observaciones',1,$tipo_pago,$nro_factura,$totalVenta, '0', 2, '', $tipoCambio, 0,0)";

$sql_inserta = mysql_query($consulta);
echo "aaaa:$consulta";

echo "<script language='Javascript'>
    alert('Los datos fueron insertados correctamente.');
    location.href='navegador_ordenCompra.php';
    </script>";
?>
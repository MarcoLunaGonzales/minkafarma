<?php

require("conexion.inc");
if ($global_tipoalmacen == 1) {
    require("estilos_almacenes_central.inc");
} else {
    require("estilos_almacenes.inc");
}
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
$sql = "select nro_orden from orden_compra order by cod_orden desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $nro_correlativo = 1;
} else {
    $nro_correlativo = $dat[0];
    $nro_correlativo++;
}
$hora_sistema = date("Y-m-d H:i:s");

$vector_material = explode(",", $vector_material);
$vector_cantidades = explode(",", $vector_cantidades);
$vector_precio = explode(",", $vector_precio);

$consulta="insert into orden_compra values($codigo,$nro_correlativo,$cod_proveedor,'$hora_sistema','$observaciones',1)";

//echo $consulta;
$sql_inserta = mysql_query($consulta);

for ($i = 0; $i <= $cantidad_material - 1; $i++) {
    $cod_material = $vector_material[$i];
    $cantidad = $vector_cantidades[$i];
    //
    $precio = $vector_precio[$i];
    //
    $consulta="insert into orden_compra_detalle 
	values($codigo,'$cod_material','',$cantidad,$precio)";
    
	echo "bbb:$consulta";
    
	$sql_inserta2 = mysql_query($consulta);
}
/*echo "<script language='Javascript'>
    alert('Los datos fueron insertados correctamente.');
    location.href='navegador_ordenCompra.php';
    </script>";*/
?>
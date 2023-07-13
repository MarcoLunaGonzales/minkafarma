<?php

require("../../conexionmysqli.php");
require("../../funciones.php");

ob_clean(); // Limpiar el búfer de salida

$global_agencia = $_COOKIE['global_agencia'];

// Detalle Cabecera
$cod_cliente 	= $_POST['cod_cliente'];
$fecha_creacion = date('Y-m-d H:i:s');
$observacion 	= 'Cargado por Archivo Excel';

// Obtener el CODIGO del registro antes de eliminarlo
$query = "SELECT codigo FROM clientes_precios WHERE cod_cliente = $cod_cliente";
$result = mysqli_query($enlaceCon, $query);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $codigo_eliminado = $row['codigo'];
    // Limpia
    $resp=mysqli_query($enlaceCon,"DELETE FROM clientes_precios WHERE codigo = $codigo_eliminado");
    $resp=mysqli_query($enlaceCon,"DELETE FROM clientes_preciosdetalle WHERE cod_clienteprecio = $codigo_eliminado");
}
// Regista Detalle
$resp=mysqli_query($enlaceCon,"INSERT INTO clientes_precios(cod_cliente, fecha_creacion, cod_estado, observaciones) VALUES('$cod_cliente','$fecha_creacion',1,'$observacion')");
// Obtener el valor del campo CODIGO del registro insertado
$ultimo_codigo = mysqli_insert_id($enlaceCon);
// DETALLE
$detalle = $_POST['items'];
foreach($detalle as $item){
    $cod_clienteprecio   = $ultimo_codigo;
    $cod_producto        = $item['cod_producto'];
    $precio_base         = precioProductoSucursal($enlaceCon, $cod_producto, $global_agencia);
    $precio_producto     = $item['precio_producto'];
    $porcentaje_aplicado = $precio_base > 0 ? number_format((100 - (($precio_producto / $precio_base) * 100)), 2) : 0;
    $precio_aplicado     = $precio_base > 0 ? ($precio_base - $precio_producto) : 0;
	$sql_inserta = mysqli_query($enlaceCon,"INSERT INTO clientes_preciosdetalle(cod_clienteprecio,cod_producto,precio_base,porcentaje_aplicado,precio_aplicado,precio_producto) VALUES('".$cod_clienteprecio."','".$cod_producto."','".$precio_base."','".$porcentaje_aplicado."','".$precio_aplicado."','".$precio_producto."')");
}

echo true;
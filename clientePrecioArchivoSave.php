<?php

require("conexionmysqli.php");

ob_clean(); // Limpiar el búfer de salida

// Detalle Cabecera
$cod_cliente 	= $_POST['cod_cliente'];
$fecha_creacion = date('Y-m-d H:i:s');
$observacion 	= $_POST['observacion'];

// Obtener el CODIGO del registro antes de eliminarlo
$query = "SELECT codigo FROM clientes_precios WHERE cod_cliente = $cod_cliente";
$result = mysqli_query($enlaceCon, $query);
if (!$result) {
    echo "Error al obtener el CODIGO del registro: " . mysqli_error($enlaceCon);
    exit;
}
$row = mysqli_fetch_assoc($result);
$codigo_eliminado = $row['codigo'];
// Limpia
$resp=mysqli_query($enlaceCon,"DELETE FROM clientes_precios WHERE codigo = $codigo_eliminado");
// Regista Detalle
$resp=mysqli_query($enlaceCon,"INSERT INTO clientes_precios(cod_cliente, fecha_creacion, cod_estado, observaciones) VALUES('$cod_cliente','$fecha_creacion',1,'$observacion')");

// Obtener el valor del campo CODIGO del registro insertado
$ultimo_codigo = mysqli_insert_id($enlaceCon);

// DETALLE
$detalle = $_POST['items'];
// Limpia
$resp=mysqli_query($enlaceCon,"DELETE FROM clientes_preciosdetalle WHERE cod_clienteprecio = $codigo_eliminado");
foreach($detalle as $item){
	$sql_inserta=mysqli_query($enlaceCon,"INSERT INTO clientes_preciosdetalle(cod_clienteprecio,cod_producto,precio_base,porcentaje_aplicado,precio_aplicado,precio_producto) VALUES('".$ultimo_codigo."','".$item['materiales']."','".$item['precioUnitario']."','".$item['descuentoPorcentaje']."','".$item['descuentoMonto']."','".$item['montoMaterial']."')");
}
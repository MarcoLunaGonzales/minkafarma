<?php

require("../../conexionmysqli.php");
require("../../funciones.php");

ob_clean(); // Limpiar el búfer de salida

$global_agencia = $_COOKIE['global_agencia'];

// Detalle Cabecera
$cod_cliente 	= $_POST['cod_cliente'];
$fecha_creacion = date('Y-m-d H:i:s');
$observacion 	= 'Cargado por Archivo Excel';

$tipo           = $_POST['tipo']; // * 1:Limpia registro, 2:Registra
if($tipo==1){
    // Obtener el CODIGO del registro antes de eliminarlo
    $query = "SELECT codigo FROM clientes_precios WHERE cod_cliente = $cod_cliente";
    $result = mysqli_query($enlaceCon, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $codigo_eliminado = $row['codigo'];
        // Limpia
        $resp=mysqli_query($enlaceCon,"DELETE FROM clientes_precios WHERE codigo = ".intval($codigo_eliminado));
        $resp=mysqli_query($enlaceCon,"DELETE FROM clientes_preciosdetalle WHERE cod_clienteprecio = ".intval($codigo_eliminado));


    }
    echo true;
}else{
    // Buscar el último registro generado en la tabla clientes_precios para el cod_cliente especificado
    $query = "SELECT codigo FROM clientes_precios WHERE cod_cliente = $cod_cliente ORDER BY codigo DESC LIMIT 1";
    $result = mysqli_query($enlaceCon, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ultimo_codigo = $row['codigo'];
    } else {
        // No se encontraron registros, se procede a insertar uno nuevo
        $resp = mysqli_query($enlaceCon, "INSERT INTO clientes_precios(cod_cliente, fecha_creacion, cod_estado, observaciones) VALUES('$cod_cliente','$fecha_creacion',1,'$observacion')");
        $ultimo_codigo = mysqli_insert_id($enlaceCon);
    }

    // DETALLE
    $detalle = $_POST['items'];
    $count = 0;
    $registrados = 0;
    $noRegistrados = 0;
    
    foreach ($detalle as $item) {
        $cod_producto        = $item['cod_producto'];
        $precio_base         = precioProductoSucursal($enlaceCon, $cod_producto, $global_agencia);
        $precio_base         = empty($precio_base) ? "0" : $precio_base;
        $precio_producto     = !empty($item['precio_producto']) ? (is_numeric($item['precio_producto']) ? number_format(floatval($item['precio_producto']), 2) : "0") : "0";
        $porcentaje_aplicado = $precio_base > 0 ? number_format((100 - (($precio_producto / $precio_base) * 100)), 2) : 0;
        $precio_aplicado     = $precio_base > 0 ? number_format(($precio_base - $precio_producto), 2) : 0;
        
        // verificación de registro de datos
        // if (empty($precio_producto)) {
        //     $noRegistrados++;
        // } else {
        //     $registrados++;
        // }
    
        $values[] = "('$ultimo_codigo', '$cod_producto', '$precio_base', '$porcentaje_aplicado', '$precio_aplicado', '$precio_producto')";
    }
    
    // echo "Registros registrados: " . $registrados . "<br> Registros no registrados: " . $noRegistrados . "<br>";
    
    
    if (!empty($values)) {
        $valuesString = implode(',', $values);
        $sql = "INSERT INTO clientes_preciosdetalle (cod_clienteprecio, cod_producto, precio_base, porcentaje_aplicado, precio_aplicado, precio_producto) VALUES $valuesString";
    
        $sql_inserta = mysqli_query($enlaceCon, $sql);
    }
    echo true;
}
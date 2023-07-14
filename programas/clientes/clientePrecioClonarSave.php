<?php

require("../../conexionmysqli.php");
require("../../funciones.php");
date_default_timezone_set('America/La_Paz');

ob_clean(); // Limpiar el búfer de salida

// Detalle Cabecera
$cod_cliente 	     = $_POST['cod_cliente'];         // Código del cliente destino donde se duplicará el registro
$cod_cliente_clonado = $_POST['cod_cliente_clonado']; // Código del cliente origen para duplicar

// Limpia
// $resp=mysqli_query($enlaceCon,"DELETE FROM clientes_precios WHERE codigo = ".intval($cod_cliente));
// $resp=mysqli_query($enlaceCon,"DELETE FROM clientes_preciosdetalle WHERE cod_clienteprecio = ".intval($cod_cliente));

// CABECERA
// Realizar la duplicación del registro en una consulta SQL
$insertQuery = "INSERT INTO clientes_precios (cod_cliente,fecha_creacion,cod_estado,observaciones) 
                SELECT '$cod_cliente','".date('Y-m-d H:i:s')."',cod_estado,observaciones
                FROM clientes_precios 
                WHERE cod_cliente = '$cod_cliente_clonado'";
// Ejecutar la consulta de duplicación
$resp = mysqli_query($enlaceCon, $insertQuery);

if ($resp) {
    // Obtener el ID NUEVO
    $cod_clienteprecio = mysqli_insert_id($enlaceCon);
    // Se obtiene ID del CLONADO
    $sql    = "SELECT codigo FROM clientes_precios WHERE cod_cliente = '$cod_cliente_clonado' LIMIT 1";
    $result = mysqli_query($enlaceCon, $sql);
    $row    = mysqli_fetch_assoc($result);
    $cod_clienteprecio_clonado = $row['codigo'];
    // DETALLE
    // Realizar la duplicación del registro en una consulta SQL
    $insertQuery = "INSERT INTO clientes_preciosdetalle (cod_clienteprecio,cod_producto,precio_base,porcentaje_aplicado,precio_aplicado,precio_producto) 
                    SELECT '$cod_clienteprecio',cod_producto,precio_base,porcentaje_aplicado,precio_aplicado,precio_producto
                    FROM clientes_preciosdetalle 
                    WHERE cod_clienteprecio = '$cod_clienteprecio_clonado'";
    // Ejecutar la consulta de duplicación
    $resp = mysqli_query($enlaceCon, $insertQuery);
    echo json_encode([
        'message' => "Duplicación completada",
        'status'  => true
    ]);
} else {
    echo json_encode([
        'message' => "Error al duplicar el registro: " . mysqli_error($enlaceCon),
        'status'  => true
    ]);
}

exit;
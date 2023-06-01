<?php

require("conexionmysqli.php");

ob_clean(); // Limpiar el búfer de salida

// Detalle Cabecera
$cod_cliente  = $_POST['cod_cliente'];
$cod_producto = $_POST['cod_producto'];

$resp = mysqli_query($enlaceCon,"SELECT cpd.*
                FROM clientes_precios cp
                LEFT JOIN clientes_preciosdetalle cpd ON cpd.cod_clienteprecio = cp.codigo
                WHERE cpd.cod_producto = '$cod_producto'
                AND cp.cod_cliente = '$cod_cliente'
                LIMIT 1");

if ($resp) {
    // Obtener el primer registro de la consulta
    $registro = mysqli_fetch_assoc($resp);
  
    // Verificar si se encontró un registro
    if ($registro) {
        // Acceder a los valores de las columnas
        $porcentaje_aplicado = $registro['porcentaje_aplicado'];
        $precio_aplicado     = $registro['precio_aplicado'];
        $precio_base         = $registro['precio_base'];
        $precio_producto     = $registro['precio_producto'];
  
        echo json_encode(array(
            'data'    => [
                'porcentaje_aplicado'=> $porcentaje_aplicado,
                'precio_aplicado'    => $precio_aplicado,
                'precio_base'        => $precio_base,
                'precio_producto'    => $precio_producto,
            ],
            'message' => "Se encontraron registros.",
            'status'  => true
        ));
    } else {
        echo json_encode(array(
            'message' => "No se encontraron registros.",
            'status'  => false
        ));
    }
} else {
    echo json_encode(array(
        'message' => "Error al ejecutar la consulta: " . mysqli_error($enlaceCon),
        'status'  => false
    ));
}

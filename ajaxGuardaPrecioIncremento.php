<?php
require('conexion.inc');

/**
 * Actualizamos Masivamente los precios Incrementados
 */
$array = [];
$items = $_POST['items'];
// Recorrer los items y ejecutar la consulta SQL
foreach($items as $item) {
    // $cod_material = $item['cod_material'];
    // $precio_nuevo = $item['precio_nuevo'];
    // $array[] = $cod_material;
    // Ejecutar la consulta SQL para actualizar los datos
    // $sql = "UPDATE precios 
    //         SET precio = '$precio_nuevo' 
    //         WHERE codigo_material = '$cod_material' 
    //         AND cod_ciudad = '$cod_ciudad'";
    // // Ejecutar la consulta y verificar el resultado
    // $resp = mysqli_query($enlaceCon, $sql);
    // if($resp) {
    //     // La consulta se ejecutó correctamente
    //     echo "Actualización exitosa para el material con código: $cod_material";
    // } else {
    //     // Ocurrió un error al ejecutar la consulta
    //     echo "Error al actualizar el material con código: $cod_material";
    // }
}

echo $array;
?>
<?php
require("conexionmysqli.php");

// error_reporting(E_ALL);
// ini_set('display_errors', '1');
header('Content-Type: application/json');

try{
    // Limpiar el búfer de salida
    ob_clean();
    /**
     * Actualizamos Masivamente los precios Incrementados
     */
    $count_error = 0;
    $items       = $_POST['items'];
    // Recorrer los items y ejecutar la consulta SQL
    foreach($items as $item) {
        $cod_material = $item['cod_material'];
        $precio_nuevo = $item['precio_nuevo'];
        $cod_ciudad   = $item['cod_ciudad'];
        
        // Ejecutar la consulta SQL para actualizar los datos
        $sql = "UPDATE precios 
                SET precio = '$precio_nuevo' 
                WHERE codigo_material = '$cod_material' 
                AND cod_ciudad = '$cod_ciudad'";
        // Ejecutar la consulta y verificar el resultado
        $resp = mysqli_query($enlaceCon,$sql);
        // if($resp) {
        //     $count_error++;
        //     // La consulta se ejecutó correctamente
        //     // echo "Actualización exitosa para el material con código: $cod_material";
        // } else {
        //     // Ocurrió un error al ejecutar la consulta
        //     // echo "Error al actualizar el material con código: $cod_material";
        // }
    }
    echo json_encode(array(
        'message' => 'Actualización de registro exitoso.',
        'status'  => true
    ));
} catch (Exception $e) {
    echo json_encode(array(
        'message' => "Error de actualización de registros",
        'status' => false
    ));
}

?>
<?php
require("conexionmysqli.php");

header('Content-Type: application/json');

try {
    // Limpiar el búfer de salida
    ob_clean();

    $codIngresoAlmacen = $_POST['codIngresoAlmacen'];
    $codMaterial       = $_POST['codMaterial'];
    $lote              = $_POST['lote'];
    $nuevaFechaVencimiento = $_POST['nuevaFechaVencimiento'];

    // Ejecutar la consulta SQL para actualizar los datos
    $sql = "UPDATE ingreso_detalle_almacenes
            SET fecha_vencimiento = '$nuevaFechaVencimiento'
            WHERE cod_ingreso_almacen = '$codIngresoAlmacen'
            AND cod_material = '$codMaterial'
            AND lote = '$lote'";

    // Ejecutar la consulta y verificar el resultado
    $resp = mysqli_query($enlaceCon, $sql);

    if ($resp) {
        echo json_encode(array(
            'message' => 'Actualización de registro exitoso.',
            'status'  => true
        ));
    } else {
        throw new Exception('Error al actualizar el registro.');
    }
} catch (Exception $e) {
    echo json_encode(array(
        'message' => $e->getMessage(),
        'status' => false
    ));
}
?>

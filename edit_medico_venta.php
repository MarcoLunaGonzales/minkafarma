<?php
require("conexionmysqli.php");

$cod_salida_almacen = $_POST['cod_salida_almacen'];
$cod_medico         = $_POST['cod_medico'];

// Elimina la asignación de Medicos
$sqlDel="DELETE FROM recetas_salidas 
        WHERE cod_salida_almacen = '$cod_salida_almacen'";
mysqli_query($enlaceCon, $sqlDel);

$upd="INSERT INTO recetas_salidas (cod_medico, cod_salida_almacen)
        VALUES ('$cod_medico', '$cod_salida_almacen')";
$resp = mysqli_query($enlaceCon, $upd);

ob_clean();
if ($resp) {
    echo json_encode(array(
            "success" => true, 
            "message" => "Se modifico el médico asignado a la Venta."
        ));
} else {
    // Si ocurrió un error durante la actualización, devuelve una respuesta negativa
    echo json_encode(array(
            "success" => false, 
            "message" => "Error al actualizar: " . $conn->error
        ));
}
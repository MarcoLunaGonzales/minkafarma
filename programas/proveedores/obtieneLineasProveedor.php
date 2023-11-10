<?php
require("../../conexionmysqli2.inc");
// Verifica si se ha enviado el parámetro cod_proveedor
if (isset($_POST['cod_proveedor'])) {
    $codProveedor = $_POST['cod_proveedor'];

    $sqlLineas = "SELECT pl.cod_linea_proveedor, pl.nombre_linea_proveedor 
                FROM proveedores_lineas pl
                WHERE pl.cod_proveedor = '$codProveedor'
                ORDER BY pl.nombre_linea_proveedor";
    $respLineas = mysqli_query($enlaceCon, $sqlLineas);

    $lineasArray = array();
    while ($linea = mysqli_fetch_assoc($respLineas)) {
        $lineasArray[] = $linea;
    }

    $lineasJSON = json_encode($lineasArray);

	ob_clean(); // Limpiar el búfer de salida
    // Devuelve el resultado como respuesta al AJAX
    echo $lineasJSON;
} else {
	ob_clean(); // Limpiar el búfer de salida
    // En caso de que no se haya enviado el parámetro cod_proveedor
    echo json_encode(array('error' => 'No se proporcionó el parámetro cod_proveedor.'));
}
?>

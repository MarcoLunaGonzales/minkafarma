<?php

require("conexionmysqli.php");

ob_clean(); // Limpiar el búfer de salida
date_default_timezone_set('America/La_Paz');
try {
    $cod_documento  = $_POST['cod_documento'];
    $sqlInsert = "UPDATE clientes_documentos SET cod_estado = 2 WHERE cod_documento = '$cod_documento'";
    $resp=mysqli_query($enlaceCon, $sqlInsert); 
    echo json_encode(array(
        'message' => 'Eliminación de archivo exitoso!',
        'status'  => true,
    ));   
} catch (\Throwable $th) {
    echo json_encode(array(
        'message' => 'Ocurrió un error en el proceso de eliminación.',
        'status'  => false
    ));
}
exit;
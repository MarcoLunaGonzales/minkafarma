<?php

require("conexionmysqli.php");

ob_clean(); // Limpiar el búfer de salida
date_default_timezone_set('America/La_Paz');
session_start();

$cod_cliente  = $_POST['cod_cliente'];
$nombre       = $_POST['nombre'];
// Preparación de archivo
$dir          = dirname(__FILE__) . "/assets/cliente_documento/";
$fecha= date('Y-m-d H:i:s');
$time_detail  = date('YmdHis', strtotime($fecha));
$file_name    = $time_detail . basename($_FILES["file"]["name"]);
$name         = $dir . $file_name;

if (move_uploaded_file($_FILES["file"] ["tmp_name"], $name)) {
    $sqlInsert = "INSERT INTO clientes_documentos (cod_cliente, nombre, archivo, fecha)
                    VALUES ('$cod_cliente', '$nombre', '$file_name', '$fecha')";
    $resp=mysqli_query($enlaceCon, $sqlInsert);    
    echo json_encode(array(
        'message' => 'Registro realizado con éxito!',
        'status'  => true,
    ));
} else {
    echo json_encode(array(
        'message' => 'Ocurrió un error en el proceso de registro.',
        'status'  => false
    ));
}
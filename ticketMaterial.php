<?php
$estilosVenta=1;
require('conexionmysqli.inc');
include 'assets/php-barcode-master/barcode.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Codigo de CIUDAD
$global_agencia = $_COOKIE["global_agencia"];

// Modigo de Material
$cod_material = $_GET['cod_material'];

$sqlConf="SELECT ma.codigo_material, ma.descripcion_material, ROUND(p.precio, 2)
            FROM material_apoyo ma 
            LEFT JOIN precios p ON p.codigo_material = ma.codigo_material
            WHERE ma.codigo_material = '$cod_material'
            AND p.cod_ciudad = '$global_agencia'
            AND p.cod_precio = 1
            LIMIT 1";
$respConf=mysqli_query($enlaceCon,$sqlConf);

$nombre_producto = mysqli_result($respConf,0,1);
$nombre_producto = mysqli_result($respConf,0,1);
$codigo          = mysqli_result($respConf,0,0);
$precio          = empty(mysqli_result($respConf,0,2)) ? '0.00' : mysqli_result($respConf,0,2);


barcode('codigo_barra/'.$codigo.'.png', $codigo, 20, 'horizontal', 'code128', true);

// Redirecciona a otro archivo PHP pasando los datos en la URL como parámetros
header('Location: ticketMaterialPrint.php?codigo=' . urlencode($codigo) . '&nombre=' . urlencode($nombre_producto) . '&precio=' . urlencode($precio));
exit;
?>
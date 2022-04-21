<?php

require("../../conexionmysqli.php");

$codPro = $_GET["codpro"];
$nomPro = $_GET["nompro"];
$dir = $_GET["dir"];
$tel1 = $_GET["tel1"];
$tel2 = $_GET["tel2"];
$contacto = $_GET["contacto"];

$nomPro = str_replace("'", "''", $nomPro);
$dir = str_replace("'", "''", $dir);
$tel1 = str_replace("'", "''", $tel1);
$tel2 = str_replace("'", "''", $tel2);
$contacto = str_replace("'", "''", $contacto);

$consulta="
    UPDATE proveedores SET
    nombre_proveedor = '$nomPro',
    direccion = '$dir',
    telefono1 = '$tel1',
    telefono2 = '$tel2',
    contacto = '$contacto'
    WHERE cod_proveedor = $codPro
";
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha modificado el proveedor.');listadoProveedores();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al modificar proveedor');</script>";
}

?>

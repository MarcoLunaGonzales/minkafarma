<?php

require("../../conexionmysqli.php");

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
INSERT INTO proveedores (cod_proveedor, nombre_proveedor, direccion, telefono1, telefono2, contacto)
VALUES ( (SELECT ifnull(max(p.cod_proveedor),0)+1 FROM proveedores p) , '$nomPro', '$dir', '$tel1', '$tel2', '$contacto')
";
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha adicionado un nuevo proveedor.');listadoProveedores();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al crear proveedor');</script>";
}

?>

<?php

require("../../conexion.inc");

$nomCli = $_GET["nomcli"];
$nit = $_GET["nit"];
$dir = $_GET["dir"];
$tel1 = $_GET["tel1"];
$mail = $_GET["mail"];
$area = $_GET["area"];
$fact = $_GET["fact"];
$tipoPrecio=$_GET["tipo_precio"];

$nomCli = str_replace("'", "''", $nomCli);
$nit = str_replace("'", "''", $nit);
$dir = str_replace("'", "''", $dir);
$tel1 = str_replace("'", "''", $tel1);
$mail = str_replace("'", "''", $mail);
$area = $area;
$fact = str_replace("'", "''", $fact);

$consulta="
INSERT INTO clientes (cod_cliente, nombre_cliente, nit_cliente, dir_cliente, telf1_cliente, email_cliente, cod_area_empresa, nombre_factura, cod_tipo_precio)
VALUES ( (SELECT ifnull(max(c.cod_cliente),0)+1 FROM clientes c) , '$nomCli', '$nit', '$dir', '$tel1', '$mail', $area, '$fact', '$tipoPrecio')
";
$resp=mysql_query($consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha adicionado un nuevo cliente.');listadoClientes();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al crear cliente');</script>";
}

?>

<?php

require("../../conexion.inc");

$codCli = $_GET["codcli"];

$consulta="DELETE FROM clientes WHERE cod_cliente in ($codCli) ";
$resp=mysql_query($consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha eliminado el cliente.');listadoClientes();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al eliminar cliente');</script>";
}

?>

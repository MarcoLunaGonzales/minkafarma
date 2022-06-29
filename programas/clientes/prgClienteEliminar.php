<?php

require("../../conexionmysqli.php");

$codCli = $_GET["codcli"];

$consulta="DELETE FROM clientes WHERE cod_cliente in ($codCli) ";
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha eliminado el cliente.');listadoClientes();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al eliminar cliente');</script>";
}

?>

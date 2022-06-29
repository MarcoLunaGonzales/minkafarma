<?php

require("../../conexion.inc");

$codPro = $_GET["codpro"];
$consulta="DELETE FROM proveedores WHERE cod_proveedor in ($codPro) ";
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha eliminado el proveedor.');listadoProveedores();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al eliminar proveedor');</script>";
}

?>

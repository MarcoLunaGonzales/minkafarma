<?php

require("../../conexionmysqli.php");

$codLinea = $_GET["datos"];
$codProveedor = $_GET["codProveedor"];

$consulta="update proveedores_lineas set estado=0 where cod_linea_proveedor in ($codLinea) and cod_proveedor='$codProveedor'";
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script>
		alert('Se eliminaron los datos.');
		location.href='navegadorLineasDistribuidores.php?codProveedor=$codProveedor';
		</script>";
} else {
    //echo "$consulta";
    echo "<script>
	alert('Error al eliminar proveedor');
	location.href='navegadorLineasDistribuidores.php?codProveedor=$codProveedor';
	</script>";
}

?>

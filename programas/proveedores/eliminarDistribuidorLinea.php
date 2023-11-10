<?php

require("../../conexionmysqli2.inc");
//  error_reporting(E_ALL);
//  ini_set('display_errors', '1');

$codDistribuidor   = $_GET["codDistribuidor"];
$codLineaProveedor = $_GET["codLineaProveedor"];

try {
	$eliminarConsulta = "DELETE FROM distribuidores_lineas WHERE cod_distribuidor = '$codDistribuidor' AND cod_linea_proveedor = '$codLineaProveedor'";
	$respEliminar = mysqli_query($enlaceCon, $eliminarConsulta);
	
	if (!$respEliminar) {
		echo "<script>
			alert('Error al eliminar la línea de proveedor $codigoLinea.');
			location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
			</script>";
	}else{
		echo "<script>
			alert('Se eliminaron la linea de proveedor correctamente.');
			location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
			</script>";
	}
} catch (Exception $e) {
	echo "<script>
		alert('Error al eliminar la línea de proveedor.');
		location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
		</script>";
}
?>

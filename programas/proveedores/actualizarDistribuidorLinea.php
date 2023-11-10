<?php

require("../../conexionmysqli2.inc");
//  error_reporting(E_ALL);
//  ini_set('display_errors', '1');

$codDistribuidor   		  = $_POST["codDistribuidor"];
$codLineaProveedorAntiguo = $_POST["cod_linea_proveedor_antiguo"];
$codLineaProveedor 		  = $_POST["cod_linea_proveedor"];


try {
	// Verificar si ya existe el registro
	$verificarConsulta = "SELECT COUNT(*)
						FROM distribuidores_lineas dl
						WHERE dl.cod_distribuidor = '$codDistribuidor'
						AND dl.cod_linea_proveedor = '$codLineaProveedor'";
	$verificarResp = mysqli_query($enlaceCon, $verificarConsulta);
	$verificarResultado = mysqli_fetch_array($verificarResp);
	// Si no existe, realizar la inserción
	if ($verificarResultado[0] == 0) {
		// Actualizar
		$actualizacionConsulta = "UPDATE distribuidores_lineas
			SET cod_linea_proveedor = '$codLineaProveedor'
			WHERE cod_distribuidor = '$codDistribuidor' AND cod_linea_proveedor = '$codLineaProveedorAntiguo'";
	
		$resp = mysqli_query($enlaceCon, $actualizacionConsulta);
	
		if ($resp) {
			echo "<script>
				alert('La actualización se realizó correctamente.');
				location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
				</script>";
		} else {
			echo "<script>
				alert('Error al actualizar la línea de proveedor.');
				location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
				</script>";
		}
	}
	echo "<script>
		alert('Ya se encuentra registrado la linea seleccionada.');
		location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
		</script>";

} catch (Exception $e) {
	echo "<script>
		alert('Error al registrar las líneas de proveedores.');
		location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
		</script>";
}

?>

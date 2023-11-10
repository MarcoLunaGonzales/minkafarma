<?php

require("../../conexionmysqli2.inc");
//  error_reporting(E_ALL);
//  ini_set('display_errors', '1');

$codDistribuidor   = $_POST["codDistribuidor"];
$codLineaProveedor = $_POST["cod_linea_proveedor"];


try {
	foreach ($codLineaProveedor as $codigoLinea) {
		// Verificar si ya existe el registro
		$verificarConsulta = "SELECT COUNT(*) FROM distribuidores_lineas WHERE cod_distribuidor = '$codDistribuidor' AND cod_linea_proveedor = '$codigoLinea'";
		$verificarResp = mysqli_query($enlaceCon, $verificarConsulta);
		$verificarResultado = mysqli_fetch_array($verificarResp);
		// Si no existe, realizar la inserción
		if ($verificarResultado[0] == 0) {
			// Realizar la inserción para cada línea seleccionada
			$consulta = "INSERT INTO distribuidores_lineas (cod_distribuidor, cod_linea_proveedor) VALUES ('$codDistribuidor', '$codigoLinea')";
			$resp = mysqli_query($enlaceCon, $consulta);
		}
	}
	
	echo "<script>
		alert('Se guardo la linea correctamente.');
		location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
		</script>";
} catch (Exception $e) {
	echo "<script>
		alert('Error al registrar las líneas de proveedores.');
		location.href='navegadorListaDistribuidorLineas.php?codProveedor=$codDistribuidor';
		</script>";
}

?>

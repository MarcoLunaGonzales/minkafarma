<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");


$usuarioVendedor=$_COOKIE['global_usuario'];

$tipoSalida=$_POST['tipoSalida'];
$tipoDoc=$_POST['tipoDoc'];
$almacenDestino=$_POST['almacen'];
$codCliente=$_POST['cliente'];

$tipoPrecio=$_POST['tipoPrecio'];
$razonSocial=$_POST['razonSocial'];
$nitCliente=$_POST['nitCliente'];

$observaciones=$_POST["observaciones"];
$almacenOrigen=$global_almacen;

$totalVenta=$_POST["totalVenta"];
$descuentoVenta=$_POST["descuentoVenta"];
$totalFinal=$_POST["totalFinal"];

$fecha=$_POST["fecha"];
$cantidad_material=$_POST["cantidad_material"];
$nroCorrelativoFactura=$_POST["nroCorrelativoFactura"];

if($descuentoVenta=="" || $descuentoVenta==0){
	$descuentoVenta=0;
}

$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);


$sql="SELECT IFNULL(max(s.nro_correlativo)+1,1) FROM salida_almacenes s WHERE s.cod_almacen='$global_almacen' 
 and s.`cod_tipo_doc`='$tipoDoc'";
$resp=mysql_query($sql);
$nro_correlativo=mysql_result($resp,0,0);

$fecha=formateaFechaVista($fecha);

//$fecha=date("Y-m-d");
$hora=date("H:i:s");



/*if($tipoDoc==1){
	$nro_correlativo=$nroCorrelativoFactura;
}else{
}*/

$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
		`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
		`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', 
		'$nitCliente', '$usuarioVendedor', '$vehiculo',0)";
$sql_inserta=mysql_query($sql_inserta);

if($sql_inserta==1){
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
		$precioUnitario=$_POST["precio_unitario$i"];
		$descuentoProducto=$_POST["descuentoProducto$i"];
		$montoMaterial=$_POST["montoMaterial$i"];
		
		$respuesta=descontar_inventarios($codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial);
		if($respuesta!=1){
			echo "<script>
				alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
			</script>";
		}	
	}

	if($tipoSalida==1001){
			echo "<script type='text/javascript' language='javascript'>
		alert('Los datos fueron insertados correctamente.');
		location.href='navegadorVentas.php';
		</script>";	
	}else{
			echo "<script type='text/javascript' language='javascript'>
		alert('Los datos fueron insertados correctamente.');
		location.href='navegador_salidamateriales.php';
		</script>";
	}
	
}else{
		echo "<script type='text/javascript' language='javascript'>
		alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		location.href='navegador_salidamateriales.php';
		</script>";
}

?>




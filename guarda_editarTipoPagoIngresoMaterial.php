<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");


$codIngreso    	 = $_POST['codIngreso'];
$cod_tipopago    = $_POST['cod_tipopago'];
$dias_credito    = $_POST['dias_credito'];
$fecha_factura_proveedor = empty($_POST['fecha_factura_proveedor']) ? '' : $_POST['fecha_factura_proveedor'];



$consulta = "UPDATE ingreso_almacenes 
            SET cod_tipopago = '$cod_tipopago',
                dias_credito = '$dias_credito',
                fecha_factura_proveedor = '$fecha_factura_proveedor'
            WHERE cod_ingreso_almacen = $codIngreso";

$sql_inserta = mysqli_query($enlaceCon,$consulta);
//echo "aaaa:$consulta";

if($sql_inserta==1){

	echo "<script language='Javascript'>
		alert('Los datos actualizados correctamente.');
		location.href='navegador_ingresomateriales.php';
		</script>";	
}else{
	echo "<script language='Javascript'>
		alert('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.');
		location.href='navegador_ingresomateriales.php';
		</script>";
}

?>
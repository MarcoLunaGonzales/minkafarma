<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$codigo_ingreso=$_POST['codigoIngreso'];
$factura=$_POST['factura'];
$nro_factura=$_POST['nro_factura'];
$cantidad_items=$_POST['numeroItems'];


$consulta="update ingreso_almacenes set factura_proveedor='$factura', nro_factura_proveedor='$nro_factura', estado_liquidacion=1
	where cod_ingreso_almacen='$codigo_ingreso'";

$sql_inserta = mysql_query($consulta);


for ($i = 1; $i <= $cantidad_items-1; $i++) {
	$cod_material = $_POST["material$i"];
    $precioBruto=$_POST["precioBruto$i"];
	$precioNeto=$_POST["precioNeto$i"];
	
    $consulta="update ingreso_detalle_almacenes set precio_bruto='$precioBruto', precio_neto='$precioNeto' 
		where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$cod_material'";
    
	//echo "bbb:$consulta";
    
	$sql_inserta2 = mysql_query($consulta);
}
echo "<script language='Javascript'>
    alert('Los datos fueron guardados correctamente.');
    opener.location.reload();
	window.close;
    </script>";
?>
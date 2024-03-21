<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

$fecha=date("Y-m-d");
$hora=date("H:i");

$globalCiudad=$_COOKIE["global_agencia"];
$globalAlmacen=$_COOKIE["global_almacen"];

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$banderaActPreciosTraspaso=obtenerValorConfiguracion($enlaceCon,24);
$txtActPrecios="";
if($banderaActPreciosTraspaso==1){
	$txtActPrecios="*** Los precios seran actualizados en el Traspaso.";
}else{
	$txtActPrecios="*** Los precios NO seran actualizados en el Traspaso.";
}

$sql_datos_salidaorigen="select s.nro_correlativo, s.cod_tiposalida, a.nombre_almacen, a.cod_ciudad from salida_almacenes s, almacenes a
where a.cod_almacen=s.cod_almacen and s.cod_salida_almacenes='$codigo_registro'";
$resp_datos_salidaorigen=mysqli_query($enlaceCon,$sql_datos_salidaorigen);
$datos_salidaorigen=mysqli_fetch_array($resp_datos_salidaorigen);
$correlativo_salidaorigen=$datos_salidaorigen[0];
$tipo_salidaorigen=$datos_salidaorigen[1];
$nombre_almacen_origen=$datos_salidaorigen[2];
$codSucursalOrigen=$datos_salidaorigen[3];
$observaciones="";


echo "<form action='guarda_ingresotransito.php' method='post'>";
echo "<h1></h1>";

echo "<table border='0' class='textotit' align='center'>
		<tr><th></th><th>Registrar Ingreso en Transito</th><th></th></tr>
		<tr><th align='left'><span class='textopequenorojo' style='background-color:yellow;'><b>$txtActPrecios</b></span></th>
			<th></th>
		</tr>
		</table><br>";

echo "<center>
	<table class='texto'>";
echo "<tr><th>Fecha</th><th>Nota de Ingreso</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
echo "<tr><td>";
	echo"<INPUT type='date' class='texto' value='$fecha' id='fecha' size='10' name='fecha' readonly>";
echo "<td><input type='text' disabled='true' size='40' name='' value='Salida:$correlativo_salidaorigen $nombre_almacen_origen' class='texto'></td>";
echo "<input type='hidden' name='nota_ingreso' value='Salida:$correlativo_salidaorigen $nombre_almacen_origen'>";


echo "<td align='center'><input type='text' class='texto' name='nombre_tipoingreso' value='INGRESO POR TRASPASO' size='30' readonly></td>";

echo "<input type='hidden' name='tipo_ingreso' value='1003'>";

echo "<input type='hidden' name='nro_factura' value='0'>";
echo "<input type='hidden' name='proveedor' value='0'>";

echo "<td align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='60'></td></tr>";
echo "</table><br>";

echo "<table class='texto'>";

$sql_detalle_salida="select s.cod_salida_almacen, s.cod_material, sum(s.cantidad_unitaria), s.costo_almacen, m.cantidad_presentacion
from salida_detalle_almacenes s, material_apoyo m where s.cod_material=m.codigo_material and s.cod_salida_almacen='$codigo_registro' and cantidad_unitaria>0 
group by cod_salida_almacen, cod_material";
$resp_detalle_salida=mysqli_query($enlaceCon,$sql_detalle_salida);
$cantidad_materiales=mysqli_num_rows($resp_detalle_salida);

echo "<input type='hidden' name='codigo_salida' value='$codigo_registro'>";
echo "<input type='hidden' name='cantidad_material' value='$cantidad_materiales'>";
echo "<tr><th width='5%'>&nbsp;</th><th width='30%'>Material</th><th width='20%'>Cantidad de Origen</th><th width='20%'>Cantidad Recibida</th><th width='25%'>Obs</th></tr>";

$indice_detalle=1;

/* Esta bandera es para cuando falta un precio de origen no pueda guardarse el mismo */
$banderaErrorPrecios=0;

while($dat_detalle_salida=mysqli_fetch_array($resp_detalle_salida))
{	$cod_material=$dat_detalle_salida[1];
	$cantidad_unitaria=$dat_detalle_salida[2];
	$costo_almacen=$dat_detalle_salida[3];
	$cantidadPresentacion=$dat_detalle_salida[4];

	$cantidad_unitaria=redondear2($cantidad_unitaria);
	
	echo "<tr><td align='center'>$indice_detalle</td>";
	$sql_materiales="select m.codigo_material, m.descripcion_material, p.nombre_proveedor, pl.nombre_linea_proveedor
	from material_apoyo m
	LEFT JOIN proveedores_lineas pl ON pl.cod_linea_proveedor=m.cod_linea_proveedor
	LEFT JOIN proveedores p ON p.cod_proveedor=pl.cod_proveedor
	where 
	m.codigo_material='$cod_material' and m.codigo_material<>0 order by m.descripcion_material";
	$resp_materiales=mysqli_query($enlaceCon,$sql_materiales);
	$dat_materiales=mysqli_fetch_array($resp_materiales);
	$nombre_material=$dat_materiales[1]." - ".$dat_materiales[2]." ".$dat_materiales[3];

	/*************************************************/
	/*** Verificar los Precios en origen y Destino ***/
	/*************************************************/
	$precioSucursalOrigen=precioProductoSucursal($enlaceCon, $cod_material, $codSucursalOrigen);
	$precioSucursalDestino=precioProductoSucursal($enlaceCon, $cod_material, $globalCiudad);

	$precioSucursalOrigenF=formatonumeroDec($precioSucursalOrigen);
	$precioSucursalDestinoF=formatonumeroDec($precioSucursalDestino);

	$txtObsPrecios="";
	
	if( ($precioSucursalOrigen>0 && $precioSucursalDestino==0) || ($banderaActPreciosTraspaso==1 && $precioSucursalOrigen>$precioSucursalDestino) ){
		$txtObsPrecios="<span style='color:red;'>El precio de la sucursal se actualizará a $precioSucursalOrigenF</span>";
	}
	if($precioSucursalOrigen==0 && $precioSucursalDestino==0){
		$txtObsPrecios="<span style='color:red;'>El producto no tiene precio registrado. Consultar con el administrador del sistema.</span>";
		$banderaErrorPrecios=1;	
	}
	/*************************************************/
	/*** Fin Verificar los Precios en origen y Destino ***/
	/*************************************************/

	echo "<td>$nombre_material</td>";
	echo "<input type='hidden' value='$cod_material' name='material$indice_detalle'>";
	echo "<input type='hidden' value='$cantidad_unitaria' name='cantidad_origen$indice_detalle'>";
	echo "<input type='hidden' value='$costo_almacen' name='precio$indice_detalle'>";
	echo "<input type='hidden' name='cantidadpresentacion$indice_detalle' id='cantidadpresentacion$indice_detalle' value='$cantidadPresentacion'>";
	echo "<input type='hidden' name='precio_unitario$indice_detalle' id='precio_unitario$indice_detalle' value='0'>";
	
	echo "<td align='center'>$cantidad_unitaria</td>";
	echo "<td><input type='number' name='cantidad_unitaria$indice_detalle' step='0.1' value='$cantidad_unitaria' class='texto' required></td>";
	echo "<td><span style='color:blue;'>Precio Origen: $precioSucursalOrigenF Precio Destino: $precioSucursalDestinoF</span><br>$txtObsPrecios</td>";
	echo "</tr>";
	$indice_detalle++;
}
echo "</table></center>";
$indice_detalle--;

echo "<input type='hidden' name='cantidad_material' value='$indice_detalle'>";
echo "<input type='hidden' name='cod_salida' value='$codigo_registro'>";

if($banderaErrorPrecios==0){
	echo "<div class='divBotones'>
	<input type='submit' class='boton' value='Guardar'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresotransito.php\"'>
	</div>";	
}


echo "</form>";
echo "</div></body>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>
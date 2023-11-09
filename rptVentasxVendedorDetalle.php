<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_GET['rpt_territorio'];
$rpt_persona=$_GET['rpt_persona'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Ventas por Vendedor
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";
	
$sql="SELECT 
(select t.abreviatura from tipos_docs t where t.codigo=s.cod_tipo_doc)as tipodoc, 
 s.nro_correlativo, s.fecha, s.hora_salida, m.cod_linea_proveedor,
 (select pp.nombre_proveedor from proveedores_lineas pl, proveedores pp where pl.cod_linea_proveedor=m.cod_linea_proveedor and pp.cod_proveedor=pl.cod_proveedor) as lineaproveedor,
 m.codigo_anterior, m.descripcion_material, 
 sd.cantidad_unitaria, sd.precio_unitario, sd.descuento_unitario, sd.monto_unitario,
 (select pre.precio from precios pre where pre.cod_precio=1 and pre.cod_ciudad=1 and pre.codigo_material=m.codigo_material)as precio_registrado,
 (select concat(f.paterno,' ',f.nombres) from funcionarios f where f.codigo_funcionario=s.cod_chofer)as vendedor, s.descuento, s.monto_total
from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m
where s.cod_salida_almacenes=sd.cod_salida_almacen and  sd.cod_material=m.codigo_material and 
s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad='$rpt_territorio') and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.salida_anulada=0  and s.cod_chofer in ($rpt_persona)
and s.cod_tiposalida=1001 order by vendedor, s.fecha, s.nro_correlativo, m.descripcion_material;";

//echo $sql;

$resp=mysqli_query($enlaceCon, $sql);

if($rpt_ver==1){
	echo "<br><table align='center' class='texto' width='100%'>
	<tr>
	<th colspan='7'>Vendedor</th>
	<th>Monto Venta</th>
	<th>Descuento</th>
	<th>Subtotal</th>
	</tr>";
}else{
	echo "<br><table align='center' class='texto' width='100%'>
	<tr>
	<th>Documento</th>
	<th>Fecha</th>
	<th>Vendedor</th>
	<th>Linea</th>
	<th>Item</th>
	<th>Cantidad</th>
	<th>Precio<br>Venta</th>
	<th>Monto Venta</th>
	<th>Descuento</th>
	<th>Subtotal</th>
	</tr>";
}

$totalVentaBruta=0;
$totalVenta=0;
$totalDescuentos=0;

$subTotalVenta=0;
$subTotalDescuentos=0;
$subTotalDiferenciaPrecios=0;

$sumaDiferenciaPreciosCabecera=0;

$vendedorPivote="XXX";

while($datos=mysqli_fetch_array($resp)){	
	$numeroDoc=$datos[0]."-".$datos[1];	
	$fechaHora=$datos[2]." ".$datos[3];
	$lineaProveedor=$datos[5];
	$codInterno=$datos[6];
	$nombreItem=$datos[7];
	$cantidadItem=$datos[8];
	$cantidadItemF=formatonumero($cantidadItem);
	$precioItem=$datos[9];
	$precioItemF=formatonumeroDec($precioItem);
	$descuentoVenta=$datos[10];
	$descuentoVentaF=formatonumeroDec($descuentoVenta);
	
	$txtDiferenciaPorcentualPrecio="-";
	$txtDiferenciaBsPrecio="-";
	$diferenciaBsPrecio=0;
	
	$precioRegistrado=$datos[12];
	$precioRegistradoF=formatonumeroDec($precioRegistrado);
	
	$nombreVendedor=$datos[13];

	$diferenciaPorcentualPrecio=(($precioItem-$precioRegistrado)/$precioRegistrado)*100;
	$diferenciaPorcentualPrecioF=formatonumeroDec($diferenciaPorcentualPrecio);
	$diferenciaBsPrecio=$precioItem-$precioRegistrado;
	$diferenciaBsPrecioF=formatonumeroDec($diferenciaBsPrecio);
	
	if($diferenciaPorcentualPrecio<0){
		$txtDiferenciaPorcentualPrecio="<span style='color:red; font-size:16px;'>$diferenciaPorcentualPrecioF %</span>";
		$txtDiferenciaBsPrecio="<span style='color:red; font-size:15px;'>$diferenciaBsPrecioF</span>";
		$sumaDiferenciaPreciosCabecera=$sumaDiferenciaPreciosCabecera+$diferenciaBsPrecio;
	}


	$totalItem=$cantidadItem*$precioItem;
	$totalItemF=formatonumeroDec($totalItem);	
	$montoItem=$totalItem-$descuentoVenta;
	
	/* Esta parte saca el descuento cabecera */
	$descuentoVentaCab=$datos[14];
	$montoNotaCab=$datos[15];
	$descuentoAdiProducto=0;
	if($descuentoVentaCab>0){
		$porcentajeVentaProd=($montoItem/$montoNotaCab);
		$descuentoAdiProducto=($descuentoVentaCab*$porcentajeVentaProd);
		$montoItem=$montoItem-$descuentoAdiProducto;
	}
	/* Fin saca el descuento cabecera */


	$montoItemF=formatonumeroDec($montoItem);

	


	if($vendedorPivote!=$nombreVendedor && $nombreVendedor!="XXX" ){
		$subTotalUtilidad=$subTotalVenta-$subTotalCosto;
		$subTotalUtilidadF=number_format($subTotalUtilidad,2,".",",");
		$subTotalVentaF=number_format($subTotalVenta,2,".",",");
		$subTotalDescuentosF=number_format($subTotalDescuentos,2,".",",");
		
		if($diferenciaBsPrecio<0){
			$subTotalDiferenciaPrecios=$subTotalDiferenciaPrecios+$diferenciaBsPrecio;
		}
		$subTotalDiferenciaPreciosF="<span style='color:red; font-size:16px;'>".number_format($subTotalDiferenciaPrecios,2,".",",")."</span>";	
		
		$subTotalVentaX=$subTotalVenta-$subTotalDescuentos;
		$subTotalVentaXF=number_format($subTotalVentaX,2,".",",");
		if($vendedorPivote!="XXX"){
			echo "<tr>
			<td class='textomedianorojo' colspan='7'>$vendedorPivote</td>
			<td align='right' class='textomedianorojo'>$subTotalVentaF</td>
			<td align='right' class='textomedianorojo'>$subTotalDescuentosF</td>
			<td align='right' class='textomedianorojo'>$subTotalVentaXF</td>
			</tr>";			
		}
		$subTotalDescuentos=0;
		$subTotalVenta=0;
		$subTotalDiferenciaPrecios=0;
		$vendedorPivote=$nombreVendedor;
	}

	$subTotalDescuentos=$subTotalDescuentos+$descuentoVenta+$descuentoAdiProducto;

	//Sumamos el total de las ventas brutas
	$subTotalVenta=$subTotalVenta+$totalItem;

	if($diferenciaBsPrecio<0){
		$subTotalDiferenciaPrecios=$subTotalDiferenciaPrecios+$diferenciaBsPrecio;
	}

	$totalVenta=$totalVenta+$montoItem;
	$totalVentaBruta=$totalVentaBruta+$totalItem;
	$totalDescuentos=$totalDescuentos+$descuentoVenta+$descuentoAdiProducto;

	if($rpt_ver==2){
		echo "<tr>
		<td>$numeroDoc</td>
		<td>$fechaHora</td>
		<td><small><b>$nombreVendedor</b></small></td>
		<td><small>$lineaProveedor</small></td>
		<td><small><b>$nombreItem</b></small></td>
		<td>$cantidadItemF</td>
		<td align='right'>$precioItemF</td>
		<td align='right'>$totalItemF</td>
		<td align='right'>$descuentoVentaF</td>
		<td align='right'>$montoItemF</td>
		</tr>";
	}
}

$subTotalVentaF=number_format($subTotalVenta,2,".",",");
$subTotalDescuentosF=number_format($subTotalDescuentos,2,".",",");
$subTotalDiferenciaPreciosF="<span style='color:red; font-size:16px;'>".number_format($subTotalDiferenciaPrecios,2,".",",")."</span>";	
$subTotalVentaX=$subTotalVenta-$subTotalDescuentos;
$subTotalVentaXF=number_format($subTotalVentaX,2,".",",");

if($vendedorPivote!="XXX"){
	echo "<tr>
	<td class='textomedianorojo' colspan='7'>$vendedorPivote</td>
	<td align='right' class='textomedianorojo'>$subTotalVentaF</td>
	<td align='right' class='textomedianorojo'>$subTotalDescuentosF</td>
	<td align='right' class='textomedianorojo'>$subTotalVentaXF</td>
	</tr>";			
}

$totalVentaF=formatonumeroDec($totalVenta);
$totalVentaBrutaF=formatonumeroDec($totalVentaBruta);
$totalDescuentosF=formatonumeroDec($totalDescuentos);

$sumaDiferenciaPreciosCabeceraF="<span style='color:red; font-size:16px;'>$sumaDiferenciaPreciosCabecera</span>";

//<!--td align='right'>$sumaDiferenciaPreciosCabeceraF</td>
echo "<tr>
	<td colspan='7'>TOTAL:</td>
	<td align='right'>$totalVentaBrutaF</td>
	<td align='right'>$totalDescuentosF</td>
	<td align='right'>$totalVentaF</td>
<tr>";

echo "</table>";
include("imprimirInc.php");
?>
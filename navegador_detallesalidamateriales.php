<?php

	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");

	$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
	$respEmpresa=mysqli_query($enlaceCon,$sqlEmpresa);
	$datEmpresa=mysqli_fetch_array($respEmpresa);
	$nombreEmpresa=$datEmpresa[0];//$nombreEmpresa=mysql_result($respEmpresa,0,0);
	$nitEmpresa=$datEmpresa[1];//$nitEmpresa=mysql_result($respEmpresa,0,1);
	$direccionEmpresa=$datEmpresa[2];//$direccionEmpresa=mysql_result($respEmpresa,0,2);
	
	
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones,
	s.nro_correlativo, s.territorio_destino, s.almacen_destino, (select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	(select c.dir_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	s.monto_total, s.descuento, s.monto_final
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$nro_correlativo=$dat[4];
	$territorio_destino=$dat[5];
	$almacen_destino=$dat[6];
	$nombreCliente=$dat[7];
	$direccionCliente=$dat[8];
	$montoNota=$dat[9];
	$montoNota=redondear2($montoNota);
	$descuentoNota=$dat[10];
	$descuentoNota=redondear2($descuentoNota);
	$montoFinal=$dat[11];
	$montoFinal=redondear2($montoFinal);

		
	echo "<table class='texto' align='center'>";
	echo "<tr><th align='left' width='30%'>$nombreEmpresa</th>
	<th align='center' width='30%'>Nota de Remision<br>Nro. $nro_correlativo</th>
	<th align='right' width='30%'>Fecha: $fecha_salida_mostrar</th>
	</tr>";
	
	echo "<tr><td align='left' class='bordeNegroTdMod'>Cliente: $nombreCliente<br>Direccion: $direccionCliente</td>
	<td align='right' colspan='2'>Observaciones: $obs_salida</td></tr>";
			
	echo "</table><br>";

	echo "<table border='0' class='texto' cellspacing='0' width='90%' align='center'>";
	
	echo "<tr><th>Codigo</th><th>Material</th><th>Vencimiento</th>
	<th>Cantidad</th></tr>";
	
	//echo "<tr><td colspan='4'>&nbsp;</td></tr>";
	echo "<form method='post' action=''>";
	
	$sql_detalle="select s.cod_material, m.descripcion_material, s.lote, s.fecha_vencimiento, 
		s.cantidad_unitaria, s.precio_unitario, s.`descuento_unitario`, s.`monto_unitario` 
		from salida_detalle_almacenes s, material_apoyo m
		where s.cod_salida_almacen='$codigo' and s.cod_material=m.codigo_material";
	
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	$indice=0;
	$montoTotal=0;
	$pesoTotal=0;

	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$nombre_material=$dat_detalle[1];
		$loteProducto=$dat_detalle[2];
		$fechaVencimiento=$dat_detalle[3];
		$cantidad_unitaria=$dat_detalle[4];
		$cantidad_unitariaF=formatonumero($cantidad_unitaria);
		$precioUnitario=$dat_detalle[5];
		$precioUnitario=redondear2($precioUnitario);
		$descuentoUnitario=$dat_detalle[6];
		$descuentoUnitario=redondear2($descuentoUnitario);
		$montoUnitario=$dat_detalle[7];
		$montoUnitario=redondear2($montoUnitario);
		
		echo "<tr>
			<td class='bordeNegroTdMod'>$cod_material</td>
			<td class='bordeNegroTdMod'>$nombre_material</td>
			<td align='center' class='bordeNegroTdMod'>$fechaVencimiento</td>
			<td class='bordeNegroTdMod' align='right'>$cantidad_unitariaF</td>
		</tr>";
		$indice++;
		$montoTotal=$montoTotal+$montoUnitario;
		$montoTotal=redondear2($montoTotal);
	
	}
	
	echo "</table>";
?>
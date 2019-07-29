<?php
require("conexion.inc");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$nroCorrelativoBusqueda=$_GET['nroCorrelativoBusqueda'];
$verBusqueda=$_GET['verBusqueda'];
$global_almacen=$_GET['global_almacen'];
$clienteBusqueda=$_GET['clienteBusqueda'];

$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);

echo "<center><table class='texto' cellspacing='0' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Nro. Factura</th><th>Fecha/hora<br>Registro Salida</th><th>Tipo de Salida</th>
	<th>Cliente</th><th>Razon Social</th><th>NIT</th><th>Observaciones</th><th>&nbsp;</th><th>&nbsp;</th></tr>";	
//
$consulta = "
	SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
	(select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
	s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
	(select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc 
	FROM salida_almacenes s, tipos_salida ts 
	WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 ";

if($nroCorrelativoBusqueda!="")
   {$consulta = $consulta."AND s.nro_correlativo='$nroCorrelativoBusqueda' ";
   }
if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=s.fecha AND s.fecha<='$fechaFinBusqueda' ";
   }
if($clienteBusqueda!=0){
	$consulta=$consulta." and cod_cliente='$clienteBusqueda' ";
}   
if($verBusqueda==1){
	$consulta=$consulta." AND estado_salida=4 ";
}
if($verBusqueda==2){
    $consulta=$consulta." AND salida_anulada=1 ";
}
$consulta = $consulta."ORDER BY s.fecha desc, s.nro_correlativo DESC";

//
$resp = mysql_query($consulta);
	
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
	$nombreCliente=$dat[10];
	$codTipoDoc=$dat[11];
	$razonSocial=$dat[12];
	$nitCli=$dat[13];
	
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
	
	$sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
	$respEstadoColor=mysql_query($sqlEstadoColor);
	$numFilasEstado=mysql_num_rows($respEstadoColor);
	if($numFilasEstado>0){
		$color_fondo=mysql_result($respEstadoColor,0,0);
	}else{
		$color_fondo="#ffffff";
	}
	$chk = "<input type='checkbox' name='codigo' value='$codigo'>";

	
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'>$nro_correlativo</td>";
    echo "<td align='center'>$fecha_salida_mostrar $hora_salida</td>";
    echo "<td>$nombre_tiposalida</td>";
    echo "<td>&nbsp;$nombreCliente</td><td>&nbsp;$razonSocial</td><td>&nbsp;$nitCli</td><td>&nbsp;$obs_salida</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
    
	/*echo "<td bgcolor='$color_fondo'><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Detalle'></a></td>";
	*/
	if($codTipoDoc==1){
		echo "<td  bgcolor='$color_fondo'><a href='formatoFactura.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
		echo "<td  bgcolor='$color_fondo'><a href='formatoFacturaExtendido.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Extendida'></a></td>";
	}
	else{
		echo "<td  bgcolor='$color_fondo'><a href='formatoNotaRemisionOficial.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
	}
	
	/*echo "<td  bgcolor='$color_fondo'><a href='notaSalida.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Grande'></a></td>";*/
	
	echo "</tr>";
}
echo "</table></center><br>";


?>

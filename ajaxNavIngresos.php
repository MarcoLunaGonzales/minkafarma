<?php
require("conexionmysqli.php");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$notaIngreso=$_GET['notaIngreso'];
$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];

$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);

echo "<center><table class='texto' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Nro. Factura Proveedor</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th><th>&nbsp;</th></tr>";
	
//
$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nro_factura_proveedor, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso
    AND i.cod_almacen='$global_almacen'";

if($notaIngreso!="")
   {$consulta = $consulta."AND i.nro_correlativo='$notaIngreso' ";
   }
if($fechaIniBusqueda!="" && $fechaFinBusqueda!="")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=i.fecha AND i.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=0){
	$consulta=$consulta." and cod_proveedor='$provBusqueda' ";
}   
$consulta = $consulta."ORDER BY i.nro_correlativo DESC";

//echo $consulta;
//

$resp = mysqli_query($enlaceCon,$consulta);
	
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];

    if($nota_entrega==0){
        $nota_entrega="-";
    }

    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
	$proveedor=$dat[8];
	
	$anio_ingreso=intval("$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]");
	$globalGestionActual=intval($_COOKIE["globalGestion"]);

    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
                where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
    $resp_verifica_movimiento = mysqli_query($enlaceCon,$sql_verifica_movimiento);
    $num_filas_movimiento = mysqli_num_rows($resp_verifica_movimiento);
    if ($num_filas_movimiento != 0) {
        $color_fondo = "#ffff99";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    if ($num_filas_movimiento == 0 && $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    //if ($anio_ingreso != $globalGestionActual) {
    if ($anio_ingreso != 2023 ) {
        $chkbox = "";
    }
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td>&nbsp;$obs_ingreso</td><td align='center'>
	<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'>
	<img src='imagenes/detalles.png' border='0' title='Ver Detalles del Ingreso' width='40'></a></td></tr>";
}

echo "</table></center><br>";


?>

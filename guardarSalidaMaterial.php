<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");


$tipoSalida=$_POST["tipoSalida"];
$tipoDoc=$_POST["tipoDoc"];
$almacenDestino=$_POST["almacen"];
$codCliente=$_POST["cliente"];
if($codCliente==""){$codCliente=0;}

$tipoPrecio=$_POST["tipoPrecio"];
$razonSocial=$_POST["razonSocial"];
$nitCliente=$_POST["nitCliente"];
$observaciones=$_POST["observaciones"];
$almacenOrigen=$global_almacen;
$totalVenta=$_POST["totalVenta"];
$descuentoVenta=$_POST["descuentoVenta"];
$totalFinal=$_POST["totalFinal"];
$chofer=$_POST["chofer"];
$vehiculo=$_POST["vehiculo"];

$fecha=$_POST["fecha"];

$cantidad_material=$_POST["cantidad_material"];

$nroCorrelativoFactura=$_POST["nroCorrelativoFactura"];

if($descuentoVenta==""){
	$descuentoVenta=0;
}

$sql="SELECT cod_salida_almacenes FROM salida_almacenes ORDER BY cod_salida_almacenes DESC";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}
$sql="SELECT s.nro_correlativo FROM salida_almacenes s WHERE s.cod_almacen='$global_almacen' 
 and s.`cod_tipo_doc`='$tipoDoc' ORDER BY s.cod_salida_almacenes DESC";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $nro_correlativo=1;
}
else
{   $nro_correlativo=$dat[0];
    $nro_correlativo++;
}

$fecha=formateaFechaVista($fecha);

//$fecha=date("Y-m-d");
$hora=date("H:i:s");



if($tipoDoc==1){
	$nro_correlativo=$nroCorrelativoFactura;
}else{
}

$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
		`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
		`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '0', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', 
		'$nitCliente', '$chofer', '$vehiculo',0)";

//echo $sql_inserta;

$sql_inserta=mysql_query($sql_inserta);

/*
//insertamos si es factura en el otro almacen
if($tipoDoc==1 && $almacenOrigen!=1003){
	$codigoSSS=$codigo+1;
	$codAlmacen=1003;
	$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
		`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
		`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado)
		values ('$codigoSSS', '$codAlmacen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '0', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$chofer', 
		'$vehiculo',0)";
	$sql_inserta=mysql_query($sql_inserta);
}
//fin factura*/

for($i=1;$i<=$cantidad_material;$i++)
{   	
	$codMaterial=$_POST["materiales$i"];
	$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
	$precioUnitario=$_POST["precio_unitario$i"];
	$descuentoProducto=$_POST["descuentoProducto$i"];
	$montoMaterial=$_POST["montoMaterial$i"];
	

    $sql_detalle_ingreso="
        SELECT id.cod_ingreso_almacen, id.cantidad_restante
        FROM ingreso_detalle_almacenes id, ingreso_almacenes i
        WHERE i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$global_almacen' 
		AND id.cod_material='$codMaterial' AND id.cantidad_restante<>0
        ORDER BY id.cod_ingreso_almacen";
    $resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
    $cantidad_bandera=$cantidadUnitaria;
    $bandera=0;
    while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
    {   $cod_ingreso_almacen=$dat_detalle_ingreso[0];
        $cantidad_restante=$dat_detalle_ingreso[1];
        $nro_lote=$dat_detalle_ingreso[2];
        if($bandera!=1)
        {   if($cantidad_bandera>$cantidad_restante)
            {   $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso 
				VALUES('$codigo','$cod_ingreso_almacen','$codMaterial','$cantidad_restante')";
                $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				//echo $sql_salida_det_ingreso;
                $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=0 WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND 
				cod_material='$codMaterial'";
                $resp_upd_cantidades=mysql_query($upd_cantidades);
            }
            else
            {   $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso 
				VALUES('$codigo','$cod_ingreso_almacen','$codMaterial','$cantidad_bandera')";
                $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                $cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
                $bandera=1;
				
				//echo $sql_salida_det_ingreso;
                $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=$cantidad_a_actualizar 
				WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND cod_material='$codMaterial'";
                $resp_upd_cantidades=mysql_query($upd_cantidades);
                $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
            }
        }
    }
	$sql_insertaDetalle="INSERT INTO `salida_detalle_almacenes` (`cod_salida_almacen`, `cod_material`, `cantidad_unitaria`, 
			`precio_unitario`, `descuento_unitario`, `monto_unitario`, `observaciones`, `costo_almacen`, 
			`costo_actualizado_final`, `costo_actualizado`) 
			values('$codigo', '$codMaterial', '$cantidadUnitaria', '$precioUnitario', '$descuentoProducto', '$montoMaterial',
			'', '0', '0', '0')";	
	//echo $sql_insertaDetalle;
    $sql_inserta2=mysql_query($sql_insertaDetalle);
	
	
}

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
if($tipoSalida==1001){
	echo "    location.href='navegadorVentas.php';";
}else{
	echo "    location.href='navegador_salidamateriales.php';";
}
echo "</script>";

?>




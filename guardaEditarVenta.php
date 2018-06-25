<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");

$codigoRegistro=$_POST["codigoRegistro"];
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


$fecha=formateaFechaVista($fecha);

//$fecha=date("Y-m-d");
$hora=date("H:i:s");


//$restaura=restauraCantidades($codigoRegistro);
$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigoRegistro'";
				//echo $sql_detalle;
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	//echo "entro";
		$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysql_query($sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysql_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		//echo "<br>UPDATES".$sql_actualiza;
		$resp_actualiza=mysql_query($sql_actualiza);			
	}

if($tipoDoc==1){
	$nro_correlativo=$nroCorrelativoFactura;
}else{
}

$sqlUpd="UPDATE `salida_almacenes` SET `cod_tiposalida` = '$tipoSalida', `cod_tipo_doc` = '$tipoDoc', 
  `fecha` = '$fecha', `observaciones` = '$observaciones', `cod_cliente` = '$codCliente', `monto_total` = '$totalVenta', 
   	`descuento` = '$descuentoVenta', `monto_final` = '$totalFinal', 
     `razon_social` = '$razonSocial', `nit` = '$nitCliente', `cod_chofer` = '$chofer', `cod_vehiculo` = '$vehiculo'  
       WHERE  
      `cod_salida_almacenes` = '$codigoRegistro'";

$respUpd=mysql_query($sqlUpd);

$sqlDelete="delete from salida_detalle_almacenes where cod_salida_almacen='$codigoRegistro'";
$respDelete=mysql_query($sqlDelete);


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
				VALUES('$codigoRegistro','$cod_ingreso_almacen','$codMaterial','$cantidad_restante')";
                $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				
                $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=0 WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND 
				cod_material='$codMaterial'";
                $resp_upd_cantidades=mysql_query($upd_cantidades);
            }
            else
            {   $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso 
				VALUES('$codigoRegistro','$cod_ingreso_almacen','$codMaterial','$cantidad_bandera')";
                $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                $cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
                $bandera=1;
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
			values('$codigoRegistro', '$codMaterial', '$cantidadUnitaria', '$precioUnitario', '$descuentoProducto', '$montoMaterial',
			'', '0', '0', '0')";	
    $sql_inserta2=mysql_query($sql_insertaDetalle);
}

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron modificados correctamente.');";
if($tipoSalida==1001){
	echo "    location.href='navegadorVentas.php';";
}else{
	echo "    location.href='navegador_salidamateriales.php';";
}
echo "</script>";

?>




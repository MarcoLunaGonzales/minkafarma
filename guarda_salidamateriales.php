<?php

require("conexion.inc");
if($global_tipoalmacen==1)
{   require("estilos_almacenes_central.inc");
}
else
{   require("estilos_almacenes.inc");
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
$sql="SELECT nro_correlativo FROM salida_almacenes WHERE cod_almacen='$global_almacen' ORDER BY cod_salida_almacenes DESC";
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

$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$hora_salida=date("H:i:s");
$vector_material=explode(",",$vector_material);
$vector_cantidades=explode(",",$vector_cantidades);	
$vector_precios=explode(",",$vector_precios);	
$vector_fecha_vencimiento=explode(",",$vector_fechavenci);

$sql_inserta="INSERT INTO `salida_almacenes` (`cod_salida_almacenes`, `cod_almacen`, `cod_tiposalida`, `fecha`, `hora_salida`, `territorio_destino`,
  `almacen_destino`, `observaciones`, 
  `estado_salida`,`nro_correlativo`, `salida_anulada`, `cod_cliente`, `monto_total`, monto_cancelado) 
   VALUE ($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',
   0,'$nro_correlativo',0,$funcionario,0,0)";

   /*$sql_inserta="INSERT INTO salida_almacenes 
	VALUES($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',0,2,'','',0,0,0,0,'','$nro_correlativo',0,0,0)";
*/

$sql_inserta=mysql_query($sql_inserta);

for($i=0;$i<=$cantidad_material-1;$i++)
{   $cod_material=$vector_material[$i];
    $fecha_vencimiento=$vector_fechavenci[$i];
    $fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
    $cantidad=$vector_cantidades[$i];
	$precioUnitario=$vector_precios[$i];
	
    $sql_detalle_ingreso="
        SELECT id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote
        FROM ingreso_detalle_almacenes id, ingreso_almacenes i
        WHERE i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$global_almacen' AND id.cod_material='$cod_material' AND id.cantidad_restante<>0
        ORDER BY id.cod_ingreso_almacen";
    $resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
    $cantidad_bandera=$cantidad;
    $bandera=0;
    while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
    {   $cod_ingreso_almacen=$dat_detalle_ingreso[0];
        $cantidad_restante=$dat_detalle_ingreso[1];
        $nro_lote=$dat_detalle_ingreso[2];
        if($bandera!=1)
        {   if($cantidad_bandera>$cantidad_restante)
            {   $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso 
				VALUES('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','2')";
                $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
                $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=0 WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND cod_material='$cod_material' AND nro_lote='$nro_lote'";
                $resp_upd_cantidades=mysql_query($upd_cantidades);
            }
            else
            {   $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso 
				VALUES('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','2')";
                $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                $cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
                $bandera=1;
                $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=$cantidad_a_actualizar WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND cod_material='$cod_material' AND nro_lote='$nro_lote'";
                $resp_upd_cantidades=mysql_query($upd_cantidades);
                $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
            }
        }
    }
	$sql_insertaDetalle="INSERT INTO salida_detalle_almacenes VALUES($codigo,'$cod_material',$cantidad,$precioUnitario,' ',0,0,0)";
	echo $sql_insertaDetalle;
    $sql_inserta2=mysql_query($sql_insertaDetalle);
}

?>
<!--script type='text/javascript' language='javascript'>
    alert('Los datos fueron insertados correctamente.');
    location.href='navegador_salidamateriales.php';
</script-->

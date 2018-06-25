<?php
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
$sql_confirmacion=mysql_query("select * from salida_almacenes where cod_salida_almacenes='$codigo_registro' and salida_anulada=0");
$numero_filas_afectadas=mysql_num_rows($sql_confirmacion);
if($numero_filas_afectadas==1)
{		$sql="update salida_almacenes set salida_anulada=1 where cod_salida_almacenes='$codigo_registro' and salida_anulada=0";
		$resp=mysql_query($sql);
 		if($grupo_salida==1)
		{	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria, nro_lote
					from salida_detalle_ingreso
					where cod_salida_almacen='$codigo_registro'";
		$resp_detalle=mysql_query($sql_detalle);
		while($dat_detalle=mysql_fetch_array($resp_detalle))
		{	$codigo_ingreso=$dat_detalle[0];
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
			$resp_actualiza=mysql_query($sql_actualiza);			
		}
		echo "<script language='Javascript'>
				alert('El registro fue anulado.');
				location.href='navegador_salidamuestras.php';
				</script>";
		}
		if($grupo_salida==2)
		{	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
					from salida_detalle_ingreso
					where cod_salida_almacen='$codigo_registro'";
			$resp_detalle=mysql_query($sql_detalle);
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$codigo_ingreso=$dat_detalle[0];
			$material=$dat_detalle[1];
			$cantidad=$dat_detalle[2];
			$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
									where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
			$resp_ingreso_cantidad=mysql_query($sql_ingreso_cantidad);
			$dat_ingreso_cantidad=mysql_fetch_array($resp_ingreso_cantidad);
			$cantidad_restante=$dat_ingreso_cantidad[0];
			$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
			$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
							where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
			$resp_actualiza=mysql_query($sql_actualiza);
		
			}
			echo "<script language='Javascript'>
				alert('El registro fue anulado.');
				location.href='navegador_salidamateriales.php';
				</script>";
		}
}
else
{		echo "<script language='Javascript'>
				alert('Esta salida ya esta anulada.');
				history.back();
				</script>";
}
?>
<?php
require("conexion.inc");
//require("estilos_visitador.inc");
/*$sql_borrar="delete from rutero where cod_visitador='$global_visitador'";
$resp_borrar=mysql_query($sql_borrar);
$sql_borrar_det="delete from rutero_detalle where cod_visitador='$global_visitador'";
$resp_borrar_det=mysql_query($sql_borrar_det);*/
//sacamos la gestion y el codigo del ciclo
$sql_gestion="select cod_ciclo, codigo_gestion from ciclos where estado='Activo' and codigo_linea='1007'";
$resp_gestion=mysql_query($sql_gestion);
$datos_gestion=mysql_fetch_array($resp_gestion);
$cod_ciclo=$datos_gestion[0];
$cod_gestion=$datos_gestion[1];
echo "$cod_ciclo $cod_gestion";
$sql_cab="select f.codigo_funcionario, l.codigo_linea
			from funcionarios f, funcionarios_lineas l
			where f.codigo_funcionario=l.codigo_funcionario and f.cod_ciudad='$territorio' and f.cod_cargo='1011' and f.estado=1";
$resp_cab=mysql_query($sql_cab);
while($dat_cab=mysql_fetch_array($resp_cab))
{	$global_visitador=$dat_cab[0];
	$global_linea=$dat_cab[1];	
	$sql="select r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje, r.cod_contacto
		from rutero_maestro r, rutero_maestro_cab rc 
		where r.cod_rutero=rc.cod_rutero and rc.estado_aprobado='1' and rc.cod_visitador=r.cod_visitador and rc.cod_visitador='$global_visitador' and rc.codigo_linea='$global_linea'";
	$resp=mysql_query($sql);
	while($datos=mysql_fetch_array($resp))
	{	$codigo_rutero=$datos[0];
		$codigo_vis=$datos[1];
		$dia_contacto=$datos[2];
		$turno=$datos[3];
		$zona_viaje=$datos[4];
		$cod_contacto_maestro=$datos[5];
		//formamos el codigo de contacto
		$sql_cod="select cod_contacto from rutero order by cod_contacto desc";
		$resp_cod=mysql_query($sql_cod);
		$num_filas=mysql_num_rows($resp_cod);
		if($num_filas==0)
		{	$cod_contacto=1000;
		}
		else
		{	$dat_cod=mysql_fetch_array($resp_cod);
			$cod_contacto=$dat_cod[0];
			$cod_contacto=$cod_contacto+1;
		}
		//fin formar codigo contacto
		$sql_inserta="insert into rutero values('$cod_ciclo','$cod_gestion','$cod_contacto','$codigo_vis','$dia_contacto','$turno','$zona_viaje','$global_linea')";	
		//echo $sql_inserta;
		$resp_inserta=mysql_query($sql_inserta);
		//insertamos en la tabla de rutero auxiliar denominada rutero_utilizado
		$sql_inserta_extra="insert into rutero_utilizado values('$cod_ciclo','$cod_gestion','$cod_contacto','$codigo_vis','$dia_contacto','$turno','$zona_viaje','$global_linea')";	
		$resp_inserta_extra=mysql_query($sql_inserta_extra);
		
		//aqui insertamos en la tabla de rutero_detalle
		$sql_detalle="select * from rutero_maestro_detalle where cod_contacto='$cod_contacto_maestro'";
		$resp_detalle=mysql_query($sql_detalle);
		while($datos_detalle=mysql_fetch_array($resp_detalle))
		{	$orden_visita=$datos_detalle[1];
			$cod_med=$datos_detalle[3];
			$cod_espe=$datos_detalle[4];
			$categoria_med=$datos_detalle[5];
			$zona=$datos_detalle[6];
			$estado=$datos_detalle[7];
			$sql_inserta_det="insert into rutero_detalle values('$cod_contacto','$orden_visita','$codigo_vis','$cod_med','$cod_espe','$categoria_med','$zona','$estado')";
			$resp_inserta_det=mysql_query($sql_inserta_det);
		
			//insertamos en la tabla rutero_detalle_utilizado
			$sql_inserta_det_extra="insert into rutero_detalle_utilizado values('$cod_contacto','$orden_visita','$codigo_vis','$cod_med','$cod_espe','$categoria_med','$zona','$estado')";
			$resp_inserta_det_extra=mysql_query($sql_inserta_det_extra);	
		}	
	}	
}
echo "<center>El rutero se establecio correctamente para el ciclo en curso.</center>";
/*echo "<script language='Javascript'>
			alert('Se establecio el rutero actual correctamente.');
			location.href='navegador_rutero_actual.php';
			</script>";*/
?>
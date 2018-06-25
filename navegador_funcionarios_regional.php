<script language='Javascript'>
	function depurar(codVisitador){
		if(confirm('Esta seguro de depurar los medicos asignados al visitador.')){		
			location.href='depurarMedicos.php?codVisitador='+codVisitador+'';
		}else{	
			return(false);
		}	
	}
</script>
<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	echo "<form method='post' action=''>";
	//esta parte saca el ciclo activo
	$sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.telefono, f.celular,f.email
from funcionarios f, cargos c, ciudades ci, funcionarios_lineas cl
where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.cod_ciudad='$global_agencia' and f.estado=1 and cl.codigo_funcionario=f.codigo_funcionario and cl.codigo_linea='$global_linea' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Visitadores Médicos de la Línea</td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='100%'>";
	echo "<tr><th>&nbsp;</th><th>Nombre</th><th>Medicos Asignados</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$cargo=$dat[1];
		$paterno=$dat[2];
		$materno=$dat[3];
		$nombre=$dat[4];
		$nombre_f="$paterno $materno $nombre";
		$telf=$dat[5];
		$cel=$dat[6];
		$email=$dat[7];
		$sql_num_medicos="select * from medico_asignado_visitador where codigo_visitador='$codigo' and codigo_linea='$global_linea'";
		$resp_num_medicos=mysql_query($sql_num_medicos);
		$filas_num_medicos=mysql_num_rows($resp_num_medicos);
		echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_f</td><td align='center'>$filas_num_medicos</td>
		<td align='center'><a href='asignar_med_fun.php?j_funcionario=$codigo'>Asignar Médicos >></a></td>
		<td align='center'><a href='medicos_asignados.php?visitador=$codigo'>Ver Médicos>></a></td>
		<td align='center'><a href='Javascript:depurar($codigo);'>Depurar Listado>></a></td>
		<td align='center'><a href='importar_rutero_maestro.php?j_funcionario=$codigo'>Importar RM >></a></td>
		<td align='center'><a href='rutero_funcionario.php?visitador=$codigo'>Ver RM >></a></td>
		<td align='center'><a href='ruteroAprobadoxCiclo.php?visitador=$codigo'>Ver RM Aprobados>></a></td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "</form>";
	require("home_regional1.inc");
?>
<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select distinct(f.codigo_funcionario), f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c
	where f.cod_cargo=c.cod_cargo and f.estado=1 and f.cod_ciudad in ($codTerritorio) 
	order by f.paterno";
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_persona' id='rpt_persona' class='texto' size='10' multiple>";
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codigo=$dat_visitador[0];
	$nombre="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
	$ciudadX=$dat_visitador[4];
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";
?>
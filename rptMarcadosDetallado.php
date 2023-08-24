<?php
require("conexionmysqli2.inc");
require("estilos.inc");

$fechaInicio=$_POST['exafinicial'];
$fechaFinal=$_POST['exaffinal'];


echo "<h1>Reporte Marcados de Personal Detallado</h1>";

$sqlPersonal="select distinct(f.codigo_funcionario), concat(f.paterno,' ',f.materno,' ',f.nombres), 
	m.fecha_marcado, m.ip, m.user_agent from marcados_personal m, funcionarios f
where f.codigo_funcionario=m.cod_funcionario and 
m.fecha_marcado BETWEEN '$fechaInicio 00:00:00' and '$fechaFinal 23:59:59' order by 1,2";
//echo $sqlPersonal;
$respPersonal=mysqli_query($enlaceCon, $sqlPersonal);

echo "<center><table class='texto'>";
echo "<tr><th>Personal</th><th>Fecha</th><th>IP</th><th>AGENTE</th></tr>";


while($datPersonal=mysqli_fetch_array($respPersonal)){
	$codPersonal=$datPersonal[0];
	$nombrePersonal=$datPersonal[1];
	$fechaHoraMarcado=$datPersonal[2];
	$ip=$datPersonal[3];
	$agente=$datPersonal[4];
	
	echo "<tr><td>$nombrePersonal</td><td>$fechaHoraMarcado</td><td>$ip</td><td>$agente</td></tr>";	
}
echo "</table></center>";
echo "</form>";
?>
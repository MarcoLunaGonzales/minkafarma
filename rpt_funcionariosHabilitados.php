<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];

echo "<table border='0' class='textotit' align='center'><tr><th>Funcionarios Habilitados<br>
</th></tr></table></center><br>";

echo "<table border=1 class='texto' cellspacing=0 cellpading=0 id='main' align='center' width='70%'>
<tr><th>Territorio</th><th>Cargo</th><th>Visitador</th><th>Lineas</th></tr>";

$sql="select f.codigo_funcionario, c.`descripcion`, concat(f.`paterno`,' ',f.`nombres`), ca.`cargo` 
			from funcionarios f, ciudades c, cargos ca where c.`cod_ciudad` in ($rpt_territorio) and 
			f.`cod_ciudad` = c.`cod_ciudad` and f.`estado`=1 and f.cod_cargo=ca.cod_cargo order by  
      c.`descripcion`, ca.cargo, f.`paterno`";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codFuncionario=$dat[0];
	$territorio=$dat[1];
	$visitador=$dat[2];
	$cargo=$dat[3];
	$sqlLinea="select l.`nombre_linea` from `funcionarios_lineas` fl, 
	     lineas l where fl.`codigo_linea` = l.`codigo_linea` and  fl.`codigo_funcionario`=$codFuncionario and l.`estado`=1
	     order by l.nombre_linea";
	$respLinea=mysql_query($sqlLinea);
	$lineas="&nbsp;";
	while($datLinea=mysql_fetch_array($respLinea)){
		$lineas=$lineas.$datLinea[0]."<br>";
	}
	echo "<tr><td>$territorio</td><td>$cargo</td><td>$visitador</td><td>$lineas</td></tr>";
}
echo "</table>";
?>
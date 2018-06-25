<html>
<body>
<table align='center'>
<tr>
<th>Cod. Sistema</th><th>Material</th><th>Cod. Interno</th>
</tr>
<?php
require("conexion.inc");
$codTipo=$_GET['codTipo'];
$codItem=$_GET['codItem'];
$nombreItem=$_GET['nombreItem'];

$sql="select codigo_material, descripcion_material, orden_grupo from `material_apoyo` where estado='Activo' ";
if($codTipo!=0){
	$sql=$sql. " and cod_tipo_material=$codTipo";
}
if($codItem!=""){
	$sql=$sql. " and orden_grupo=$codItem";
}
if($nombreItem!=""){
	$sql=$sql. " and descripcion_material like '%$nombreItem%'";
}
$sql=$sql." order by orden_grupo";
$resp=mysql_query($sql);

$numFilas=mysql_num_rows($resp);

while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	$ordenGrupo=$dat[2];
	
	echo "<tr><td>$codigo</td><td><a href='javascript:setMateriales(form1, $codigo, \"$nombre\")'>$nombre</a></td><td>$ordenGrupo</td></tr>";
}

?>
</table>

</body>
</html>
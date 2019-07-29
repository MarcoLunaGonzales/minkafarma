<script>
function cargaInicio(f){
	f.clave_marcado.focus();
}
</script>
<body onload="cargaInicio(form1);">
<?php
require("conexion.inc");
require("estilos.inc");

echo "<form name='form1' action='guarda_marcado.php' method='post'>";
echo "<h1>Registrar Marcado de Personal</h1>";

echo "<center><table class='texto' width='50%'>";
echo "<tr><th>Introducir la clave del sistema para realizar el marcado</th></tr>";
echo "<tr><td align='center'><input type='password' value='' name='clave_marcado' id='clave_marcado' size='50' required></td>";
echo "</table></center>";

echo "<div class='divBotones'><input type='submit' class='boton' value='Guardar Marcado' onClick='validar(this.form)'>
</div>";


$sql="select m.fecha_marcado, 
(select concat(f.paterno,' ', f.nombres) from funcionarios f where f.codigo_funcionario=m.cod_funcionario) 
from marcados_personal m order by m.fecha_marcado desc limit 0,10";
$resp=mysql_query($sql);
echo "<center><table class='texto'>";
echo "<tr><th colspan='2'>Ultimos Marcados del personal</th></tr>";
echo "<tr><th>Fecha/Hora Marcado</th><th>Personal</th></tr>";
while($dat=mysql_fetch_array($resp)){
	$fechaHora=$dat[0];
	$nombresPersonal=$dat[1];
	
	echo "<tr><td>$fechaHora</td><td>$nombresPersonal</td></tr>";
}
echo "</table></center>";

echo "</form>";
?>



</body>
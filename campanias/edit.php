<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

$sql=mysqli_query($enlaceCon,"select nombre, abreviatura, fecha_inicio, fecha_fin from $table where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
$abreviatura=$dat[1];
$fechaInicio=$dat[2];
$fechaFin=$dat[3];

echo "<form action='$urlSaveEdit' method='post'>";

echo "<h1>Editar $moduleNameSingular</h1>";

echo "<center>
<table class='texto'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='left'><input type='text' class='texto' name='nombre' value='$nombre' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'><input type='text' class='texto' name='abreviatura' value='$abreviatura' size='20' required></td></tr>";

echo "</tr>";
echo "<tr><th align='left'>Fecha Inicio</th>";
echo "<td align='left'>
	<input type='date' class='texto' name='fecha_inicio' value='$fechaInicio' size='30' required>
</td>";
echo "</tr>";
echo "<tr><th align='left'>Fecha Final</th>";
echo "<td align='left'>
	<input type='date' class='texto' name='fecha_final' value='$fechaFin' size='30' required>
</td>";
echo "</tr>";


echo "</table>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
</div>";

echo "</form>";
?>
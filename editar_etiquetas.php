<?php

require("conexion.inc");
require("estilos.inc");

$codigo_registro=$_GET["codigo_registro"];

$sql="select txt1, txt2, txt3, alineado_izq, alineado_arriba, cantidad from etiquetas where id='$codigo_registro'";
//echo $sql;
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$txt1=$dat[0];
$txt2=$dat[1];
$txt3=$dat[2];
$alignIzq=$dat[3];
$alignTop=$dat[4];
$cantidad=$dat[5];

echo "<form action='guarda_editaretiquetas.php' method='post'>";

echo "<h1>Editar Etiquetas</h1>";

echo "<center><table class='texto' >";
echo "<tr><th>Txt1</th><th>Txt2</th><th>Txt3</th><th>Margen Izq.</th><th>Margen Arriba</th><th>Cantidad</th></tr>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";

echo "<tr><td align='center'>
	<input type='text' class='texto' name='txt1' value='$txt1'>
	</td>";
echo "<td align='center'>
	<input type='text' class='texto' name='txt2' value='$txt2'>
	</td>";
echo "<td align='center'>
	<input type='text' class='texto' name='txt3' value='$txt3'>
	</td>";
	
echo "<td align='center'>
	<input type='number' step='1' class='texto' name='izquierda' value='$alignIzq'>
	</td>";
echo "<td align='center'>
	<input type='number' step='1' class='texto' name='top' value='$alignTop'>
	</td>";
echo "<td align='center'>
	<input type='number' step='1' class='texto' name='cantidad' value='$cantidad'>
	</td>";
echo "</tr></table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_etiquetas.php\"'>
</div>";

echo "</form>";
?>
<?php

require("conexion.inc");
require("estilos.inc");
echo "<form action='guarda_tipogasto.php' method='post'>";

echo "<h1>Adicionar Tipo de Gasto</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='center'>
	<input type='text' class='texto' name='nombre' size='30' required>
</td></tr>";

echo "<tr><th>Tipo</th>
	<td align='center'>
		<select class='selectpicker' name='tipo_gasto' id='tipo_gasto'>
			<option value='1'>Gasto Diario / Normal</option>
			<option value='2'>Mensual / Anual</option>
		</select>
	</td></tr>";

echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_tiposgasto.php\"'>
";

echo "</form>";
?>
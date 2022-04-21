<?php
require("conexionmysqli.php");
require("estilos.inc");

echo "<form action='saveAccionter.php' method='post'>";
echo "<h1>Registrar Accion Terapeutica</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Nombre</th></tr>";

echo "<tr>
<td align='center'>
	<input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'>
</td>";
echo "</table></center>";

echo "<div class='divBotones'><input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='javascript:location.href=\"navegador_formasfar.php\"'>
</div>";

echo "</form>";
?>
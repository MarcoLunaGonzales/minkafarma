<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");

$sql=mysqli_query($enlaceCon,"select nombre_tipogasto from tipos_gasto where cod_tipogasto=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];

echo "<form action='guarda_editartipogasto.php' method='post'>";

echo "<h1>Editar Tipo de Gasto</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='texto' name='nombre' value='$nombre' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Estado</th>";
echo "<td align='center'>
<select name='estado'>
	<option value='1'>Activo</option>
	<option value='2'>Inactivo</option>
</select></td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_tiposgasto.php\"'>
</div>";

echo "</form>";
?>
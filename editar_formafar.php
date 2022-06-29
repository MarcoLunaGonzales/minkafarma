<?php

require("conexionmysqli.php");
require("estilos.inc");

$codigo_registro=$_GET["codigo_registro"];

$sql=mysqli_query($enlaceCon,"select cod_forma_far, nombre_forma_far from formas_farmaceuticas where cod_forma_far=$codigo_registro");
$dat=mysqli_fetch_array($sql);
$nombre=$dat[1];

echo "<form action='saveEditFormafar.php' method='post'>";

echo "<h1>Editar Forma Farmaceutica</h1>";

echo "<center><table class='texto'>";

echo "<tr><th>Nombre</th></tr>";
echo "<tr>
<td align='center'>
	<input type='hidden' name='codigo' value='$codigo_registro'>
	<input type='text' class='texto' name='nombre' value='$nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'>
</td>";

echo "</table></center>";

echo "<div class='divBotones'><input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='javascript:location.href=\"navegador_formasfar.php\"'>
</div>";

echo "</form>";
?>
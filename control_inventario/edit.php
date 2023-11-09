<meta charset="utf-8">
<?php

require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");

$sql=mysqli_query($enlaceCon,"select nombre from $table where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
echo "<form action='$urlSaveEdit' method='post'>";

echo "<h1>Editar $moduleNameSingular</h1>";

echo "<center>
<table class='table table-sm'>";

echo "<tr><th align='left' class='bg-info text-white'>Nombre</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='left' colspan='3'><input type='text' class='form-control' name='nombre' value='$nombre' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "</table>";

echo "<div class='divBotones'>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
</div>";

echo "</form>";
?>
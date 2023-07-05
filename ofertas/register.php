<?php

require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
$fecha_rptinidefault=date("Y")."-".date("m")."-01";
$hora_rptinidefault=date("H:i");
$fecha_rptdefault=date("Y-m-d");
echo "<form action='$urlSave' method='post'>";

echo "<h1>Registrar $moduleNameSingular</h1>";

echo "<center><table class='table table-sm' width='60%'>";

echo "<tr><td align='left' class='bg-info text-white'>Nombre</td>";
echo "<td align='left' colspan='3'>
	<input type='text' class='form-control' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td></tr>";
echo "<tr><td align='left' class='bg-info text-white'>Descuento %</td>";
echo "<td align='left' colspan='3'>
	<input type='number' class='form-control' name='abreviatura' size='30' required>
</td>";
echo "</tr>";
echo "<tr><td align='left' class='bg-info text-white'>Nivel Descuento</td>";
echo "<td align='left' colspan='3'>
	<select name='nivel_descuento' class='selectpicker form-control' data-style='btn btn-info'>
		<option value='1' selected>Linea</option>
		<option value='0'>Producto</option>
	</select>	
</td>";
echo "</tr>";
echo "<tr><td align='left' class='bg-info text-white'>Desde</td>";
echo "<td align='left'>
	<INPUT  type='date' class='form-control' value='$fecha_rptinidefault' id='fecha_ini' size='10' name='fecha_ini'><INPUT  type='time' class='form-control' value='$hora_rptinidefault' id='hora_ini' size='10' name='hora_ini'>
</td><td align='left' class='bg-info text-white'>Hasta</td><td align='left' colspan='3'>
	<INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin'><INPUT  type='time' class='form-control' value='$hora_rptinidefault' id='hora_fin' size='10' name='hora_fin'>
</td>";
echo "</tr>";
echo "</table></center>";

echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
";

echo "</form>";
?>
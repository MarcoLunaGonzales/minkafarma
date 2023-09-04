<style>
	input[type=radio]
	{
	  /* Double-sized Checkboxes */
	  -ms-transform: scale(2); /* IE */
	  -moz-transform: scale(2); /* FF */
	  -webkit-transform: scale(2); /* Safari and Chrome */
	  -o-transform: scale(2); /* Opera */
	  transform: scale(2);
	  padding: 10px;
	}

	/* Might want to wrap a span around your checkbox text */
	.radiotext
	{
	  /* Checkbox text */
	  font-size: 110%;
	  display: inline;
	}

</style>
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
echo "<tr><td align='left' class='bg-info text-white'>Descuento BASE %</td>";
echo "<td align='left' colspan='3'>
	<input type='number' class='form-control' name='abreviatura' size='30' required>
</td>";
echo "</tr>";

echo "<tr><td align='left' class='bg-info text-white'>Oferta con STOCK LIMITADO</td>";
echo "<td align='left' colspan='3'>
		<input type='radio' name='stock_limitado' value='0' checked><span class='radiotext'>&nbsp;&nbsp; NO &nbsp;&nbsp;</span>
        <input type='radio' name='stock_limitado' value='1'><span class='radiotext'>&nbsp; &nbsp;  SI &nbsp;&nbsp;</span>
</td>";
echo "</tr>";

//<option value='1' selected>Linea</option>
echo "<tr><td align='left' class='bg-info text-white'>Nivel Descuento</td>";
echo "<td align='left' colspan='3'>
	<select name='nivel_descuento' class='selectpicker form-control' data-style='btn btn-info'>
		<option value='0'>Producto</option>
		<option value='2'>Solo Medicamentos</option>
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
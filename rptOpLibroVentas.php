<script language='JavaScript'>
function envia_formulario(f)
{	var codAnio,codMes;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	
	window.open('rptLibroVentas.php?codAnio='+codAnio+'&codMes='+codMes,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_formularioTXT(f)
{	var codAnio,codMes;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	
	window.open('rptLibroVentastxt.php?codAnio='+codAnio+'&codMes='+codMes,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php
	
require("conexion.inc");
require("estilos_administracion.inc");

echo "<form action='rptLibroVentas.php' method='post'>";

echo "<h1>Reporte Libro de Ventas</h1>";

echo "<center><table class='texto'>";

echo "<tr><th>Año</th><th>Mes</th></tr>";

echo "<tr>

<td align='center'><select name='cod_anio' id='cod_anio' class='textograndenegro'>";
for($i=2018; $i<=2030; $i++){
	echo "<option value='$i'>$i</option>";
}
echo "</select></td>";

echo "<td align='center'><select name='cod_mes' id='cod_mes' class='textograndenegro'>";
echo "<option value='1'>Enero</option>
		<option value='2'>Febrero</option>
		<option value='3'>Marzo</option>
		<option value='4'>Abril</option>
		<option value='5'>Mayo</option>
		<option value='6'>Junio</option>
		<option value='7'>Julio</option>
		<option value='8'>Agosto</option>
		<option value='9'>Septiembre</option>
		<option value='10'>Octubre</option>
		<option value='11'>Noviembre</option>
		<option value='12'>Diciembre</option>
		";
echo "</select></td>";


echo "</table></center>";

echo "<div class='divBotones'>
<input type='button' class='boton' value='Reporte HTML' onClick='envia_formulario(this.form)'>
<input type='button' class='boton' value='Reporte txt' onClick='envia_formularioTXT(this.form)'>";

echo "</form>";
?>
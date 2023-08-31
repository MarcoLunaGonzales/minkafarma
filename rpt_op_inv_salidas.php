<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$fecha_rptdefault=date("Y-m-d");
$fecha_rptdefault_ini=date("Y-m-01");

echo "<h1>Reporte Salidas Almacen</h1>";

echo"<form method='post' action='rpt_inv_salidas.php' target='_blank'>";
	echo"\n<table class='texto' align='center'>\n";
	
	echo "<tr>
	<th align='left'>Almacen</th>
	<td>
	<select name='rpt_almacen' class='selectpicker' data-style='btn-primary'>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Salida</th>";
	$sql_tiposalida="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida<>1001
	 order by nombre_tiposalida";
	$resp_tiposalida=mysqli_query($enlaceCon, $sql_tiposalida);
	echo "<td>
	<select name='tipo_salida[]' class='selectpicker form-control' data-style='btn-success' multiple size='5' required>";
	while($datos_tiposalida=mysqli_fetch_array($resp_tiposalida))
	{	$codigo_tiposalida=$datos_tiposalida[0];
		$nombre_tiposalida=$datos_tiposalida[1];
		echo "<option value='$codigo_tiposalida' selected>$nombre_tiposalida</option>";
	}
	echo "</select></td>";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo "<td>
			<input type='date' class='texto' value='$fecha_rptdefault_ini' id='exafinicial' size='10' name='exafinicial'>";
    		echo "</td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo "<td bgcolor='#ffffff'>
			<input type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo "</td>";
	echo "</tr>";

	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";
?>
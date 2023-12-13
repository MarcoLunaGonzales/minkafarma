<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$fecha_rptdefault=date("Y-m-d");
$fecha_rptdefault_ini=date("Y-m-01");

echo "<h1>Reporte Ingresos con Costo 0</h1>";

echo"<form method='post' action='rpt_inv_ingresos_costo.php' target='_blank'>";
	echo"\n<table class='texto' align='center'>\n";
	
	echo "<tr>
	<th align='left'>Almacen</th>
	<td>
	<select name='rpt_almacen[]' class='selectpicker form-control' data-style='btn-success' multiple size='5' required>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Ingreso</th>";
	$sql_tipoingreso="SELECT cod_tipoingreso, nombre_tipoingreso from tipos_ingreso where cod_tipoingreso<>1001
	 order by nombre_tipoingreso";
	$resp_tipoingreso=mysqli_query($enlaceCon, $sql_tipoingreso);
	echo "<td>
	<select name='tipo_ingreso[]' class='selectpicker form-control' data-style='btn-success' multiple size='5' required>";
	while($datos_tipoingreso=mysqli_fetch_array($resp_tipoingreso))
	{	$codigo_tipoingreso=$datos_tipoingreso[0];
		$nombre_tipoingreso=$datos_tipoingreso[1];
		echo "<option value='$codigo_tipoingreso' selected>$nombre_tipoingreso</option>";
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
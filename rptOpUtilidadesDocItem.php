<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Costos x Documento e Item</h1>";
echo"<form method='post' action='rptUtilidadesDocItem.php' target='_blank'>";

	echo"<center><table class='texto'>\n";
	echo "<tr>
	<th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha Inicio:</th>";
			echo" <td>
			<input  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' name='exafinicial' required>";
    		echo"  </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha Final:</th>";
			echo" <td>
			<input type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' name='exaffinal' required>";
    		echo" </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>
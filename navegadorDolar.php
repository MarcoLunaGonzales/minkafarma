<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function cambiarCot()
		{	location.href='cambiarCotDolar.php';
		}
		</script>";
	require("conexion.inc");
	require("estilos.inc");
	echo "<form method='post' action=''>";
	$sql="select valor from cotizaciondolar";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Cotizacion $us</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
	echo "<tr><th>Cotizacion</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$valor=$dat[0];
		echo "<tr></td><td>$valor</td></tr>";
	}
	echo "</table></center><br>";

	echo "<tr><td><input type='button' value='Cambiar' class='boton' onclick='cambiarCot()'></td></tr></table></center>";
	echo "</form>";
?>
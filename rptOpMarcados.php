<?php
require("conexion.inc");
require("estilos_almacenes.inc");

echo "<h1>Reporte de Marcados de Asistencia</h1>";
echo"<form method='post' action='rptMarcados.php'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' name='exafinicial' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' name='exaffinal' required>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";

	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	
?>
<?php
require("conexion.inc");
require("estilos_almacenes.inc");
?>
<script language='JavaScript'>
	function envia_form(f){
		f.action="rptMarcados.php";
		f.submit();
		return(true);
	}
	function envia_form2(f){
		f.action="rptMarcadosDetallado.php";
		f.submit();
		return(true);
	}
</script>
<?php

echo "<h1>Reporte de Marcados de Asistencia</h1>";

$fechaActual=date("Y-m-d");

echo"<form method='post' action='' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD><INPUT  type='date' class='texto' value='$fechaActual' id='exafinicial' name='exafinicial' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD><INPUT  type='date' class='texto' value='$fechaActual' id='exaffinal' name='exaffinal' required>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";

	echo "<center>
	<input type='button' name='reporte' value='Ver Reporte' class='boton' onclick='envia_form1(this.form)' >
	<input type='button' name='reporte1' value='Ver Detalle de Marcados' class='boton2' onclick='envia_form2(this.form)' >
	</center><br>";
	echo"</form>";
	echo "</div>";
	
?>
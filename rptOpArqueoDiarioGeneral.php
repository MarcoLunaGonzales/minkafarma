<script language='JavaScript'>
function envia_formulario(f)
{	var fecha_ini;
	var fecha_fin;
	var hora_ini;
	var hora_fin;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;

	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	hora_ini=f.exahorainicial.value;
	hora_fin=f.exahorafinal.value;
	window.open('rptArqueoDiarioGeneralPDF.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&hora_ini='+hora_ini+'&hora_fin='+hora_fin,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>


<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$fecha_rptinidefault=date("Y")."-".date("m")."-01";
//$hora_rptinidefault=date("H:i");
$hora_rptinidefault="06:00";
$hora_rptfindefault="23:59";
$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];
$globalUser=$_COOKIE['global_usuario'];
echo "<h1>Reporte Arqueo Diario de Caja</h1><br>";
echo"<form method='post' action='rptArqueoDiarioPDF.php'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='selectpicker form-control'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($codigo_ciudad==$globalCiudad){
			echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";			
		}else{
			echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha Inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'><INPUT  type='time' class='texto' value='$hora_rptinidefault' id='exahorainicial' size='10' name='exahorainicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha Fin:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'><INPUT  type='time' class='texto' value='$hora_rptfindefault' id='exahorafinal' size='10' name='exahorafinal'>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' class='boton' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='btn btn-info'></center><br>";

	echo"</form>";
	echo "</div>";
?>

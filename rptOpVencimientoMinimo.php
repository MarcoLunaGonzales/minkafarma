<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$global_usuario=$_COOKIE['global_usuario'];

if($rpt_territorio==""){
	$rpt_territorio=$globalCiudad;
}
echo "<h1>Reporte de Vencimientos y Mínimos</h1>"; 

echo"<form method='POST' action='rptVencimientoMinimo.php'  target='_blank'>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";	
	
	
	echo "<tr>
		<th align='left'>Almacen</th>
		<td><select name='rpt_almacen[]' id='rpt_almacen' class='texto' size='4' multiple>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Distribuidor</th><td><select name='rpt_grupo[]' class='texto' size='8' multiple>";
	$sql="select p.cod_proveedor, p.nombre_proveedor from proveedores p;";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>A Fecha:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='text' value='$fecha_rptdefault' id='rpt_ini' name='rpt_ini' readonly>
			</TD>";
	echo "</tr>";	
	
	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton2'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>
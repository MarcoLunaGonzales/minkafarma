<?php
require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");

$rpt_territorio=$_POST["rpt_territorio"];
//echo "rpt territorio: ".$rpt_territorio;

echo "<h1>Reporte Existencias Almacen</h1>";
echo"<form method='post' action='rpt_inv_existencias.php' target='_blank'>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";

	echo "<tr><th align='left'>Almacen</th>
	<td>
	<select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen)
		{	echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		}
		else
		{	echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";


	echo "<tr><th align='left'>Distribuidor</th><td>
		<select name='rpt_distribuidor[]' id='rpt_distribuidor' class='texto' multiple size='7'>";
	$sql="select p.cod_proveedor, p.nombre_proveedor from proveedores p order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='texto'>";
	echo "<option value='0'>Todos los Productos con algún Movimiento en el Almacen</option>";
	echo "<option value='2'>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "<option value='1'>Toda La BD de Productos</option>";
	echo "</tr>";

	echo "<tr><th align='left'>Tipo de Impresión</th>";
	echo "<td><select name='rpt_tipo_impresion' class='texto'>";
		echo "<option value='0'>NORMAL</option>";
		echo "<option value='1'>PARA INVENTARIO</option>";	
	echo "</select></td>";
	echo "</tr>";


	$fecha_rptdefault=date("Y-m-d");	
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <td bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha'>";
    		echo"  </td>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar Por:</th>";
	echo "<td><select name='rpt_ordenar' class='texto'>";
	echo "<option value='1'>Producto</option>";
	echo "<option value='2'>Linea y Producto</option>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>
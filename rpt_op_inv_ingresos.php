<?php
$global_usuario = $_COOKIE['global_usuario'];
$global_agencia = $_COOKIE['global_agencia']; 
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio,rpt_almacen, tipo_ingreso,fecha_ini, fecha_fin, rpt_item;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			tipo_ingreso=f.tipo_ingreso.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;
			rpt_item=f.rpt_item.value;

			window.open('rpt_inv_ingresos.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&tipo_ingreso='+tipo_ingreso+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_item='+rpt_item,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");

require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Ingresos Almacen</h1>";

echo"<form method='post' action=''>";
	echo"\n<table class='texto' align='center'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	if($global_tipoalmacen==1)
	{	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	}
	else
	{	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia' order by descripcion";
	}
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$rpt_territorio'";
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

	echo "<tr><th align='left'>Tipo de Ingreso</th>";
	$sql_tipoingreso="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
	$resp_tipoingreso=mysqli_query($enlaceCon, $sql_tipoingreso);
	echo "<td><select name='tipo_ingreso' class='texto'>";
	echo "<option value=''>Todos los tipos</option>";
	while($datos_tipoingreso=mysqli_fetch_array($resp_tipoingreso))
	{	$codigo_tipoingreso=$datos_tipoingreso[0];
		$nombre_tipoingreso=$datos_tipoingreso[1];
		if($tipo_ingreso==$codigo_tipoingreso)
		{	echo "<option value='$codigo_tipoingreso' selected>$nombre_tipoingreso</option>";
		}
		else
		{	echo "<option value='$codigo_tipoingreso'>$nombre_tipoingreso</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Material</th><td><select name='rpt_item' class='texto'>";	
	$sql_item="select codigo_material, descripcion_material from material_apoyo where codigo_material<>0 order by descripcion_material";	
	$resp=mysqli_query($enlaceCon, $sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		if($tipo_item==1)
		{	$nombre_item="$dat[1] $dat[2]";
		}
		else
		{	$nombre_item=$dat[1];
		}
		if($rpt_item==$codigo_item)
		{	echo "<option value='$codigo_item' selected>$nombre_item</option>";
		}
		else
		{	echo "<option value='$codigo_item'>$nombre_item</option>";
		}
	}
	echo "</select></td></tr>";	
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial' required>";
    		echo" </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>";
    		echo" </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
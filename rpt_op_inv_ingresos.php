<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio,rpt_almacen, tipo_ingreso,fecha_ini, fecha_fin;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			tipo_ingreso=f.tipo_ingreso.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;

			window.open('rpt_inv_ingresos.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&tipo_ingreso='+tipo_ingreso+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Ingresos Almacen</th></tr></table><br>";
echo"<form method='post' action=''>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	if($global_tipoalmacen==1)
	{	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	}
	else
	{	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia' order by descripcion";
	}
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
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
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
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
	$sql_tipoingreso="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso where tipo_almacen='$global_tipoalmacen' order by nombre_tipoingreso";
	$resp_tipoingreso=mysql_query($sql_tipoingreso);
	echo "<td><select name='tipo_ingreso' class='texto'>";
	echo "<option value=''>Todos los tipos</option>";
	while($datos_tipoingreso=mysql_fetch_array($resp_tipoingreso))
	{	$codigo_tipoingreso=$datos_tipoingreso[0];
		$nombre_tipoingreso=$datos_tipoingreso[1];
		if($tipo_ingreso==$codigo_tipoingreso)
		{	echo "<option value='$codigo_tipoingreso' selected>$nombre_tipoingreso</option>";
		}
		else
		{	echo "<option value='$codigo_tipoingreso'>$nombre_tipoingreso</option>";
		}
	}
	echo "</select></td>";

	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exafinicial' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo" <IMG id='imagenFecha1' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exaffinal' ";
    		echo" click_element_id='imagenFecha1'></DLCALENDAR>";
    		echo"  </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
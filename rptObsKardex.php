<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_salida,fecha_ini, fecha_fin, tipo_item, rpt_linea,tipo_reporte;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			tipo_item=f.tipo_item.value;
			window.open('rpt_inv_obs_kardexexistencias.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&tipo_item='+tipo_item+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
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
echo "<table align='center' class='textotit'><tr><th>Reporte Diferencias entre Kardex y Existencias</th></tr></table><br>";
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
	echo "<tr><th align='left'>Tipo de Item:</th>";
	echo "<td><select name='tipo_item' class='texto'>";
	echo "<option value='1'>Muestra Médica</option>";
	echo "<option value='2'>Material de Apoyo</option>";
	echo "</tr>";
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_salida,fecha_ini, fecha_fin, tipo_item;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;
			tipo_item=f.tipo_item.value;
			var rpt_linea=new Array();
			var rpt_linea1=new Array();
			var j=0;
			for(var i=0;i<=f.rpt_linea.options.length-1;i++)
			{	if(f.rpt_linea.options[i].selected)
				{	rpt_linea[j]=f.rpt_linea.options[i].value;
					rpt_linea1[j]=f.rpt_linea.options[i].innerHTML;
					j++;
				}
			}
			var rpt_tiposalida=new Array();
			var rpt_tiposalida1=new Array();
			j=0;
			for(var i=0;i<=f.tipo_salida.options.length-1;i++)
			{	if(f.tipo_salida.options[i].selected)
				{	rpt_tiposalida[j]=f.tipo_salida.options[i].value;
					rpt_tiposalida1[j]=f.tipo_salida.options[i].innerHTML;
					j++;
				}
			}
			window.open('rpt_inv_salidasRegional.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_tiposalida='+rpt_tiposalida+'&rpt_tiposalida1='+rpt_tiposalida1+'&rpt_linea='+rpt_linea+'&rpt_linea1='+rpt_linea1+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&tipo_item='+tipo_item+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
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
echo "<table align='center' class='textotit'><tr><th>Reporte Salida de Productos x Regional</th></tr></table><br>";
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
	echo "<tr><th align='left'>Tipo de Salida</th>";
	$sql_tiposalida="select cod_tiposalida, nombre_tiposalida from tipos_salida where tipo_almacen='$global_tipoalmacen' order by nombre_tiposalida";
	$resp_tiposalida=mysql_query($sql_tiposalida);
	echo "<td><select name='tipo_salida' class='texto' multiple size='5'>";
	while($datos_tiposalida=mysql_fetch_array($resp_tiposalida))
	{	$codigo_tiposalida=$datos_tiposalida[0];
		$nombre_tiposalida=$datos_tiposalida[1];
		if($tipo_salida==$codigo_tiposalida)
		{	echo "<option value='$codigo_tiposalida' selected>$nombre_tiposalida</option>";
		}
		else
		{	echo "<option value='$codigo_tiposalida'>$nombre_tiposalida</option>";
		}
	}
	echo "</select></td>";
	echo "<tr><th align='left'>Línea</th><td><select name='rpt_linea' class='texto' multiple size='8'>";
	$sql="select codigo_linea, nombre_linea from lineas where linea_inventarios=1 order by nombre_linea";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_linea=$dat[0];
		$nombre_linea=$dat[1];
		if($rpt_linea==$codigo_linea)
		{	echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
		}
		else
		{	echo "<option value='$codigo_linea'>$nombre_linea</option>";
		}
	}
	echo "</select></td></tr>";

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
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
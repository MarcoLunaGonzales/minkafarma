<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio, rpt_almacen,fecha_ini, fecha_fin, tipo_item, rpt_item;
	rpt_territorio=f.rpt_territorio.value;
	rpt_almacen=f.rpt_almacen.value;
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	//tipo_item=f.tipo_item.value;
	//rpt_item=f.rpt_item.value;
	window.open('calcularCostosUEPS.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_select(form){
	form.submit();
	return(true);
}
</script>
<?php

require("conexion.inc");
require("estilos_administracion.inc");

$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Calcular Costos de Materiales UEPS</th></tr></table><br>";

echo"<form method='post' action='navegadorCalcularCostosUEPS.php'>";

	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
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
	
	/*echo "<tr><th align='left'>Material</th><td><select name='rpt_item' class='texto'>";
	
	$sql_item="select codigo_material, descripcion_material from material_apoyo where codigo_material<>0 order by descripcion_material";
	
	$resp=mysql_query($sql_item);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
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
	echo "</select></td></tr>";	*/
	
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
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
<script language='JavaScript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxPersonal(f){
	var codTerritorio=document.getElementById('rpt_territorio').value;
	var contenedor;
	contenedor = document.getElementById('divPersonal');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxPersonal.php?codTerritorio='+codTerritorio+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_persona=new Array();
	var rpt_grupo=new Array();
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	j=0;
	for(i=0;i<=f.rpt_persona.options.length-1;i++)
	{	if(f.rpt_persona.options[i].selected)
		{	rpt_persona[j]=f.rpt_persona.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rpt_grupo.options.length-1;i++)
	{	if(f.rpt_grupo.options[i].selected)
		{	rpt_grupo[j]=f.rpt_grupo.options[i].value;
			j++;
		}
	}
	
	var forms = f;
    if(forms.checkValidity()){
		window.open('rptVentasxVendedorDetalle.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_persona='+rpt_persona+'&rpt_grupo='+rpt_grupo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
		return(true);
	} else{
        alert("Debe seleccionar todos los campos del reporte.");
		return false;
    }

}
</script>
<?php

require("conexion.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Ventas x Vendedor Detallado</th></tr></table><br>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' id='rpt_territorio' class='texto' onChange='ajaxPersonal(this.form)' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value='0'></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Personal</th>";
	echo "<td><div id='divPersonal'>
	<select name='rpt_persona' id='rpt_persona' class='texto' size='10' multiple required></select>
	</div>
	</td></tr>";

	echo "<tr><th align='left'>Grupo</th>
	<td><select name='rpt_grupo[]' id='rpt_grupo' class='texto' size='5' onChange='ajaxReporteItems(this.form);' multiple required>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	echo "</tr>";	
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal' required>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>
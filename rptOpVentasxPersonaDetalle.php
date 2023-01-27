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
	var rpt_ver=f.rpt_ver.value;
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	j=0;
	for(i=0;i<=f.rpt_persona.options.length-1;i++)
	{	if(f.rpt_persona.options[i].selected)
		{	rpt_persona[j]=f.rpt_persona.options[i].value;
			j++;
		}
	}
	window.open('rptVentasxVendedorDetalle.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_persona='+rpt_persona+'&rpt_ver='+rpt_ver,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Ventas x Vendedor</th></tr></table><br>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' id='rpt_territorio' class='texto' onChange='ajaxPersonal(this.form)' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value='0'></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Personal</th>";
	echo "<td><div id='divPersonal'></div>
	</td></tr>";
	
	echo "<tr><th align='left'>Ver</th><td><select name='rpt_ver' id='rpt_ver' class='texto' size='2'>";
	echo "<option value='1' selected>Resumido</option>";
	echo "<option value='2'>Detallado</option>";
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
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
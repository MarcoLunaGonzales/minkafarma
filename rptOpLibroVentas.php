<script language='JavaScript'>
function envia_formulario(f)
{	var codAnio,codMes,tipo;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	tipo=f.rpt_tipo.value;
	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rptLibroVentas.php?codTipoTerritorio='+codTipoTerritorio+'&codAnio='+codAnio+'&codMes='+codMes+'&tipo='+tipo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_formulario2(f)
{	var codAnio,codMes,tipo;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	tipo=f.rpt_tipo.value;
	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rptLibroVentasAnuladas.php?codTipoTerritorio='+codTipoTerritorio+'&codAnio='+codAnio+'&codMes='+codMes+'&tipo='+tipo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_formularioTXT(f)
{	var codAnio,codMes,tipo;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	tipo=f.rpt_tipo.value;
	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rptLibroVentastxt_facilito.php?codTipoTerritorio='+codTipoTerritorio+'&codAnio='+codAnio+'&codMes='+codMes+'&tipo='+tipo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}

function envia_formularioTXT_siat(f)
{	var codAnio,codMes,tipo;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	tipo=f.rpt_tipo.value;
	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rptLibroVentastxt.php?codTipoTerritorio='+codTipoTerritorio+'&codAnio='+codAnio+'&codMes='+codMes+'&tipo='+tipo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}

function envia_formularioTXT_siatv2(f)
{	var codAnio,codMes,tipo;
	codAnio=f.cod_anio.value;
	codMes=f.cod_mes.value;
	tipo=f.rpt_tipo.value;
	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rptLibroVentastxt_siatv2.php?codTipoTerritorio='+codTipoTerritorio+'&codAnio='+codAnio+'&codMes='+codMes+'&tipo='+tipo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>
<?php
	
require("conexionmysqli.inc");
require("estilos_administracion.inc");

echo "<form action='rptLibroVentas.php' method='post'>";

echo "<h1>Reporte Libro de Ventas</h1>";

echo "<center><table class='table table-bordered'>";

echo "<tr class='bg-info text-white'><th>Año</th><th>Mes</th><th>Sucursal</th><th>Tipo</th></tr>";

echo "<tr>

<td align='center' width='15%'><select name='cod_anio' id='cod_anio' class='selectpicker'>";
for($i=2018; $i<=date("Y"); $i++){
	if($i==date("Y")){
	    echo "<option value='$i' selected>$i</option>";	
	}else{
		echo "<option value='$i'>$i</option>";
	}
	
}
echo "</select></td>";
echo "<td align='center' width='20%'><select name='cod_mes' id='cod_mes' class='selectpicker'>";
$meses=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
for ($i=0; $i < count($meses); $i++) { 
	$me=$i+1;
	$nombreMes=$meses[$i];
	 if($me==date("m")){
	    echo "<option value='$me' selected>$nombreMes</option>";	
	}else{
		echo "<option value='$me'>$nombreMes</option>";
	}
}
echo "</select></td>";
echo "<td width='50%'><select name='rpt_territorio' data-live-search='true' title='-- Elija una sucursal --'  id='rpt_territorio' multiple data-actions-box='true' data-style='select-with-transition' data-actions-box='true' data-size='10' class='selectpicker form-control' required>";
$globalAgencia=$_COOKIE["global_agencia"];
	if($_COOKIE["admin_central"]==1){
       $sql="select cod_ciudad, descripcion from ciudades where cod_ciudad>0 order by descripcion";    
	}else{	   
       $sql="select cod_ciudad, descripcion from ciudades where cod_ciudad>0 and cod_ciudad='$globalAgencia' order by descripcion";
	}
	
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($codigo_ciudad==$globalAgencia){
           echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}else{
		   echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";	
		}		
	}
echo "</select></td>";
echo "<td width='15%'><select name='rpt_tipo' id='rpt_tipo' data-style='btn btn-rose' class='selectpicker form-control' required>";
	echo "<option value='0'>TODO</option>";
	echo "<option value='1'>AUTOMATICAS</option>";
	echo "<option value='2'>MANUALES</option>";
echo "</select></td></tr>";
echo "</table></center>";

echo "<div class=''>
<input type='button' class='boton' value='Generar TXT (SIAT V2)' onClick='envia_formularioTXT_siatv2(this.form)'>
<input type='button' class='boton2' value='Facturas Anuladas' onClick='envia_formulario2(this.form)'>";

echo "</form>";
?>
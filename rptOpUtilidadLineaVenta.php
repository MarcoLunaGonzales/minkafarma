<script language='JavaScript'>
function envia_formulario(f,formato)
{	var fecha_ini, fecha_fin;
	

	var codSubGrupo=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_subcategoria.options.length-1;i++)
	{	if(f.rpt_subcategoria.options[i].selected)
		{	codSubGrupo[j]=f.rpt_subcategoria.options[i].value;
			j++;
		}
	}

	var codTipoTerritorio=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	codTipoTerritorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	var rpt_tipo=document.getElementById('rpt_tipo').value;
	window.open('rptOpUtilidadLineaRepoVenta.php?codTipoTerritorio='+codTipoTerritorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+''+'&codSubGrupo='+codSubGrupo+''+'&rpt_formato='+formato+'&rpt_tipo='+rpt_tipo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,height=800');			
	return(true);
}

</script>
<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Ranking Ventas x Distribuidor</h1><br>";
echo"<form method='post' action='rptOpKardexCostos.php'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";

	echo "<tr><th align='left' class='text-muted'>Sucursal</th><td><select name='rpt_territorio' data-live-search='true' title='-- Elija una sucursal --'  id='rpt_territorio' multiple class='texto'>";
	$sql="select c.cod_ciudad, c.descripcion from almacenes a join ciudades c on c.cod_ciudad=a.cod_ciudad order by c.descripcion";
	echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";


	echo "<tr><th align='left' class='text-muted' >Proveedor:</th>
	<td><select name='rpt_subcategoria'  id='rpt_subcategoria' class='selectpicker form-control' multiple title='-- Elija un proveedor --' data-live-search='true' data-actions-box='true' data-style='select-with-transition' data-size='10'>";
	$sql="select cod_proveedor, nombre_proveedor from proveedores where cod_proveedor>0 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_cat=$dat[0];
		$nombre_cat=$dat[1];
		echo "<option value='$codigo_cat' selected>$nombre_cat</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left' class='text-muted'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left' class='text-muted'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </TD>";
	echo "</tr>";
		echo "<tr><th align='left' class='text-muted' >Nivel:</th>
	<td><select name='rpt_tipo'  id='rpt_tipo' class='texto'  data-style='btn btn-info' >";
	echo "<option value='1'>PROVEEDOR</option>";
	echo "<option value='2'>SUBLINEA</option>";
	echo "</select></td></tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' class='boton' value='VER REPORTE' onClick='envia_formulario(this.form,1)' class='btn btn-success'>

	</center><br>";//	<input type='button' name='reporte' value='Menos Rentables' onClick='envia_formulario(this.form,0)' class='btn btn-rose'>
	echo"</form>";
	echo "</div>";

?>
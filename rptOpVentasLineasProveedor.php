<script language='JavaScript'>
function envia_formulario(f)
{	var fecha_ini, fecha_fin, rpt_formato;
	
	var codTipoPago=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_tipopago.options.length-1;i++)
	{	if(f.rpt_tipopago.options[i].selected)
		{	codTipoPago[j]=f.rpt_tipopago.options[i].value;
			j++;
		}
	}

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
	rpt_formato=$("#rpt_formato").val();
	window.open('rptVentasLineaProducto.php?codTipoTerritorio='+codTipoTerritorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&codTipoPago='+codTipoPago+''+'&codSubGrupo='+codSubGrupo+''+'&rpt_formato='+rpt_formato+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,height=800');			
	return(true);
}
function cambiarSubLinea(){
  var categoria=$("#rpt_categoria").val();
  var parametros={"categoria":categoria};
     $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxCambiarComboLinea.php",
        data: parametros,   
        success:  function (resp) { 
        	//alert(resp);
          $("#rpt_subcategoria").html(resp);
          $(".selectpicker").selectpicker("refresh");
        }
    });
}
</script>
<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("Y-m-d");
echo "<h1>Reporte Ventas x Linea y Proveedor</h1><br>";
echo"<form method='post' action='rptOpKardexCostos.php'>";

	echo"\n<table class='' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left' class='text-muted'>Sucursal</th><td><select name='rpt_territorio' data-live-search='true' title='-- Elija una sucursal --'  id='rpt_territorio' multiple data-actions-box='true' data-style='select-with-transition' data-actions-box='true' data-size='10' class='selectpicker form-control'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left' class='text-muted' >Tipo de Pago:</th>
	<td><select name='rpt_tipopago' class='selectpicker form-control' multiple data-style='btn btn-primary' data-actions-box='true'>";
	$sql="select cod_tipopago, nombre_tipopago from tipos_pago order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_pago=$dat[0];
		$nombre_pago=$dat[1];
		echo "<option value='$codigo_pago'>$nombre_pago</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left' class='text-muted' >Proveedor:</th>
	<td><select name='rpt_categoria'  id='rpt_categoria' class='selectpicker form-control' data-style='btn btn-primary' onchange='cambiarSubLinea()' data-live-search='true'>
	<option value='' disabled selected>--Seleccione--</option>";
	$sql="select cod_proveedor, nombre_proveedor from proveedores order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_cat=$dat[0];
		$nombre_cat=$dat[1];
		echo "<option value='$codigo_cat'>$nombre_cat</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left' class='text-muted' >Linea:</th>
	<td><select name='rpt_subcategoria' id='rpt_subcategoria' class='selectpicker form-control' multiple data-style='btn btn-primary' data-actions-box='true' data-live-search='true'>";
	echo "</select></td></tr>";

	echo "<tr><th align='left' class='text-muted'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exafinicial' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left' class='text-muted'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='form-control' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exaffinal' ";
    		echo" click_element_id='imagenFecha1'></DLCALENDAR>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left' class='text-muted' >Formato:</th>
	<td><select name='rpt_formato' id='rpt_formato' class='selectpicker form-control' data-style='btn btn-primary'>";
	echo "<option value='1'>RESUMIDO</option>";
	echo "<option value='2'>DETALLADO</option>";
	echo "</select></td></tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='btn btn-primary'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
<?php
require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen, tipo_item, rpt_ver, rpt_fecha, rpt_ordenar, rpt_tipo_impresion;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			rpt_ver=f.rpt_ver.value;
			rpt_fecha=f.rpt_fecha.value;
			rpt_ordenar=f.rpt_ordenar.value;
			rpt_tipo_impresion=f.rpt_tipo_impresion.value;
			
			var rpt_distribuidor=new Array();
			var j=0;
			for(i=0;i<=f.rpt_distribuidor.options.length-1;i++)
			{	if(f.rpt_distribuidor.options[i].selected)
				{	rpt_distribuidor[j]=f.rpt_distribuidor.options[i].value;
					j++;
				}
			}

			window.open('rpt_inv_existencias.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_ver='+rpt_ver+'&rpt_fecha='+rpt_fecha+'&rpt_ordenar='+rpt_ordenar+'&rpt_distribuidor='+rpt_distribuidor+'&rpt_tipo_impresion='+rpt_tipo_impresion,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');

			return(true);
		}
		function activa_tipomaterial(f){
			if(f.tipo_item.value==1)
			{	f.rpt_tipomaterial.disabled=true;
			}
			else
			{	f.rpt_tipomaterial.disabled=false;
			}
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
$fecha_rptdefault=date("d/m/Y");

$rpt_territorio=$_POST["rpt_territorio"];
//echo "rpt territorio: ".$rpt_territorio;

echo "<h1>Reporte Existencias Almacen</h1>";
echo"<form method='post' action=''>";
	
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value='0'>Todos</option>";
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


	echo "<tr><th align='left'>Distribuidor</th><td>
		<select name='rpt_distribuidor' id='rpt_distribuidor' class='texto' multiple size='7'>";
	$sql="select p.cod_proveedor, p.nombre_proveedor from proveedores p order by 2";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='texto'>";
	echo "<option value='1'>Todo</option>";
	echo "<option value='2'>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "</tr>";

	echo "<tr><th align='left'>Tipo de Impresión</th>";
	echo "<td><select name='rpt_tipo_impresion' class='texto'>";
		echo "<option value='0'>NORMAL</option>";
		echo "<option value='1'>PARA INVENTARIO</option>";	
	echo "</select></td>";
	echo "</tr>";


	$fecha_rptdefault=date("d/m/Y");	
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='rpt_fecha' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";
    		echo"  </TD>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar Por:</th>";
	echo "<td><select name='rpt_ordenar' class='texto'>";
	echo "<option value='1'>Producto</option>";
	echo "<option value='2'>Linea y Producto</option>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>
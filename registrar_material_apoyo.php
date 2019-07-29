<script language='Javascript'>
	function validar(f)
	{
		if(f.material.value=='')
		{	alert('El campo Nombre esta vacio.');
			f.material.focus();
			return(false);
		}
		if(f.codLinea.value=='')
		{	alert('Debe seleccionar Linea.');
			f.codLinea.focus();
			return(false);
		}
		if(f.codForma.value=='')
		{	alert('Debe seleccionar Forma Farmaceutica.');
			f.codForma.focus();
			return(false);
		}
		if(f.codEmpaque.value=='')
		{	alert('Debe seleccionar Empaque.');
			f.codEmpaque.focus();
			return(false);
		}
		if(f.codTipoVenta.value=='')
		{	alert('Debe seleccionar Tipo de Venta.');
			f.codTipoVenta.focus();
			return(false);
		}
		
		
		var codAccionTerapeutica=new Array();
		var j=0;
		for(i=0;i<=f.codAccionTerapeutica.options.length-1;i++)
		{	if(f.codAccionTerapeutica.options[i].selected)
			{	codAccionTerapeutica[j]=f.codAccionTerapeutica.options[i].value;
				j++;
			}
		}
		f.arrayAccionTerapeutica.value=codAccionTerapeutica;		
	}

</script>

<head>
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="autoComplete/tokenize2.css" rel="stylesheet" />
    <script src="autoComplete/tokenize2.js"></script>
    <link href="autoComplete/demo.css" rel="stylesheet" />
</head>
<?php
require("conexion.inc");
require('estilos.inc');

echo "<form action='guarda_material_apoyo.php' method='post' name='form1'>";

echo "<h1>Adicionar Producto</h1>";


echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;'>
	</td>";
	
echo "<tr><th align='left'>Linea</th>";
$sql1="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
<div class='container'>
		<div class='col-md-6'>
		<select name='codLinea' id='codLinea' class='tokenize-limit-demo2'>
		<option value=''></option>";
		while($dat1=mysql_fetch_array($resp1))
		{	$codLinea=$dat1[0];
		$nombreLinea=$dat1[1];
		echo "<option value='$codLinea'>$nombreLinea</option>";
		}
		echo "</select>
	</div>
	</div>
</td>";
echo "</tr>";

echo "<tr><th>Forma Farmaceutica</th>";
$sql1="select f.cod_forma_far, f.nombre_forma_far from formas_farmaceuticas f 
where f.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
<div class='container'>
		<div class='col-md-4'>
			<select name='codForma' id='codForma' class='tokenize-limit-demo2'>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codForma=$dat1[0];
				$nombreForma=$dat1[1];
				echo "<option value='$codForma'>$nombreForma</option>";
			}
			echo "</select>
	</div>
	</div>
</td>";
echo "</tr>";

echo "<tr><th>Empaque</th>";
$sql1="select e.cod_empaque, e.nombre_empaque from empaques e where e.estado=1 order by 2;";
$resp1=mysql_query($sql1);
echo "<td>
	<div class='container'>
		<div class='col-md-4'>
			<select name='codEmpaque' id='codEmpaque' class='tokenize-limit-demo2'>
				<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codEmpaque=$dat1[0];
				$nombreEmpaque=$dat1[1];
				echo "<option value='$codEmpaque'>$nombreEmpaque</option>";
			}
echo "</select>
	</div>
	</div>
</td>";
echo "</tr>";

echo "<tr><th>Cantidad Presentacion</th>
	<td><input type='number' name='cantidadPresentacion' id='cantidadPresentacion' min='1' max='1000' value='1'></td>
	</tr>";
	
echo "<tr><th>Principio Activo</th>
	<td><input type='text' name='principioActivo' id='principioActivo' style='text-transform:uppercase;'></td>
	</tr>";

echo "<tr><th>Tipo Venta</th>";
$sql1="select t.cod_tipoventa, t.nombre_tipoventa from tipos_venta t where t.estado=1;";
$resp1=mysql_query($sql1);
echo "<td>
	<div class='container'>
		<div class='col-md-4'>
			<select name='codTipoVenta' id='codTipoVenta' class='tokenize-limit-demo2'>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codTipoVenta=$dat1[0];
				$nombreTipoVenta=$dat1[1];
				echo "<option value='$codTipoVenta'>$nombreTipoVenta</option>";
			}
echo "</select>
	</div>
	</div>
</td>";
echo "</tr>";


echo "<tr><th>Accion Terapeutica</th>";
$sql1="select l.cod_accionterapeutica as value, l.nombre_accionterapeutica as texto from acciones_terapeuticas l;";
$resp1=mysql_query($sql1);
echo "<td>
	<div class='container'>
		<div class='col-md-6'>
			<select name='codAccionTerapeutica' id='codAccionTerapeutica' class='tokenize-sample-demo1' multiple>
			<option value=''></option>";
			while($dat1=mysql_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				echo "<option value='$codigo'>$nombre</option>";
			}
echo "</select>
	</div>
	</div>
</td>";
echo "</tr>";


echo "<tr><th>Producto Controlado</th>";
echo "<td>
		<input type='radio' name='producto_controlado' value='0' checked>NO
        <input type='radio' name='producto_controlado' value='1'>SI
</td>";
echo "</tr>";

echo "<tr><th align='left'>Precio de Venta</th>";
echo "<td align='left'>
	<input type='number' class='texto' name='precio_producto' id='precio_producto' step='0.01'>
	</td></tr>";

?>	

	<script>
		$('.tokenize-sample-demo1').tokenize2();
		$('.tokenize-limit-demo2').tokenize2({
                tokensMaxItems: 1
        });
	</script>
	
<?php
	echo "</td></tr>";
echo "</table></center>";
echo "<input type='hidden' name='arrayAccionTerapeutica' id='arrayAccionTerapeutica'>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>

<script>
    $('.tokenize-sample-demo1').tokenize2();
</script>


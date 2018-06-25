<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre_material.value=='')
		{	alert('El campo Nombre de Material esta vacio.');
			f.nombre_material.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_materiales.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Materiales</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Tipo de Material</th>";
$sql1="select * from tipos_material order by nombre_tipomaterial";
$resp1=mysql_query($sql1);
echo "<td><select name='tipo_material' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipomaterial=$dat1[0];
	$nombre_tipomaterial=$dat1[1];
	echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Nombre de Material</th>";
echo "<td align='center'><input type='text' class='texto' name='nombre_material' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "</tr>";
echo "<tr><th align='left'>Producto</th>";
$sql2="select cod_producto, descripcion from productos order by descripcion";
$resp2=mysql_query($sql2);
echo "<td><select name='producto' class='texto'>";
while($dat2=mysql_fetch_array($resp2))
{	$cod_producto=$dat2[0];
	$nombre_producto=$dat2[1];
	echo "<option value='$cod_producto'>$nombre_producto</option>";
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Forma Farmaceutica</th>";
$sql3="select cod_forma, nombre_forma from formas_farmaceuticas order by nombre_forma";
$resp3=mysql_query($sql3);
echo "<td><select name='forma_farmaceutica' class='texto'>";
echo "<option value=''></option>";
while($dat3=mysql_fetch_array($resp3))
{	$cod_forma=$dat3[0];
	$nombre_forma=$dat3[1];
	echo "<option value='$cod_forma'>$nombre_forma</option>";
}
echo "</select></td>";
echo "</tr>";
echo "</tr>";
echo "<tr><th align='left'>Presentación</th>";
echo "<td align='center'><input type='text' class='texto' name='presentacion' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "</tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_almacenes.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>
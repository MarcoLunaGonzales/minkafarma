<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		var almacen, ciudad, responsable;
		if(f.nombre_almacen.value=='')
		{	alert('El campo Nombre de Almacen esta vacio.');
			f.nombre_almacen.focus();
			return(false);
		}
		almacen=f.nombre_almacen.value;
		ciudad=f.territorio.value;
		responsable=f.responsable.value;
		location.href='guarda_almacenes.php?nombre_almacen='+almacen+'&territorio='+ciudad+'&responsable='+responsable+'';
	}
	function envia_form(f)
	{	f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos.inc");
echo "<form action='' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Almacen</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre Almacen</th><th>Territorio</th><th>Responsable</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' value='$nombre_almacen' name='nombre_almacen' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
$sql1="select * from ciudades order by descripcion";
$resp1=mysql_query($sql1);
echo "<td><select name='territorio' class='texto' OnChange='envia_form(this.form)'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_ciudad=$dat1[0];
	$nombre_ciudad=$dat1[1];
	if($cod_ciudad==$territorio)
	{	echo "<option value='$cod_ciudad' selected>$nombre_ciudad</option>";
	}
	else
	{	echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
	}
}
echo "</select></td>";
$sql2="select codigo_funcionario, paterno, materno, nombres from funcionarios where cod_cargo=1016 and cod_ciudad='$territorio' order by paterno, materno";
$resp2=mysql_query($sql2);
echo "<td><select name='responsable' class='texto'>";
while($dat2=mysql_fetch_array($resp2))
{	$cod_funcionario=$dat2[0];
	$nombre_funcionario="$dat2[1] $dat2[2] $dat2[3]";
	if($responsable==$cod_funcionario)
	{	echo "<option value='$cod_funcionario' selected>$nombre_funcionario</option>";
	}
	else
	{	echo "<option value='$cod_funcionario'>$nombre_funcionario</option>";
	}
}
echo "</select></td>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_almacenes.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>
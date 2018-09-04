<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.tipo_ingreso.value=='')
		{	alert('El campo Nombre de Tipo de Ingreso esta vacio.');
			f.tipo_ingreso.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
$sql=mysql_query("select nombre_tipoingreso, obs_tipoingreso, tipo_almacen from tipos_ingreso where cod_tipoingreso=$codigo_registro");
$dat=mysql_fetch_array($sql);
$nombre_tipoingreso=$dat[0];
$obs_tipoingreso=$dat[1];
$tipo_almacen=$dat[2];
echo "<form action='guarda_modi_tiposingreso.php' method='post'>";

echo "<h1>Editar Tipo de Ingreso</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Nombre de Tipo de Ingreso</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='texto' name='tipo_ingreso' value='$nombre_tipoingreso' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Definición Tipos de Ingreso</th>";
echo "<td align='center'><textarea class='texto' name='obs_tipo_ingreso' cols='40' rows='5'>$obs_tipoingreso</textarea></td></tr>";
echo "<tr><th>Tipo de Almacen</th><td align='center'><select name='tipo_almacen' class='texto'>";
if($tipo_almacen==1)
{	echo "<option value='1' selected>Almacen Central</option>";
	echo "<option value='2'>Almacen Regional</option>";
}
else
{	echo "<option value='1'>Almacen Central</option>";
	echo "<option value='2' selected>Almacen Regional</option>";
}
echo "</select></td>";
echo "</tr>";

echo "</table></center>";

echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='javascript:location.href=\"navegador_tiposingreso.php\"'>
</div>";

echo "</form>";
?>
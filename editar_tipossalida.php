<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.tipo_salida.value=='')
		{	alert('El campo Nombre de Tipo de Salida esta vacio.');
			f.tipo_salida.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexionmysqli.php");
require("estilos_administracion.inc");
$sql=mysqli_query($enlaceCon,"select nombre_tiposalida, obs_tiposalida, tipo_almacen from tipos_salida where cod_tiposalida=$codigo_registro");
$dat=mysqli_fetch_array($sql);
$nombre_tiposalida=$dat[0];
$obs_tiposalida=$dat[1];
$tipo_almacen=$dat[2];
echo "<form action='guarda_modi_tipossalida.php' method='post'>";

echo "<h1>Editar Tipos de Salida</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Nombre de Tipo de Salida</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='texto' name='tipo_salida' value='$nombre_tiposalida' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Definición Tipos de Salida</th>";
echo "<td align='center'><textarea class='texto' name='obs_tipo_salida' cols='40' rows='5'>$obs_tiposalida</textarea></td></tr>";
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
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_tipossalida.php\"'>
</div>";

echo "</form>";
?>
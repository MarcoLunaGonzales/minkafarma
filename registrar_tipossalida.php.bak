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
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_tipossalida.php' method='post'>";

echo "<h1>Registrar Tipo de Salida</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre Tipo de Salida</th>";

echo "<td align='center'><input type='text' class='texto' name='tipo_salida' size='40' 
onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Definicion Tipo de Salida</th>";
echo "<td align='center'><textarea class='texto' name='obs_tipo_salida' cols='40' rows='5'></textarea></td></tr>";
echo "<tr><th>Tipo de Almacen</th><td align='center'><select name='tipo_almacen' class='texto'>
<option value='1'>Almacen Central</option>
<option value='2'>Almacen Regional</option>
</select></td>";
echo "</tr>";
echo "</table></center>";


echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_tipossalida.php\"'>
</div>";
echo "</form>";
?>
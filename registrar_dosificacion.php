<script language='Javascript'>
	function validar(f)
	{
		return(true);
	}

</script>

<?php
require("conexionmysqli.php");
require('estilos.inc');

echo "<form action='guarda_dosificaciones.php' method='post' name='form1'>";

echo "<h1>Adicionar Dosificacion de Facturas</h1>";


echo "<center><table class='texto'>";
	
echo "<tr><th>Sucursal</th>";
$sql1="select cod_ciudad, descripcion from ciudades order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_sucursal' id='cod_sucursal' required>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codCiudad=$dat1[0];
				$nombreCiudad=$dat1[1];
				echo "<option value='$codCiudad'>$nombreCiudad</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Nro. de Autorizacion</th>
	<td><input type='number' name='nro_autorizacion' id='nro_autorizacion' style='width:300px;height:20px' required></td>
	</tr>";

echo "<tr><th>Llave de Dosificacion</th>
	<td><input type='text' name='llave_dosificacion' id='llave_dosificacion' style='width:600px;height:20px' required></td>
	</tr>";
	
echo "<tr><th>Fecha Limite Emision</th>
	<td><input type='date' name='fecha_limite_emision' id='fecha_limite_emision' required></td>
	</tr>";

echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_dosificaciones.php\"'>
</div>";

?>
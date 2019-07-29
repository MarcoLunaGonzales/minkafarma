<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.contrasena.value=='')
		{
		  	alert('El campo Clave esta vacio.');
			f.contrasena.focus();
			return(false);
		}
		else
		{
			 if(f.contrasena.value.length < 8)
			 {
			 	alert('La Clave debe tener al menos 8 caracteres.');
				return(false);  
			 }
			  
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
	$txtCab="select f.paterno, f.materno, f.nombres from funcionarios f, usuarios_sistema u 
		where f.codigo_funcionario='$codigo_funcionario' and f.codigo_funcionario=u.codigo_funcionario";
	$sql_cab=mysql_query($txtCab);
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_funcionario="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
	
echo "<form action='guarda_restablecer_contrasena.php' method='get'>";
echo "<h1>Restablecer Clave<br>Funcionario: $nombre_funcionario</h1>";
echo "<center><table class='texto'>";
echo "<tr><th>Codigo</th><th>Clave</th></tr>";

echo "<tr>
	<th>$codigo_funcionario</th>
	<td align='center'><input type='text' class='texto' name='contrasena' size='40'></td>
	</tr>";
	
echo "<input type='hidden' name='codigo_funcionario' value='$codigo_funcionario'>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio'>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_funcionarios.php?cod_ciudad=$cod_territorio'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "<center><table border='0' width='40%'><tr><th>Nota: La Clave debe tener al menos 8 caracteres.</th></tr></table></center>";
?>
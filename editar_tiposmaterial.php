<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.tipo_material.value=='')
		{	alert('El campo Nombre de Tipo de material esta vacio.');
			f.tipo_material.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos.inc");
$sql=mysql_query("select nombre_tipomaterial, obs_tipomaterial from tipos_material where cod_tipomaterial=$codigo_registro");
$dat=mysql_fetch_array($sql);
$nombre_tipomaterial=$dat[0];
$obs_tipomaterial=$dat[1];
echo "<form action='guarda_modi_tiposmaterial.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Tipos de Material</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Nombre de Tipo de Material</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='texto' name='tipo_material' value='$nombre_tipomaterial' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Definición de Tipo de Material</th>";
echo "<td align='center'><textarea class='texto' name='obs_tipo_material' cols='40' rows='5'>$obs_tipomaterial</textarea></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_tiposmaterial.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>
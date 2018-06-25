<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.placa.value=='')
		{	alert('El campo Placa esta vacio.');
			f.placa.focus();
			return(false);
		}
		
		if(f.nombre.value=='')
		{	alert('El campo Nombre esta vacio.');
			f.nombre.focus();
			return(false);
		}
		
		if(f.peso.value=='')
		{	alert('El campo Peso Maximo esta vacio.');
			f.peso.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guardaVehiculos.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Vehiculos</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Placa</th>
	<td><input type='text' class='texto' name='placa' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "</tr>";
echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='center'><input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "</tr>";
echo "<tr><th align='left'>Peso Maximo</th>";
echo "<td><input type='texto' name='peso' class='texto'></td>";
echo "</tr>";

echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegadorVehiculos.php'>
	<img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>
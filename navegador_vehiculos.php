<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrarVehiculos.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un registro para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminarVehiculo.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar sus datos.');
				}
				else
				{
					location.href='editarVehiculo.php?codigo='+j_ciclo+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require('estilos_almacenes.inc');
	
	echo "<h1>Registro de Vehiculos</h1>";
	
	echo "<form method='post' action=''>";
	$sql="select codigo, placa, nombre, peso_maximo from vehiculos order by placa";
	$resp=mysql_query($sql);

	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";

	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>&nbsp;</th><th>Placa</th><th>Nombre</th><th>Peso Maximo (Kg.)</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$placa=$dat[1];
		$nombre=$dat[2];
		$pesoMaximo=$dat[3];

		echo "<tr><td align='center'>$indice_tabla</td>
		<td align='center'><input type='checkbox' name='codigo' value='$codigo'></td>
		<td align='center'>$placa</td>
		<td>$nombre</td>
		<td>$pesoMaximo</td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>

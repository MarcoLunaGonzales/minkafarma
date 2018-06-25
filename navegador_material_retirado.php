<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function habilitar(f)
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
			{	alert('Debe seleccionar al menos un material retirado para habilitarlo.');
			}
			else
			{
				if(confirm('Esta seguro de habilitar este material.'))
				{
					location.href='habilitar_material.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>
	";
	require("conexion.inc");
	require("estilos_inicio_adm.inc");
	echo "<form method='post' action=''>";
	$sql="select * from material_apoyo where estado='Retirado' order by descripcion_material";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Material de Apoyo Retirado de todas las Líneas</td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Material de Apoyo Retirado</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$desc=$dat[1];
		echo "<tr><td align='center'>$indice_tabla</td><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$desc</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	require('home_central.inc');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Habilitar' class='boton' onclick='habilitar(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
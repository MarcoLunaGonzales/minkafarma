<?php

echo "<script language='Javascript'>
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Registro para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Almacen para editar sus datos.');
				}
				else
				{
					location.href='editar_etiquetas.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_almacenes.inc");

	echo "<form method='post' action=''>";
	$sql="select e.id, e.txt1, e.txt2, e.txt3, e.alineado_izq, e.alineado_arriba, e.cantidad from etiquetas e";
	$resp=mysql_query($sql);
	echo "<h1>Configuracion de Etiquetas</h1>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Texto 1</th><th>Texto 2</th><th>Texto 3</th><th>Margen Izquierda</th><th>Margen Arriba</th><th>Cantidad</th><th>-</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$txt1=$dat[1];
		$txt2=$dat[2];
		$txt3=$dat[3];
		$alignIzq=$dat[4];
		$alignTop=$dat[5];
		$cantidad=$dat[6];
		
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$txt1</td><td>$txt2</td><td>$txt3</td><td>$alignIzq</td><td>$alignTop</td><td>$cantidad</td>
		<td><a href='formatoEtiquetas.php' target='_BLANK'><img src='imagenes/print.jpg' width='30' border='0' title='Imprimir'></a>
		</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	</div>";
	echo "</form>";
?>
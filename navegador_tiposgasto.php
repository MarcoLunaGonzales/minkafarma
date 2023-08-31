<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_tipogasto.php';
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
			{	alert('Debe seleccionar al menos un Grupo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_tiposgasto.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente un Grupo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Grupo para editar sus datos.');
				}
				else
				{
					location.href='editar_tipogasto.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexionmysqli.php");
	require("estilos_almacenes.inc");
	
	echo "<form method='post' action=''>";
	$sql="select cod_tipogasto, nombre_tipogasto, estado, tipo from tipos_gasto where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Registro de Tipos de Gasto</h1>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nombre</th><th>Tipo</th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$material=$dat[1];
		$tipo=$dat[3];
		$nombreTipo="";
		if($tipo==1){
			$nombreTipo="Diario / Normal";
		}else{
			$nombreTipo="Mensual / Anual";
		}
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$material</td>
		<td>$nombreTipo</td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
	echo "</form>";
?>
<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_material_apoyo.php';
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
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_material_apoyo.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente un material de apoyo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un material de apoyo para editar sus datos.');
				}
				else
				{
					location.href='editar_material_apoyo.php?cod_material='+j_ciclo+'';
				}
			}
		}
		function cambiar_vista(sel_vista, f)
		{
			var modo_vista;
			modo_vista=sel_vista.value;
			location.href='navegador_material.php?vista='+modo_vista+'';
		}
		</script>";
		
	require("conexion.inc");
	require('estilos.inc');
	
	echo "<h1>Registro de Materiales</h1>";

	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, 
	m.peso, m.orden_grupo, m.abreviatura, m.item_metraje, m.nro_metros from material_apoyo m, 
	tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial and m.estado='Activo' order by t.nombre_tipomaterial, m.orden_grupo";
	if($vista==1)
	{	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, 
		m.peso, m.orden_grupo, m.abreviatura, m.item_metraje, m.nro_metros from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial 
		and m.estado='Retirado' order by t.nombre_tipomaterial, m.orden_grupo";
	}
	if($vista==2)
	{
	 	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, 
		m.peso, m.orden_grupo, m.abreviatura, m.item_metraje, m.nro_metros from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial 
		order by t.nombre_tipomaterial, m.orden_grupo";
	}
	$resp=mysql_query($sql);
	
	echo "<table align='center' class='texto'><tr><th>Ver Material de Apoyo:</th>
	<th><select name='vista' class='texto' onChange='cambiar_vista(this, this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	if($vista==2)	echo "<option value='0'>Activos</option><option value='1'>Retirados</option><option value='2' selected>Todo</option>";
	echo "</select>";
	echo "</th></tr></table><br>";
	
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		</div>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>&nbsp;</th><th>Codigo Interno</th><th>Abreviatura</th><th>Nombre Item</th>
		<th>Tipo de Material de Apoyo</th><th>Peso</th><th>Manejo por Metros</th><th>Nro. Metros</th></tr>";
	
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$desc=$dat[1];
		$estado=$dat[2];
		$tipo_material=$dat[3];
		$pesoMaterial=$dat[4];
		$ordenGrupo=$dat[5];
		$abreviatura=$dat[6];
		$itemMetraje=$dat[7];
		$nroMetros=$dat[8];

		if($itemMetraje==0){
			$nombreMetraje="No";
		}else{
			$nombreMetraje="Si";
		}
		
		if($estado=='Retirado')
		{
			$fondo_fila="#ff6666";
		}
		else
		{
		 	$fondo_fila="";
		}
		echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td><td align='center'>
		<input type='checkbox' name='codigo' value='$codigo'></td>
		<td align='center'><span style='color:#ff0000; font-size: 14pt'>$ordenGrupo</span></td><td>$abreviatura</td><td>$desc</td><td>$tipo_material</td>
		<td>&nbsp;$pesoMaterial</td><td>&nbsp;$nombreMetraje</td><td>&nbsp;$nroMetros</td></tr>";
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

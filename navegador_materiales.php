<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_materiales.php';
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
			{	alert('Debe seleccionar al menos un Material para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_materiales.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente un Material para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Material para editar sus datos.');
				}
				else
				{
					location.href='editar_materiales.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select m.cod_material, tm.nombre_tipomaterial, m.nombre_material, p.descripcion, f.nombre_forma, presentacion
	from materiales m, tipos_material tm, productos p, formas_farmaceuticas f where 
	m.cod_tipomaterial=tm.cod_tipomaterial AND p.cod_producto=m.cod_producto and f.cod_forma=m.cod_forma
	order by tm.nombre_tipomaterial, m.nombre_material";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Materiales</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='90%'>";
	echo "<tr><th>&nbsp;</th><th>Tipo de Material</th><th>Nombre Material</th><th>Producto</th><th>Forma Farmaceutica</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre_tipomaterial=$dat[1];
		$nombre_material=$dat[2];
		$nombre_producto=$dat[3];
		$forma_farmaceutica=$dat[4];
		$presentacion=$dat[5];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$nombre_tipomaterial</td><td>$nombre_material</td><td>$nombre_producto</td><td>$forma_farmaceutica</td><td>$presentacion</td></tr>";
	}
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
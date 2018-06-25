<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_tiposbaja.php';
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
			{	alert('Debe seleccionar al menos un Tipo de Baja para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_tiposbaja.php?datos='+datos+'';
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
			var j_cargo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cargo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Tipo de Baja para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Tipo de Baja para editar sus datos.');
				}
				else
				{
					location.href='editar_tiposbaja.php?codigo='+j_cargo+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select codigo_motivo, tipo_motivo, descripcion_motivo from motivos_baja order by 2,3";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Tipos de Baja</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Tipo Motivo</th><th>Motivo</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$tipoMotivo=$dat[1];
		if($tipoMotivo==1){
			$nombreTipo="Baja de Dias";	
		}else{
			$nombreTipo="Baja de Medicos";
		}
		$motivo=$dat[2];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$nombreTipo</td><td>$motivo</td></tr>";
	}
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
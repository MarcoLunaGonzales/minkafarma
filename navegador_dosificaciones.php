<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_dosificacion.php';
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
			{	alert('Debe seleccionar al menos un Almacen para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_dosificaciones.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function activar_nav(f){
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
			{	alert('Debe seleccionar solamente un registro para activar.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para activar.');
				}
				else
				{
					location.href='activar_dosificacion.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexionmysqli.php");
	require("estilos_almacenes.inc");

	echo "<form method='post' action=''>";
	
	$sql="select d.cod_dosificacion, c.descripcion, e.nombre_estado, d.nro_autorizacion, d.llave_dosificacion, d.fecha_limite_emision, d.cod_estado
		from dosificaciones d, estados_dosificacion e, ciudades c
		where d.cod_estado=e.cod_estado and c.cod_ciudad=d.cod_sucursal and d.cod_estado in (1,2,3)";
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Registro de Dosificaciones</h1>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Ciudad</th><th>Nro.Autorizacion</th><th>Llave</th><th>Fecha Limite Emision</th><th>Estado</th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreCiudad=$dat[1];
		$nombreEstado=$dat[2];
		$nroAutorizacion=$dat[3];
		$llaveDosificacion=$dat[4];
		$fechaLimiteEmision=$dat[5];
		$codEstado=$dat[6];
		
		if($codEstado==2){
			$chk="<input type='checkbox' name='codigo' value='$codigo'>";
		}else{
			$chk="&nbsp;";
		}
		
		echo "<tr><td>$chk</td><td>$nombreCiudad</td>
		<td>$nroAutorizacion</td><td>$llaveDosificacion</td><td>$fechaLimiteEmision</td>
		<td>$nombreEstado</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Activar' name='activar' class='boton' onclick='activar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	echo "</form>";
?>
<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_salidaalmacenes.php';
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
			{	alert('Debe seleccionar solamente un Ingreso Pendiente para registrar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Ingreso Pendiente para registrar sus datos.');
				}
				else
				{
					location.href='registrar_ingresotransito.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1';
				}
			}
		}
		</script>";
	require("conexion.inc");
	if($global_tipoalmacen==1)
	{	require("estilos_almacenes_central.inc");
	}
	else
	{	require("estilos_almacenes.inc");
	}
	echo "<form method='post' action=''>";
	$sql="select s.cod_salida_almacenes, s.cod_almacen, s.despacho_fecha, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.nro_correlativo 
	FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a 
	where s.cod_tiposalida=ts.cod_tiposalida and s.almacen_destino='$global_almacen' and s.cod_almacen<>'$global_almacen' and s.grupo_salida='1' and s.estado_salida=1 and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen order by despacho_fecha";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Ingreso de Muestras en Transito</th></tr></table></center><br>";
	require('home_almacen.php');
	echo "<center><table border='0' class='texto'>";
	if($global_usuario!="1129")
	{	echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";	
	}
	echo "<center><table border='1' class='texto' cellspacing='0' width='100%'>";
	echo "<tr><th>&nbsp;</th><th>Fecha Despacho</th><th>Tipo de Salida<br>(Origen)</th><th>Territorio<br>Origen</th><th>Nota de Remision<br>(Origen)</th><th>Observaciones</th><th>Funcionario Destino</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$cod_almacen_origen=$dat[1];
		$sql_almacen_origen=mysql_query("select a.nombre_almacen, c.descripcion from almacenes a, ciudades c 
		where a.cod_almacen=$cod_almacen_origen and a.cod_ciudad=c.cod_ciudad");
		$dat_almacen_origen=mysql_fetch_array($sql_almacen_origen);
		$nombre_almacen_origen=$dat_almacen_origen[0];
		$ciudad_almacen_origen=$dat_almacen_origen[1];
		$fecha_salida=$dat[2];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$nombre_tiposalida=$dat[3];
		$nombre_ciudad=$dat[4];
		$nombre_almacen=$dat[5];
		$obs_salida=$dat[6];
		$nro_correlativo=$dat[7];
		$sql_funcionario="select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador sv
		where sv.cod_salida_almacen='$codigo' and f.codigo_funcionario=sv.codigo_funcionario";
		$resp_funcionario=mysql_query($sql_funcionario);
		$dat_funcionario=mysql_fetch_array($resp_funcionario);
		$nombre_funcionario="$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td align='center'>$nombre_tiposalida</td><td>$nombre_almacen_origen $ciudad_almacen_origen</td><td align='center'>$nro_correlativo</td><td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td>";
		echo "<td><a target='_BLANK' href='navegador_detallesalidamuestrastransito.php?codigo_salida=$codigo&almacen_origen=$cod_almacen_origen&grupo_salida=1'><img src='imagenes/detalles.gif' border='0' alt='Ver Detalles'>Detalles Ingreso</a></td>";
		echo "<td><a target='_BLANK' href='navegador_detalleingresotransitoenvio.php?codigo_salida=$codigo&almacen_origen=$cod_almacen_origen'><img src='imagenes/detalles.gif' border='0' alt='Ver Detalles'>Detalles envio</a></td></tr>";
	}
	echo "</table></center><br>";
	require('home_almacen.php');
	echo "<center><table border='0' class='texto'>";
	if($global_usuario!="1129")
	{	echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";	
	}
	//echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
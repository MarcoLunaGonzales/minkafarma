<?php
	require("conexionmysqli.php");
	require("estilos_almacenes.inc");

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
			{	alert('Debe seleccionar solamente un Ingreso.');
			}
			else
			{
				if(j==0) 
				{
					alert('Debe seleccionar un Ingreso.');
				}
				else
				{
					location.href='registrar_ingresotransito.php?codigo_registro='+j_cod_registro;
				}
			}
		}
		</script>";
	echo "<form method='post' action=''>";
	$sql="select s.cod_salida_almacenes, s.cod_almacen, s.fecha, ts.nombre_tiposalida, a.nombre_almacen, s.observaciones, s.nro_correlativo 
	FROM salida_almacenes s, tipos_salida ts, almacenes a 
	where s.cod_tiposalida=ts.cod_tiposalida and s.almacen_destino='$global_almacen' and s.estado_salida=1 and a.cod_almacen=s.cod_almacen and s.salida_anulada <> 1 ORDER BY 1 desc";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<h1>Ingreso en Transito</h1>";

	echo "<center><table class='texto'>";
	echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";


	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Fecha Despacho</th><th>Tipo de Salida<br>(Origen)</th><th>Territorio<br>Origen</th><th>Nota de Remision<br>(Origen)</th><th>Observaciones</th><th>Detalle</th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$cod_almacen_origen=$dat[1];
		$sql_almacen_origen=mysqli_query($enlaceCon,"select a.nombre_almacen, c.descripcion from almacenes a, ciudades c 
		where a.cod_almacen=$cod_almacen_origen and a.cod_ciudad=c.cod_ciudad");
		$dat_almacen_origen=mysqli_fetch_array($sql_almacen_origen);
		$nombre_almacen_origen=$dat_almacen_origen[0];
		$ciudad_almacen_origen=$dat_almacen_origen[1];
		$fecha_salida=$dat[2];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$nombre_tiposalida=$dat[3];
		$nombre_almacen=$dat[4];
		$obs_salida=$dat[5];
		$nro_correlativo=$dat[6];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_almacen_origen $ciudad_almacen_origen</td><td align='center'>$nro_correlativo</td><td>&nbsp;$obs_salida</td><td><a target='_BLANK' href='detalleIngresoTransito.php?codigo_salida=$codigo&almacen_origen=$cod_almacen_origen'>";
		echo "<img src='imagenes/detalle.png' title='Ver Detalles' width='40'></a></td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	
	echo "</form>";
?>
<script language='Javascript'>
function enviar(f){
	f.submit();
}
</script>

<?php

	require("conexion.inc");
	require("estilos.inc");
	require("funciones.php");

	$codMes=$_GET['codMes'];
	$codAnio=$_GET['codAnio'];
	$codAlmacen=$_GET['codAlmacen'];
	
	echo "<form method='POST' action='guardarCostosMes.php' name='form1'>";
	
	echo "<input type='hidden' name='codMes' value='$codMes'>
			<input type='hidden' name='codAnio' value='$codAnio'>
			<input type='hidden' name='codAlmacen' value='$codAlmacen'>";
	
	$sql="select codigo_material, descripcion_material, t.`nombre_tipomaterial` from material_apoyo ma, `tipos_material` t
			where ma.`cod_tipo_material`=t.`cod_tipomaterial` order by 3,2";

	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Costos x Mes<br>Año: $codAnio Mes: $codMes</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%' id='main'>";
	echo "<tr><th>Material</th>
	<th>Costo</th>
	</tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreMaterial=$dat[1];
		$nombreTipo=$dat[2];

		echo "<tr><td>$nombreMaterial</td>";

		$sqlCosto="select c.`costo_unitario` from `costo_promedio_mes` c where 
			c.`anio`='$codAnio' and c.`mes`='$codMes' and c.`cod_material`='$codigo' and c.cod_almacen='$codAlmacen'";
		$respCosto=mysql_query($sqlCosto);
		$numFilas=mysql_num_rows($respCosto);
		
		if($numFilas==1){
			$costoUnit=mysql_result($respCosto,0,0);
		}else{
			$costoUnit=0;
		}

		$indice++;

		echo "<td align='center'><input type='text' size='8' value='$costoUnit' id='costo' name='$codigo'></td>";
		echo "</tr>";
	}
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Modificar' name='adicionar' class='boton' onclick='enviar(form1)'></td></tr></table></center>";
	echo "</form>";
?>
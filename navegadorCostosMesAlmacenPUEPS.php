<?php
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";

	echo "<center><table border='0' class='textotit'><tr><td>Cargado de Costos Iniciales x Almacen x Mes PEPS/UEPS</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";


	$sql="select a.`cod_almacen`, a.`nombre_almacen` from `almacenes` a";
	$resp=mysql_query($sql);
	echo "<tr><th>Almacen</th><th>Gestión</th><th>Mes</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre_almacen=$dat[1];
		$anioCosto=date("Y");
		$mesCosto=date("m");
		for($i=1; $i<=6; $i++){
			echo "<tr><td>$nombre_almacen</td><td>$anioCosto</td>
				<td align='center'><a href='costosMesMaterialPUEPS.php?codMes=$mesCosto&codAnio=$anioCosto&codAlmacen=$codigo'>$mesCosto</a></td></tr>";
			$mesCosto=$mesCosto-1;
			if($mesCosto==0){
				$anioCosto=$anioCosto-1;
				$mesCosto=12;
			}
		}
	}

	echo "</table></center><br>";
	echo "</form>";
?>
<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require("estilos_almacenes.inc");
	echo "<form method='post' action=''>";
	// utilizamos $codigo_gestion de estilos.inc
	$sql="select distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g 	
			  where c.`codigo_gestion`=g.`codigo_gestion` and g.`estado`='Activo' order by 1 desc";
	/*$sql="select distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g 	
			  where c.`codigo_gestion`=g.`codigo_gestion` and g.codigo_gestion=1007 order by 1 desc";		  */
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Ciclos</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>Ciclo</th><th>Ver >></th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$codGestion=$dat[1];
		echo "<tr><td align='center'>$codigo</td><td align='center'><a href='navegador_devolucionVisitador.php?cod_ciclo=$codigo&cod_gestion=$codGestion'>Ver>></a></td></tr>";
	}
	echo "</table></center><br>";
	echo "</form>";
?>
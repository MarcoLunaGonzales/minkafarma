<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

echo "<center>";

echo "<h1>Distribuidores</h1>";
echo "<table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Nombre</th><th>Direccion</th><th>Telefono 1</th><th>Telefono 2</th><th>Contacto</th><th>Detalle Lineas</th><th>Ver Lineas</th>";
echo "</tr>";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2, p.contacto
    FROM proveedores AS p 
    WHERE 1 = 1 ORDER BY p.nombre_proveedor ASC
";
$rs=mysqli_query($enlaceCon,$consulta);//se actualizo la conexion
$cont=0;
while($reg=mysqli_fetch_array($rs))
   {$cont++;
    $codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
	
	$consultaDet="select p.nombre_linea_proveedor
	from proveedores_lineas p where p.cod_proveedor=$codProv and estado=1 order by 1";
	//echo $consultaDet;
	$rsDet=mysql_query($consultaDet);
	$txtLineas="";
	while($regDet=mysql_fetch_array($rsDet)){
		$txtLineas.="".$regDet[0].", ";
	}
	
    echo "<tr>";
    echo "<td><input type='checkbox' id='idchk$cont' value='$codProv' ></td><td>$nomProv</td><td>$direccion</td><td>$telefono1</td>
	<td>$telefono2</td><td>$contacto</td>
	<td>$txtLineas</td>";
	
    echo "<td><a href='navegadorLineasDistribuidores.php?codProveedor=$codProv'><img src='../../imagenes/detalle.png' width='40' title='Ver Lineas'></a></td>";
	echo "</tr>";
   }
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";


?>

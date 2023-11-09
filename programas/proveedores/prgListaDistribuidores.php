<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

echo "<h2>Distribuidores</h2>";

echo "<div class='divBotones2'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";
echo "<br><center><table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Codigo</th>
	<th>Nombre</th>
	<th>Direccion</th>
	<th>Telefono 1</th>
	<th>Telefono 2</th>
	<th>Contacto</th>
	<th>Detalle Lineas<br>Distribución</th>
	<th>Ver Lineas<br>Distribución</th>";
echo "</tr>";
$consulta="
    SELECT p.codigo, p.nombre, p.direccion, p.telefono1, p.telefono2, p.contacto
    FROM distribuidores AS p 
    WHERE estado=1 ORDER BY p.nombre ASC
";
$rs=mysqli_query($enlaceCon,$consulta);//se actualizo la conexion
$cont=0;
while($reg=mysqli_fetch_array($rs))
   {$cont++;
    $codProv = $reg["codigo"];
    $nomProv = $reg["nombre"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
	
	$consultaDet="select CONCAT('[', p.nombre_proveedor,']-', pl.nombre_linea_proveedor)as lineas from distribuidores_lineas dl, proveedores_lineas pl, proveedores p where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=dl.cod_linea_proveedor and  
		dl.cod_distribuidor='$codProv'";
	//echo $consultaDet;
	$rsDet=mysqli_query($enlaceCon,$consultaDet);
	$txtLineas="";
	while($regDet=mysqli_fetch_array($rsDet)){
		$txtLineas.="$regDet[0]"."<br>";
	}	
    echo "<tr>";
    echo "<td><input type='checkbox' id='idchk$cont' name='idchk$cont' value='$codProv' ></td><td>$codProv</td><td>$nomProv</td><td>$direccion</td><td>$telefono1</td>
	<td>$telefono2</td><td>$contacto</td>
	<td>$txtLineas</td>";
	
   echo "<td><a href='navegadorLineasDistribuidores.php?codProveedor=$codProv'><img src='../../imagenes/detalles.png' width='30' title='Ver Lineas de Distribucion'></a></td>";
   echo "</tr>";
   }
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones2'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";


?>

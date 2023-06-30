<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

echo "<h2 align='center'>Distribuidores</h2>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";
echo "<br>";
echo "<center><table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Direccion</th><th>Telefono 1</th><th>Telefono 2</th><th>Contacto</th><th>Detalle Lineas</th><th>Ver Lineas</th>
		<th>Incrementar Precios Todo</th>
		<th>Incrementar Precios solo con Stock</th>";
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
	
	$consultaDet="select p.cod_linea_proveedor, p.nombre_linea_proveedor
	from proveedores_lineas p where p.cod_proveedor=$codProv and estado=1 order by 1";
	//echo $consultaDet;
	$rsDet=mysqli_query($enlaceCon,$consultaDet);
	$txtLineas="";
	while($regDet=mysqli_fetch_array($rsDet)){
		$txtLineas.="$regDet[1] ($regDet[0]), ";
	}
	
    echo "<tr>";
    echo "<td><input type='checkbox' id='idchk$cont' name='idchk$cont' value='$codProv' ></td><td>$codProv</td><td>$nomProv</td><td>$direccion</td><td>$telefono1</td>
	<td>$telefono2</td><td>$contacto</td>
	<td>$txtLineas</td>";
	
   echo "<td><a href='navegadorLineasDistribuidores.php?codProveedor=$codProv'><img src='../../imagenes/detalle.png' width='40' title='Ver Lineas'></a></td>";
	echo "<td align='center'><a href='../../navegador_precio_subir.php?codProveedor=$codProv&tipo=0' target='_BLANK'><img src='../../imagenes/edit.png' width='35' title='Incrementar Precio Todo'></a></td>";
	echo "<td align='center'><a href='../../navegador_precio_subir.php?codProveedor=$codProv&tipo=1' target='_BLANK'><img src='../../imagenes/factura1.jpg' width='35' title='Incrementar Precio Stock'></a></td>";
   echo "</tr>";
   }
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";


?>

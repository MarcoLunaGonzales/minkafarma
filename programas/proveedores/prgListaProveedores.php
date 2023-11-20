<?php

require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");

echo "<h2>Proveedores & Representantes</h2>";

echo "<div class='divBotones2'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";
echo "<br><center><table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Direccion</th><th>Telefono 1</th><th>Telefono 2</th><th>Contacto</th><th>Tipo Proveedor</th><th>Detalle Lineas</th><th>Distribuidor Lineas Asociadas</th>
        <th>Ver Lineas</th>
		<th>Incrementar Precios Todo</th>
		<th>Incrementar Precios solo con Stock</th>
		<th>Ver Compras</th>";
echo "</tr>";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2, p.contacto, p.cod_tipoproveedor, tp.nombre_tipoventa
    FROM proveedores AS p 
    LEFT JOIN tipos_proveedor tp ON tp.cod_tipoventa = p.cod_tipoproveedor
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
    $tipoProveedor = $reg["cod_tipoproveedor"];
    $nombreTipoventa = $reg["nombre_tipoventa"];
	
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
    <td>$nombreTipoventa</td>
	<td>$txtLineas</td>";
	
    if($tipoProveedor == 1){
        echo "<td></td>";
        echo "<td><a href='navegadorLineasDistribuidores.php?codProveedor=$codProv'><img src='../../imagenes/detalle.png' width='40' title='Ver Lineas'></a></td>";
        echo "<td align='center'><a href='../../navegador_precio_subir.php?codProveedor=$codProv&tipo=0' target='_BLANK'><img src='../../imagenes/edit.png' width='35' title='Incrementar Precio Todo'></a></td>";
        echo "<td align='center'><a href='../../navegador_precio_subir.php?codProveedor=$codProv&tipo=1' target='_BLANK'><img src='../../imagenes/factura1.jpg' width='35' title='Incrementar Precio Stock'></a></td>";
        echo "<td align='center'><a href='../../navegador_precio_subir.php?codProveedor=$codProv&tipo=1' target='_BLANK'><img src='../../imagenes/documento.png' width='35' title='Incrementar Precio Stock'></a></td>";
    }else{
        echo "<td align='center'>
                <a href='navegadorListaDistribuidorLineas.php?codProveedor=$codProv'>
                    <img src='../../imagenes/ruteroaprobado.png' width='35' title='Asociar Lineas'>
                </a>
            </td>";
        echo "<td></td><td></td><td></td>";
    }

   echo "</tr>";
   }
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones2'><input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton' type='button' value='Editar' onclick='javascript:frmModificar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'></div>";


?>

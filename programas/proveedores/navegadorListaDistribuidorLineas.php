<?php
require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");
require("../../funcion_nombres.php");
echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

?>
<script language='Javascript'>
		function enviar_nav(codProveedor)
		{	location.href='registrarDistribuidorLineas.php?codProveedor='+codProveedor;
		}
		</script>
<form>
<?php


$codProveedor=$_GET['codProveedor'];
$nombreProveedor=nombreProveedor($enlaceCon,$codProveedor,$enlaceCon);

$globalAdmin=$_COOKIE["global_admin_cargo"];

echo "<h2>Lineas de Distribuidor Asociadas<br> $nombreProveedor</h2>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onClick='enviar_nav($codProveedor);'>
</div>";

echo "<center>";
echo "<table class='texto'>";
echo "<tr>";
echo "	<th width='10%'>#</th>
		<th width='40%'>Nombre Proveedor</th>
		<th width='40%'>Nombre Linea</th>
		<th width='10%'>Acciones</th>
	</tr>";
$consulta="SELECT dl.cod_distribuidor, dl.cod_linea_proveedor , pl.nombre_linea_proveedor, p.nombre_proveedor
			FROM distribuidores_lineas dl
			LEFT JOIN proveedores_lineas pl ON dl.cod_linea_proveedor = pl.cod_linea_proveedor
			LEFT JOIN proveedores p ON p.cod_proveedor = pl.cod_proveedor
			WHERE dl.cod_distribuidor = '$codProveedor'";
$rs=mysqli_query($enlaceCon,$consulta);

$cont=0;
while($reg=mysqli_fetch_array($rs)){
	$cont++;
    $cod_distribuidor 		= $reg["cod_distribuidor"];
    $cod_linea_proveedor 	= $reg["cod_linea_proveedor"];
    $nombre_linea_proveedor = $reg["nombre_linea_proveedor"];
    $nombre_proveedor 		= $reg["nombre_proveedor"];
    echo "<tr>";
    echo "
	<td>$cont</td>
    <td>$nombre_proveedor</td>
    <td>$nombre_linea_proveedor</td>";
	if($globalAdmin==1){
		echo "
		<td>
			<a hidden href='editarDistribuidorLineas.php?codDistribuidor=$cod_distribuidor&codLineaProveedor=$cod_linea_proveedor' title='Editar'><img src='../../imagenes/rutero.png' width='40'></a>
			<a href='eliminarDistribuidorLinea.php?codDistribuidor=$cod_distribuidor&codLineaProveedor=$cod_linea_proveedor' title='Eliminar'><img src='../../imagenes/no2.png' width='40'></a>
		</td>";
	}
    echo "</tr>";
   }
echo "</table>";
echo "</center>";

echo "<div class='divBotones'>
		<input class='boton' type='button' value='Adicionar' onClick='enviar_nav($codProveedor);'>";
?>
		<input type='button' class='boton2' value='Cancelar' onClick="location.href='inicioProveedores.php'">
	</div>
</form>
<?php
require("../conexionmysqli.inc");
error_reporting(0);
require("../estilos2.inc");
require("../funciones.php");
require("configModule.php");

$codigo=$_POST['codigo'];
$cod_sub=$_POST['tipo'];
$globalAlmacen=$_COOKIE['global_almacen'];

$sql_upd=mysqli_query($enlaceCon,"DELETE FROM tipos_precio_productos WHERE cod_tipoprecio=$cod_sub");
for ($i=0; $i < count($codigo); $i++) { 
	if($codigo[$i]!=""){
	   $producto=$codigo[$i];
	   $porcentMat=$_POST['descuento'.$producto];

	   $stockProductoX=stockProducto($enlaceCon,$globalAlmacen,$producto);

       $sql_upd=mysqli_query($enlaceCon,"INSERT INTO tipos_precio_productos (cod_tipoprecio,cod_material,porcentaje_material,stock_oferta) values($cod_sub,$producto,'$porcentMat','$stockProductoX')");
	}
}

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlList2';
			</script>";
?>
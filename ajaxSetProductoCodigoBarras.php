<?php
require("conexionmysqli2.inc");
require("funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$codigoItem=$_GET['codigo'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];

$codigoItem=trim($codigoItem);

	$sql="select m.codigo_material, m.descripcion_material, m.cantidad_presentacion,
		(select pl.nombre_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor) as linea from material_apoyo m where estado=1 
		and m.codigo_barras = '$codigoItem'";
	$sql=$sql." limit 1";
	$resp=mysqli_query($enlaceCon, $sql);
	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];			
			
			//SACAMOS EL PRECIO
			$precioItem=0;
			$costoItem=0;
			$sqlPrecio="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' 
			and cod_ciudad='$globalAgencia'";
			$respPrecio=mysqli_query($enlaceCon, $sqlPrecio);
			$numFilas2=mysqli_num_rows($respPrecio);
			if($numFilas2>0){
				$precioItem=mysqli_result($respPrecio,0,0);
			}
			//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
			$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
			and cod_ciudad='$globalAgencia'";
			$respCosto=mysqli_query($enlaceCon, $sqlCosto);
			$numFilas2=mysqli_num_rows($respCosto);
			if($numFilas2>0){
				$costoItem=mysqli_result($respCosto,0,0);
			}

			$precioItem=round($precioItem);
			$costoItem=round($costoItem);
			
			$nombreLinea=$dat[3];
			echo "1#####".$codigo."#####".$nombre."#####".$cantidadPresentacion."#####".$costoItem."#####".'.'."#####".'.'."#####".$codigo."#####".$nombreLinea."#####".$precioItem;
		}
	}else{
		echo "0#####_#####_#####_#####_#####_#####_#####_#####_";
	}

?>
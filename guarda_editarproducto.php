<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$paginaRetorno=$_POST['pagina_retorno'];

$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['material'];
$codLinea=$_POST['codLinea'];
$codForma=$_POST['codForma'];
$codEmpaque=$_POST['codEmpaque'];
$cantidadPresentacion=$_POST['cantidadPresentacion'];
$principioActivo=$_POST['principioActivo'];
$codTipoVenta=$_POST['codTipoVenta'];
$productoControlado=$_POST['producto_controlado'];
$accionTerapeutica=$_POST['accion_terapeutica'];
$codigoBarras=$_POST['codigo_barras'];
$lineaAnterior=$_POST['linea_anterior'];

$arrayAccionTerapeutica=$_POST['arrayAccionTerapeutica'];

/*RECUPERAMOS LOS PRECIOS*/
$arrayPrecios=[];
$sqlSucursales="select cod_ciudad, descripcion from ciudades order by 1";
$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
while($datSucursales=mysqli_fetch_array($respSucursales)){
	$codCiudadPrecio=$datSucursales[0];
	$nombreCiudadPrecio=$datSucursales[1];
	$precioProducto=$_POST["precio_producto|".$codCiudadPrecio];
	$arrayPrecios[$codCiudadPrecio]=$precioProducto;
}


$sql_inserta="update material_apoyo set descripcion_material='$nombreProducto', cod_linea_proveedor='$codLinea', 
cod_forma_far='$codForma', cod_empaque='$codEmpaque', cantidad_presentacion='$cantidadPresentacion', 
principio_activo='$principioActivo', cod_tipoventa='$codTipoVenta', producto_controlado='$productoControlado', accion_terapeutica='$accionTerapeutica', codigo_barras='$codigoBarras' where codigo_material='$codProducto'";
//echo $sql_inserta;
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

/*$sqlDel="delete from material_accionterapeutica where codigo_material='$codProducto'";
$respDel=mysqli_query($enlaceCon,$sqlDel);
$vectorAccionTer=explode(",",$arrayAccionTerapeutica);
$n=sizeof($vectorAccionTer);
for($i=0;$i<$n;$i++){
	$sql="insert into material_accionterapeutica (codigo_material, cod_accionterapeutica) values('$codProducto','$vectorAccionTer[$i]')";
	$resp=mysqli_query($enlaceCon,$sql);
}*/


$resp=actualizarPrecios($enlaceCon,$codProducto,$arrayPrecios,0);


if($resp_inserta){
	
		if($paginaRetorno==0){
			echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_material.php';
			</script>";			
		}elseif($paginaRetorno==2){
			echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_ajustarpreciostock.php';
			</script>";
		}
		else{
			echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			window.parent.location.reload();
			location.href='detalleMaterialLineas.php?linea=$lineaAnterior';
			</script>";			
		}

}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}	

?>
<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$nombreProducto=$_POST['material'];
$codLinea=$_POST['codLinea'];
$codForma=$_POST['codForma'];
$codEmpaque=$_POST['codEmpaque'];
$cantidadPresentacion=$_POST['cantidadPresentacion'];
$principioActivo=$_POST['principioActivo'];
$codTipoVenta=$_POST['codTipoVenta'];
$productoControlado=$_POST['producto_controlado'];
//$arrayAccionTerapeutica=$_POST['arrayAccionTerapeutica'];
$accionTerapeutica=$_POST['accion_terapeutica'];
$codigoBarras=$_POST['codigo_barras'];

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


$sql="select IFNULL((max(codigo_material)+1),1) as codigo  from material_apoyo m";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
//echo $codigo;
//$codigo=mysql_result($resp,0,0);

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_forma_far, cod_empaque,
cantidad_presentacion, principio_activo, cod_tipoventa, producto_controlado, accion_terapeutica, codigo_barras) values ($codigo,'$nombreProducto','1','$codLinea','$codForma','$codEmpaque',
'$cantidadPresentacion','$principioActivo','$codTipoVenta','$productoControlado','$accionTerapeutica','$codigoBarras')";
//echo $sql_inserta;
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);


$resp=actualizarPrecios($enlaceCon,$codigo,$arrayPrecios);

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>
<?php
require("conexion.inc");
require("estilos.inc");

//recogemos variables
$nombreProducto=$_POST['material'];
$codLinea=$_POST['codLinea'];
$codForma=$_POST['codForma'];
$codEmpaque=$_POST['codEmpaque'];
$cantidadPresentacion=$_POST['cantidadPresentacion'];
$principioActivo=$_POST['principioActivo'];
$codTipoVenta=$_POST['codTipoVenta'];

$arrayAccionTerapeutica=$_POST['arrayAccionTerapeutica'];


$sql="select IFNULL((max(codigo_material)+1),1) as codigo  from material_apoyo m";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_forma_far, cod_empaque,
cantidad_presentacion, principio_activo, cod_tipoventa) values ($codigo,'$nombreProducto','1','$codLinea','$codForma','$codEmpaque',
'$cantidadPresentacion','$principioActivo','$codTipoVenta')";
$resp_inserta=mysql_query($sql_inserta);

$vectorAccionTer=explode(",",$arrayAccionTerapeutica);
$n=sizeof($vectorAccionTer);
for($i=0;$i<$n;$i++){
	$sql="insert into material_accionterapeutica (codigo_material, cod_accionterapeutica) values('$codigo','$vectorAccionTer[$i]')";
	$resp=mysql_query($sql);
}

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
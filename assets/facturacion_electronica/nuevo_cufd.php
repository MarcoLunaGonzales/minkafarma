<a href="form_ventas.php" class="">VOLVER A INTENTAR</a>
<?php
require("../conexionmysqli2.inc");
require "functions.php";

$globalCiudad=$_COOKIE['global_agencia'];
$globalUsuario=$_COOKIE['global_usuario'];
$fechaActual=date("Y-m-d");
$sqlCufd="select cufd FROM facturacion_electronica_cufd where cod_ciudad='$globalCiudad' and estado=1 and fecha='$fechaActual'";
$respCufd=mysqli_query($enlaceCon,$sqlCufd);
$cufd=mysqli_result($respCufd,0,0);
if(!$cufd>0){
	//obtener Cuis
	$anio=date("Y");
	$sqlCuis="select cuis FROM facturacion_electronica_cuis where cod_ciudad='$globalCiudad' and estado=1 and cod_gestion='$anio'";
	$respCuis=mysqli_query($enlaceCon,$sqlCuis);
	$cuis=mysqli_result($respCuis,0,0);
	if($cuis==""){
			$cuis=obtenerCuis($globalCiudad,0); //punto de venta y sucursal
			$sqlInsertCuis="INSERT INTO facturacion_electronica_cuis (cuis,cod_gestion,cod_ciudad,created_by,created_at,estado) VALUES ('$cuis','$anio','$globalCiudad','$globalUsuario',NOW(),1);";
			mysqli_query($enlaceCon,$sqlInsertCuis);
	}

	$respCufd=obtenerCufd($globalCiudad,0,$cuis);
	$cufd=$respCufd[0];
	$control=$respCufd[1];
	if($cufd!=""){
		$sqlInsertCufd="INSERT INTO facturacion_electronica_cufd (cufd,codigo_control,fecha,cod_ciudad,created_by,created_at,estado) VALUES ('$cufd','$control','$fechaActual','$globalCiudad','$globalUsuario',NOW(),1);";
		mysqli_query($enlaceCon,$sqlInsertCufd);
		//echo $sqlInsertCufd;		
	}
}

?><script type="text/javascript">window.location.href='../form_ventas.php'</script>
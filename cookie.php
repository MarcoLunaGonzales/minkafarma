<?php

$estilosVenta=0; //para no ejecutar las librerias js css


require("conexionmysqli.php");
require("funciones.php");
require("funcion_nombres.php");
$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];
$contrasena = str_replace("'", "''", $contrasena);

$sql = "
    SELECT f.cod_cargo, f.cod_ciudad
    FROM funcionarios f, usuarios_sistema u
    WHERE u.codigo_funcionario=f.codigo_funcionario AND u.codigo_funcionario='$usuario' AND u.contrasena='$contrasena' ";

$resp = mysqli_query($enlaceCon,$sql);
$num_filas = mysqli_num_rows($resp);

if ($num_filas != 0) {
    $dat = mysqli_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];

    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
	
	//sacamos la gestion activa
	$sqlGestion="select cod_gestion, nombre_gestion from gestiones where estado=1";
	$respGestion=mysqli_query($enlaceCon,$sqlGestion);
	$datGestion=mysqli_fetch_array($respGestion);
	$globalGestion=datGestion[0];
	//$globalGestion=mysqli_result($respGestion,0,0);
	$nombreG=datGestion[1];
	//$nombreG=mysqli_result($respGestion, 0, 1);
	
	//almacen
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$cod_ciudad'";
	//echo $sql_almacen;
	$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
	$dat_almacen=mysqli_fetch_array($resp_almacen);
	$global_almacen=$dat_almacen[0];

	setcookie("global_almacen",$global_almacen);
	setcookie("globalGestion", $nombreG);
	
	
	$stringGlobalAdmins=obtenerValorConfiguracion($enlaceCon, 0);
	$posBuscada = strpos($stringGlobalAdmins, $usuario);
	if ($posBuscada === true) {
		setcookie("global_admin_cargo", 0);	    
	}else{
		setcookie("global_admin_cargo", 1);		
	}


	if($cod_cargo==1000){
		header("location:indexGerencia.php");
	}
	if($cod_cargo==1010){
		header("location:indexAdmin.php");
	}
	if($cod_cargo==1002){
		header("location:indexAlmacenReg.php");
	}
	if($cod_cargo==1016 || $cod_cargo==1017 || $cod_cargo==1018){
		header("location:indexSecond.php");
	}
	if($cod_cargo==1018){
		header("location:indexConta.php");
	}
	

} else {
    echo "<link href='stilos.css' rel='stylesheet' type='text/css'>
        <form action='problemas_ingreso.php' method='post' name='formulario'>
        <h1>Sus datos de acceso no son correctos.</h1>
        </form>";
}
?>
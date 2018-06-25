<?php

require("conexion.inc");
$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];
$contrasena = str_replace("'", "''", $contrasena);
$sql = "
    SELECT f.cod_cargo, f.cod_ciudad
    FROM funcionarios f, usuarios_sistema u
    WHERE u.codigo_funcionario=f.codigo_funcionario AND u.codigo_funcionario='$usuario' AND u.contrasena='$contrasena' ";

$resp = mysql_query($sql);
$num_filas = mysql_num_rows($resp);
if ($num_filas != 0) {
    $dat = mysql_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];

    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
	
	//sacamos la gestion activa
	$sqlGestion="select cod_gestion, nombre_gestion from gestiones where estado=1";
	$respGestion=mysql_query($sqlGestion);
	$globalGestion=mysql_result($respGestion,0,0);
	$nombreG=mysql_result($respGestion, 0, 1);
	
	//almacen
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$cod_ciudad'";
	//echo $sql_almacen;
	$resp_almacen=mysql_query($sql_almacen);
	$dat_almacen=mysql_fetch_array($resp_almacen);
	$global_almacen=$dat_almacen[0];

	setcookie("global_almacen",$global_almacen);
	setcookie("globalGestion", $globalGestion);
	
	if($cod_cargo==1000){
		header("location:index_administracion.html");
	}else{
		header("location:indexAlmacenReg.php");
	}
	

} else {
    echo "<link href='stilos.css' rel='stylesheet' type='text/css'>
        <form action='problemas_ingreso.php' method='post' name='formulario'>
        <h1>Sus datos de acceso no son correctos.</h1>
        </form>";
}
?>
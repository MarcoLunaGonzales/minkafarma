<?php
	require("conexionmysqli.php");
	
	$sql = "select paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario=$global_usuario";
	$resp = mysqli_query($enlaceCon,$sql );
	$dat = mysqli_fetch_array($resp);
	$paterno = $dat[ 0 ];
	$materno = $dat[ 1 ];
	$nombre = $dat[ 2 ];	
	$nombreUsuarioSesion = "$paterno $nombre";

	$sql = "select descripcion from ciudades where cod_ciudad=$global_agencia";
	$resp = mysqli_query($enlaceCon,$sql );
	$dat = mysqli_fetch_array( $resp );
	$nombreAgenciaSesion = $dat[ 0 ];
	
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$global_agencia'";
	$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
	$dat_almacen=mysqli_fetch_array($resp_almacen);
	$nombreAlmacenSesion=$dat_almacen[1];
	
	
	$sqlNombreEmpresa="select nombre from datos_empresa where cod_empresa=1";
	$respNombreEmpresa=mysqli_query($enlaceCon,$sqlNombreEmpresa);
	$datNombreEmpresa=mysqli_fetch_array($respNombreEmpresa);
	$nombreEmpresa=$datNombreEmpresa[0];

	date_default_timezone_set('America/La_Paz');
	$fechaSistemaSesion = date( "d-m-Y" );
	$horaSistemaSesion = date( "H:i" );
?>

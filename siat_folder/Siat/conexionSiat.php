<?php

	if(isset($_SESSION['globalEntidadSes'])){//conexion desde servicio
		$globalEntidad=$_SESSION['globalEntidadSes'];
		//echo "ntro:".$globalEntidad;
	}else{
		if(isset($_COOKIE['globalIdEntidad'])){//conexion desde sistema
			$globalEntidad=$_COOKIE['globalIdEntidad'];
		}else{
			$globalEntidad=1;//	
		}
		//echo "nada";
	}
	require dirname(__DIR__). SB_DS ."../conexionmysqli2.php";
	$consulta="select nombre_sistema,codigo_sistema,tipo_sistema,nit,razon_social,token_delegado,fecha_limite,modalidad from siat_credenciales where cod_estado=1 and cod_entidad=$globalEntidad";
	//echo $consulta;
	$respFactura = mysqli_query($enlaceCon,$consulta);
	$dataFact = $respFactura->fetch_array(MYSQLI_ASSOC);			
	$siat_nombreSistema = $dataFact['nombre_sistema'];			
	$siat_codigoSistema=$dataFact['codigo_sistema'];
	$siat_tipo=$dataFact['tipo_sistema'];
	$siat_nit=$dataFact['nit'];
	$siat_razonSocial=$dataFact['razon_social'];
	$siat_tokenDelegado=$dataFact['token_delegado'];
	$siat_modalidad=(int)$dataFact['modalidad'];
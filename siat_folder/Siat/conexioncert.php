<?php

	// if(isset($_COOKIE['globalIdEntidad'])){
	// 	$globalEntidad=$_COOKIE['globalIdEntidad'];
	// }else{
	// 	$globalEntidad=3;//
	// }

	if(isset($_SESSION['globalEntidadSes'])){
		$globalEntidad=$_SESSION['globalEntidadSes'];
		//echo "ntro:".$globalEntidad;
	}else{
		if(isset($_COOKIE['globalIdEntidad'])){
	    	$globalEntidad=$_COOKIE['globalIdEntidad'];
	  	}else{
	  		$globalEntidad=1;
	  	}
		
	}
	require dirname(__DIR__). SB_DS ."../conexionmysqli2.php";
	$consulta="select cert_privatekey,cert_publickey from siat_credenciales where cod_estado=1 and cod_entidad=$globalEntidad";
	$respCred = mysqli_query($enlaceCon,$consulta);
	$dataCred = $respCred->fetch_array(MYSQLI_ASSOC);
	$privatekey=$dataCred['cert_privatekey'];
	$publickey=$dataCred['cert_publickey'];

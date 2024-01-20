<?php

if(isset($_GET['cod_ciudad']) && isset($_GET['fecha'])){
	if(isset($_GET['sw'])){
		$sw_bandera=false;	
	}else{
		$sw_bandera=true;	
	}
	require_once("../funciones_siat.php");
	require_once("../../conexionmysqli2.inc");
	$DatosConexion=verificarConexion();
	if($DatosConexion[0]==1){
		$stringFechas=$_GET['fecha'];
		$arrayFechas=explode(",", $stringFechas);
		foreach ($arrayFechas as  $value) {
			// echo $value."<br>";
			consultaEventoSucursal($value,$_GET['cod_ciudad'],$sw_bandera,$enlaceCon);	
		}
		echo 1;
	}else{
		echo "ERROR EN CONEXIÓN";
	}
}

function consultaEventoSucursal($fechaEvento,$global_agencia,$sw_bandera=false,$enlaceCon){
	// $fechaEvento=$_GET['fecha'];
	// $global_agencia=$_GET['cod_ciudad'];
	$fechaActual=date("Y-m-d");
	$consulta="SELECT s.cuis,c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad limit 1) as punto_venta,(SELECT cufd from siat_cufd where fecha='$fechaActual' and cod_ciudad=c.cod_ciudad and s.cuis=cuis and estado=1 order by fecha limit 1)as siat_cufd from siat_cuis s join ciudades c on c.cod_ciudad=s.cod_ciudad where s.cod_ciudad='$global_agencia' and cod_gestion=YEAR(NOW()) and estado=1 order by s.codigo desc limit 1";		
	$resp = mysqli_query($enlaceCon,$consulta);	
	$dataList = $resp->fetch_array(MYSQLI_ASSOC);
	// $cuis = $dataList['cuis'];
	$codigoPuntoVenta = $dataList['punto_venta'];
	$cufd = $dataList['siat_cufd'];
	$cod_impuestos = $dataList['cod_impuestos'];
	$cufdEvento='';
	$descripcionX='';
	$respuesta=consultaEventoSignificativo($fechaEvento,$global_agencia);
	if($sw_bandera){
		echo "Fecha: ".$fechaEvento."<br>";
		echo "Eventos <br>";
		print_r($respuesta);
	}
	if($lista=$respuesta->RespuestaListaEventos->listaCodigos){
		if(isset($lista->codigoEvento)){//cuando solo es uno
			$codigoEvento=$lista->codigoEvento;
			$codigoRecepcionEventoSignificativo=$lista->codigoRecepcionEventoSignificativo;
			$fechaFin=$lista->fechaFin;
			$fechaInicio=$lista->fechaInicio;

			$consultaVeri="select codigo from siat_eventos where codigoRecepcionEventoSignificativo=$codigoRecepcionEventoSignificativo";
			$respVerf = mysqli_query($enlaceCon,$consultaVeri);	
			$dataVerf = $respVerf->fetch_array(MYSQLI_ASSOC);
			// $cuis = $dataVerf['cuis'];
			$codigoVerf = $dataVerf['codigo'];
			if($codigoVerf==0 || $codigoVerf==null || $codigoVerf=="" || $codigoVerf==" "){
				$sql="INSERT INTO siat_eventos(codigoMotivoEvento,codigoPuntoVenta,codigoSucursal,cufd,cufdEvento,descripcion,fechaHoraInicioEvento,fechaHoraFinEvento,codigoRecepcionEventoSignificativo) values('$codigoEvento','$codigoPuntoVenta','$cod_impuestos','$cufd','$cufdEvento','$descripcionX','$fechaInicio','$fechaFin','$codigoRecepcionEventoSignificativo')";
		           // echo $sql;
				mysqli_query($enlaceCon,$sql);
				if($sw_bandera){
					echo "<br><br>Eventos Insertados:<br>";
					echo $sql."<br>";
				}
			}
		}
		foreach ($lista as $li) {
			if(isset($li->codigoEvento)){
				$codigoEvento=$li->codigoEvento;
				$codigoRecepcionEventoSignificativo=$li->codigoRecepcionEventoSignificativo;
				$fechaFin=$li->fechaFin;
				$fechaInicio=$li->fechaInicio;
				$consultaVeri="select codigo from siat_eventos where codigoRecepcionEventoSignificativo='$codigoRecepcionEventoSignificativo'";
				$respVerf = mysqli_query($enlaceCon,$consultaVeri);	
				$dataVerf = $respVerf->fetch_array(MYSQLI_ASSOC);
				// $cuis = $dataVerf['cuis'];
				$codigoVerf = $dataVerf['codigo'];
				if($codigoVerf==0 || $codigoVerf==null || $codigoVerf=="" || $codigoVerf==" "){
					$sql="INSERT INTO siat_eventos(codigoMotivoEvento,codigoPuntoVenta,codigoSucursal,cufd,cufdEvento,descripcion,fechaHoraInicioEvento,fechaHoraFinEvento,codigoRecepcionEventoSignificativo) values('$codigoEvento','$codigoPuntoVenta','$cod_impuestos','$cufd','$cufdEvento','$descripcionX','$fechaInicio','$fechaFin','$codigoRecepcionEventoSignificativo')";
			           // echo $sql;
					mysqli_query($enlaceCon,$sql);
					if($sw_bandera){
						echo "<br><br>Eventos Insertados:<br>";
						echo $sql."<br>";
					}
				}
			}
		}
	}
}


?>
<?php
require_once "siat_folder/funciones_siat.php";  
$nitCliente=$_GET['nit'];
try {
	$data=verificarNitClienteSiat($nitCliente);
	if(!isset($data->RespuestaVerificarNit)){
		echo "0#####Error de Servicio#####0";
	}else{
		$datos=$data->RespuestaVerificarNit;
		echo $datos->mensajesList->codigo."#####".$datos->mensajesList->descripcion."#####".$datos->transaccion;		
	}	
} catch (Exception $e) {
	echo "0#####Desconexión#####0";
}
// print_r($datos);
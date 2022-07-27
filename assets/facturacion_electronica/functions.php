<?php

function obtenerCuis($codPuntoVenta,$codSucursal){
	require "soap_siat.php";
	$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
	$codigoSistema="71E4E06A36F8587F3BE98A6";
	$codigoAmbiente=2;
	$nitEmpresa="1022039027";

	$siat=new SiatDatos();
	$client=$siat->newClientSoap($wsdl);	
	

	$parameters['SolicitudCuis']=array(
      'codigoAmbiente' => $codigoAmbiente,
      'codigoPuntoVenta' => $codPuntoVenta,
      'codigoSistema' => $codigoSistema,
      'nit' => $nitEmpresa,
      'codigoSucursal' => $codSucursal,
      'codigoModalidad' => 1
	);

	$respons = $client->cuis($parameters);
	return $respons->RespuestaCuis->codigo;
}
function sincronizarActividades($wsdl,$parameters){
	// require "soap_siat.php";
	// $siat=new SiatDatos();
	// $client=$siat->newClientSoap($wsdl);	
	// $respons = $client->sincronizarActividades($parameters);
	// return $respons->RespuestaCuis->codigo;
}

function obtenerCufd($codPuntoVenta,$codSucursal,$cuis){
	require "soap_siat.php";
	$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
	$codigoSistema="71E4E06A36F8587F3BE98A6";
	$codigoAmbiente=2;
	$nitEmpresa="1022039027";

	$parameters['SolicitudCufd']=array(
	      'codigoAmbiente' => $codigoAmbiente,
	      'codigoModalidad' => 1,
	      'codigoPuntoVenta' => $codPuntoVenta,
	      'codigoSistema' => $codigoSistema,
	      'nit' => $nitEmpresa,
	      'codigoSucursal' => $codSucursal,
	      'cuis' => $cuis
	);

	$siat=new SiatDatos();
	$client=$siat->newClientSoap($wsdl);      

    $respons = $client->cufd($parameters);
//     echo "<pre>";
// print_r($respons);
// echo "</pre>";

    return array($respons->RespuestaCufd->codigo,$respons->RespuestaCufd->codigoControl);
}
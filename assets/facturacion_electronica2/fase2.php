<?php
//sincronizacion de catalogos
require "soap_siat.php";
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
$cuis="983EE1C8";//"81E5C14E";
$parameters['SolicitudSincronizacion']=array(
      'codigoAmbiente' => 2,
      'codigoPuntoVenta' => 1,
      'codigoSistema' => '71E4E06A36F8587F3BE98A6',
      'nit' => '1022039027',
      'codigoSucursal' => 0,
      'cuis' => $cuis
);


$siat=new SiatDatos();
$client=$siat->newClientSoap($wsdl);      

for ($i=0; $i < 55; $i++) { 
    $respons = $client->sincronizarParametricaEventosSignificativos($parameters); 
}


echo "<pre>";
print_r($respons);
echo "</pre>";


// return $respons->RespuestaCuis->codigo;

// $cuis=sincronizarActividades($wsdl,$parameters);
// echo $cuis;


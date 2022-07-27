<?php
//sincronizacion de catalogos
require "soap_siat.php";
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
$cuis="81E5C14E";//"81E5C14E"; 983EE1C8
$parameters['SolicitudCufd']=array(
      'codigoAmbiente' => 2,
      'codigoModalidad' => 1,
      'codigoPuntoVenta' => 0,
      'codigoSistema' => '71E4E06A36F8587F3BE98A6',
      'nit' => '1022039027',
      'codigoSucursal' => 0,
      'cuis' => $cuis
);


$siat=new SiatDatos();
$client=$siat->newClientSoap($wsdl);      

     $respons = $client->cufd($parameters);
 for ($i=0; $i < 100; $i++) { 
}


echo "<pre>";
print_r($respons);
echo "</pre>";


// return $respons->RespuestaCuis->codigo;

// $cuis=sincronizarActividades($wsdl,$parameters);
// echo $cuis;




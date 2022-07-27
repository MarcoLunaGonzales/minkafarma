<?php

$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJDT0JPRkFSU0ExMCIsImNvZGlnb1Npc3RlbWEiOiI3MUU0RTA2QTM2Rjg1ODdGM0JFOThBNiIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTBNREl5TUxZME1ESUhBT2ZINEt3S0FBQUEiLCJpZCI6MTIzNjA3LCJleHAiOjE2Nzc4MDE2MDAsImlhdCI6MTY0NjMzMTk5MSwibml0RGVsZWdhZG8iOjEwMjIwMzkwMjcsInN1YnNpc3RlbWEiOiJTRkUifQ._6PUETTgIpYSX0ZZrrfgCdMiclP_AIGuIDEz3lWgRSVwj6FkWi8QVAj77Jz1YPOGvho51PHGI0e8r7W3D36tAg';

$opts = array(
      'http' => array(
           'header' => "ApiKey: TokenApi $token",
      )
);

$context = stream_context_create($opts);

$client = new \SoapClient($wsdl, [
      'stream_context' => $context
]);

$parameters=array('SolicitudCuis'=>array('codigoAmbiente' => 2,'codigoPuntoVenta' => 1,'codigoSistema' => '71E4E06A36F8587F3BE98A6','nit' => '1022039027','codigoSucursal' => 0,'codigoModalidad' => 1));

$respons = $client->cuis($parameters);
echo "<pre>";
print_r($respons);
echo "</pre>";
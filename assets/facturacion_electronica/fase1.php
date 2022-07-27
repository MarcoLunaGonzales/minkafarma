<?php
require "functions.php";
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
$parameters['SolicitudCuis']=array(
      'codigoAmbiente' => 2,
      'codigoPuntoVenta' => 1,
      'codigoSistema' => '71E4E06A36F8587F3BE98A6',
      'nit' => '1022039027',
      'codigoSucursal' => 0,
      'codigoModalidad' => 1
);
$cuis=obtenerCuis($wsdl,$parameters);
echo $cuis;
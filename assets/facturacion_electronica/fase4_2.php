<?php
include ("cano.php");
date_default_timezone_set("America/La_Paz");
//sincronizacion de catalogos
require "soap_siat.php";
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
//$cuis="983EE1C8";//"81E5C14E";
$cuis="81E5C14E";
$cufd="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QkFVR1NSTERXVUFFFNEUwNkEzNkY4N";
$codigoControl="38F9E905D346D74";

//RESTAR 10 MINUTOS POR HORA EN PC DAVID
$date= date('Y-m-d H:i:s'); 
$newDate = strtotime ( '-10 minute' ,strtotime ($date)); 

$anio=date("Y",$newDate);
$mes=date("m",$newDate);
$dia=date("d",$newDate);
$hora=date("H",$newDate);
$minuto=date("i",$newDate);
$segundo=date("s",$newDate);
$fechaEnvio=$anio."-".$mes."-".$dia."T".$hora.":".$minuto.":".$segundo.".000";

$datos = new stdClass();

//DATOS SI O SI CUF
$datos->codigoModalidad=1;
$datos->fechaHora=$anio.$mes.$dia.$hora.$minuto.$segundo."000";
$datos->codigoEmision=1; //CODIGO INTERNO EMISION COD VENTA
$datos->codigoDocumentoFiscal=1;
$datos->codigoDocumentoSector=1;
$datos->codigoPuntoVenta=0;

$montoFINAL=10.12;
$descuentoFINAL=0;

$datos->nitEmisor=1022039027;
$datos->numeroFactura=1;
$datos->cufd=$cufd;
$datos->codigoControl=$codigoControl;
$datos->codigoSucursal=0;
$datos->direccion="Landaeta 1721";
$datos->fechaEmision=$fechaEnvio;
$datos->nombreRazonSocial="RAZON SOCIAL PRUEBA";
$datos->codigoTipoDocumentoIdentidad=1; //1 CI, 2 NIT
$datos->numeroDocumento="9957352";
$datos->codigoCliente="9957352";
$datos->codigoMetodoPago=1;  //1 EFECTIVO 2 TARJETA
$datos->montoTotal=$montoFINAL;
if($datos->codigoMetodoPago==1){
      $datos->montoTotalMoneda=$montoFINAL;
}else{
      $datos->montoTotalMoneda=0;
}
$datos->descuentoAdicional=$descuentoFINAL;

for ($i=0; $i < 1; $i++) { //recorrer productos
      $det = new stdClass();
      $det->codigoProducto=40;
      $det->descripcion="Antigripal LCH";
      $det->cantidad=4;
      $det->precioUnitario=2.53;
      $det->montoDescuento=10;
      $det->subTotal=10.12;
      $datos->detalle[$i]=$det;
}


$documentoFiscal=generarDocumentoElectronico($datos);
//echo $documentoFiscal;
?>
<p>FACTURA DE PRUEBA GENERADA</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoFiscal?></textarea>
<br><br>
<?php

$documentoGenerado = C14N::canonicalizar($documentoFiscal);      
?>
<p>CANONICALACION FACTURA</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoGenerado;?></textarea>
<br><br>
<?php
//echo "<br>ARCHIVO<BR>".$archivo;

$documentoSha=generarHashArchivo($documentoGenerado);
$cadenaDocumentoHash=base64_encode($documentoSha);


$datosExtras='<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
    <SignedInfo>
      <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />
      <SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256" />
      <Reference URI="">
        <Transforms>
          <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />
          <Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments" />
        </Transforms>
        <DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256" />
        <DigestValue>'.$cadenaDocumentoHash.'</DigestValue>
      </Reference>
    </SignedInfo><SignatureValue /><KeyInfo /><Object /></Signature>';

$hashSectorFirma=generarHashArchivo($datosExtras);
include "ks.php";
try {
    openssl_private_encrypt($hashSectorFirma, $crypttext, $privateKey, OPENSSL_PKCS1_PADDING);
    // echo base64_encode($crypttext);
    // openssl_public_decrypt($crypttext, $decrypted, $publicKey, OPENSSL_PKCS1_PADDING);
    // echo "<br>".$decrypted;
    // print($dataToSign === $decrypted) . PHP_EOL;
} catch (Exception $e) {
  //error de encriptacion  
      $crypttext="ERROR!";
}

echo "<BR>***".$crypttext."<BR>";

$encriptFirma=base64_encode($crypttext);


$firmaXml=explode("<SignatureValue /><KeyInfo /><Object />", $datosExtras)[0];
$signature=$firmaXml.'<SignatureValue>'.$encriptFirma.'</SignatureValue><KeyInfo>
      <X509Data>
        <X509Certificate>MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAo9bdaa1FON50e9cBPSyd
xqM4Ly6FRdTBrP8rQhrYZ1Zmjk5xym9MD2SH9u47w2kCNMaqomdUA+zuzYMahEn1
MlDsI0zfUvPzmOIiiPxeULw2lmlo/NXyqHmEvBlZihbDatp21mCjJ/QHSMN+SCoj
W0XUkWsCPImYSdeTWLDKiSalxh+jcciIpQsyAeuxk4LJDJHOqNjX55dgN6sJbppN
7lsgZ92nTFFTg+rVqGaQJJLSgqVEWVWqLZZx1/rWcd4sLWJeD+8nytE+yjFJ8trd
9DshzySqi3BTSBL69KNWOcg9C1jtBs83+5bdmu8/7FeqfVW2Z8rEDJnYvcPVt+7T
8QIDAQAB</X509Certificate>
      </X509Data>
    </KeyInfo></Signature>';

  /*<KeyInfo>
      <X509Data>
        <X509Certificate>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</X509Certificate>
      </X509Data>
    </KeyInfo>*/


$archivoPre=explode("</facturaElectronicaCompraVenta>",$documentoFiscal)[0];

$documentoFiscal=$archivoPre.$signature."</facturaElectronicaCompraVenta>";

?>
<p>DOCUMENTO FISCAL FINAL</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoFiscal;?></textarea>
<br><br>
<?php


$archivo=generarArchivo($documentoFiscal);
$hashArchivo=generarHashArchivo($archivo);
//echo "<br>HASH<BR>".$hashArchivo;

$parameters['SolicitudServicioRecepcionFactura']=array(
      'codigoAmbiente' => 2,      
      'codigoEmision'=>$datos->codigoEmision,
      'archivo'=>$archivo,
      'codigoSistema' => '71E4E06A36F8587F3BE98A6',
      'hashArchivo'=>$hashArchivo,
      'codigoSucursal' => $datos->codigoSucursal,
      'codigoModalidad' => $datos->codigoModalidad,
      'cuis' => $cuis,
      'codigoPuntoVenta' => $datos->codigoPuntoVenta,
      'fechaEnvio'=>$datos->fechaEmision,
      'tipoFacturaDocumento'=>1,
      'nit' => $datos->nitEmisor,
      'codigoDocumentoSector'=>$datos->codigoDocumentoSector,
      'cufd'=>$cufd  
      
);


$siat=new SiatDatos();
$client=$siat->newClientSoap($wsdl);   
$client->namespaces = array(
     'SOAP-ENV' => "http://schemas.xmlsoap.org/soap/envelope/",
     'siat' => "https://siat.impuestos.gob.bo/",
);
$respons=$client->recepcionFactura($parameters);      
// for ($i=0; $i < 100; $i++) {   
// }

echo "<BR>".$fechaEnvio;
echo "<pre>";
print_r($respons);
echo "</pre>";



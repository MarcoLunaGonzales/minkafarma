<?php
include "ks.php";
    require '../vendor/autoload.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

include ("cano.php");
date_default_timezone_set("America/La_Paz");
//sincronizacion de catalogos
require "soap_siat.php";
require 'ValidacionXSD.php';
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
//$cuis="983EE1C8";//"81E5C14E";
$cuis="81E5C14E";
$cufd="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QjlyWlBQVERXVUFFFNEUwNkEzNkY4N";
$codigoControl="2C34CAA0B546D74";



//RESTAR 10 MINUTOS POR HORA EN PC DAVID
$date= date('Y-m-d H:i:s'); 
$newDate = strtotime ($date); 

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
<!-- <p>FACTURA DE PRUEBA GENERADA</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoFiscal?></textarea>
<br><br> -->
<?php

$documentoGenerado = C14N::canonicalizar($documentoFiscal);      
?>
<!-- <p>CANONICALACION FACTURA</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoGenerado;?></textarea>
<br><br> -->
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

    // $doc = new DOMDocument();
    // $doc->loadXML($datosExtras);

    // // Create a new Security object 
    // $objDSig = new XMLSecurityDSig();
    // // Use the c14n exclusive canonicalization
    // $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
    // // Sign using SHA-256
    // $objDSig->addReference(
    //     $doc, 
    //     XMLSecurityDSig::SHA256, 
    //     array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
    // );




// $doc = new \DOMDocument();
//         $doc->loadXML($datosExtras);
//         $objDSig = new XMLSecurityDSig();
//         $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
//         $objDSig->addReference($doc, XMLSecurityDSig::SHA256, ['http://www.w3.org/2000/09/xmldsig']);
//         $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));
//         $objKey->loadKey('path/to/file/privatekey.pem', true);
//         $objDSig->sign($objKey);
//         $objDSig->add509Cert(file_get_contents('path/to/file/CORPORACIONFARMACIASBOLIVIASA.csr'),false);
//         $objDSig->appendSignature($doc->documentElement);
        
//         $crypttext =$doc->saveXML();

openssl_sign($hashSectorFirma, $crypttext,'path/to/file/privatekey.pem','OPENSSL_ALGO_SHA256');
error_get_last();
print_r(error_get_last());
echo "<br><br>";
openssl_error_string();
echo openssl_error_string();

 // echo "<BR>".$crypttext."<BR>";

$encriptFirma=base64_encode($crypttext);


$firmaXml=explode("<SignatureValue /><KeyInfo /><Object />", $datosExtras)[0];
$signature=$firmaXml.'<SignatureValue>'.$encriptFirma.'
</SignatureValue>
    <KeyInfo>
      <X509Data>
        <X509Certificate>MIIDVzCCAj8CAQAwggEQMRkwFwYDVQQDDBBGUkVDSU8gUk9EUklHVUVaMTAwLgYD
VQQKDCdDT1JQT1JBQ0lPTiBCT0xJVklBTkEgREUgRkFSTUFDSUFTIFMuQS4xHDAa
BgNVBAsME1JFUFJFU0VOVEFOVEUgTEVHQUwxHDAaBgNVBAwME1JFUFJFU0VOVEFO
VEUgTEVHQUwxCzAJBgNVBAYTAkJPMQswCQYDVQQuEwJDSTEUMBIGBysGAQEBAQAM
BzQ0MTU0OTExEjAQBgoJkiaJk/IsZAEsDAIyMDETMBEGA1UEBRMKMTAyMjAzOTAy
NzEsMCoGCSqGSIb3DQEJARYdcmluZGFAZmFybWFjaWFzYm9saXZpYS5jb20uYm8w
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDzYTB28L4rt+jvWs64UEA8
v3G7eGNmzvHApD//9K1SkBFzt1aZO8XP/wej1aihB8mG8IKR0Vvom/9HyardBlZg
uzAF3KPxJ7D/2qRaWciwjrVXtfn1hgl94fGOwFa6xmxZIPU29otS9FCvfDBs9LCm
/bVm+ShM1g+N+3EpFDwNl1TulU1LqHNx00Sw+90eIDMJVroQnhsyalgExMDLaz+Z
SQMre46ldEbi7pEDXR/sRlqLsqAxCkraCSYGiepky/0Sji8fbBBED/cb5dsDfDsM
j/Kewxn+maBpwBZGDu4QVzmKoa4mB1Hq2bEFOO5Dk7aY7KludMGC68/lRRhrrdrB
AgMBAAGgADANBgkqhkiG9w0BAQUFAAOCAQEAvwIlFx5AfeZlsf4z0xkcT8cxq/XF
twP5JVgLvTGYrUBw7MW5v6mIDBWP4uSUt16AsqHnPaCflBdGa+0Oalo3lQyToMvS
xGQK4rYDDdXrYgw3naxth57KYK/40WrqX5gbl0HbTR8R3fewZuwcyJThiWDkZhAm
8Pz9BZhKgeeuJGKnInJpAQeAkfN/xhHwZWDKLi5Zzj47+kNhQ1Zy4+RAowQ5MXHi
em4oXmzL2mLeO1WBsIKSar+zD/eWB98svjmzHhO+KsWzr5bhsvDA7Untgt8zzByd
tMUGboHfbvcXz8U3Xsq54OpypsCZOLHuJnLavHtq0P5IvQF/AZG4Glo/fg==
        </X509Certificate>
      </X509Data>
    </KeyInfo>
</Signature>';

$archivoPre=explode("</facturaElectronicaCompraVenta>",$documentoGenerado)[0];

$documentoGenerado=$archivoPre.$signature."</facturaElectronicaCompraVenta>";
//$documentoGenerado=$archivoPre."</facturaElectronicaCompraVenta>";

//validamos documento
// $doc1 = new \DOMDocument();
// $doc1->loadXML($documentoGenerado);
// $doc1->save('pruebas.xml');
// $valXSD = new ValidacionXSD();
// if(!$valXSD->validar('pruebas.xml','facturaElectronicaCompraVenta.xsd')){
//     print $valXSD->mostrarError();
// }else{
//     echo "****VAlido*****";
// }




?>
<p>DOCUMENTO FISCAL FINAL</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoGenerado;?></textarea>
<br><br>
<?php

//$documentoGenerado=file_get_contents("45D4083F1463611231430260A22EC4A3C044F590739808651EE146D74.xml");

$archivo=generarArchivo($documentoGenerado);
$hashArchivo=generarHashArchivo($archivo);
// echo "<br>HASH<BR>".$archivo;

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



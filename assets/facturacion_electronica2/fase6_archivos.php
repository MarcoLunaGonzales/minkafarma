<?php

function generarArchivos_xml($wsdl,$cuis,$cufd){


include ("cano.php");
date_default_timezone_set("America/La_Paz");
//sincronizacion de catalogos
// require "soap_siat.php";
// $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
// //$cuis="983EE1C8";//"81E5C14E";
// $cuis="81E5C14E";
// $cufd="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QkFVR1NSTERXVUFFFNEUwNkEzNkY4N";
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

echo "<BR>".$crypttext."<BR>";

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

$archivoPre=explode("</facturaElectronicaCompraVenta>",$documentoFiscal)[0];

$documentoFiscal=$archivoPre.$signature."</facturaElectronicaCompraVenta>";

?>
<p>DOCUMENTO FISCAL FINAL</p>
<textarea style="width: 100%;height: 500px;background: #DAF7A6;color:#000;"><?=$documentoFiscal;?></textarea>
<br><br>
<?php


$archivo=generarArchivo_tar($documentoFiscal);

return  $archivo;

}

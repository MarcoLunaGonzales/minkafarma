<?php
//sincronizacion de catalogos
date_default_timezone_set("America/La_Paz");
//sincronizacion de catalogos
require "soap_siat.php";
require "fase6_archivos.php";
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
//$cuis="983EE1C8";//"81E5C14E";
$cuis="81E5C14E";
$cufd="QUHCoUNuVUNCQQ==NTg3RjNCRTk4QTY=QsKhekplTFBEV1VBNzFFNEUwNkEzNkY4";//2022-03-13T11:56:42.593-04:00
$cufd="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QkFDSlFTUERXVUFFFNEUwNkEzNkY4N";
$codigoControl="31E086E84446D74";


$date= date('Y-m-d H:i:s'); 
$newDate = strtotime ($date); 
$anio=date("Y",$newDate);
$mes=date("m",$newDate);
$dia=date("d",$newDate);
$hora=date("H",$newDate);
$minuto=date("i",$newDate);
$segundo=date("s",$newDate);

$codigoPuntoVenta=0;//Solo se envía cuando la transacción se realiza utilizando un punto de venta. Caso contrario enviar 0.
$codigoSucursal=0;// Valor que identifica a la sucursal donde se realiza la emisión de la Factura: Casa Matriz: 0 Sucursal: 1,2,...,n
$tipoFacturaDocumento= 1;//Código que identifica el Tipo de Factura o Documento de Ajuste que se está enviando
$archivo=generarArchivos_xml($wsdl,$cuis,$cufd);
$fechaEnvio=$anio."-".$mes."-".$dia."T".$hora.":".$minuto.":".$segundo.".000";
$cafc=null;//Código de autorización de emisión de facturas manuales de contingencia. Nulo si son facturas normales
$cantidadFacturas=1;
$codigoEvento=294070;//Código que devolvió el método de registro de evento
//292000//evnto tipo 7
SolicitudServicioRecepcionPaquete($codigoPuntoVenta,$codigoSucursal,$tipoFacturaDocumento,$archivo,$fechaEnvio,$cafc,$cantidadFacturas,$codigoEvento,$cufd);


function SolicitudServicioRecepcionPaquete($codigoPuntoVenta,$codigoSucursal,$tipoFacturaDocumento,$archivo,$fechaEnvio,$cafc,$cantidadFacturas,$codigoEvento,$cufd){
    //valores estaticos
    $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
    $direccion_soap_env='http://schemas.xmlsoap.org/soap/envelope/';
    $direccion_siat="https://siat.impuestos.gob.bo/";
    $codigoAmbiente=2;
    $codigoDocumentoSector=1;//Código que identifica el sector de la  Factura.
    $codigoEmision=2;
    $codigoModalidad=1;
    $codigoSistema='71E4E06A36F8587F3BE98A6';
    $cuis="81E5C14E";
    $nit='1022039027';
    // $archivo=generarArchivos_xml($wsdl,$cuis,$cufd);
    $hashArchivo=generarHashArchivo($archivo);
    $parameters['SolicitudServicioRecepcionPaquete']=array(
          'codigoAmbiente' => $codigoAmbiente,//1 produccion,2 pruebas
          'codigoDocumentoSector' => $codigoDocumentoSector,//Código  que identifica el sector de la  Factura.
          'codigoEmision' => $codigoEmision,//Describe si la emisión se realizó fuera de línea. El valor permitido es: Offline : 2
          'codigoModalidad' => $codigoModalidad,//Electrónica en Línea: 1
          'codigoPuntoVenta' =>  $codigoPuntoVenta,//Solo se envía cuando la transacción se realiza utilizando un punto de venta. Caso contrario enviar 0.
          'codigoSistema' =>  $codigoSistema,//Código de Sistema que le fue asignado al momento de realizar la solicitud de autorización.
          'codigoSucursal' =>  $codigoSucursal,// Valor que identifica a la sucursal donde se realiza la emisión de la Factura: Casa Matriz: 0 Sucursal: 1,2,...,n
          'cufd' =>  $cufd,
          'cuis' =>  $cuis,
          'nit' =>  $nit,
          'tipoFacturaDocumento' =>  $tipoFacturaDocumento,//Código que identifica el Tipo de Factura o Documento de Ajuste que se está enviando
          'archivo' =>  $archivo,//Paquete de Facturas que son enviadas para su validación.
          'fechaEnvio' =>  $fechaEnvio,
          'hashArchivo' =>  $hashArchivo,//Sha256 de la cadena Archivo que se envía.
          'cafc' =>  $cafc,//Código de autorización de emisión de facturas manuales de contingencia. Nulo si son facturas normales
          'cantidadFacturas' => $cantidadFacturas,
          'codigoEvento' => $codigoEvento //Código que devolvió el método de registro de evento
    );
    $siat=new SiatDatos();
    $client=$siat->newClientSoap($wsdl);   
    $client->namespaces = array('SOAP-ENV' => $direccion_soap_env,'siat' => $direccion_siat);
    $respons = $client->recepcionPaqueteFactura($parameters);  

    echo "<pre>";
    print_r($respons);
    echo "</pre>";
    // return $respons;

}








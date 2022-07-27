<?php
//sincronizacion de catalogos
require "soap_siat.php";
$wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";
$cuis="81E5C14E";//"983EE1C8";//"81E5C14E"; //"983EE1C8"
$cufdAnt="QUHCoUNuVUNCQQ==NTg3RjNCRTk4QTY=QsKhWXRTT0pEV1VCNzFFNEUwNkEzNkY4"; // 14:18
$cufdAnt="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QlVbYTRPSkRXVUJFFNEUwNkEzNkY4N"; // 10/03 15:05
$cufdAnt="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QkFhcU1TTURXVUFFFNEUwNkEzNkY4N"; //11/03/2022 18:17
$cufd="QUHCoUNuVUNCQQ==NTg3RjNCRTk4QTY=QsKhekplTFBEV1VBNzFFNEUwNkEzNkY4"; //11/03/2022 18:17


//generar desde las 14:27
$inicial=0;





$siat=new SiatDatos();
$client=$siat->newClientSoap($wsdl);      
 
for ($i=0; $i < 1; $i++) { 

$date= date('Y-m-d H:i:s'); 
$ini=$inicial+2;
$fin=$inicial+1;
$newDate = strtotime ( '-'.$ini.' minutes' ,strtotime ($date)); 

$anio=date("Y",$newDate);
$mes=date("m",$newDate);
$dia=date("d",$newDate);
$hora=date("H",$newDate);
$minuto=date("i",$newDate);
$segundo=date("s",$newDate);
$fechaInicioEvento=$anio."-".$mes."-".$dia."T".$hora.":".$minuto.":".$segundo.".000";
$fechaInicioEvento="2022-03-11T18:18:20.000";

$date= date('Y-m-d H:i:s'); 
$newDate = strtotime ( '-'.$fin.' minutes' ,strtotime ($date)); 

$anio=date("Y",$newDate);
$mes=date("m",$newDate);
$dia=date("d",$newDate);
$hora=date("H",$newDate);
$minuto=date("i",$newDate);
$segundo=date("s",$newDate);
$fechaFinEvento=$anio."-".$mes."-".$dia."T".$hora.":".$minuto.":".$segundo.".000";
$fechaFinEvento="2022-03-11T22:00:00.000";

echo $fechaInicioEvento."FIN".$fechaFinEvento;
$parameters['SolicitudEventoSignificativo']=array(
      'codigoAmbiente' => 2,
      'codigoMotivoEvento'=>1,
      'codigoPuntoVenta' => 0,
      'codigoSistema' => '71E4E06A36F8587F3BE98A6',
      'codigoSucursal' => 0,
      'cufd' => $cufd,
      'cufdEvento' => $cufdAnt,
      'codigoModalidad' => 1,
      'cuis' => $cuis,
      'descripcion' => 'CORTE DEL SERVICIO DE INTERNET',
      'fechaHoraFinEvento' => $fechaFinEvento,
      'fechaHoraInicioEvento' => $fechaInicioEvento,
      'nit' => '1022039027'    
);
    $respons = $client->registroEventoSignificativo($parameters);  
    $inicial=$ini;
  }
echo "<br>".$inicial;

echo "<pre>";
print_r($respons);
echo "</pre>";

// SE REGISTRARON EVENTOS DESDE 7 DE MARZO





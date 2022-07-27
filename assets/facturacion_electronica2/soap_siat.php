<?php

/**
 * 
 */
class SiatDatos
{
	var $wsdl = "";
	var $token = "";
	var $opts = array();
	var $context = "";
	
	function __construct()
	{

		$this->token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJDT0JPRkFSU0ExMCIsImNvZGlnb1Npc3RlbWEiOiI3MUU0RTA2QTM2Rjg1ODdGM0JFOThBNiIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTBNREl5TUxZME1ESUhBT2ZINEt3S0FBQUEiLCJpZCI6MTIzNjA3LCJleHAiOjE2Nzc4MDE2MDAsImlhdCI6MTY0NjMzMTk5MSwibml0RGVsZWdhZG8iOjEwMjIwMzkwMjcsInN1YnNpc3RlbWEiOiJTRkUifQ._6PUETTgIpYSX0ZZrrfgCdMiclP_AIGuIDEz3lWgRSVwj6FkWi8QVAj77Jz1YPOGvho51PHGI0e8r7W3D36tAg";
				
	}

	function newClientSoap($wsql){
		$this->wsdl=$wsql;
		$this->setContext();
		return $client = new \SoapClient($this->wsdl, [
      			'stream_context' => $this->context
		]);
	}
	function setContext(){
		$this->opts=array('http' => array(
           'header' => "ApiKey: TokenApi ".$this->token,
      		)
		);
		$this->context=stream_context_create($this->opts);
	}
}


function generarDocumentoElectronico($datos){
	$cuf=generarCUF($datos->nitEmisor, $datos->fechaHora, $datos->codigoSucursal, $datos->codigoModalidad, $datos->codigoEmision, $datos->codigoDocumentoFiscal, $datos->codigoDocumentoSector, $datos->numeroFactura, $datos->codigoPuntoVenta,$datos->codigoControl);
	//echo $cuf;
$archivoXml='<?xml version="1.0" encoding="utf-8"?>
<facturaElectronicaCompraVenta xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                               xsi:noNamespaceSchemaLocation="facturaElectronicaCompraVenta.xsd">
  <cabecera>
  	<nitEmisor>'.$datos->nitEmisor.'</nitEmisor>
	<razonSocialEmisor>CORPORACION BOLIVIANA DE FARMACIAS S.A.</razonSocialEmisor>
	<municipio>La Paz</municipio>
	<telefono>2311190</telefono>
	<numeroFactura>'.$datos->numeroFactura.'</numeroFactura>
	<cuf>'.$cuf.'</cuf>
	<cufd>'.$datos->cufd.'</cufd>
	<codigoSucursal>'.$datos->codigoSucursal.'</codigoSucursal>
	<direccion>'.$datos->direccion.'</direccion>
	<codigoPuntoVenta>'.$datos->codigoPuntoVenta.'</codigoPuntoVenta>
	<fechaEmision>'.$datos->fechaEmision.'</fechaEmision>
	<nombreRazonSocial>'.$datos->nombreRazonSocial.'</nombreRazonSocial>
	<codigoTipoDocumentoIdentidad>'.$datos->codigoTipoDocumentoIdentidad.'</codigoTipoDocumentoIdentidad>
	<numeroDocumento>'.$datos->numeroDocumento.'</numeroDocumento>
	<complemento xsi:nil ="true"></complemento>
	<codigoCliente>'.$datos->codigoCliente.'</codigoCliente>
	<codigoMetodoPago>'.$datos->codigoMetodoPago.'</codigoMetodoPago>
	<numeroTarjeta xsi:nil ="true"></numeroTarjeta>
	<montoTotal>'.$datos->montoTotal.'</montoTotal>
	<montoTotalSujetoIva>'.$datos->montoTotal.'</montoTotalSujetoIva>
	<codigoMoneda>1</codigoMoneda>
	<tipoCambio>1</tipoCambio>
	<montoTotalMoneda>'.$datos->montoTotalMoneda.'</montoTotalMoneda>
	<montoGiftCard>0</montoGiftCard>
	<descuentoAdicional>'.$datos->descuentoAdicional.'</descuentoAdicional>
	<codigoExcepcion xsi:nil ="true"/>
	<cafc xsi:nil ="true"></cafc>
	<leyenda>Ley N° 453: Tienes derecho a recibir información sobre las características y contenidos de los servicios que utilices.
	</leyenda>
	<usuario>u_cobofar</usuario>
	<codigoDocumentoSector>'.$datos->codigoDocumentoSector.'</codigoDocumentoSector>
 </cabecera>';

foreach ($datos->detalle as $det) {
 $archivoXml.='
	<detalle>
		<actividadEconomica>451010</actividadEconomica>
		<codigoProductoSin>49111</codigoProductoSin>
		<codigoProducto>'.$det->codigoProducto.'</codigoProducto>
		<descripcion>'.$det->descripcion.'</descripcion>
		<cantidad>'.$det->cantidad.'</cantidad>
		<unidadMedida>1</unidadMedida>
		<precioUnitario>'.$det->precioUnitario.'</precioUnitario>
		<montoDescuento>'.$det->montoDescuento.'</montoDescuento>
		<subTotal>'.$det->subTotal.'</subTotal>
		<numeroSerie xsi:nil ="true"></numeroSerie>
		<numeroImei xsi:nil ="true"></numeroImei>
 </detalle>';	
}

$archivoXml.='</facturaElectronicaCompraVenta>';
return $archivoXml;
}

function generarArchivo($xml){

	// unlink("fact.tar");
	// unlink("fact.tar.gz");
	// $p = new PharData('fact.tar');
	// $p['fact.xml'] = $xml;
	// $p1 = $p->compress(Phar::GZ);
 //    $xml = file_get_contents("fact.tar.gz");  

   //$codificado=base64_encode($xml);
   //$gzcodificado = gzencode($codificado, 9);
   //$arrGz=base64_encode($xml);
   //return $arrGz;

	unlink("fact2.xml");
	unlink("fact2.gz");
    $archivo = fopen('fact2.xml','a');    
	fputs($archivo,$xml);
	fclose($archivo);
   comprimir("fact2.xml", "fact2.gz");
   $xml=file_get_contents("fact2.gz");  


   //$xml=base64_encode($xml);
   return $xml;
}

function generarArchivo_nuevo($xml){

	// unlink("fact.tar");
	// unlink("fact.tar.gz");
	// $p = new PharData('fact.tar');
	// $p['fact.xml'] = $xml;
	// $p1 = $p->compress(Phar::GZ);
 //    $xml = file_get_contents("fact.tar.gz");  

   //$codificado=base64_encode($xml);
   //$gzcodificado = gzencode($codificado, 9);
   //$arrGz=base64_encode($xml);
   //return $arrGz;

	unlink("fact2.xml");
	unlink("fact2.gz");
    $archivo = fopen('fact2.xml','a');    
	fputs($archivo,$xml);
	fclose($archivo);
   comprimir("fact2.xml", "fact2.gz");
   $xml=file_get_contents("fact2.gz");  


   //$xml=base64_encode($xml);
   return $xml;
}
function generarArchivo_tar($xml){

	unlink("fact.tar");
	unlink("fact.tar.gz");
	$p = new PharData('fact.tar');
	$p['fact.xml'] = $xml;
	$p1 = $p->compress(Phar::GZ);
    $xml = file_get_contents("fact.tar");  

   //$codificado=base64_encode($xml);
   //$gzcodificado = gzencode($codificado, 9);
   //$arrGz=base64_encode($xml);
   //return $arrGz;

	unlink("fact2.xml");
	unlink("fact2.gz");
    $archivo = fopen('fact2.xml','a');    
	fputs($archivo,$xml);
	fclose($archivo);
   comprimir("fact2.xml", "fact2.gz");
   $xml=file_get_contents("fact2.gz");  


   //$xml=base64_encode($xml);
   return $xml;
}
function generarHashArchivo($arrGz){
  // $sha=hash('sha256', $arrGz);
  $sha=hash('sha256', $arrGz, !true);
  return $sha;
}
function comprimir($origen, $destino) {
  $fp = fopen($origen, "r");
  $data = fread ($fp, filesize($origen));
  fclose($fp);
  $zp = gzopen($destino, "w9");
  gzwrite($zp, $data);
  gzclose($zp);
}


function generarCUF($nit, $fechaHora, $sucursal, $modalidad, $tipoEmision, $codigoDocFiscal, $tipoDocumentoSector, $nroFactura, $pos,$codigoControl){
	//RELLENAMOS CON LA CANTIDAD DE 0 A LA IZQ
	$nit=str_pad($nit, 13, "0", STR_PAD_LEFT);
	$fechaHora=str_pad($fechaHora, 17, "0", STR_PAD_LEFT);
	$sucursal=str_pad($sucursal, 4, "0", STR_PAD_LEFT);
	$tipoDocumentoSector=str_pad($tipoDocumentoSector, 2, "0", STR_PAD_LEFT);
	$nroFactura=str_pad($nroFactura, 10, "0", STR_PAD_LEFT);
	$pos=str_pad($pos, 4, "0", STR_PAD_LEFT);
	
	$varConcatenada=$nit.$fechaHora.$sucursal.$modalidad.$tipoEmision.$codigoDocFiscal.$tipoDocumentoSector.$nroFactura.$pos;
	//echo "concatenacion1: ".$varConcatenada."<br>";
	
	$digitoBase11=calculaDigitoMod11($varConcatenada,1,9,false);
	
	$varConcatenada.=$digitoBase11;
	
	//echo "concatenacion2: ".$varConcatenada."<br>";
	
	$CUF=convBase($varConcatenada,'0123456789','0123456789ABCDEF');
	return $CUF.$codigoControl;
	
}
function calculaDigitoMod11($numDado, $numDig, $limMult, $x10){
  if(!$x10) $numDig = 1;
  for($n=1; $n<=$numDig; $n++){
    $suma = 0;
    $mult = 2;
    for($i=strlen($numDado) - 1; $i>=0; $i--){
      $suma += $mult * intval(substr($numDado, $i ,1));
      if(++$mult > $limMult) $mult = 2;
    }
    if($x10){
      $dig = (($suma * 10) % 11) % 10;
    } else {
      $dig = $suma % 11;
    }
      if($dig == 10) $numDado .= "1";
      if($dig == 11) $numDado .= "0";
      if($dig < 10) $numDado .= $dig; 
  }
  return substr($numDado, strlen($numDado)-$numDig);
}

function convBase($numberInput, $fromBaseInput, $toBaseInput)
{
    if ($fromBaseInput==$toBaseInput) return $numberInput;
    $fromBase = str_split($fromBaseInput,1);
    $toBase = str_split($toBaseInput,1);
    $number = str_split($numberInput,1);
    $fromLen=strlen($fromBaseInput);
    $toLen=strlen($toBaseInput);
    $numberLen=strlen($numberInput);
    $retval='';
    if ($toBaseInput == '0123456789')
    {
        $retval=0;
        for ($i = 1;$i <= $numberLen; $i++)
            $retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase),bcpow($fromLen,$numberLen-$i)));
        return $retval;
    }
    if ($fromBaseInput != '0123456789')
        $base10=convBase($numberInput, $fromBaseInput, '0123456789');
    else
        $base10 = $numberInput;
    if ($base10<strlen($toBaseInput))
        return $toBase[$base10];
    while($base10 != '0')
    {
        $retval = $toBase[bcmod($base10,$toLen)].$retval;
        $base10 = bcdiv($base10,$toLen,0);
    }
    return $retval;
}




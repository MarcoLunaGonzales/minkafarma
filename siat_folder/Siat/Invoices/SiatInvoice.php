<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Message;
use SimpleXMLElement;
use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\DocumentTypes;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

abstract class SiatInvoice extends Message
{
	const 	FACTURA_DERECHO_CREDITO_FISCAL = 1;
	const 	FACTURA_SIN_DERECHO_CREDITO_FISCAL = 2;
	const 	FACTURA_DOCUMENTO_AJUSTE = 3;
	
	const 	TIPO_EMISION_ONLINE 	= 1;
	const 	TIPO_EMISION_OFFLINE 	= 2;
	const 	TIPO_EMISION_MASIVA 	= 3;
	
	/**
	 * @var InvoiceHeader
	 */
	public	$cabecera;
	/**
	 * @var InvoiceDetail[]
	 */
	public	$detalle = [];
	
	
	protected	$required = [];
	protected	$nsData = [];
	
	public		$endpoint;
	
	public function __construct()
	{
		$this->xmlAllFields = true;
		$this->skipProperties = ['endpoint', 'classAlias', 'xmlAllFields', 'namespaces', 'skipProperties', 'xmlAttributes'];
		$this->namespaces = [
			//['name' => 'xmlns:xsi', 'value' => 'http://www.w3.org/2001/XMLSchema-instance', 'ns' => 'http://www.w3.org/2001/XMLSchema-instance']
		];
		$this->cabecera = new InvoiceHeader();
		$this->detalle = [];
	}
	public function validate()
	{
		$this->cabecera->validate();
		
	}
	public function calculaDigitoMod11(string $cadena, int $numDig, int $limMult, bool $x10)
	{
		$cadenaSrc = $cadena;
		
		$mult = $suma = $i = $n = $dig = 0;
		
		if (!$x10) $numDig = 1;
		
		for($n = 1; $n <= $numDig; $n++) 
		{
			$suma = 0;
			$mult = 2;
			for($i = strlen($cadena) - 1; $i >= 0; $i--) 
			{
				$cadestr = $cadena[$i];//substr($cadena, $i, $i + 1);
				$intNum = (int)($cadestr);
				//echo 'cadestr: ', $cadestr, "\n";
				//echo 'intNum: ', $intNum, "\n";
				$suma += ($mult * $intNum);
				if(++$mult > $limMult) $mult = 2;
			}
			if ($x10) 
			{
				$dig = (($suma * 10) % 11) % 10;
			}
			else 
			{
				$dig = $suma % 11;
			}
			if ($dig == 10) 
			{
				$cadena .= "1";
			}
			if ($dig == 11) 
			{
				$cadena .= "0";
			}
			if ($dig < 10) {
				
				//$cadena .= String.valueOf(dig);
				$cadena .= $dig;
			}
			//echo "Dig: ", $dig, "\n";
		}
		
		$modulo = substr($cadena, strlen($cadena) - $numDig, strlen($cadena));
		
		//echo $cadena, "\n";
		//echo 'Calculado modulo 11: ', $cadenaSrc, " => ", $modulo, "\n";
		
		return $modulo;
	}
	public function buildCuf($sucursalNro, $modalidad, $tipoEmision, $tipoFactura, $codigoControl)
	{
		$nitEmisor 			= str_pad($this->cabecera->nitEmisor, 13, '0', STR_PAD_LEFT);
		$sucursalNro 		= str_pad($sucursalNro, 4, '0', STR_PAD_LEFT);
		$tipoSector 		= str_pad($this->cabecera->codigoDocumentoSector, 2, '0', STR_PAD_LEFT);
		$numeroFactura 		= str_pad($this->cabecera->numeroFactura, 10, '0', STR_PAD_LEFT);
		$numeroPuntoVenta 	= str_pad($this->cabecera->codigoPuntoVenta, 4, '0', STR_PAD_LEFT);
		// $fechaHora 			= date('YmdHism0');
		$fechaHora=date('YmdHisv',strtotime($this->cabecera->fechaEmision));
		// $fechaHora 			= '2022032215100000';
		// $fechaHora=str_pad($fechaHora, 17, "0", STR_PAD_LEFT);
		/*
		$nitEmisor 			= str_pad('123456789', 13, '0', STR_PAD_LEFT);
		$sucursalNro 		= str_pad('0', 4, '0', STR_PAD_LEFT);
		$tipoSector 		= str_pad('1', 2, '0', STR_PAD_LEFT);
		$numeroFactura 		= str_pad('1', 10, '0', STR_PAD_LEFT);
		$numeroPuntoVenta 	= str_pad('0', 4, '0', STR_PAD_LEFT);
		$fechaHora 			= '20190113163721231';
		*/
		

		$cadena 		= "{$nitEmisor}{$fechaHora}{$sucursalNro}{$modalidad}{$tipoEmision}{$tipoFactura}{$tipoSector}{$numeroFactura}{$numeroPuntoVenta}";
		


		//$cadena 		= '00002432120202021122902151805100002110100000000190000';
		//$codigoControl 	= '83A53D46710EC74';
		
		$verificador 	= $this->calculaDigitoMod11($cadena, 1, 9, false);
		//$b16_str 		= $this->bcdechex(ltrim($cadena . $verificador));
		$b16_str 		= strtoupper( $this->bcdechex( $cadena . $verificador ) );
		$this->cabecera->cuf = $b16_str . $codigoControl;

		// $this->cabecera->cuf = '45EE4580730626D0FBFFE721F0D6306CE29B0206B05736660F9646D74';

		
		//die("cadena length: ". strlen($cadena) ."\nverificador: $verificador\nb16_str: $b16_str\nCUF: {$this->header->cuf}\n");
		/*
		print "Cadena: $cadena\nLength: " . strlen($cadena) . "\n";
		echo "Cadena INT: ", ltrim($cadena . $verificador, '0'), "\n";
		echo "Cadena HEX: ", dechex($cadena . $verificador), "\n";
		echo 'Verificador: ', $verificador, "\n";
		echo 'B16: ', $b16_str, "\n";
		echo 'CUF: ', $this->cuf, "\n";
		*/
	}
	public function buildCuf2($sucursalNro, $modalidad, $tipoEmision, $tipoFactura, $codigoControl)
	{
		$nitEmisor 			= str_pad($this->cabecera->nitEmisor, 13, '0', STR_PAD_LEFT);
		$sucursalNro 		= str_pad(0, 4, '0', STR_PAD_LEFT);
		$tipoSector 		= str_pad($this->cabecera->codigoDocumentoSector, 2, '0', STR_PAD_LEFT);
		$numeroFactura 		= str_pad($this->cabecera->numeroFactura, 10, '0', STR_PAD_LEFT);
		$numeroPuntoVenta 	= str_pad($this->cabecera->codigoPuntoVenta, 4, '0', STR_PAD_LEFT);
		// $fechaHora 			= '20220323094500000';
		 
		 $fechaHora=date('YmdHisv',strtotime($this->cabecera->fechaEmision));
		
		

		//$fechaHora=str_pad($fechaHora, 17, "0", STR_PAD_LEFT);
		/*
		$nitEmisor 			= str_pad('123456789', 13, '0', STR_PAD_LEFT);
		$sucursalNro 		= str_pad('0', 4, '0', STR_PAD_LEFT);
		$tipoSector 		= str_pad('1', 2, '0', STR_PAD_LEFT);
		$numeroFactura 		= str_pad('1', 10, '0', STR_PAD_LEFT);
		$numeroPuntoVenta 	= str_pad('0', 4, '0', STR_PAD_LEFT);
		$fechaHora 			= '20190113163721231';
		*/
		

		$cadena 		= "{$nitEmisor}{$fechaHora}{$sucursalNro}{$modalidad}{$tipoEmision}{$tipoFactura}{$tipoSector}{$numeroFactura}{$numeroPuntoVenta}";
		


		//$cadena 		= '00002432120202021122902151805100002110100000000190000';
		//$codigoControl 	= '83A53D46710EC74';
		
		$verificador 	= $this->calculaDigitoMod11($cadena, 1, 9, false);
		//$b16_str 		= $this->bcdechex(ltrim($cadena . $verificador));
		$b16_str 		= strtoupper( $this->bcdechex( $cadena . $verificador ) );
		$this->cabecera->cuf = $b16_str . $codigoControl;

		// $this->cabecera->cuf = '45EE4580730626D0FBFFE721F0D6306CE29B038D4B5736660F9646D74';

		//
		//45EE458073062 6CE3 2DE8E85F94961EAAF7B0513EE 3EF13D37D646D74
		//45EE458073062 6FF8 0A4D3138C822345B97B0513F0 3EF13D37D646D74 

		//45EE458073062 7017 5F7D5D38C188BAF78D30513EB 3EF13D37D646D74
		//45EE458073062 7016 1CC6B351E810B3348A30513F0 3EF13D37D646D74

		//45EE458073062 7017 3E5EC34B5B6F98874330513EE 3EF13D37D646D74
		//45EE45807306270175F7D5D38C188BAF78D30513EB3EF13D37D646D74

		//die("cadena length: ". strlen($cadena) ."\nverificador: $verificador\nb16_str: $b16_str\nCUF: {$this->header->cuf}\n");
		/*
		print "Cadena: $cadena\nLength: " . strlen($cadena) . "\n";
		echo "Cadena INT: ", ltrim($cadena . $verificador, '0'), "\n";
		echo "Cadena HEX: ", dechex($cadena . $verificador), "\n";
		echo 'Verificador: ', $verificador, "\n";
		echo 'B16: ', $b16_str, "\n";
		echo 'CUF: ', $this->cuf, "\n";
		*/
	}
	public function bcdechex($dec) 
	{
		$hex = '';
		do {
			$last = bcmod($dec, 16);
			$hex = dechex($last).$hex;
			$dec = bcdiv(bcsub($dec, $last), 16);
		} while($dec>0);
		return $hex;
	}
	/*
	public function toXml($rootTagName = null)
	{
		if( !$this->type )
			throw new Exception('Invalid invoice type [computarizada|enlinea]');
		
		$this->namespaces = [
			$this->nsData[$this->type]
		];
		$xml = parent::toXml($rootTagName);
		
		$header = $xml->addChild('cabecera', '', null);
		$detail = $xml->addChild('detalle', '', null);
		$this->buildHeader($header);
		$this->buildDetail($detail);
		
		//$buffer = $xml->asXML();
		
		return $xml;
	}
	*/
	/**
	 * 
	 * @param string $filename
	 * @throws Exception
	 * @return SiatInvoice
	 */
	public static function buildFromXmlFile($filename)
	{
		if( !is_file($filename) )
			throw new Exception('Invalid invoice file');
		$obj = simplexml_load_file($filename, static::class);
		return $obj;
	}
	// public function getEndpoint( $modalidad, $ambiente )
	// {
	// 	if( $this->cabecera->codigoDocumentoSector == DocumentTypes::FACTURA_SERV_BASICOS )
	// 		return $ambiente == 1 ? "" : "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionServicioBasico?wsdl";
	// 	if( $this->cabecera->codigoDocumentoSector == DocumentTypes::FACTURA_COMPRA_VENTA )
	// 		return $ambiente == 1 ? "" : "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
		
	// 	if( ServicioSiat::MOD_ELECTRONICA_ENLINEA == $modalidad )
	// 	{
	// 		return $ambiente == 1 ? '' : 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionElectronica?wsdl';
	// 	}
		
	// 	return $ambiente == 1 ? '' : 'https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionComputarizada?wsdl';
	// }
	public function toXml($rootTagName = null, $isRoot = false, $standalone = false)
	{
		return parent::toXml($rootTagName, true, true);
	}
}
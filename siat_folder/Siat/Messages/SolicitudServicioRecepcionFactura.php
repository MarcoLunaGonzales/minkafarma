<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Messages;

use Exception;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Invoices\SiatInvoice;

class SolicitudServicioRecepcionFactura extends SolicitudRecepcion
{
	public	$archivo;
	public	$fechaEnvio;
	public	$hashArchivo;
	
	public function __construct()
	{
		
	}
	public function loadFile(string $filename)
	{
		if( !is_file($filename) )
			throw new Exception('The invoice document does not exists "'. $filename .'"');
		
		$this->setBuffer(file_get_contents($filename));
	}
	public function setBuffer($binaryBuffer, $compress = true)
	{
		if( empty($binaryBuffer) )
			throw new Exception('The invoice buffer is empty');
		
		//$this->archivo = base64_encode(gzencode($binaryBuffer, 9, FORCE_GZIP));
		//$this->archivo 		= $compress ? gzencode($binaryBuffer, 9, FORCE_GZIP) : $binaryBuffer;
		$this->archivo 		= $compress ? gzcompress($binaryBuffer, 9, FORCE_GZIP) : $binaryBuffer;
		$this->hashArchivo 	= hash('sha256', $this->archivo, !true);
	}
	public function setBufferFromFiles(array $files)
	{
		$package = MOD_SIAT_TEMP_DIR . SB_DS . 'invoices-'. time() .'.tar';
		$tar = new \PharData($package);
		foreach($files as $file)
		{
			$tar->addFile($file);
		}
		$this->setBuffer(file_get_contents($package));
	}
	public function setBufferFromInvoicesXml(array $invoicesXml)
	{
		$package = MOD_SIAT_TEMP_DIR . SB_DS . 'invoices-'. time() .'.tar';
		$tar = new \PharData($package);
		foreach($invoicesXml as $i => $xml)
		{
			$localname = sprintf("invoice-%d.xml", $i);
			$tar->addFromString($localname, $xml);
		}
		$this->setBuffer(file_get_contents($package));
	}
	public function validate()
	{
		$tag = '['. basename(str_replace('\\', SB_DS, static::class)).'] ERROR: ';
		parent::validate();
		if( empty($this->archivo) )
			throw new Exception("$tag Invalid data \"archivo\", required base64 string");
		if( empty($this->fechaEnvio) )
			throw new Exception("$tag Invalid data \"fechaEnvio\", required non empty string");
		if( empty($this->hashArchivo) )
			throw new Exception("$tag Invalid data \"fechaEnvio\", required non empty string");
	}
	public function toXml($tagName = null, $isRoot = false, $standalone = false)
	{
		$xml = parent::toXml($tagName /*'SolicitudServicioRecepcionFactura'*/);
		
		return $xml;
	}
	
}
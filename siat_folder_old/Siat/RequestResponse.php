<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

class RequestResponse
{
	public	$info;
	public	$body;
	public	$rawBody;
	public	$rawHeaders;
	public	$headers = [];
	public	$cookies = [];
	public	$rawCookies = [];
	
	public function __construct($body, $info)
	{
		$this->info 	= $info;
		$this->rawBody	= $body;
		$this->processResponse();
	}
	protected function processResponse()
	{
		$this->rawHeaders 	= substr($this->rawBody, 0, $this->info['header_size']);
		$this->body			= trim(substr($this->rawBody, $this->info['header_size']));
		$this->parseHeaders();
	}
	protected function parseHeaders()
	{
		if( empty($this->rawHeaders) )
			return false;
		
		//var_dump($this->rawHeaders, explode("\r\n", $this->rawHeaders));
		//die($this->rawHeaders);
		foreach(explode("\r\n", $this->rawHeaders) as $h)
		{
			if( empty($h) || stristr($h, 'HTTP/') ) continue;
			
			list($key, $val) = array_map('trim', explode(':', $h));
			if( $key == 'Set-Cookie' )
			{
				$this->parseCookie($val);
				continue;
			}
			$this->headers[$key] = $val;
		}
	}
	public function getHeader($key, $def = null)
	{
		if( !isset($this->headers[$key]) )
			return $def;
		return $this->headers[$key];
	}
	public function json()
	{
		return json_decode($this->body);
	}
}
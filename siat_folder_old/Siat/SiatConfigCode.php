<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;
use Exception;

class SiatConfigCode extends SiatObject
{
	public 	$codigo;
	
	public	$direccion;
	public 	$fechaVigencia;
	public	$timeVigencia;
	public 	$mensajesList;
	public 	$transaccion;
	
	public function __construct($data = nul)
	{
		$this->bind($data);
	}
	public function bind($data)
	{
		parent::bind($data);
		$this->timeVigencia = strtotime($this->fechaVigencia);
	}
	public function validate()
	{
		list($class,)  = explode('::', __METHOD__);
		if( !isset($this->codigo) )
			throw new Exception($class . ' ERROR: Codigo invalido');
		if( !$this->fechaVigencia )
			throw new Exception($class . ' ERROR: Fecha vigencia invalida');
		if( (int)$this->timeVigencia <= 0 )
			throw new Exception($class . ' ERROR: Time vigencia invalido');
	}
	public function expirado()
	{
		$this->validate();
		
		return ( time() > $this->timeVigencia );
	}
}
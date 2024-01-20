<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

use Exception;


class SiatConfig extends SiatObject
{
	public	$nombreSistema;
	public	$codigoSistema;
	public	$tipo;
	public	$nit;
	public	$razonSocial;
	public	$modalidad;
	public	$ambiente;
	public	$tokenDelegado;
	/**
	 * 
	 * @var SiatConfigCuis
	 */
	public	$cuis			= null;
	/**
	 * 
	 * @var SiatConfigCufd
	 */
	public	$cufd			= null;
	
	
	public static function toArray()
	{
		$self = new \ReflectionClass(self::class);
		$props = $self->getProperties(\ReflectionProperty::IS_STATIC);
		
		$data = [];
		foreach($props as $prop)
		{
			$data[$prop->name] = $prop->getValue(self::class);
		}
		return $data;
	}
	
	public function __construct($data = null)
	{
		$this->bind($data);
	}
	public function bind($data)
	{
		parent::bind($data);
		if( isset($data->cuis) )
			$this->cuis = new SiatConfigCuis($data->cuis);
		if( isset($data->cufd) )
			$this->cufd = new SiatConfigCufd($data->cufd);
	}
	public function validate()
	{
		// list($class, ) = explode('::', __METHOD__);
		
		// if( !isset($this->tokenDelegado) )
		// 	throw new Exception("$class ERROR: Nombre de sistema invalido");
		// if( !isset($this->nombreSistema) )
		// 	throw new Exception("$class ERROR: Nombre de sistema invalido");
		// if( !isset($this->codigoSistema) )
		// 	throw new Exception("$class ERROR: Codigo de sistema invalido");
		// if( !in_array((int)$this->ambiente, [1, 2]) )
		// 	throw new Exception("$class ERROR: Codigo de ambiente invalido");
		// if( (int)$this->nit <= 0 )
		// 	throw new Exception("$class ERROR: NIT invalido");
		// if( !in_array((int)$this->modalidad, [1, 2]) )
		// 	throw new Exception("$class ERROR: Modalidad invalida");
		
	}
	public function validateExpirations()
	{
		$class = static::class;
		if( !$this->cuis )
			throw new Exception("$class ERROR: CUIS no existe");
		if( !$this->cufd )
			throw new Exception("$class ERROR: CUFD no existe");
		if( $this->cuis->expirado() )
			throw new Exception("$class ERROR: CUIS Expirado");
		if( $this->cufd->expirado() )
			throw new Exception("$class ERROR: CUF Expirado");
	}
}
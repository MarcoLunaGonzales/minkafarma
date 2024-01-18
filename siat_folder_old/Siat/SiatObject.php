<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

class SiatObject
{
	public function bind($data)
	{
		if( !$data || !is_array($data) && !is_object($data) )
			return false;
		foreach($data as $prop => $val)
		{
			if( !property_exists($this, $prop) ) continue;
			$this->$prop = $val;
		}
	}
}
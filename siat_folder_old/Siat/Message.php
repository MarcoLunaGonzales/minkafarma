<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

use SimpleXMLElement;

abstract class Message
{
	protected	$xmlAllFields = true;
	protected	$namespaces = [];
	protected	$classAlias = null;
	protected	$skipProperties = [];
	protected	$xmlAttributes = [];
	
	abstract public function validate();
	
	/**
	 * Get HTTP headers for request
	 * 
	 * @return array
	 */
	public function getHeaders()
	{
		return [];
	}
	protected function setXmlElementAttribute(\SimpleXMLElement $el)
	{
		if( !isset($this->xmlAttributes[$el->getName()]) )
			return false;
		$attrs = $this->xmlAttributes[$el->getName()];
		foreach($attrs as $item)
		{
			$el->addAttribute($item['attr'], $item['value'], $item['ns']);
		}
	}
	protected function buildChildFromObject(\SimpleXMLElement $parent, object $obj, string $tagName)
	{
		$el = null;
		
		if( method_exists($obj, 'toXml') )
		{
			$el = $obj->toXml($tagName, false);
			SiatXmlElement::addChildElement($parent, $el);
			
		}
		else
		{
			$el = $parent->addChild($tagName, null, null);
			$this->buildChilds($el, get_object_vars($val));
		}
		return $el;
	}
	protected function buildChildFromArray(\SimpleXMLElement $parent, array $childs, string $tagName)
	{
		foreach($childs as $child)
		{
			$this->buildChild($parent, $child, $tagName);
		}
	}
	protected function buildChild(\SimpleXMLElement $parent, $data, $tagName)
	{
		if( is_array($data) )
			$this->buildChildFromArray($parent, $data, $tagName);
		elseif( is_object($data) )
			$this->buildChildFromObject($parent, $data, $tagName);
		else
		{
			$el = $parent->addChild($tagName, $data, null);
			$this->setXmlElementAttribute($el);
		}
	}
	public function buildChilds(\SimpleXMLElement $element, $data, $ns = null)
	{
		if( !is_array($data) && !is_object($data) )
		{
			return;	
		}
		foreach($data as $property => $_val)
		{
			$tag = $property;
			//var_dump($tag, $_val);
			if( in_array($tag, $this->skipProperties) )
				continue;
			$this->buildChild($element, $_val, $tag);
		}
	}
	/**
	 * 
	 * @param string $rootTagName
	 * @return \SimpleXMLElement
	 */
	public function toXml($rootTagName = null, $isRoot = false, $standalone = false)
	{
		if( !$rootTagName )
			$rootTagName = $this->classAlias ? $this->classAlias : basename(str_replace(['\\'], ['/'], static::class));
		$xmlTag = $isRoot ? sprintf("<?xml version=\"1.0\" encoding=\"utf-8\" %s ?>", $standalone ? "standalone=\"yes\"" : '') : '';
		
		$xml = new SimpleXMLElement($xmlTag . '<'.$rootTagName . '/>');
		//$xml->addAttribute('encoding', 'utf-8');
		//$root = $xml->addChild();
		foreach($this->namespaces as $ns)
			$xml->addAttribute($ns['name'], $ns['value'], $ns['ns']);
		
		//if( !$this->xmlAllFields )
		//	return $xml;
		
		$data = $this->getPropertiesData();
		$this->buildChilds($xml, $data);
		
		return $xml;
		
	}
	protected function __buildArrayChilds($array)
	{
		$childs = [];
		
		foreach($array as $key => $value)
		{
			if( is_array($value) )
			{
				$childs[$key] = [];
				foreach($value as $val)
				{
					$childs[$key][] = $this->__buildArrayChilds($value);
				}
			}
			elseif( is_object($value) )
			{
				$childs[$key] = $this->__buildArrayChilds($value);
			}
			else 
			{
				$childs[$key] = $value;
			}
			
		}
		return $childs;
	}
	public function toArray()
	{
		$className = $this->classAlias ? $this->classAlias : basename(str_replace(['\\'], ['/'], static::class));
		
		$data = $this->getPropertiesData();
		$arrayData = $this->__buildArrayChilds($data);
		
		return [$className => $arrayData];
	}
	public function getPropertiesData()
	{
		$self = new \ReflectionObject($this);
		/**
		 * @var \ReflectionProperty[] $props
		 */
		$props = $self->getProperties(\ReflectionProperty::IS_PUBLIC);
		
		$data = [];
		foreach($props as $prop)
		{
			$data[$prop->name] = $prop->getValue($this);
		}
		return $data;
	}
}
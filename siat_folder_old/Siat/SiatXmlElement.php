<?php
namespace SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat;

class SiatXmlElement extends \SimpleXMLElement
{
	public static function addChildElement(\SimpleXMLElement $parent, \SimpleXMLElement $el)
	{
		$elDom = dom_import_simplexml($el);
		$dom = dom_import_simplexml($parent);
		$dom->appendChild($dom->ownerDocument->importNode($elDom, true));
	}
}
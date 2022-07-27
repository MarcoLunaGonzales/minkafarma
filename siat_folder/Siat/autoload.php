<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'constants.php';

spl_autoload_register(function($className)
{
	$baseNamespace 	= 'SinticBolivia\\SBFramework\\Modules\\Invoices\\Classes\\Siat';
	$classPath 		= str_replace([$baseNamespace, '\\'],  ['', SB_DS], $className);
	$classFilename 	= MOD_SIAT_DIR . $classPath . '.php';
	//var_dump($className, $classPath, $classFilename);echo "\n\n";
	if( is_file($classFilename) )
		require_once $classFilename;
}, true, true);
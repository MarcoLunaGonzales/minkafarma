<?php
define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;

class SyncTest
{
	/**
	 * 
	 * @return SiatConfig
	 */
	public static function buildConfig()
	{
		return new SiatConfig([
			'nombreSistema'	=> 'Acrópolis SGF',
			'codigoSistema'	=> '6D307F64485BE20AEB91C0F',
			'tipo' 			=> 'PROVEEDOR',
			'nit'			=> 9665224017,
			'razonSocial'	=> 'MELGAR PEREZ MARIA SUSANA',
			'modalidad' 	=> ServicioSiat::MOD_COMPUTARIZADA_ENLINEA,
			'ambiente' 		=> ServicioSiat::AMBIENTE_PRUEBAS,
			'tokenDelegado'	=> 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI5NjY1MjI0MDE3U2MiLCJjb2RpZ29TaXN0ZW1hIjoiNkQzMDdGNjQ0ODVCRTIwQUVCOTFDMEYiLCJuaXQiOiJINHNJQUFBQUFBQUFBTE0wTXpNMU1qSXhNRFFIQUVqTkx5UUtBQUFBIiwiaWQiOjExNDE4MDksImV4cCI6MTY3MDAyNTYwMCwiaWF0IjoxNjM4NTYwOTYzLCJuaXREZWxlZ2FkbyI6OTY2NTIyNDAxNywic3Vic2lzdGVtYSI6IlNGRSJ9.gAtmPtHd5k-WTht22VIUoRd1EfFp9njHHELXBKHBvsJUVi5q6hSuwxyzc0DoBNBnmeIPC44i3_iOazBjP1bzRw',
			'cuis'			=> null,
			'cufd'			=> null,
		]);
	}
	public static function testSync($action)
	{
		try
		{
			$config = self::buildConfig();
			$config->validate();
			
			$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$servCodigos->setConfig((array)$config);
			$resCuis = $servCodigos->cuis();
			//print_r($resCuis);
			$sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
			$sync->setConfig((array)$config);
			$res = call_user_func([$sync, $action]);
			print_r($res);
			
		}
		catch(\Exception $e)
		{
			echo  $e->getMessage(), "\n\n";
		}
		
	}
}
//SyncTest::testSync('verificarComunicacion');
//SyncTest::testSync('sincronizarParametricaTipoMoneda');
//SyncTest::testSync('sincronizarParametricaTipoDocumentoSector');
//SyncTest::testSync('sincronizarParametricaTiposFactura');
SyncTest::testSync('sincronizarParametricaTipoDocumentoIdentidad');
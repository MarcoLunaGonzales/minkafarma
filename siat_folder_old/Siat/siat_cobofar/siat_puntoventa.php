<?php
define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioOperaciones;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class PuntoVentaTest
{
	/**
	 * 
	 * @return SiatConfig
	 */
	public static function buildConfig()
	{
		include dirname(__DIR__). SB_DS ."conexionSiat.php";	
		
		//echo $siat_codigoSistema;	
		return new SiatConfig([
			'nombreSistema'	=> $siat_nombreSistema,
			'codigoSistema'	=> $siat_codigoSistema,
			'tipo' 			=> $siat_tipo,
			'nit'			=> $siat_nit,
			'razonSocial'	=> $siat_razonSocial,
			'modalidad'     => ServicioSiat::MOD_COMPUTARIZADA_ENLINEA,
			// 'ambiente'      => ServicioSiat::AMBIENTE_PRUEBAS,
			'ambiente'      => conexionSiatUrl::AMBIENTE_ACTUAL,
			'tokenDelegado'	=> $siat_tokenDelegado,
			'cuis'			=> null,
			'cufd'			=> null,
		]);
	}


	public static function testCrearPuntoVenta($ciudad,$codigoSucursal,$tipoPuntoVenta,$nombrePuntoVenta)
	{
		try
		{
			//echo "asdasd";
			$config = self::buildConfig();
			$config->validate();
			
			$codigoPuntoVenta = 0;
			
			$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$servCodigos->setConfig((array)$config);


			$resCuis = $servCodigos->cuis($codigoPuntoVenta, $codigoSucursal);
			// print_r($resCuis);
			$cuis=$resCuis->RespuestaCuis->codigo;
			// echo "**".$cuis."**";

			$service = new ServicioOperaciones($cuis, null, $config->tokenDelegado);
			$service->setConfig((array)$config);

			$service->debug = true;			
			$res = $service->registroPuntoVenta($codigoSucursal,$tipoPuntoVenta,$nombrePuntoVenta);

			$codigoPuntoVentaNuevo=$res->RespuestaRegistroPuntoVenta->codigoPuntoVenta;
			print_r($res);
			if((int)$codigoPuntoVentaNuevo>0){

				require dirname(__DIR__). SB_DS ."../../conexionmysqli2.inc";
				$sqlDelete="DELETE FROM siat_puntoventa where cod_ciudad=".$ciudad;				

				mysqli_query($enlaceCon,$sqlDelete);
				$sqlInsert="INSERT INTO siat_puntoventa (cod_ciudad,codigoPuntoVenta) VALUES ('$ciudad','$codigoPuntoVentaNuevo')";
				//echo $sqlInsert;
				mysqli_query($enlaceCon,$sqlInsert);

			}				
				
		}
		catch(\Exception $e)
		{
			echo  $e->getMessage(), "\n\n";
			//return  $e->getMessage();
		}
		
	}

	public static function testCerrarPuntoVenta($ciudad,$codigoSucursal)
	{
		try
		{
			//echo "asdasd";
			$config = self::buildConfig();
			$config->validate();
			
			$codigoPuntoVenta = 0;
			
			$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$servCodigos->setConfig((array)$config);


			$resCuis = $servCodigos->cuis($codigoPuntoVenta, $codigoSucursal);
			print_r($resCuis);
			$cuis=$resCuis->RespuestaCuis->codigo;

			$service = new ServicioOperaciones($cuis, null, $config->tokenDelegado);
			$service->setConfig((array)$config);

			$service->debug = true;			
			$res = $service->consultaPuntoVenta($codigoSucursal);
			
			foreach ($res->RespuestaConsultaPuntoVenta->listaPuntosVentas as $lista) {
				$codPuntoVen=$lista->codigoPuntoVenta;
				$res2 = $service->cierrePuntoVenta($codigoSucursal,$codPuntoVen);
			}

			require dirname(__DIR__). SB_DS ."../../conexionmysqli2.inc";
			$sqlDelete="DELETE FROM siat_puntoventa where cod_ciudad=".$ciudad;	
			mysqli_query($enlaceCon,$sqlDelete);				
				
		}
		catch(\Exception $e)
		{
			echo  $e->getMessage(), "\n\n";
			//return  $e->getMessage();
		}
		
	}
}


<?php
define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class CuisTest
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


	public static function testCuis($ciudad,$codigoSucursal,$codigoPuntoVenta)
	{
		try
		{
			//echo "asdasd";
			$config = self::buildConfig();
			$config->validate();			
			
			$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$servCodigos->setConfig((array)$config);

			//echo "DATOS:".$codigoPuntoVenta."_".$codigoSucursal;
			$resCuis = $servCodigos->cuis($codigoPuntoVenta, $codigoSucursal);			
			//print_r($resCuis);
			$cuis=$resCuis->RespuestaCuis->codigo;

			//
			require dirname(__DIR__). SB_DS ."../../conexionmysqli2.inc";
			$yearActual=date("Y");
			$sql="select cuis from siat_cuis where cod_ciudad='$ciudad' and cod_gestion='$yearActual' and estado=1";
			$resp=mysqli_query($enlaceCon,$sql);
			$dat=mysqli_fetch_array($resp);
			$cuisAnt=$dat[0];
			//echo $cuisAnt." - ".$cuis;
			if($cuisAnt==""||$cuisAnt!=$cuis){
				//echo "ENTRO!!!";
				if($cuisAnt!=$cuis){
					$sqlUpdate="UPDATE siat_cuis SET estado=0 where cod_ciudad='$ciudad' and cod_gestion='$yearActual' and estado=1;";
					mysqli_query($enlaceCon,$sqlUpdate);
				}
				$sqlInsert="INSERT INTO siat_cuis (cuis,cod_gestion,cod_ciudad,created_by,created_at,estado) VALUES ('$cuis','$yearActual','$ciudad','0',NOW(),1)";
				mysqli_query($enlaceCon,$sqlInsert);
			}											
		}
		catch(\Exception $e)
		{
			echo  $e->getMessage(), "\n\n";
			//return  $e->getMessage();
		}
		
	}

}


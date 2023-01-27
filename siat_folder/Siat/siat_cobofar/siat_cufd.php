<?php
define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class CufdTest
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


	public static function testCufd($ciudad,$codigoSucursal,$codigoPuntoVenta)
	{
		try
		{
			$config = self::buildConfig();
			$config->validate();			
			
			$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$servCodigos->setConfig((array)$config);

			//
			require dirname(__DIR__). SB_DS ."../../conexionmysqli2.inc";

			$resCuis = $servCodigos->cuis($codigoPuntoVenta, $codigoSucursal);
			$cuis = $resCuis->RespuestaCuis->codigo;	

			$fechaActual=date("Y-m-d");
			//estado=1 para que solo busque los activos caso: eventos significativos
			$sql="select cufd from siat_cufd where cod_ciudad='$ciudad' and fecha='$fechaActual' and estado=1 and cuis='$cuis'";
			// echo $sql;
			$resp=mysqli_query($enlaceCon,$sql);
			$dat=mysqli_fetch_array($resp);
			$cufdAnt=$dat[0];
			//echo "CUFD:".$cufdAnt;
			if($cufdAnt==""){									
				$servCodigos->cuis = $cuis;		
				//$servCodigos->cuis = 'C5ACBC6F';
				$resCufd = $servCodigos->cufd($codigoPuntoVenta, $codigoSucursal);				
				$cufd=$resCufd->RespuestaCufd->codigo;
				if($cufdAnt=="" && $cufd!=""){
					//echo $cufd;
					$sqlUpdate="UPDATE siat_cufd SET estado=0 where cod_ciudad='$ciudad' and fecha='$fechaActual' and cuis='$cuis' and estado=1;";
					mysqli_query($enlaceCon,$sqlUpdate);
					$control=$resCufd->RespuestaCufd->codigoControl;
					$sqlInsert="INSERT INTO siat_cufd (cufd,codigo_control,fecha,cod_ciudad,created_by,created_at,estado,cuis) VALUES ('$cufd','$control','$fechaActual','$ciudad','0',NOW(),1,'$cuis')";
					mysqli_query($enlaceCon,$sqlInsert);					
				}
			}														

		}
		catch(\Exception $e)
		{
			echo  $e->getMessage(), "\n\n";
			//return  $e->getMessage();
		}
		
	}

}


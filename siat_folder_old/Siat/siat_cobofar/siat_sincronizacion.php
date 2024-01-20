<?php
if (!defined('BASEPATH')) define('BASEPATH', dirname(__DIR__));
defined('SB_DS') or define('SB_DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . SB_DS . 'functions.php';
sb_siat_autload();

use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionSincronizacion;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioSiat;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\Services\ServicioFacturacionCodigos;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\SiatConfig;
use SinticBolivia\SBFramework\Modules\Invoices\Classes\Siat\conexionSiatUrl;

class SyncTest
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
			 //print_r($res);
			return $res->RespuestaFechaHora->fechaHora;
			
		}
		catch(\Exception $e)
		{
			//echo  $e->getMessage(), "\n\n";
			return  $e->getMessage();
		}
		
	}

	public static function testSyncInsert($action)
	{
		try
		{
			$config = self::buildConfig();
			$config->validate();
			
			$servCodigos = new ServicioFacturacionCodigos(null, null, $config->tokenDelegado);
			$servCodigos->setConfig((array)$config);
			$resCuis = $servCodigos->cuis();
			// print_r($resCuis);
			$sync = new ServicioFacturacionSincronizacion($resCuis->RespuestaCuis->codigo, null, $config->tokenDelegado);
			$sync->setConfig((array)$config);
			$res = call_user_func([$sync, $action]);
			// print_r($res);
			
			require dirname(__DIR__). SB_DS ."../../conexionmysqli2.inc";

			switch ($action) {
				case 'sincronizarActividades':					

					$lista=$res->RespuestaListaActividades->listaActividades;
					if(count($lista)==1){
						$lista=$res->RespuestaListaActividades;
					}
					// $lista=$res->RespuestaListaActividades;
					// print_r($lista);
					$sqlDelete="DELETE FROM siat_sincronizaractividades";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						if(isset($li->codigoCaeb) && isset($li->descripcion)){
							$sqlInsert="INSERT INTO siat_sincronizaractividades (codigoCaeb,descripcion,tipoActividad,created_at) VALUES ('$li->codigoCaeb','$li->descripcion','$li->tipoActividad',NOW())";
						}
						// echo $sqlInsert;
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarListaActividadesDocumentoSector':
					$lista=$res->RespuestaListaActividadesDocumentoSector->listaActividadesDocumentoSector;
					$sqlDelete="DELETE FROM siat_sincronizarlistaactividadesdocumentosector";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarlistaactividadesdocumentosector (codigoActividad,codigoDocumentoSector,tipoDocumentoSector,created_at) VALUES ('$li->codigoActividad','$li->codigoDocumentoSector','$li->tipoDocumentoSector',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarListaLeyendasFactura':
					$lista=$res->RespuestaListaParametricasLeyendas->listaLeyendas;
					$sqlDelete="DELETE FROM siat_sincronizarlistaleyendasfactura where estado!=1 or estado is null ";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlVeri="select IFNULL(codigo,0) from siat_sincronizarlistaleyendasfactura where codigoActividad='$li->codigoActividad' and descripcionLeyenda='$li->descripcionLeyenda' limit 1";
						$respVeri=mysqli_query($enlaceCon,$sqlVeri);
						$estadoVeri=mysqli_result($respVeri,0,0);	
						if($estadoVeri==0){
							$sqlInsert="INSERT INTO siat_sincronizarlistaleyendasfactura (codigoActividad,descripcionLeyenda,created_at,estado) VALUES ('$li->codigoActividad','$li->descripcionLeyenda',NOW(),1)";
							mysqli_query($enlaceCon,$sqlInsert);							
						}
					}
				break;
				case 'sincronizarListaMensajesServicios':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarlistamensajesservicios";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarlistamensajesservicios (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarListaProductosServicios':
					$lista=$res->RespuestaListaProductos->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarlistaproductosservicios";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarlistaproductosservicios (codigoActividad,codigoProducto,descripcionProducto,created_at) VALUES ('$li->codigoActividad','$li->codigoProducto','$li->descripcionProducto',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaEventosSignificativos':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricaeventossignificativos";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricaeventossignificativos (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaMotivoAnulacion':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricamotivoanulacion";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricamotivoanulacion (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaTipoDocumentoIdentidad':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricatipodocumentoidentidad";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricatipodocumentoidentidad (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaTipoDocumentoSector':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricatipodocumentosector";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricatipodocumentosector (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaTipoEmision':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricatipoemision";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricatipoemision (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaTipoMetodoPago':
					$lista=$res->RespuestaListaParametricas->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricatipometodopago";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricatipometodopago (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;
				case 'sincronizarParametricaTipoMoneda':
					//$lista=$res->RespuestaListaParametricas->listaCodigos;
					$lista=$res->listaCodigos;
					$sqlDelete="DELETE FROM siat_sincronizarparametricatipomoneda";
					mysqli_query($enlaceCon,$sqlDelete);
					foreach ($lista as $li) {
						$sqlInsert="INSERT INTO siat_sincronizarparametricatipomoneda (codigoClasificador,descripcion,created_at) VALUES ('$li->codigoClasificador','$li->descripcion',NOW())";
						mysqli_query($enlaceCon,$sqlInsert);
					}
				break;				
				default:
					// code...
					break;
			}

			
		}
		catch(\Exception $e)
		{
			echo  $e->getMessage(), "\n\n";
			//return  $e->getMessage();
		}
		
	}
}


//sincronizarActividades
//sincronizarListaActividadesDocumentoSector
//sincronizarListaLeyendasFactura
//sincronizarListaMensajesServicios
//sincronizarListaProductosServicios
//sincronizarParametricaEventosSignificativos
//sincronizarParametricaMotivoAnulacion
//sincronizarParametricaTipoDocumentoIdentidad
//sincronizarParametricaTipoDocumentoSector
//sincronizarParametricaTipoEmision
//sincronizarParametricaTipoMetodoPago
//sincronizarParametricaTipoMoneda

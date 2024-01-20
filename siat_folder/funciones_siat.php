<?php
require "Siat/siat_cobofar/siat_facturacionoffline.php";   
date_default_timezone_set('America/La_Paz');

function obtenerFechasEmisionFacturas($string_codigos,$cod_almacen,$fecha,$siat_codigocufd){
  $sql="SELECT max(s.siat_fechaemision)as fin,min(s.siat_fechaemision)as inicio
    FROM salida_almacenes s 
    WHERE s.cod_salida_almacenes in ($string_codigos) and s.cod_almacen=$cod_almacen and DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d') = '$fecha' and s.siat_codigocufd=$siat_codigocufd";
    // echo  $sql;
    $valor=0;
    // require("../../conexionmysqli.inc");
    require dirname(__DIR__)."/conexionmysqli.inc";
    $resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){ 
      $inicio_x=$row['inicio'];
      $fin_x=$row['fin'];
      $inicio_x=explode("T", $inicio_x);
      $inicio=$inicio_x[1];
      $fin_x=explode("T", $fin_x);
      $fin=$fin_x[1];
    }
    return array($inicio,$fin);
}

function solicitudEventoSignificativo($codigoClasificador,$descripcion,$codigoPuntoVenta,$codigoSucursal,$cufd,$cufdAntiguo,$fechaFin,$fechaInicio,$cuis){
  $eventoSignificativo= new FacturacionOffLine();
  $resEvent=$eventoSignificativo::RecepcionEvento($codigoClasificador,$descripcion,$codigoPuntoVenta,$codigoSucursal,$cufd,$cufdAntiguo,$fechaInicio,$fechaFin,$cuis);
 	return array($resEvent[0],$resEvent[1]);
}

function obtenerCuis_vigente_BD($cod_ciudad,$cod_entidad){
	$sql="SELECT cuis from siat_cuis where cod_ciudad=$cod_ciudad and estado=1 order by codigo desc limit 1";
  //echo "sql CUIS VIGENTE: ".$sql;    
  $valor="0";
  // require("../../conexionmysqli2.php");
  require dirname(__DIR__)."/conexionmysqli2.php";
  $resp=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp)){ 
    $valor=$row['cuis'];
  }
  // echo $valor;
  return $valor;
}

function obtenerCufd_vigente_BD($cod_ciudad,$fecha,$cuis){
	$sql="select cufd from siat_cufd where cod_ciudad=$cod_ciudad and fecha = '$fecha' and estado=1 and cuis='$cuis' AND (cufd <> '' or cufd <> null)";
	 	   // echo $sql;
  $valor="0";
  // require("../../conexionmysqli2.php");
  require dirname(__DIR__)."/conexionmysqli2.php";
  $resp=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp)){ 
    $valor=$row['cufd'];
  }
  // echo $valor;
  return $valor;
}
function obtenerEventosignificativo_BD($codigoMotivoEvento,$codigoPuntoVenta,$cod_impuestos,$fecha_fin,$fecha_inicio,$cuis=0){
  $sql="select codigoRecepcionEventoSignificativo from siat_eventos where codigoMotivoEvento='$codigoMotivoEvento' and codigoPuntoVenta='$codigoPuntoVenta' and cod_cuis='$cuis' and fechaHoraInicioEvento <= '$fecha_inicio' and  '$fecha_fin'<=fechaHoraFinEvento ";//and codigoRecepcionPaquete is null
        // echo $sql;
  $valor="-1";
  $sw=0;
  // require("../../conexionmysqli2.php");
  require dirname(__DIR__)."/conexionmysqli2.php";
  $resp=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp)){ 
    $valor=$row['codigoRecepcionEventoSignificativo'];
    $sw=1;
  }
  // echo $valor;
  return array($valor,$sw);
}



function obtenerPuntoVenta_BD($cod_ciudad){



  $sql="select codigoPuntoVenta from siat_puntoventa where cod_ciudad=$cod_ciudad";
       // echo $sql;
  $valor="0";
  // require("../conexionmysqli2.php");
  require dirname(__DIR__)."/conexionmysqli2.php";   
  $resp=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp)){ 
    $valor=$row['codigoPuntoVenta'];
  }
  // echo $valor;
  return $valor;
}


function obtenerCufd_anterior_BD($cod_ciudad,$fecha,$cuis){
	$sql="select cufd from siat_cufd where cod_ciudad=$cod_ciudad and fecha='$fecha' and cuis='$cuis' AND cufd <> '' or cufd <> null ";
	 // echo $sql;
  $valor="0";
  // require("../../conexionmysqli2.php");
  require dirname(__DIR__)."/conexionmysqli2.php";
  // require dirname(__DIR__)."/conexionmysqli2.php";
  $resp=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp)){ 
    $valor=$row['cufd'];
  }
  // echo $valor;
  return $valor;
}

function solicitudRecepcionPaquetes($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis,$codigoEvento,$tipo,$nuevo_cuf){
  $recepcionFactura= new FacturacionOffLine();
  $resEvent=$recepcionFactura::RecepcionPaqueteFactura($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis,$codigoEvento,$tipo,$nuevo_cuf);
 	return array($resEvent[0],$resEvent[1],$resEvent[2]);
}
function obtenerCuis_siat($codigoPuntoVenta,$codigoSucursal){
  $cuis= new FacturacionOffLine();
  $rescuis=$cuis::SolicitudCuis($codigoPuntoVenta,$codigoSucursal);
 	return $rescuis;
}

function obtenerFechaHoraSiat(){
  //echo "asdasd";
   require "Siat/siat_cobofar/siat_sincronizacion.php";   
   $sincro= new SyncTest();
   return $sincro::testSync('sincronizarFechaHora');
}

function sincronizarParametrosSiat($act = "",$cod_entidad){    
   require "Siat/siat_cobofar/siat_sincronizacion.php";   
   $sincro= new SyncTest();
   if($act!=""){
      $sincro::testSyncInsert($act,$cod_entidad);
   }else{
       $sincro::testSyncInsert('sincronizarActividades',$cod_entidad);
       $sincro::testSyncInsert('sincronizarListaActividadesDocumentoSector',$cod_entidad);
       $sincro::testSyncInsert('sincronizarListaLeyendasFactura',$cod_entidad);
       $sincro::testSyncInsert('sincronizarListaMensajesServicios',$cod_entidad);
       $sincro::testSyncInsert('sincronizarListaProductosServicios',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaEventosSignificativos',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaMotivoAnulacion',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaTipoDocumentoIdentidad',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaTipoDocumentoSector',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaTipoEmision',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaTipoMetodoPago',$cod_entidad);
       $sincro::testSyncInsert('sincronizarParametricaTipoMoneda',$cod_entidad);
   }   
}
function abrirPuntoVenta($ciudad,$codigoSucursal,$tipoPuntoVenta,$nombrePuntoVenta,$cod_entidad){
   require "Siat/siat_cobofar/siat_puntoventa.php";  
   $punto= new PuntoVentaTest();
   $punto::testCrearPuntoVenta($ciudad,$codigoSucursal,$tipoPuntoVenta,$nombrePuntoVenta,$cod_entidad);
}

function cerrarPuntoVenta($ciudad,$codigoSucursal,$cod_entidad){
   require "Siat/siat_cobofar/siat_puntoventa.php";  
   $punto= new PuntoVentaTest();
   $punto::testCerrarPuntoVenta($ciudad,$codigoSucursal,$cod_entidad);
}

function obtenerCantidadPuntosVenta($codTipo,$globalEmpresa){
    $sql="SELECT count(*) from ciudades where cod_empresa=$globalEmpresa";
    $total=0;
    require dirname(__DIR__)."/conexionmysqli2.inc";    
    $resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){ 
      $total=$row[0];
    }
    $abiertos=0;
    $sql="SELECT count(*) from siat_puntoventa where cod_empresa=$globalEmpresa";
    $resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){ 
      $abiertos=$row[0];
    }

    if($codTipo==1){
      return $abiertos;  
    }else{
      return $total-$abiertos;
    }
}

function generarCuis($ciudad,$codigoSucursal,$codigoPuntoVenta,$cod_entidad){
   require_once "Siat/siat_cobofar/siat_cuis.php";
   $test= new CuisTest();
   $test::testCuis($ciudad,$codigoSucursal,$codigoPuntoVenta,$cod_entidad);
}
function generarCufd($ciudad,$codigoSucursal,$codigoPuntoVenta,$cod_entidad){
   require_once "Siat/siat_cobofar/siat_cufd.php";  
   $test= new CufdTest();
   $test::testCufd($ciudad,$codigoSucursal,$codigoPuntoVenta,$cod_entidad);
}

function deshabilitarCufd($cod_ciudad,$cuis,$fecha_X,$cod_entidad){
   
  
   // echo $sql;
  $valor="0";
  // require("../../conexionmysqli2.php");
  require dirname(__DIR__)."/conexionmysqli2.php";
  $sqlUpdate="UPDATE siat_cufd SET estado=0 where cod_ciudad='$cod_ciudad' and fecha='$fecha_X' and cuis='$cuis' and estado=1;";
  mysqli_query($enlaceCon,$sqlUpdate);

  return $valor;

}

function generarFacturaVentaImpuestos($codSalidaSucursal,$ex = false,$online_siat = 1){
    require_once "Siat/siat_cobofar/siat_factura_online.php";  
    $factura= new FacturaOnline();
    return $factura::testRecepcionFacturaElectronica($codSalidaSucursal,1,$ex,$online_siat);   
}
function generarXMLFacturaVentaImpuestos($codSalidaSucursal){
    require_once "Siat/siat_cobofar/siat_factura_online.php";  
    $factura= new FacturaOnline();
    return $factura::testRecepcionFacturaElectronica($codSalidaSucursal,-1); //-1 PARA DEVOLVER XML   
}

function anulacionFactura_siat($codigoPuntoVenta,$codigoSucursal,$cuis,$cufd,$cuf){
  require_once "Siat/siat_cobofar/siat_factura_online.php"; 
  // echo "***";
  $FacturaOnline= new FacturaOnline();
  $resFac=$FacturaOnline::anularFacturaEnviada($codigoPuntoVenta,$codigoSucursal,$cuis,$cufd,$cuf);
  return array($resFac[0],$resFac[1]);
}

function verificarNitClienteSiat($nit,$cod_ciduad=1){
  require_once "Siat/siat_cobofar/siat_factura_online.php";   
  $factura= new FacturaOnline();
  return $factura::verificarNitCliente($nit,$cod_ciduad);
}
function verificarEstadoFactura($codVenta,$global_agencia=null){
  require_once "Siat/siat_cobofar/siat_factura_online.php";   
  $factura= new FacturaOnline();
  return $factura::verificarEstadoFactura($codVenta,$global_agencia);  
}


function ultimaHoraActualizacion($act,$globalEntidad){
  $act=strtolower($act);
  $sql="SELECT MAX(created_at) from siat_".$act." where cod_entidad=$globalEntidad";
  //echo $sql;
    $fecha="";
    require dirname(__DIR__)."/conexionmysqli2.inc";    
    $resp=mysqli_query($enlaceCon,$sql);
    while($row=mysqli_fetch_array($resp)){ 
      $fecha=date("d/m/Y H:i:s",strtotime($row[0]));
    }
    return $fecha;
}

function verificarConexion(){
  require_once "Siat/siat_cobofar/siat_factura_online.php";   
  $factura= new FacturaOnline();
  $resFac=$factura::verificarConexion();
  return array($resFac[0],$resFac[1]);  
}

function consultaEventoSignificativo($fechaEvento,$global_agencia=null){
  // require_once "Siat/siat_cobofar/siat_factura_online.php";   
  // $factura= new FacturaOnline();
  $eventoSignificativo= new FacturacionOffLine();
  return $eventoSignificativo::consultaEventoSignificativo($fechaEvento,$global_agencia);  
}

?>
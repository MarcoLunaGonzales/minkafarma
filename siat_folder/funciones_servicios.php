<?php
function verificarCUFDEmpresa($idEmpresa,$nitEmpresa,$codSucursal,$enlaceCon){
    // require_once '../conexionmysqli2.php';  
    $fechaActual=date("Y-m-d");
    $cons = "SELECT count(*) from siat_cufd sc, siat_puntoventa sp, datos_empresa de where de.cod_empresa=sp.cod_entidad and sc.cod_ciudad=sp.cod_ciudad and sp.cod_ciudad='$codSucursal' and sc.fecha='$fechaActual' and sc.estado=1 and de.nit='$nitEmpresa' and de.cod_empresa='$idEmpresa';";
    $respCons = mysqli_query($enlaceCon,$cons);    
    $valor=0;
    while ($datCons = mysqli_fetch_array($respCons)) {
        $valor=$datCons[0];        
    }

    return $valor;
}

function verificarExistenciaEmpresa($idEmpresa,$nitEmpresa,$enlaceCon){  
  require_once '../conexionmysqli2.php';  
  $cons = "SELECT cod_empresa from datos_empresa where nit='$nitEmpresa' and cod_empresa='$idEmpresa'";
  $respCons = mysqli_query($enlaceCon,$cons);
  $value=false;
  while ($datCons = mysqli_fetch_array($respCons)) {
    $value=true;
  }
  return $value;
}

function verificarComunicacion($idEmpresa,$nitEmpresa){
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');  
  require_once '../siat_folder/funciones_siat.php';
  $DatosConexion=verificarConexion();
  // if($DatosConexion[0]==1){
  //   return array(2,'Conexion Establecida');
  // }else{
  //   return array(2,'Error en serivcio SIAT');
  // }
  return $DatosConexion;
}
function sincronizarParametros($act,$cod_entidad,$enlaceCon){    
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');  
    // require_once '../siat_folder/funciones_siat.php';
    // sincronizarParametrosSiat($act,$cod_entidad);//sincroizamos desde el siat
    // echo $act;
    switch ($act) {
        case 'sincronizarActividades':                      
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizaractividades where cod_entidad=$cod_entidad order by codigo;";
               // echo $sql;
        break;
        case 'sincronizarListaActividadesDocumentoSector':
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarlistaactividadesdocumentosector where cod_entidad=$cod_entidad order by codigo;";
                
        break;
        case 'sincronizarListaLeyendasFactura':
                 $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarListaLeyendasFactura where (codigoActividad=$cod_entidad order by codigo;";
        break;
        case 'sincronizarListaMensajesServicios':
            $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarlistamensajesservicios where cod_entidad=$cod_entidad order by codigo;";
                
               
        break;
        case 'sincronizarListaProductosServicios':
            $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarlistaproductosservicios where cod_entidad=$cod_entidad order by codigo;";
                
             
        break;
        case 'sincronizarParametricaEventosSignificativos':
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricaeventossignificativos where cod_entidad=$cod_entidad order by codigo;";
                
                
        break;
        case 'sincronizarParametricaMotivoAnulacion':
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricamotivoanulacion where cod_entidad=$cod_entidad order by codigo;";
                
             
        break;
        case 'sincronizarParametricaTipoDocumentoIdentidad':
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricatipodocumentoidentidad where cod_entidad=$cod_entidad order by codigo;";
        break;
        case 'sincronizarParametricaTipoDocumentoSector':
            $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricatipodocumentosector where cod_entidad=$cod_entidad order by codigo;";
                
              
        break;
        case 'sincronizarParametricaTipoEmision':
            $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricatipoemision where cod_entidad=$cod_entidad order by codigo;";
                
              
        break;
        case 'sincronizarParametricaTipoMetodoPago':
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricatipometodopago where cod_entidad=$cod_entidad order by codigo;";
        break;
        case 'sincronizarParametricaTipoMoneda':
             $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricatipomoneda where cod_entidad=$cod_entidad order by codigo;";
        break;              
        default:
            // code...
            break;
    }
    // $sql="SELECT codigo,codigoClasificador,descripcion from siat_sincronizarparametricatipometodopago where cod_entidad=$cod_entidad order by codigo;";
    // echo $sql;
    $resp=mysqli_query($enlaceCon,$sql);
    $ff=0;
    $datos=[];
    while ($dat = mysqli_fetch_array($resp)) {
        $datos[$ff]['codigo']=$dat['codigo'];
        $datos[$ff]['codigoClasificador']=$dat['codigoClasificador'];
        $datos[$ff]['descripcion']=$dat['descripcion'];        
        $ff++;
    }    
    return $datos;
}



function generarCufd_minka($cod_entidad,$nitEmpresa,$codSucursal,$enlaceCon){
    $codigoSucursal=0;    
    $codigoPuntoVenta=0;
    $cons= "select c.cod_impuestos,(select sp.codigoPuntoVenta from siat_puntoventa sp where sp.cod_entidad=c.cod_entidad and sp.cod_ciudad=c.cod_ciudad limit 1) as codigoPuntoVenta from ciudades c where c.cod_entidad=$cod_entidad and c.cod_ciudad=$codSucursal";
    // echo $cons;
    $respCons = mysqli_query($enlaceCon,$cons);    
    while ($datCons = mysqli_fetch_array($respCons)) {
        $codigoSucursal=$datCons[0];
        $codigoPuntoVenta=$datCons[1];
    }
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');  
  require_once '../siat_folder/funciones_siat.php';
    $cuis=obtenerCuis_vigente_BD($codSucursal,$cod_entidad);
    deshabilitarCufd($codSucursal,$cuis,date('Y-m-d'),$cod_entidad);
    generarCufd($codSucursal,$codigoSucursal,$codigoPuntoVenta,$cod_entidad);
    $banderaCUFD=verificarCUFDEmpresa($cod_entidad,$nitEmpresa,$codSucursal,$enlaceCon);
    // $resultado="";    
    // if($banderaCUFD>=1){
    //     $resultado=array("estado"=>1,
    //         "mensaje"=>"Correcto. CUFD Valido para la sucursal.");
    // }elseif($banderaCUFD==0){
    //     $resultado=array("estado"=>2,
    //         "mensaje"=>"No existe el CUFD Actual para la sucursal solicitada.");
    // }
  return $banderaCUFD;
}

?>
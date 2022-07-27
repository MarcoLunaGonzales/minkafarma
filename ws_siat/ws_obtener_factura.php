<?php
require "funciones.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = json_decode(file_get_contents("php://input"), true); 
    $accion=NULL;
    if(isset($datos['accion'])&&isset($datos['sIdentificador'])&&isset($datos['sKey']))
        if($datos['sIdentificador']=="farma_online"&&$datos['sKey']=="RmFyYl9pdF8wczIwMjI="&&obtenerTokenAleatorio($datos['sToken'])==$datos['sToken']){
        $accion=$datos['accion']; //recibimos la accion
        $estado=0;
        $mensaje="";
        $existeFuncion=0;
        switch ($accion) {
          case 'listadoFacturasCliente':
            $datosResp=obtenerListadoFacturasCliente(0,"","","",$datos['cliente']);$existeFuncion=1; 
          break;
          case 'listadoFacturasClienteBuscar':
            $datosResp=obtenerListadoFacturasCliente(0,$datos['nro'],$datos['del'],$datos['al'],$datos['cliente']);$existeFuncion=1; 
          break;
          case 'obtenerFacturaXmlPdf':
            $datosResp=obtenerFacturaDatos($datos['codVenta'],$datos['sucursal']);$existeFuncion=1; 
          break;
        }              
        if($existeFuncion>0){                                  
           if($datosResp[0]==0){
                 $estado=0;
                 $mensaje = "Lista Vacia";
                 $resultado=array("estado"=>$estado, 
                            "mensaje"=>$mensaje, 
                            "totalComponentes"=>0);
           }else{
                  $estado=1;
                  $lista = $datosResp[1]; 
                  $resultado=array(
                            "estado"=>$estado,
                            "mensaje"=>"Lista Obtenida Correctamente", 
                            "lista"=>$lista, 
                            "totalComponentes"=>count($lista)     
                            );
            }
          }else{
              $resultado=array("estado"=>3, 
                            "mensaje"=>"Error de funcion");
          }
        }else{
            $resultado=array("estado"=>4, 
                            "mensaje"=>"Credenciales Incorrectas");
        }
            header('Content-type: application/json');
            echo json_encode($resultado);
}else{
    $resp=array("estado"=>5, 
                "mensaje"=>"El acceso al WS es incorrecto");
    header('Content-type: application/json');
    echo json_encode($resp);    
}
write_visita();



function obtenerListadoFacturasCliente($codVenta,$nroFactura,$del,$al,$cliente){    
  require_once __DIR__.'/../conexionmysqli2.inc';
  $sqlQuery="";
  $limitFactura=" limit 15 ";
  if($codVenta!=0){
    $sqlQuery=" and s.cod_salida_almacenes='$codVenta' ";
    $limitFactura="";
  }else{
    if($nroFactura!=""){
        $sqlQuery.=" and s.nro_correlativo='$nroFactura' ";
        $limitFactura=" limit 15 ";
    }
    if($del!=""&&$al!=""&&$del==$al){
        $sqlQuery.=" and STR_TO_DATE(s.siat_fechaemision, '%Y-%m-%d') ='$del' ";
            $limitFactura=" limit 15 ";
    }else{
        if($del!=""){
            $sqlQuery.=" and STR_TO_DATE(s.siat_fechaemision, '%Y-%m-%d')>='$del' ";
            $limitFactura=" limit 15 ";
        }
        if($al!=""){
            $sqlQuery.=" and STR_TO_DATE(s.siat_fechaemision, '%Y-%m-%d')<='$al' ";
            $limitFactura=" limit 15 ";
        }        
    }
    //validar 
  }

  $consulta = "SELECT s.cod_salida_almacenes,s.nro_correlativo,s.razon_social,s.siat_fechaemision,c.descripcion,s.monto_final,c.cod_ciudad,s.siat_cuf,s.salida_anulada,c.nombre_ciudad,s.siat_codigotipoemision from salida_almacenes s join almacenes a on a.cod_almacen=s.cod_almacen join ciudades c on c.cod_ciudad=a.cod_ciudad where  s.cod_cliente='".$cliente."' and s.siat_cuf!=''   $sqlQuery  order by s.siat_fechaemision desc $limitFactura ";
  $resp = mysqli_query($enlaceCon,$consulta);
  $ff=0;
  $datos=[];
  while ($dat = mysqli_fetch_array($resp)) {
        $datos[$ff]['cod_salida_almacenes']=$dat['cod_salida_almacenes'];
        $datos[$ff]['nro_correlativo']=$dat['nro_correlativo'];
        $datos[$ff]['razon_social']=$dat['razon_social'];
        $datos[$ff]['monto_final']=$dat['monto_final'];
        $datos[$ff]['siat_fechaemision']=$dat['siat_fechaemision'];
        $datos[$ff]['descripcion']=$dat['nombre_ciudad'];
        $datos[$ff]['cod_ciudad']=$dat['cod_ciudad'];
        $datos[$ff]['siat_cuf']=$dat['siat_cuf'];
        $datos[$ff]['salida_anulada']=$dat['salida_anulada'];
        $datos[$ff]['siat_codigotipoemision']=$dat['siat_codigotipoemision'];
     $ff++;    
  }
  return array($ff,$datos);
}

function obtenerFacturaDatos($codVenta,$ciudad){
    require_once __DIR__."/../conexionmysqli2.inc";
    require_once __DIR__."/../siat_folder/funciones_siat.php";  
    $facturaImpuestos=generarXMLFacturaVentaImpuestos($codVenta);   

    $_COOKIE["global_agencia"]=$ciudad;
    $_GET["codigo_salida"]=$codVenta;
    $pdfFactura="";
    $home=1;
    ob_start();
    include __DIR__."/../dFacturaElectronicaAllPdf.php";
    $html = ob_get_clean();
    //error_reporting(E_ALL);
    $sqlDatosVenta="select s.siat_cuf
            from `salida_almacenes` s
            where s.`cod_salida_almacenes`='$codVenta'";
    $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
    $cuf="";
    while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
        $cuf=$datDatosVenta['siat_cuf'];
    }
    $nombreFile=__DIR__."/../siat_folder/Siat/temp/Facturas-XML/$cuf.pdf";
    unlink($nombreFile);    
    guardarPDFArqueoCajaVerticalFactura($cuf,$html,$nombreFile,$codVenta);
    $pdfFactura = base64_encode(file_get_contents($nombreFile));
    unlink($fileName); //borrar qr
    return array(1,array("xml"=>$facturaImpuestos,"pdf"=>$pdfFactura));
}


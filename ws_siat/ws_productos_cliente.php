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
          case 'listadoProductosFacturasCliente':
            $datosResp=obtenerListadoProductosCliente($datos['cliente'],0);$existeFuncion=1; 
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



function obtenerListadoProductosCliente($cliente,$codProducto){    
  require_once __DIR__.'/../conexionmysqli2.inc';
  $sqlQuery="";
  $limitFactura=" limit 15 ";
  if($codProducto!=0){
    $sqlQuery=" and sd.cod_material='$codProducto' ";
    $limitFactura="";
  }
  $consulta="SELECT m.codigo_material,m.descripcion_material,m.cantidad_presentacion,sum(sd.cantidad_unitaria) as compra_cantidad,sum(sd.cantidad_unitaria/m.cantidad_presentacion) as compra_cantidad_caja ,
  (SELECT nombre_proveedor from proveedores where cod_proveedor=(select cod_proveedor from proveedores_lineas where cod_linea_proveedor=m.cod_linea_proveedor)) as nombre_proveedor
  from salida_detalle_almacenes sd 
      join material_apoyo m on m.codigo_material=sd.cod_material  
  where sd.cod_salida_almacen in (SELECT CONCAT(s.cod_salida_almacenes,',') FROM salida_almacenes s WHERE s.cod_cliente='$cliente' and s.salida_anulada=0 and s.cod_tiposalida=1001 and s.siat_cuf!='') $sqlQuery GROUP BY sd.cod_material order by 5 desc $limitFactura;";

  $resp = mysqli_query($enlaceCon,$consulta);
  $ff=0;
  $datos=[];
  while ($dat = mysqli_fetch_array($resp)) {
        $datos[$ff]['codigo_material']=$dat['codigo_material'];
        $datos[$ff]['descripcion_material']=$dat['descripcion_material'];
        $datos[$ff]['compra_cantidad']=$dat['compra_cantidad'];
        $datos[$ff]['compra_cantidad_caja']=$dat['compra_cantidad_caja'];
        $datos[$ff]['cantidad_presentacion']=$dat['cantidad_presentacion'];
        $datos[$ff]['nombre_proveedor']=$dat['nombre_proveedor'];
     $ff++;    
  }
  return array($ff,$datos);
}


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
          case 'login':
            $datosResp=iniciarSesionClienteOnLine($datos['usuario'],$datos['clave']);$existeFuncion=1; 
          break;
          case 'update':
            $datosResp=actualizarContra($datos['cliente'],$datos['clave']);$existeFuncion=1; 
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



function iniciarSesionClienteOnLine($usuario_adm,$contrasena){    
  require_once __DIR__.'/../conexionmysqli2.inc';  
  $cons = "SELECT c.clave from clientes c where c.email_cliente='$usuario_adm';";
  $respCons = mysqli_query($enlaceCon,$cons);
  $contra="";    
  while ($datCons = mysqli_fetch_array($respCons)) {
    $contra=$datCons['clave'];
  }
  if($contra==""){
    $nuevo=1;
    $consulta = "SELECT c.cod_cliente,c.nombre_cliente,c.paterno from clientes c where c.email_cliente='$usuario_adm' and c.nit_cliente='$contrasena';";
  }else{
    $nuevo=0;
    $consulta = "SELECT c.cod_cliente,c.nombre_cliente,c.paterno from clientes c where c.email_cliente='$usuario_adm' and c.clave='$contrasena';";
  }
  
  $resp = mysqli_query($enlaceCon,$consulta);
  $ff=0;
  $datos=[];
  while ($dat = mysqli_fetch_array($resp)) {
        $datos[$ff]['cod_cliente']=$dat['cod_cliente'];
        $datos[$ff]['nombre_cliente']=$dat['nombre_cliente'];
        $datos[$ff]['paterno']=$dat['paterno'];
        $datos[$ff]['nuevo']=$nuevo;
     $ff++;    
  }
  return array($ff,$datos);
}


function actualizarContra($cliente,$contrasena){    
  require_once __DIR__.'/../conexionmysqli2.inc';  



  $cons = "SELECT c.clave from clientes c where c.cod_cliente='$cliente';";
  $respCons = mysqli_query($enlaceCon,$cons);
  $contra="";    
  while ($datCons = mysqli_fetch_array($respCons)) {
    $contra=$datCons['clave'];
  }
  if($contra==""){
    $nuevo=1;
    $consulta = "UPDATE clientes SET clave='$contrasena' where cod_cliente='$cliente';";
    mysqli_query($enlaceCon,$consulta);
    $consulta = "SELECT c.cod_cliente,c.nombre_cliente,c.paterno from clientes c where c.cod_cliente='$cliente' and c.clave='$contrasena';";
  }  
  $resp = mysqli_query($enlaceCon,$consulta);
  $ff=0;
  $datos=[];
  while ($dat = mysqli_fetch_array($resp)) {
        $datos[$ff]['cod_cliente']=$dat['cod_cliente'];
        $datos[$ff]['nombre_cliente']=$dat['nombre_cliente'];
        $datos[$ff]['paterno']=$dat['paterno'];
        $datos[$ff]['nuevo']=$nuevo;
     $ff++;    
  }
  return array($ff,$datos);
}
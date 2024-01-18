<?php
require("../../estilos_almacenes.inc");
require("../../conexionmysqli.inc");
require("../funciones_siat.php");

  $codigoMotivoEvento=2;
  // $nuevo_cufd=0;
  // if(isset($_POST['nuevo_cufd'])){
    $nuevo_cufd=1;//si se generará nuevo cufd
  // 
  $nuevo_cuf=0;
  if(isset($_POST['nuevo_cuf'])){
    $nuevo_cuf=1;//si se generará nuevo cuf
  }
  $descripcionX="";
  $codigoPuntoVenta=0; 
  $flagSuccess=false;
  $error=true;
  $descripcionError="NO SELECCIONADO NINGUNA FACTURA...";

  $contador_items=0;
  $string_codigos="";
  $cod_tipoEmision=2;//tipo emision OFFLINE
  $sqlCab="SELECT s.cod_salida_almacenes,a.nombre_almacen as sucursal, s.fecha, s.hora_salida, s.nro_correlativo,  
  (select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente)cliente, s.cod_tipo_doc, razon_social, nit,s.cod_tipopago,s.monto_final,s.siat_codigotipoemision
  FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen
  WHERE s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_tipo_doc=1
  and s.siat_codigotipoemision=$cod_tipoEmision and s.siat_codigoRecepcion is null
  order by a.nombre_almacen,s.nro_correlativo";
  // echo $sqlCab;
  $respCab=mysqli_query($enlaceCon,$sqlCab);
  while($rowCab=mysqli_fetch_array($respCab)){   
    $contador_items++;
    $string_codigos.=$rowCab['cod_salida_almacenes'].",";
  }
  $string_codigos=trim($string_codigos,",");

//verificamos conexion
$DatosConexion=verificarConexion();
if($DatosConexion[0]==1){
if($contador_items>0){
  
  $cod_tipoEmision=2;//tipo emision OFFLINE
  $sql="SELECT s.fecha,s.cod_almacen,a.nombre_almacen,(select cod_impuestos from ciudades where cod_ciudad= a.cod_ciudad)as cod_impuestos,a.cod_ciudad,sc.cufd
    FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen join siat_cufd sc on s.siat_codigocufd=sc.codigo
    WHERE s.cod_salida_almacenes in ($string_codigos)
    GROUP BY s.cod_almacen,s.fecha,s.siat_codigocufd
    ORDER BY a.nombre_almacen,s.fecha";
    // echo $sql;
    $fecha_X=date('Y-m-d');
  $resp1=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp1)){ 
    $fecha=$row['fecha'];
    $cod_almacen=$row['cod_almacen'];
    $nombre_almacen=$row['nombre_almacen'];
    $cod_impuestos=$row['cod_impuestos'];
    $cod_ciudad=$row['cod_ciudad'];
    // $cod_ciudad=85;
    $cod_impuestos=intval($cod_impuestos);
    $codigoPuntoVenta=obtenerPuntoVenta_BD($cod_ciudad);
    $cuis=obtenerCuis_siat($codigoPuntoVenta,$cod_impuestos);
    $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
    // $cufdEvento=obtenerCufd_anterior_BD($cod_ciudad,$fecha,$cuis);
    $cufdEvento=$row['cufd'];
    // echo "aqui";
    // $cuis=obtenerCuis_vigente_BD($cod_ciudad);
    // echo  $cuis; 
    if($cufd<>"0"){
      // echo $cufd;
      $datos_hora=obtenerFechasEmisionFacturas($string_codigos,$cod_almacen,$fecha);
      $fecha_inicio=$fecha."T".$datos_hora[0];
      $fecha_fin=$fecha."T".$datos_hora[1];
      $sw=0;
      //buscamos algun evento disponible en ese rango de fechas
      $codigoEvento_datos=obtenerEventosignificativo_BD($codigoMotivoEvento,$codigoPuntoVenta,$cod_impuestos,$fecha_fin,$fecha_inicio,$cuis);
      $codigoEvento=$codigoEvento_datos[0];
      // echo "eveto:".$codigoEvento;
      $sw=$codigoEvento_datos[1];
      $descripcionEvento=" SELECCIONADO ";      
      if($sw==0){ //  si no hay registros de Evento
        if($fecha_inicio==$fecha_fin){//solo es una factura
          $fecha_z=$fecha." ".$datos_hora[1]; 
          $fechanueva = new DateTime($fecha_z); 
          // $fechanueva->modify('-5 hours'); 
          $fechanueva->modify('+10 second'); 
          // $fechanueva->modify('-30 second'); 
          $fecha_fin=$fechanueva->format('Y-m-d H:i:s');
          $fecha_fin_datos=explode(" ", $fecha_fin);
          $fecha_fin=$fecha_fin_datos[0]."T".$fecha_fin_datos[1].".000";//agregamos milisegundos 
        }
        // if($nuevo_cufd==1){
        //   deshabilitarCufd($cod_ciudad,$cuis,$fecha_X);
        //   $cufdNuevo=generarCufd($cod_ciudad,$cod_impuestos,$codigoPuntoVenta);
        //   $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
        // }
        $respEvento=solicitudEventoSignificativo($codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis);
        // echo "<br>**".print_r($respEvento)."**<br>";
        $codigoEvento=$respEvento[0];
        $descripcionEvento=$respEvento[1];
      }
      if($codigoEvento<>-1){
        //registamos el evento
       if($sw==0){
          $sql="INSERT INTO siat_eventos(codigoMotivoEvento,codigoPuntoVenta,codigoSucursal,cufd,cufdEvento,descripcion,fechaHoraInicioEvento,fechaHoraFinEvento,codigoRecepcionEventoSignificativo) values('$codigoMotivoEvento','$codigoPuntoVenta','$cod_impuestos','$cufd','$cufdEvento','$descripcionX','$fecha_inicio','$fecha_fin','$codigoEvento')";
           // echo $sql;
          $sql_inserta = mysqli_query($enlaceCon,$sql);
        }
        //enviamos el paquete con las facturas
        $respPaquete=solicitudRecepcionPaquetes($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,null,null,$cuis,$codigoEvento,1,$nuevo_cuf);
        $codigo=$respPaquete[0];
        $descripcionPaquete=$respPaquete[1];
        $descripcionValidacion=$respPaquete[2];
        if($codigo==1){
          $error=false;
          $descripcionError="<b>Evento:</b> ".$descripcionEvento;
        }else{
          $error=true;
          $descripcionError="<b>Evento:</b> ".$descripcionEvento;
          break;
        }
      }else{
        $error=true;
        $descripcionError="<b>Evento:</b> ".$descripcionEvento;
        break;
      }
    }else{
      $descripcionError="";
      if($cufd=="0"){
        $descripcionError.=" NO ENCONTRADO CUFD VIGENTE<br>";
      }
      if($cufdEvento=="0"){
        $descripcionError.=" NO ENCONTRADO CUFD de FECHA: $fecha<br>";
      }
      $error=true;
      break;
    }
  }
  if($error){
    echo "ERROR.".$descripcionError;    
  }else{
    if($nuevo_cufd==1){
      deshabilitarCufd($cod_ciudad,$cuis,$fecha_X);
      $cufdNuevo=generarCufd($cod_ciudad,$cod_impuestos,$codigoPuntoVenta);
      $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
    }
    echo "CORRECTO :).".$descripcionError;    
  }
}else{
  echo "SIN FACTURAS OFFLINE";  
}

}else{
    echo "ERROR :).".$DatosConexion[1];  
}

?>

<?php
require("../../conexionmysqli2.php");
require("../funciones_siat.php");
require("consultaEvento.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$DatosConexion=verificarConexion();
if($DatosConexion[0]==1){
  $cod_tipoEmision=2;//tipo emision OFFLINE
  $sql="SELECT DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d')as fecha2,a.cod_ciudad,GROUP_CONCAT(s.cod_salida_almacenes) as  string_salida
  FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen
  WHERE s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_tipo_doc=1
  and s.siat_codigotipoemision=2 and s.siat_codigoRecepcion is null
  GROUP BY fecha2,a.cod_ciudad
  order by fecha2,a.cod_ciudad";
  
  //echo $sql;
  
  $resp=mysqli_query($enlaceCon,$sql);
  $string_codigos="";
  while($row=mysqli_fetch_array($resp)){
    $string_salida=$row['string_salida'];
    $cod_ciudad=$row['cod_ciudad'];
    $fecha2=$row['fecha2'];    
    $string_codigos.=$string_salida.",";
    //insertamos todos los eventos que no esten en nuesta BD pero si en impuestos
    consultaEventoSucursal($fecha2,$cod_ciudad,$sw_bandera=false,$enlaceCon);
  }
  $string_codigos=trim($string_codigos,",");
  if($string_codigos==""){
    echo "SIN FACTURAS OFFLINE ".date('Y-m-d H:i:s');
  }else{
    enviarFacturasOffline($string_codigos,$enlaceCon);
  }
  
}else{
  echo "ERROR EN CONEXION.<BR>".$DatosConexion[1];
}

function enviarFacturasOffline($string_codigos,$enlaceCon){
  $entidadDefecto=0;
  $tiempo_evento=6;
  $nuevo_cufd=0;
  $nuevo_cuf=0;
  $codigoMotivoEvento=1;//Inaccesibilidad al Servicio Web de la Administración Tributaria
  $descripcioNuevo="";
  $descripcioNuevo.=" *** COMUNICACIÓN ESTABLECIDA<BR>";
  $string_codigos=trim($string_codigos,",");
  $sql="SELECT s.cod_salida_almacenes,s.siat_fechaemision,DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d')as fecha2,s.cod_almacen,a.nombre_almacen,(select cod_impuestos from ciudades where cod_ciudad= a.cod_ciudad)as cod_impuestos,a.cod_ciudad,sc.cufd,s.siat_codigocufd,s.siat_codigoPuntoVenta,s.siat_cuis
    FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen join siat_cufd sc on s.siat_codigocufd=sc.codigo
    WHERE s.cod_salida_almacenes in ($string_codigos)
    ORDER BY a.nombre_almacen,fecha2";
    // echo $sql;
    $fecha_X=date('Y-m-d');
  $resp1=mysqli_query($enlaceCon,$sql);
  while($row=mysqli_fetch_array($resp1)){ 

    $string_codigos=$row['cod_salida_almacenes'];
    $fecha=$row['fecha2'];
    $siat_fechaemision=$row['siat_fechaemision'];
    $cod_almacen=$row['cod_almacen'];
    $nombre_almacen=$row['nombre_almacen'];
    $cod_impuestos=$row['cod_impuestos'];
    $cod_ciudad=$row['cod_ciudad'];
    $siat_codigocufd=$row['siat_codigocufd'];
    $codigoPuntoVenta=$row['siat_codigoPuntoVenta'];
    // $cuis=$row['siat_cuis'];//no se usara por que en cambio de gestion podria ver algun error
    $cufdEvento=$row['cufd'];

    $descripcioNuevo.="<BR>*** ".$nombre_almacen." (".$fecha.") <BR>";
    if($fecha=="" || $fecha==" " || $fecha==null || $fecha=="S"){
        $descripcioNuevo.="*** <span style=\"color:red;\">ERROR EN FECHA EMISION</span><BR>";
    }
    
    $cod_impuestos=intval($cod_impuestos);
    // $codigoPuntoVenta=obtenerPuntoVenta_BD($cod_ciudad);
    // $cuis=obtenerCuis_siat($codigoPuntoVenta,$cod_impuestos);
    $cuis=obtenerCuis_vigente_BD($cod_ciudad,$entidadDefecto);
    //echo "aqui cuis";
    $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);

    
    //echo "llego 2";
    if($cufd<>"0"){      
      $descripcioNuevo.=" *** CUFD VIGENTE CORRECTO <BR>";
      // $datos_hora=obtenerFechasEmisionFacturas($string_codigos,$cod_almacen,$fecha,$siat_codigocufd);
      // $fecha_inicio=$fecha."T".$datos_hora[0];
      // $fecha_fin=$fecha."T".$datos_hora[1];

      // $inicio_x=$siat_fechaemision;
      // $fin_x=$row['siat_fechaemision'];
      $inicio_x=explode("T", $siat_fechaemision);
      $inicio=$inicio_x[1];
      // $fin_x=explode("T", $fin_x);
      // $fin=$fin_x[1];

      $fecha_inicio=$fecha."T".$inicio;
      $fecha_fin=$fecha."T".$inicio;//SE ENVIARA FACTURA POR FACTURA

      $sw=0;      
      //buscamos algun evento disponible en ese rango de fechas
      $codigoEvento_datos=obtenerEventosignificativo_BD($codigoMotivoEvento,$codigoPuntoVenta,$cod_impuestos,$fecha_fin,$fecha_inicio);
      $codigoEvento=$codigoEvento_datos[0];
      // echo "eveto:".$codigoEvento;
      $sw=$codigoEvento_datos[1];
      $descripcionEvento=" SELECCIONADO ";
      if($sw==0){ //  si no hay registros de Evento
        if($fecha_inicio==$fecha_fin){//solo es una factura
          $fecha_z=$fecha." ".$inicio; 
          $fechanueva = new DateTime($fecha_z); 
          switch ($tiempo_evento) {
            // case 1:
            //   $fechanueva->modify('+1 hours'); 
            //   $milisegundo="000";
            // break;
            // case 2:
            //   $fechanueva->modify('+30 minute'); 
            //   $milisegundo="000";
            // break;
            // case 3:
            //   $fechanueva->modify('+10 minute'); 
            //   $milisegundo="000";
            // break;
            // case 4:
            //   $fechanueva->modify('+1 minute'); 
            //   $milisegundo="000";
            // break;
            case 5:
              $fechanueva->modify('+10 second'); 
              $milisegundo="000";
            break;
            case 6:
              $fechanueva->modify('+1 second'); 
              $milisegundo="000";
            break;
            case 7://milisegundo
              // $fechanueva->modify('+1 second'); 
              $milisegundo="001";
            break;
            default:
              $fechanueva->modify('+1 minute'); 
              $milisegundo="000";
              break;
          }
          $fecha_fin=$fechanueva->format('Y-m-d H:i:s');
          $fecha_fin_datos=explode(" ", $fecha_fin);
          $fecha_fin=$fecha_fin_datos[0]."T".$fecha_fin_datos[1].".".$milisegundo;//agregamos milisegundos 
        }
        // if($nuevo_cufd==1){
        //   deshabilitarCufd($cod_ciudad,$cuis,$fecha_X);
        //   $cufdNuevo=generarCufd($cod_ciudad,$cod_impuestos,$codigoPuntoVenta);
        //   $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
        //   $descripcioNuevo.=" *** NUEVO CUFD OBTENIDO <BR>";
        // }
        $respEvento=solicitudEventoSignificativo($codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis);
        // echo "<br>**".print_r($respEvento)."**<br>";
        $codigoEvento=$respEvento[0];
        $descripcionEvento=$respEvento[1];
      }
      $descripcioNuevo.=" *** EVENTO : ".$descripcionEvento."<BR>";

      if($codigoEvento<>-1){
        //registamos el evento
       if($sw==0){
          $sql="INSERT INTO siat_eventos(codigoMotivoEvento,codigoPuntoVenta,codigoSucursal,cufd,cufdEvento,descripcion,fechaHoraInicioEvento,fechaHoraFinEvento,codigoRecepcionEventoSignificativo) values('$codigoMotivoEvento','$codigoPuntoVenta','$cod_impuestos','$cufd','$cufdEvento','$descripcionX','$fecha_inicio','$fecha_fin','$codigoEvento')";
           // echo $sql;
          $sql_inserta = mysqli_query($enlaceCon,$sql);
        }
        //enviamos el paquete con las facturas
        $respPaquete=solicitudRecepcionPaquetes($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcion,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,null,null,$cuis,$codigoEvento,1,$nuevo_cuf);
        // $respPaquete[0]="";
        // $respPaquete[1]="NO PAQUETE";
        // $respPaquete[2]="NO VALIDACION";

        $codigo=$respPaquete[0];
        $descripcionPaquete=$respPaquete[1];
        $descripcionValidacion=$respPaquete[2];
        if($codigo==1){
          $error=false;
          $descripcionPaquete=" <span style=\"color:green;\">".$descripcionPaquete."</span>";
          $descripcionValidacion=" <span style=\"color:green;\">CORRECTO</span>";
        }else{
          $error=true;
          $descripcionPaquete=" <span style=\"color:red;\">".$descripcionPaquete."</span>";
          $descripcionValidacion=" <span style=\"color:red;\">ERROR: ".$descripcionValidacion."</span>";
        }
        if($descripcionPaquete==""){
          $descripcionPaquete=" <span style=\"color:red;\">NO RECEPCIONADO</span>";
        }
        if($descripcionValidacion==""){
          $descripcionValidacion=" <span style=\"color:red;\">NO VALIDADO</span>";
        }
        $descripcioNuevo.=" *** PAQUETE:".$descripcionPaquete."<BR>";
        $descripcioNuevo.=" *** PAQUETE:".$descripcionValidacion."<BR>";
      }else{
        $error=true;
        $descripcionError="<b>Evento:</b> ".$descripcionEvento;
        $descripcioNuevo.=" *** ".$descripcionError."<BR>";
      }
    }else{
      $descripcionError="";
      if($cufd=="0"){
        $descripcionError.=" NO ENCONTRADO CUFD VIGENTE<br>";
      }
      if($cufdEvento=="0"){
        $descripcionError.=" NO ENCONTRADO CUFD de FECHA: $fecha<br>";
      }
      $descripcioNuevo.=" *** ".$descripcionError."<BR>";
      $error=true;
      // break;
    }
  }
  echo "EL PROCESO TERMINÓ CON EL SIGUIENTE DETALLE:<BR>"; 
  echo $descripcioNuevo."<BR>";
}

?>

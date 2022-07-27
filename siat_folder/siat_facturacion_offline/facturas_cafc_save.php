<?php
require("../../estilos_almacenes.inc");
require("../../conexionmysqli.inc");
require("../funciones_siat.php");
echo ".";//sin este echo no muestra el primer error

  //datos de cabecera
  $cantidadItems=$_POST['contador_items'];//total de intems
  $codigoMotivoEvento=$_POST['cod_motivo'];

  // $url_retorno="";
  if($_POST['rpt_territorio']>0){
    $rpt_territorio=$_POST['rpt_territorio'];
    $url_retorno="location.href='facturas_cafc_list.php?rpt_territorio=".$rpt_territorio."';";
  }else{
    $rpt_territorio=0;
    $url_retorno="location.href='facturas_cafc_list.php';";

  }

  $nuevo_cufd=0;
  if(isset($_POST['nuevo_cufd'])){
    $nuevo_cufd=1;//si se generará nuevo cufd
  }
  $nuevo_cuf=0;
  if(isset($_POST['nuevo_cuf'])){
    $nuevo_cuf=1;//si se generará nuevo cuf
  }
  $descripcionX="";
  $codigoPuntoVenta=0; 
  $flagSuccess=false;
  
  $error=true;
  // $descripcionError="NO SELECCIONADO NINGUNA FACTURA...";

  $string_codigos="";
  $cantidadSeleeccionados=0;
  for ($pro=1; $pro <= $cantidadItems ; $pro++){  
    $factura_seleccionada_s=$_POST["factura_seleccionada_s".$pro];//codigo estado de cuenta relacionado
    $cod_salida_almacenes=$_POST["cod_salida_almacenes".$pro];
    // echo $string_codigos;
    if($factura_seleccionada_s>0){
      $cantidadSeleeccionados++;
      $string_codigos.=$cod_salida_almacenes.",";
    }
  }

  if($cantidadSeleeccionados==0){
    $descripcionError="SELECCIONE FACTURAS OFFLINE POR FAVOR...";
    echo "<script language='Javascript'>
      Swal.fire({
        title: 'ERROR',
        text: '".$descripcionError."',
        type: 'error'
      }).then(function() {
          ".$url_retorno."
      });
      </script>"; 
  }else{
    $descripcionError="";
    $DatosConexion=verificarConexion();
    if($DatosConexion[0]==1){
      $string_codigos=trim($string_codigos,",");
      $cod_tipoEmision=2;//tipo emision OFFLINE
      $sql="SELECT DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d')as siat_fechaemisionx,s.cod_almacen,a.nombre_almacen,(select cod_impuestos from ciudades where cod_ciudad= a.cod_ciudad)as cod_impuestos,a.cod_ciudad,sc.cufd
        FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen join siat_cufd sc on s.siat_codigocufd=sc.codigo
        WHERE s.cod_salida_almacenes in ($string_codigos)
        GROUP BY s.cod_almacen,siat_fechaemisionx,s.siat_codigocufd
        ORDER BY a.nombre_almacen,s.created_at";
         // echo $sql;
      $fecha_X=date('Y-m-d');
      // $fecha_X=date('2022-07-02');
      $resp1=mysqli_query($enlaceCon,$sql);
      while($row=mysqli_fetch_array($resp1)){
        $fecha=$row['siat_fechaemisionx'];
        $cod_almacen=$row['cod_almacen'];
        $nombre_almacen=$row['nombre_almacen'];
        $cod_impuestos=$row['cod_impuestos'];
        $cod_ciudad=$row['cod_ciudad'];
        $cod_impuestos=intval($cod_impuestos);
        $codigoPuntoVenta=obtenerPuntoVenta_BD($cod_ciudad);
        $cuis=obtenerCuis_siat($codigoPuntoVenta,$cod_impuestos);
        $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
        $cufdEvento=$row['cufd'];
        //$cufdEvento=obtenerCufd_anterior_BD($cod_ciudad,$fecha,$cuis);
        //$cufdEvento="BQcKhQ25VQ0JBNzTg3RjNCRTk4QTY=QnxMVkRMWUZXVU9FFNEUwNkEzNkY4N";
        // echo  $cuis;
        if($cufd=="0"){
          echo "<br> * CUFD VIGENTE NO ENCONTRADO EL DIA DE HOY.<br>";
          // $descripcionError.="CUFD VIGENTE NO ENCONTRADO EL DIA DE HOY.<br>";
          deshabilitarCufd($cod_ciudad,$cuis,$fecha_X);
          $cufdNuevo=generarCufd($cod_ciudad,$cod_impuestos,$codigoPuntoVenta);
          $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
          // $descripcionError.="NUEVO CUFD OBTENIDO.<br>";
          echo "<br> * NUEVO CUFD OBTENIDO.<br>";
        }
        if($cufd<>"0"){
           // echo $cufd;
          $datos_hora=obtenerFechasEmisionFacturas($string_codigos,$cod_almacen,$fecha);
          $fecha_inicio=$fecha."T".$datos_hora[0]; 
          $fecha_fin=$fecha."T".$datos_hora[1];
          //buscamos algun evento disponible en ese rango de fechas
          $sw=0;
          $codigoEvento_datos=obtenerEventosignificativo_BD($codigoMotivoEvento,$codigoPuntoVenta,$cod_impuestos,$fecha_fin,$fecha_inicio);
          $codigoEvento=$codigoEvento_datos[0];
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
            // echo $string_codigos."-".$cod_almacen."-".$fecha."-".$codigoMotivoEvento."-".$descripcionX."-".$codigoPuntoVenta."-".$cod_impuestos."-".$cufd."-".$cufdEvento."-".$fecha_fin."-".$fecha_inicio."-".$codigoEvento."<br>";
            $respPaquete=solicitudRecepcionPaquetes($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,$descripcionX,$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,$fecha_fin,$fecha_inicio,$cuis,$codigoEvento,2,$nuevo_cuf);
            $codigo=$respPaquete[0];
            $descripcionPaquete=$respPaquete[1];
            $descripcionValidacion=$respPaquete[2];
            if($codigo==1){
              $error=false;
              // $descripcionError=$nombre_almacen.": ".$descripcion;
              $descripcionError="<b>Evento</b>: ".$descripcionEvento."<br><b>Paquete:<br>Paso 1.- </b> ".$descripcionPaquete."<br><b>Paso 2.- </b> ".$descripcionValidacion;
            }else{
              $error=true;
              // $descripcionError=$nombre_almacen.": ".$descripcion;
              $descripcionError="<b>Evento:</b> ".$descripcionEvento."<br><b>Paquete:<br>Paso 1.- </b> ".$descripcionPaquete."<br><b>Paso 2.- </b> ".$descripcionValidacion;          
              break;
            }
          }else{
            $error=true;
            // $descripcionError=$nombre_almacen.": ".$descripcion;
            $descripcionError="Evento: ".$descripcionEvento;
            break;
          }
        }else{
          $descripcionError="";
          if($cufd=="0"){
            $descripcionError.=$nombre_almacen.": NO ENCONTRADO CUFD VIGENTE\n";
          }
          $error=true;
          break;
        }
      }
      mysqli_close($enlaceCon);
      if($error){
        echo "<script language='Javascript'>
        Swal.fire({
          title: 'ERROR',
          html: '<table style=\"border:1px;font-size:14px\"><tr><td>".$descripcionError."</td></tr></table>',
          type: 'error'
        }).then(function() {
          ".$url_retorno."

        });
        </script>";  
      }else{

        if($nuevo_cufd==1){
            deshabilitarCufd($cod_ciudad,$cuis,$fecha_X);
            $cufdNuevo=generarCufd($cod_ciudad,$cod_impuestos,$codigoPuntoVenta);
            $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
          }

        echo "<script language='Javascript'>
        Swal.fire({
          title: 'CORRECTO',
          html: '<table style=\"border:1px;font-size:14px\"><tr><td>".$descripcionError."</td></tr></table>',
          type: 'success'
        }).then(function() {
            
        });
        </script>";
      }
    }else{
      echo "<script language='Javascript'>
        Swal.fire({
          title: 'ERROR EN SERVICIO',
          text: ' SIAT:".$DatosConexion[1]."',
          type: 'error'
        }).then(function() {
            ".$url_retorno."
        });
        </script>"; 
    }
}




?>

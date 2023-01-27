<?php
// require("../../estilos_almacenes.inc");
require("../../conexionmysqli2.inc");
require("../funciones_siat.php");
$DatosConexion=verificarConexion();
if($DatosConexion[0]==1){
  $codigoMotivoEvento=$_GET['cod_motivo'];
  $cod_ciudad=$_GET['cod_ciudad'];
  $codigoPuntoVenta=obtenerPuntoVenta_BD($cod_ciudad);
  $nuevo_cufd=0;
  if(isset($_GET['nuevo_cufd'])){
    $nuevo_cufd=1;//si se generará nuevo cufd
  }
  $nuevo_cuf=0;
  if(isset($_GET['nuevo_cuf'])){
    $nuevo_cuf=1;//si se generará nuevo cuf
  }
  $fecha=$_GET['fecha'];
  $stringFechas=$_GET['fecha'];
  $arrayFechas=explode(",", $stringFechas);
  foreach ($arrayFechas as $fecha) {
    // echo $fecha."<br>";
    enviarFacturasConeventosExistentes($fecha,$cod_ciudad,$codigoMotivoEvento,$codigoPuntoVenta,$nuevo_cufd,$nuevo_cuf,$enlaceCon);  
  }
  echo "1";
}else{
  echo "0";
}
  function enviarFacturasConeventosExistentes($fecha,$cod_ciudad,$codigoMotivoEvento,$codigoPuntoVenta,$nuevo_cufd,$nuevo_cuf,$enlaceCon){
    $descripcioNuevo="";//aqui se aumulará todos los mensajes
    $descripcionError="";
    $error=true;
    $sqlEventos="select codigoRecepcionEventoSignificativo,codigoSistema,fechaHoraInicioEvento,fechaHoraFinEvento from siat_eventos where codigoMotivoEvento='$codigoMotivoEvento' and codigoPuntoVenta='$codigoPuntoVenta' and codigoSucursal in (select cod_impuestos from ciudades where cod_ciudad=$cod_ciudad) and DATE_FORMAT(fechaHoraInicioEvento,'%Y-%m-%d')='$fecha' and DATE_FORMAT(fechaHoraFinEvento,'%Y-%m-%d')='$fecha'";
    // echo $sqlEventos."<br>";
    $respEventos=mysqli_query($enlaceCon,$sqlEventos);
    while($rowEventos=mysqli_fetch_array($respEventos)){
      $codigoRecepcionEventoSignificativo=$rowEventos['codigoRecepcionEventoSignificativo'];
      // $cod_impuestos=$rowEventos['codigoSistema'];
      $fechaHoraInicioEvento=$rowEventos['fechaHoraInicioEvento'];
      $fechaHoraFinEvento=$rowEventos['fechaHoraFinEvento'];
      $string_codigos="";
      $sqlFacturas="SELECT s.cod_salida_almacenes
      FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen
      WHERE s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_tipo_doc=1
      and s.siat_codigotipoemision=2 and s.siat_codigoRecepcion is null and a.cod_ciudad in ($cod_ciudad) and CONCAT_WS(' ',s.fecha,s.hora_salida)>='$fechaHoraInicioEvento' and CONCAT_WS(' ',s.fecha,s.hora_salida)<='$fechaHoraFinEvento'";
      $respFact=mysqli_query($enlaceCon,$sqlFacturas);
      while($rowFact=mysqli_fetch_array($respFact)){
        $cod_salida_almacenes=$rowFact['cod_salida_almacenes'];
        $string_codigos.=$cod_salida_almacenes.",";
      }
      $string_codigos=trim($string_codigos,",");

      $sql="SELECT DATE_FORMAT(s.siat_fechaemision,'%Y-%m-%d')as fecha2,s.cod_almacen,a.nombre_almacen,(select cod_impuestos from ciudades where cod_ciudad= a.cod_ciudad)as cod_impuestos,a.cod_ciudad,sc.cufd,s.siat_codigocufd
      FROM salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen join siat_cufd sc on s.siat_codigocufd=sc.codigo
      WHERE s.cod_salida_almacenes in ($string_codigos)
      GROUP BY s.cod_almacen,s.fecha,s.siat_codigocufd
      ORDER BY a.nombre_almacen,fecha2";
      // echo $sql;
      $fecha_X=date('Y-m-d');
      $resp1=mysqli_query($enlaceCon,$sql);
      while($row=mysqli_fetch_array($resp1)){
        $fecha=$row['fecha2'];
        $cod_almacen=$row['cod_almacen'];
        $nombre_almacen=$row['nombre_almacen'];
        $cod_impuestos=$row['cod_impuestos'];
        $cod_ciudad=$row['cod_ciudad'];
        $siat_codigocufd=$row['siat_codigocufd'];
        $cod_impuestos=intval($cod_impuestos);
        $cuis=obtenerCuis_siat($codigoPuntoVenta,$cod_impuestos);
        $cufd=obtenerCufd_Vigente_BD($cod_ciudad,$fecha_X,$cuis);
        $cufdEvento=$row['cufd'];
        // echo  $cuis; 
        if($cufd<>"0"){
          $codigoEvento=$codigoRecepcionEventoSignificativo;
          //enviamos el paquete con las facturas
          $respPaquete=solicitudRecepcionPaquetes($string_codigos,$cod_almacen,$fecha,$codigoMotivoEvento,"",$codigoPuntoVenta,$cod_impuestos,$cufd,$cufdEvento,null,null,$cuis,$codigoEvento,1,$nuevo_cuf);
          $codigo=$respPaquete[0];
          $descripcionPaquete=$respPaquete[1];
          $descripcionValidacion=$respPaquete[2];
          if($codigo==1){
            $estado=1;        
          }else{
            $estado=2;
          }
          echo $descripcionPaquete."<br>";
          echo $descripcionValidacion."<br>";
        }else{
          $descripcionError="";
          if($cufd=="0"){
            $descripcionError="NO ENCONTRADO CUFD VIGENTE<br>";
          }
        }
      }
    }
  }
?>

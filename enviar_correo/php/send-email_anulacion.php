<?php
require 'PHPMailer/send.php';
// require("../../funciones.php");

function envio_facturaanulada($idproveedor,$proveedor,$nro_correlativo,$cuf,$nitCliente,$sucursalCliente,$estado_siatCliente,$fechaCliente,$correosProveedor,$enlaceCon){
  $email = "";//cc de correo
  // $contact_message = trim($_POST['message']);
  $mail_username="KIDSPLACE";//Correo electronico emisor
  $mail_userpassword="";// contraseña correo emisor
  require_once("funciones.php");
  // $idproveedor=$_POST['idproveedor'];
  // $proveedor=$_POST['$proveedor'];
  // $nro_correlativo=$_POST['nro_correlativo'];
  // $cuf=$_POST['cuf'];

  $urlDir=obtenerValorConfiguracion($enlaceCon,46);
  // echo "aqui";
  // $correosProveedor=obtenerCorreosListaCliente($idproveedor);
  //$correosProveedor = "davidhuarina25@gmail.com,bsullcamani@gmail.com";
  if($correosProveedor<>""){
    $mail_addAddress=$correosProveedor;
    // if($email!=""){
    //   $mail_addAddress.=",".$email;  
    // }
    //$mail_addAddress="dhuarina@farmaciasbolivia.com.bo,asd";//correo electronico destino

    $template="enviar_correo/php/PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
    /*Inicio captura de datos enviados por $_POST para enviar el correo */
    $mail_setFromEmail=$mail_username;
    $mail_setFromName=$mail_username;
    $titulo_pedido_email="ANULACION DE FACTURA: Nro:".$nro_correlativo;
    $txt_message="Estimado Cliente:<br>\n<br>\n
      La factura Nro: ".$nro_correlativo;
    $txt_message.="<br>\n      
      Fue Anulada.<br>\n<br>\n
      Gracias por su preferencia!"; //Con CUF: ".$cuf."<br>\n

    $mail_subject=$titulo_pedido_email; //el subject del mensaje

      $datosCabecera['cuf']=$cuf;
      $datosCabecera['nombre_cliente']="<li>Cliente: ".$proveedor."</li>";
      if($idproveedor==146){
        $datosCabecera['nombre_cliente']="";
      }        
      $datosCabecera['nro_factura']=$nro_correlativo;
      $datosCabecera['nit']=$nitCliente;
      
      $datosCabecera['sucursal']=$sucursalCliente; 
      $datosCabecera['estado_siat']=$estado_siatCliente;        
      $datosCabecera['fecha']=$fechaCliente;

    $flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$datosCabecera,$urlDir,$enlaceCon);
    if($flag!=0){//se envio correctamente
      return 1;
    }else{//error al enviar el correo
      return 2;
    }
  }else{
    return 0;//sin correo
  }
}

function envio_factura($codigoFac,$correosProveedor,$enlaceCon){
  

  // $name = trim($_POST['name']);
  // $email = trim($_POST['email']);
  // $contact_message = trim($_POST['message']);
  // $titulo_pedido_email=trim($_POST['titulo_pedido_email']);
  // $titulo_pedido_email="FACTURA: Nro:".$nro_correlativo;
  // $idproveedor=$_POST['idproveedor'];
  // $codPedidos=$_POST['cod_pedido_string'];
  // $rutaArchivo=trim($_POST['adjuntos_texto'],",");
  // $rutaArchivoCSV=trim($_POST['adjuntos_texto_csv'],",");
  $rutaArchivo="";
  $rutaArchivoCSV="";
  $fechaActual=date("Y-m-d H:m:s");

  //SACAMOS EL NOMBRE DE LA TIENDA DESDE LAS CONFIGURACIONES Y DATOS USUARIO
  // $mail_username=$nombreTiendaRopa;//Correo electronico emisor
  // if($mail_username==""){
  //   $mail_username="Envio de Correo Compra SIAT";
  // }

  $mail_userpassword="";// contraseña correo emisor
  $sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
  $respDir=mysqli_query($enlaceCon,$sqlDir);
  // $urlDir=mysqli_result($respDir,0,0);
  $datValidar=mysqli_fetch_array($respDir);   
  $urlDir=$datValidar[0];
    
    // if($email!=""){
    //   $mail_addAddress.=",".$correosProveedor;  
    // }
    //$mail_addAddress="dhuarina@farmaciasbolivia.com.bo,asd";//correo electronico destino
  // $template="PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
  $template="enviar_correo/php/PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
  /*Inicio captura de datos enviados por $_POST para enviar el correo */

    // $txt_message=$contact_message;

    $sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, c.`nombre_cliente`, s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf,s.siat_complemento,(SELECT nombre_tipopago from tipos_pago where cod_tipopago=s.cod_tipopago) as nombre_pago,s.siat_fechaemision,s.siat_codigotipoemision,s.siat_codigoPuntoVenta,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,s.nit,
    (SELECT nombre_ciudad from ciudades where cod_ciudad=(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen))as nombre_ciudad,s.siat_codigotipodocumentoidentidad,s.siat_estado_facturacion
        from `salida_almacenes` s, `tipos_docs` t, `clientes` c
        where s.`cod_salida_almacenes` in ($codigoFac) and s.`cod_cliente`=c.`cod_cliente` and
        s.`cod_tipo_doc`=t.`codigo`";
        // echo $sqlDatosVenta;
    $respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
    $datosCabecera=[];
    $nombreCliente="";
    while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
      $nombreCliente=$datDatosVenta[2];
      $datosCabecera['cuf']=$datDatosVenta['siat_cuf'];
      $datosCabecera['nombre_cliente']="<li>Cliente: ".$datDatosVenta[2]."</li>";
      if($datDatosVenta['cod_cliente']==146){
        $datosCabecera['nombre_cliente']="";
      }        
      $datosCabecera['nro_factura']=$datDatosVenta[3];
      if($datDatosVenta['siat_codigotipodocumentoidentidad']==5){
        $datosCabecera['nit']=$datDatosVenta['nit'];  
      }else{
        $datosCabecera['nit']=$datDatosVenta['nit']." ".$datDatosVenta['siat_complemento'];
      }
      $datosCabecera['sucursal']=$datDatosVenta['nombre_ciudad']; 
      $datosCabecera['estado_siat']=$datDatosVenta['siat_estado_facturacion'];        
      $datosCabecera['fecha']=date("d/m/Y",strtotime($datDatosVenta['siat_fechaemision']));
    }
    // $mail_username="KIDSPLACE";//Correo electronico emisor
    $mail_addAddress=$correosProveedor;

    $titulo_pedido_email="FACTURA: Nro:".$datosCabecera['nro_factura'];
    $txt_message="Estimado Cliente: ".$nombreCliente." <br>\n<br>\n
      Adjuntamos la factura Nro: ".$datosCabecera['nro_factura'];
    $txt_message.="<br>\n<br>\n
      Gracias por su Compra!"; //Con CUF: ".$cuf."<br>\n
    $mail_subject=$titulo_pedido_email; //el subject del mensaje
    $mail_setFromEmail="";
    $mail_setFromName="";

    $flag=sendemailFiles($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0,$rutaArchivo,$rutaArchivoCSV,$datosCabecera,$urlDir,1,$enlaceCon);
    // echo "aqui";
    if($flag!=0){//se envio correctamente
      return 1;
    }else{//error al enviar el correo
      return 2;
    }



}
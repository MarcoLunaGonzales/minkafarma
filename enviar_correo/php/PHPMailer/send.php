<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



function sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject, $template,$inicio,$datosCabecera,$urlDir='',$enlaceCon=''){
	// echo "**";

	if($inicio==0){
		require_once 'PHPMailer/src/Exception.php';
	    require_once 'PHPMailer/src/PHPMailer.php';
	    require_once 'PHPMailer/src/SMTP.php';
	}    
	require_once 'funciones.php';
	//require_once 'conexionmysqli.inc';
	require_once 'config.php';


	$logoEnvioEmail=obtenerValorConfiguracion($enlaceCon,13);
	$mail_setFromName=obtenerValorConfiguracion($enlaceCon,12);

	/*SACAR EL NIT DE LA CONFIGURACION 9 UN SOLO NIT POR CADA INSTANCIA*/
	$sqlConf="select id, valor from configuracion_facturas where id=9 limit 0,1";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$nitTxt=mysqli_result($respConf,0,1);


	$mail = new PHPMailer;
	$mail->isSMTP();                            // Establecer el correo electrónico para utilizar SMTP

	$mail->Host = EMAIL_HOST;             // Especificar el servidor de correo a utilizar 
	$mail->SMTPAuth = true;                     // Habilitar la autenticacion con SMTP
	$mail->Username = EMAIL_USERNAME;          // Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail_setFromEmail=EMAIL_FROM;
	$mail->Password = EMAIL_PASSWORD; 	

	$mail->SMTPSecure = 'tls';                  // Habilitar encriptacion, `ssl` es aceptada
	$mail->Port = EMAIL_PORT;                          // Puerto TCP  para conectarse 
	$mail->setFrom($mail_setFromEmail, $mail_setFromName);//Introduzca la dirección de la que debe aparecer el correo electrónico. Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente en lugar de la dirección de correo electrónico en sí.
	$mail->addReplyTo($mail_setFromEmail, $mail_setFromName);//Introduzca la dirección de la que debe responder. El segundo parámetro opcional para esta función es el nombre que se mostrará para responder

	$correo_array=explode( ',', $mail_addAddress);//convertimos a array para el envio multiple
	for($i = 0; $i < count($correo_array); $i++) {	    
	    $mail->addAddress($correo_array[$i]);   // Agregar quien recibe el e-mail enviado
	}

	//$mail->addAddress($mail_addAddress);   // Agregar quien recibe el e-mail enviado

	
	///////////////////////////////////////para la version de php 7
	$mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
          )
      );
	///////////////////////////////////////////////////////////////77
	$message = file_get_contents($template);
	$message = str_replace('{{first_name}}', $mail_setFromName, $message);
	$message = str_replace('{{titulo_men}}', $mail_subject, $message);
	$message = str_replace('{{message}}', $txt_message, $message);
	$message = str_replace('{{customer_email}}', $mail_setFromEmail, $message);



	//DATOS 
	$botonEnvio='<a href="'.$urlDir.'/consulta/QR?nit={{codigo_nit_gerente}}&cuf={{codigo_cuf}}&numero={{codigo_factura}}&t=2" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#00cfe8;border-radius:20px;width:auto;border-top:1px solid #00cfe8;border-right:1px solid #00cfe8;border-bottom:1px solid #00cfe8;border-left:1px solid #00cfe8;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;" target="_blank"><span style="padding-left:40px;padding-right:40px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Verificar Factura</span></span></a>';
	if($datosCabecera['estado_siat']==1){
		$message = str_replace('{{boton_verificar}}', $botonEnvio, $message);		
	}else{
		$message = str_replace('{{boton_verificar}}', '', $message);	
	}

	$message = str_replace('{{codigo_cuf}}', $datosCabecera['cuf'], $message);
	$message = str_replace('{{codigo_cliente}}', $datosCabecera['nombre_cliente'], $message);
	$message = str_replace('{{codigo_nit}}', $datosCabecera['nit'], $message);
	$message = str_replace('{{codigo_sucursal}}', $datosCabecera['sucursal'], $message);
	$message = str_replace('{{codigo_fecha}}', $datosCabecera['fecha'], $message);	
	$message = str_replace('{{codigo_factura}}', $datosCabecera['nro_factura'], $message);	
	$message = str_replace('{{codigo_nit_gerente}}', $nitTxt, $message);	

    $logoCorreo = "data:image/png;base64,".base64_encode(file_get_contents('PHPMailer/images/'.$logoEnvioEmail));
	$message = str_replace('{{logo_general}}', $logoCorreo, $message);


	$mail->isHTML(true);  // Establecer el formato de correo electrónico en HTML
	
	// $mail->Subject = $mail_subject;
	$subject = $mail_subject;
	$subject = utf8_decode($subject);
	$mail->Subject = $subject;
	// $mail->Subject = $mail_subject;
	$mail->CharSet = 'UTF-8';
	$mail->msgHTML($message);
	if(!$mail->send()){
      return 0;
	}else{
	  return 1;
	}
}
function sendemailFiles($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject, $template,$inicio,$rutaArchivo,$rutaArchivoCSV,$datosCabecera,$urlDir='',$variablex=0,$conn=null){

	

	if($inicio==0){
		require_once 'PHPMailer/src/Exception.php';
	    require_once 'PHPMailer/src/PHPMailer.php';
	    require_once 'PHPMailer/src/SMTP.php';
	}   
	if($variablex==1){
		require_once 'funciones.php';	
		require_once 'config.php';
		// require_once 'conexionmysqli.inc';
		$enlaceCon=$conn;
		$add_url="";
	}else{
		require_once '../../funciones.php';	
		require_once '../../conexionmysqli.inc';
		require_once '../../config.php';
		$add_url="../../";
	}
	$logoEnvioEmail=obtenerValorConfiguracion($enlaceCon,13);
	$mail_setFromName=obtenerValorConfiguracion($enlaceCon,12);

	/*SACAR EL NIT DE LA CONFIGURACION 9 UN SOLO NIT POR CADA INSTANCIA*/
	$sqlConf="select id, valor from configuracion_facturas where id=9 limit 0,1";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$nitTxt=mysqli_result($respConf,0,1);

	//recibimos correos
	$mail = new PHPMailer;
	$mail->isSMTP();                            // Establecer el correo electrónico para utilizar SMTP
	 // $mail->SMTPDebug = true; 

	$mail->Host = EMAIL_HOST;             // Especificar el servidor de correo a utilizar 
	$mail->SMTPAuth = true;                     // Habilitar la autenticacion con SMTP
	$mail->Username = EMAIL_USERNAME;          // Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail_setFromEmail=EMAIL_FROM;
	$mail->Password = EMAIL_PASSWORD; 	

	$mail->SMTPSecure = 'tls';                  // Habilitar encriptacion, `ssl` es aceptada
	$mail->Port = EMAIL_PORT;                          // Puerto TCP  para conectarse 
	$mail->setFrom($mail_setFromEmail, $mail_setFromName);//Introduzca la dirección de la que debe aparecer el correo electrónico. Puede utilizar cualquier dirección que el servidor SMTP acepte como válida. El segundo parámetro opcional para esta función es el nombre que se mostrará como el remitente en lugar de la dirección de correo electrónico en sí.
	$mail->addReplyTo($mail_setFromEmail, $mail_setFromName);//Introduzca la dirección de la que debe responder. El segundo parámetro opcional para esta función es el nombre que se mostrará para responder
	
	$correo_array=explode( ',', $mail_addAddress);//convertimos a array para el envio multiple
	for($i = 0; $i < count($correo_array); $i++) {	    
	    $mail->addAddress($correo_array[$i]);   // Agregar quien recibe el e-mail enviado
	}


	// $adjuntos=explode( ',', $rutaArchivo);
	// for ($i=0; $i <count($adjuntos) ; $i++) { 
	// 	$mail->addAttachment("../../siat_folder/Siat/temp/Facturas-XML/".$adjuntos[$i]);
	// }
	// $adjuntosCSV=explode( ',', $rutaArchivoCSV);
	// for ($i=0; $i <count($adjuntosCSV) ; $i++) { 
	// 	$mail->addAttachment("../../siat_folder/Siat/temp/Facturas-XML/".$adjuntosCSV[$i]);
	// }
	$mail->addAttachment($add_url."siat_folder/Siat/temp/Facturas-XML/".$datosCabecera['cuf'].".xml");
	$mail->addAttachment($add_url."siat_folder/Siat/temp/Facturas-XML/".$datosCabecera['cuf'].".pdf");
	///////////////////////////////////////para la version de php 7
	$mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
          )
      );
	///////////////////////////////////////////////////////////////77
	$message = file_get_contents($template);
	$message = str_replace('{{first_name}}', $mail_setFromName, $message);
	$message = str_replace('{{titulo_men}}', $mail_subject, $message);
	$message = str_replace('{{message}}', $txt_message, $message);		
	$message = str_replace('{{customer_email}}', $mail_setFromEmail, $message);



	//DATOS 
	$botonEnvio='<a href="'.$urlDir.'/consulta/QR?nit={{codigo_nit_gerente}}&cuf={{codigo_cuf}}&numero={{codigo_factura}}&t=2" style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#00cfe8;border-radius:20px;width:auto;border-top:1px solid #00cfe8;border-right:1px solid #00cfe8;border-bottom:1px solid #00cfe8;border-left:1px solid #00cfe8;padding-top:5px;padding-bottom:5px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;" target="_blank"><span style="padding-left:40px;padding-right:40px;font-size:16px;display:inline-block;letter-spacing:normal;"><span style="font-size: 16px; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">Verificar Factura</span></span></a>';
	if($datosCabecera['estado_siat']==1){
		$message = str_replace('{{boton_verificar}}', $botonEnvio, $message);		
	}else{
		$message = str_replace('{{boton_verificar}}', '', $message);	
	}

	$message = str_replace('{{codigo_cuf}}', $datosCabecera['cuf'], $message);
	$message = str_replace('{{codigo_cliente}}', $datosCabecera['nombre_cliente'], $message);
	$message = str_replace('{{codigo_nit}}', $datosCabecera['nit'], $message);
	$message = str_replace('{{codigo_sucursal}}', $datosCabecera['sucursal'], $message);
	$message = str_replace('{{codigo_fecha}}', $datosCabecera['fecha'], $message);	
	$message = str_replace('{{codigo_factura}}', $datosCabecera['nro_factura'], $message);	
	$message = str_replace('{{codigo_nit_gerente}}',$nitTxt, $message);	


	//imagenes
	if($variablex==1){		
		$logoCorreo = "data:image/png;base64,".base64_encode(file_get_contents('enviar_correo/php/PHPMailer/images/'.$logoEnvioEmail));
	}else{
		$logoCorreo = "data:image/png;base64,".base64_encode(file_get_contents('PHPMailer/images/'.$logoEnvioEmail));	
	}
    
	$message = str_replace('{{logo_general}}', $logoCorreo, $message);


	$mail->isHTML(true);  // Establecer el formato de correo electrónico en HTML
	$subject = $mail_subject;
	$subject = utf8_decode($subject);
	$mail->Subject = $subject;
	// $mail->Subject = $mail_subject;
	$mail->CharSet = 'UTF-8';
	// $mail->Subject = $mail_subject;
	$mail->msgHTML($message);
	
	if(!$mail->send()){
		echo 'Mailer Error: ' . $mail->ErrorInfo;
      return 0;
	}else{
		unlink($add_url."siat_folder/Siat/temp/Facturas-XML/".$datosCabecera['cuf'].".xml");
		unlink($add_url."siat_folder/Siat/temp/Facturas-XML/".$datosCabecera['cuf'].".pdf");
		//UNLINK
		// for ($i=0; $i <count($adjuntos) ; $i++) { 
		// 	unlink("../../siat_folder/Siat/temp/Facturas-XML/".$adjuntos[$i]);
		// }
		// for ($i=0; $i <count($adjuntosCSV) ; $i++) { 
		// 	unlink("../../siat_folder/Siat/temp/Facturas-XML/".$adjuntosCSV[$i]);
		// }
	  	return 1;

	}

}
?>
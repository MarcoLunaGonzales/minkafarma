<?php
require_once 'conexion.inc';
require 'PHPMailer/send.php';

/*$correo=$_GET['correo'];
$personal=$_GET['personal'];
$evento=$_GET['evento'];
$titulo=$_GET['titulo'];
$mensaje=$_GET['mensaje'];
*/

$correo="lunagonzalesmarco@gmail.com";
$personal="2";
$evento="Evento test";
$titulo="Titulo Test";
$mensaje="Mensaje de Prueba para test."; 
    
    $mail_username="mailfacturacion@minkasoftware.com";//Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail_userpassword="M1nk4.2024***";//Tu contraseña de gmail
	$mail_addAddress=$correo;//correo electronico que recibira el mensaje
	$template="PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
				
	/*Inicio captura de datos enviados por $_POST para enviar el correo */
	$mail_setFromEmail=$mail_username;
	$mail_setFromName="TUFARMA";
	$txt_message=$mensaje;
	$mail_subject=$titulo; //el subject del mensaje
	
	$flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0);			
     if($flag!=0){
     	echo "SE ENVIO CORRECTAMENTE";     
	 }else{
	 	echo "****** error ******";
	 }
?>
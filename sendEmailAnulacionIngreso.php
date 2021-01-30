<?php
require_once 'conexion.inc';
require_once 'funciones.php';
require 'PHPMailer/send.php';


$codigo_ingreso=$_GET['codigo'];

//SACAMOS LA VARIABLE PARA LA PERSONA QUE RECIBE EL CORREO
$correo=obtenerValorConfiguracion(9);

//$correo="mluna@minkasoftware.com";
$personal="2";
$evento="Anulacion de Ingreso";
$titulo="Anulacion de Ingreso";
$mensaje=""; 

//INICIO MENSAJE

	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen='$codigo_ingreso'";	
	$resp=mysql_query($sql);
	$mensaje.="<span>Estimad@ Usuari@: TuFarma comunica que tuvo lugar la anulacion del siguiente ingreso:</span><br>";
	$mensaje.="<table border='1' cellpadding='0' cellspacing='0' align='center'>";
	$mensaje.="<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	$mensaje.="<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td></tr>";
	$mensaje.="</table>";
	$sql_detalle="select i.cod_material, i.cantidad_unitaria, i.precio_neto, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'),
	(select u.nombre from ubicaciones_estantes u where u.codigo=i.cod_ubicacionestante)as estante,
	(select u.nombre from ubicaciones_filas u where u.codigo=i.cod_ubicacionfila)as fila
	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material";
	$resp_detalle=mysql_query($sql_detalle);
	$mensaje.="<br><table border='1' cellpadding='0' cellspacing='0' align='center'>";
	$mensaje.="<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Lote</th><th>Fecha Vencimiento</th><th>Ubicacion</th><th>Precio Compra(Bs.)</th><th>Total(Bs.)</th></tr>";
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$estante=$dat_detalle[5];
		$fila=$dat_detalle[6];
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		$mensaje.="<tr><td align='center'>$indice</td><td>$nombre_material</td><td align='center'>$cantidad_unitaria</td>
		<td align='center'>$loteProducto</td>
		<td align='center'>$fechaVenc</td>
		<td align='center'>$estante - $fila</td>
		<td align='center'>$precioNeto</td><td align='center'>$totalValorItem</td></tr>";
		$indice++;
	}
	$mensaje.="</table>";

//FIN MENSAJE
    
    $mail_username="noresponse@minkasoftware.com";//Correo electronico saliente ejemplo: tucorreo@gmail.com
	$mail_userpassword="minka@2019";//Tu contraseña de gmail
	$mail_addAddress=$correo;//correo electronico que recibira el mensaje
	$template="PHPMailer/email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
				
	/*Inicio captura de datos enviados por $_POST para enviar el correo */
	$mail_setFromEmail=$mail_username;
	$mail_setFromName="TuFarma";
	$txt_message=$mensaje;
	$mail_subject=$titulo; //el subject del mensaje
	
	$flag=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template,0);			
     if($flag!=0){
     	//echo "SE ENVIO CORRECTAMENTE";     
		echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_ingresomateriales.php';			
			</script>";
	 }else{
	 	echo "****** error ******";
	 }
?>
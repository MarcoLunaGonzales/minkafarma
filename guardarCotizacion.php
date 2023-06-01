<?php

$start_time = microtime(true);
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");
require("enviar_correo/php/send-email_anulacion.php");

// error_reporting(E_ALL);
// ini_set('display_errors', '1');


$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

/*SACAMOS EL TIPO DE IMPRESION PDF O HTML*/
$tipoImpresion=obtenerValorConfiguracion($enlaceCon,48);


$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=$_POST['tipoSalida'];

//echo "TIPO SALIDA: ".$tipoSalida;

//ARRASTRAMOS EL CODIGO ALMACEN ORIGEN DEL FORMULARIO ANTERIOR PARA ALMACEN Y PARA LA SUCURSAL
$almacenOrigen=$_POST['almacen_origen'];
$globalSucursal=$_POST['sucursal_origen'];


/*INSERTAMOS TIPO DOC 1*/
$tipoDoc=1;


if(!isset($_POST['no_venta'])){
   $almacenDestino=2;
   //$almacenOrigen=$global_almacen;
}else{
   $almacenDestino=$_POST['almacen'];
   //$almacenOrigen=$global_almacen;
}

$cod_tipopreciogeneral=0;
if(isset($_POST['codigoDescuentoGeneral'])){
   $cod_tipopreciogeneral=$_POST['codigoDescuentoGeneral'];
}
$cod_tipoVenta2=1;
if(isset($_POST['tipo_venta2'])){
   $cod_tipoVenta2=$_POST['tipo_venta2'];
}
$cod_tipodelivery=0;
if(isset($_POST['tipo_ventadelivery'])){
   $cod_tipodelivery=$_POST['tipo_ventadelivery'];
}

$monto_bs=0;
if(isset($_POST['efectivoRecibidoUnido'])){
   $monto_bs=$_POST['efectivoRecibidoUnido'];
}

$monto_usd=0;
if(isset($_POST['efectivoRecibidoUnidoUSD'])){
   $monto_usd=$_POST['efectivoRecibidoUnidoUSD'];
}

$tipo_cambio=0;
if(isset($_POST['tipo_cambio_dolar'])){
   $tipo_cambio=$_POST['tipo_cambio_dolar'];
}



if(isset($_POST['cliente'])){	$codCliente=$_POST['cliente']; }else{ $codCliente=0;	}
if(isset($_POST['tipoPrecio'])){	$tipoPrecio=$_POST['tipoPrecio']; }else{ $tipoPrecio=0;	}
if(isset($_POST['razonSocial'])){	$razonSocial=$_POST['razonSocial']; }else{ $razonSocial="";	}
if($razonSocial==""){
	$razonSocial="SN";
}
$razonSocial=addslashes($razonSocial);

if(isset($_POST['nitCliente'])){	$nitCliente=$_POST['nitCliente']; }else{ $nitCliente=0;	}

if((int)$nitCliente==123){
	$razonSocial="SN";
}




$fecha_emision_manual="";
if(isset($_POST['fecha_emision']) and isset($_POST['hora_emision'])){
	$fecha_emision_manual=date("Y-m-d\TH:i:s.v",strtotime($_POST['fecha_emision']." ".$_POST['hora_emision']));
}else{
	if(isset($_POST['fecha_emision'])){
	   $fecha_emision_manual=date("Y-m-d\TH:i:s.v",strtotime($_POST['fecha_emision']." ".date("H:i:s")));
	}
}


if(isset($_POST['tipoVenta'])){	$tipoVenta=$_POST['tipoVenta']; }else{ $tipoVenta=0;	}
if(isset($_POST['observaciones'])){	$observaciones=$_POST['observaciones']; }else{ $observaciones="";	}

$cuf="";

if(isset($_POST['totalVenta'])){	$totalVenta=$_POST['totalVenta']; }else{ $totalVenta=0;	}
if(isset($_POST['descuentoVenta'])){	$descuentoVenta=$_POST['descuentoVenta']; }else{ $descuentoVenta=0;	}
if(isset($_POST['totalFinal'])){	$totalFinal=$_POST['totalFinal']; }else{ $totalFinal=0;	}

$totalEfectivo=0;
$totalCambio=0;
if(isset($_POST['efectivoRecibido'])){	$totalEfectivo=$_POST['efectivoRecibido']; }else{ $totalEfectivo=0;	}
if(isset($_POST['cambioEfectivo'])){	$totalCambio=$_POST['cambioEfectivo']; }else{ $totalCambio=0;	}
//echo "total efectivo ".$totalEfectivo;
//echo "total cambio ".$totalCambio;

if(isset($_POST['complemento'])){	$complemento=$_POST['complemento']; }else{ $complemento=0;	}

$totalFinalRedondeado=round($totalFinal);

//VALIDAMOS QUE NO SEA CERO EL VALOR DEL REDONDEADO PARA EL CODIGO DE ControlCode
if($totalFinalRedondeado==0){
	$totalFinalRedondeado=1;
}

$fecha=$_POST["fecha"];
$cantidad_material=$_POST["cantidad_material"];

if($descuentoVenta=="" || $descuentoVenta==0){
	$descuentoVenta=0;
}

$vehiculo=0;

$fecha=date("Y-m-d");
$hora=date("H:i:s");


//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$tipoDocDefault=$datConf[0];
//$tipoDocDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$clienteDefault=$datConf[0];
//$clienteDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$facturacionActivada=$datConf[0];
//$facturacionActivada=mysql_result($respConf,0,0);

$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];
//$banderaValidacionStock=mysql_result($respConf,0,0);

$contador = 0;
do {

	$anio=date("Y");

	$created_at=date("Y-m-d H:i:s");
	$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM cotizaciones";
	$resp=mysqli_query($enlaceCon,$sql);
	// $codigo=mysqli_result($resp,0,0);
	$datCodSalida=mysqli_fetch_array($resp);
	$codigo=$datCodSalida[0];

	$nro_correlativo=numeroCorrelativoCotizacion($enlaceCon,1);

	//echo "nro correlativo: ".$nro_correlativo;

	$cod_dosificacion=0;


	$sql_inserta="INSERT INTO cotizaciones(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
		cod_tipo_doc, fecha, hora_salida, territorio_destino, almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada, 
		cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago, monto_efectivo, monto_cambio)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta','$totalEfectivo','$totalCambio')";
		
		//echo $sql_inserta;
		
		$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);

	$contador++;
} while ($sql_inserta<>1 && $contador <= 100);


if($sql_inserta==1){
	$code="";
	$montoTotalVentaDetalle=0;
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		if($codMaterial!=0){
			
			$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
			$precioUnitario=$_POST["precio_unitario$i"];
			$descuentoProducto=$_POST["descuentoProducto$i"];

			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;			
			

			$respuesta=insertar_detalleCotizacion($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
	
			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	}
	
	$montoTotalConDescuento=$montoTotalVentaDetalle-$descuentoVenta;
	//ACTUALIZAMOS EL PRECIO CON EL DETALLE
	$sqlUpdMonto="update cotizaciones set monto_total=$montoTotalVentaDetalle, monto_final=$montoTotalConDescuento 
				where cod_salida_almacenes=$codigo";
	$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);

		
	echo "<script type='text/javascript' language='javascript'>
		location.href='navegadorCotizaciones.php';
	</script>";			
}else{
		echo "<script type='text/javascript' language='javascript'>
			alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		</script>";//location.href='navegador_salidamateriales.php';
}

?>




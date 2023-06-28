<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

//HABILITAMOS LA BANDERA DE VENCIDOS PARA ACTUALIZAR EL PRECIO
$banderaPrecioUpd=0;
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=7";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf = mysqli_fetch_array($respConf);
$banderaPrecioUpd=$datConf[0];
//$banderaPrecioUpd=mysql_result($respConf,0,0);
$banderaPrecioUpd=obtenerValorConfiguracion($enlaceCon,7);

$banderaUpdPreciosSucursales=obtenerValorConfiguracion($enlaceCon,49);

$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat = mysqli_fetch_array($resp);
$codigo=$dat[0];
//$codigo=mysql_result($resp,0,0);

$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat = mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];
//$nro_correlativo=mysql_result($resp,0,0);

$hora_sistema = date("H:i:s");

$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=0;
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$proveedor=$_POST['proveedor'];

$codSucursalIngreso=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE["global_almacen"];

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha_real=date("Y-m-d");

$cod_Ingreso2=0;
if(isset($_POST["cod_salida"])){
  $codSalidaAlmacen=$_POST["cod_salida"];
  $sqlIngreso = "SELECT cod_ingreso_almacen FROM ingreso_almacenes where cod_salida_almacen='$codSalidaAlmacen'";
  //echo $sqlIngreso;
  $respIngreso = mysqli_query($enlaceCon,$sqlIngreso);
  $cod_Ingreso2=mysqli_result($respIngreso,0,0);
}
//echo "cod ingreso: ".$cod_Ingreso2;
if($cod_Ingreso2>0){
	echo "<script language='Javascript'>
		alert('ESTE INGRESO YA FUE REALIZADO, POR FAVOR CONTACTE CON EL ADMINISTRADOR.');
		location.href='navegador_ingresomateriales.php';
		</script>";	
}else{
	/*SI TODO ESTA OK PROCEDEMOS CON EL INGRESO*/
		$codSalida=$_POST['cod_salida'];
		$estadoSalida=4;//recepcionado
		$sqlCambiaEstado="update salida_almacenes set estado_salida='$estadoSalida' where cod_salida_almacenes=$codSalida";
		//echo $sqlCambiaEstado;
		$respCambiaEstado=mysqli_query($enlaceCon,$sqlCambiaEstado);

		$sql_datos_salidaorigen="select s.nro_correlativo, s.cod_tiposalida, a.nombre_almacen, a.cod_ciudad from salida_almacenes s, almacenes a
		where a.cod_almacen=s.cod_almacen and s.cod_salida_almacenes='$codSalida'";
		$resp_datos_salidaorigen=mysqli_query($enlaceCon,$sql_datos_salidaorigen);
		$datos_salidaorigen=mysqli_fetch_array($resp_datos_salidaorigen);
		$codSucursalOrigen=$datos_salidaorigen[3];
	

	$consulta="INSERT into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
	nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
	cod_proveedor,created_by,modified_by,created_date,modified_date) 
	values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones','$codSalida','$nota_entrega','$nro_correlativo',0,0,0,$nro_factura,0,0,'$proveedor','$createdBy','0','$createdDate','')";
	$sql_inserta = mysqli_query($enlaceCon,$consulta);
	//echo "aaaa:$consulta";

	if($sql_inserta==1){
		for ($i = 1; $i <= $cantidad_material; $i++) {
			$cod_material = $_POST["material$i"];
			
			if($cod_material!=0){
				$cantidad=$_POST["cantidad_unitaria$i"];
				$precioBruto=$_POST["precio$i"];
				$lote="0";
				$ubicacionEstante=0;
				$ubicacionFila=0;

				$fechaVencimiento=$_POST["fechaVenc$i"];
				$fechaVencimiento=UltimoDiaMes($fechaVencimiento);

				$precioUnitario=0;
				
				$costo=$precioUnitario;
							
				
				$consulta="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
				precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto, cod_ubicacionestante, cod_ubicacionfila) 
				values($codigo,'$cod_material',$cantidad,$cantidad,'$lote','$fechaVencimiento',$precioUnitario,$precioUnitario,$costo,$costo,$costo,$costo,'$ubicacionEstante','$ubicacionFila')";
				//echo "bbb:$consulta";
				$sql_inserta2 = mysqli_query($enlaceCon,$consulta);							
			

				/*************************************************/
				/*** Verificar los Precios en origen y Destino ***/
				/*************************************************/
				$arrayPrecioSucursalOrigen=precioProductoSucursalMasDescuento($enlaceCon, $cod_material, $codSucursalOrigen);
				$precioSucursalOrigen=$arrayPrecioSucursalOrigen[0];
				$descuentoSucursalOrigen=$arrayPrecioSucursalOrigen[1];

				$precioSucursalDestino=precioProductoSucursal($enlaceCon, $cod_material, $codSucursalIngreso);
				if($precioSucursalOrigen>0 && $precioSucursalDestino==0){
					$arrayPreciosModificar[$codSucursalIngreso]=$precioSucursalOrigen;
					$respModificarPrecios=actualizarPrecios($enlaceCon,$cod_material,$arrayPreciosModificar,$descuentoSucursalOrigen);
				}
				/*************************************************/
				/*** Fin Verificar los Precios en origen y Destino ***/
				/*************************************************/
			}
		}
		
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_ingresomateriales.php';
			</script>";	
	}else{
		echo "<script language='Javascript'>
			alert('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.');
			location.href='navegador_ingresomateriales.php';
			</script>";	
	}
}

?>
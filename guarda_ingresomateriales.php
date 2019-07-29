<?php

require("conexion.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

//HABILITAMOS LA BANDERA DE VENCIDOS PARA SACAR SOLO VENCIDOS
$banderaPrecioUpd=0;
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=7";
$respConf=mysql_query($sqlConf);
$banderaPrecioUpd=mysql_result($respConf,0,0);


$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysql_query($sql);
$codigo=mysql_result($resp,0,0);

$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysql_query($sql);
$nro_correlativo=mysql_result($resp,0,0);

$hora_sistema = date("H:i:s");

$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=0;
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$proveedor=$_POST['proveedor'];

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha_real=date("Y-m-d");


$consulta="insert into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date) 
values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones','0','$nota_entrega','$nro_correlativo',0,0,0,$nro_factura,0,0,'$proveedor','$createdBy','0','$createdDate','')";

$sql_inserta = mysql_query($consulta);
//echo "aaaa:$consulta";

if($sql_inserta==1){
	for ($i = 1; $i <= $cantidad_material; $i++) {
		$cod_material = $_POST["material$i"];
		
		if($cod_material!=0){
			$cantidad=$_POST["cantidad_unitaria$i"];
			$precioBruto=$_POST["precio$i"];
			$lote=$_POST["lote$i"];
			$ubicacionEstante=$_POST["ubicacion_estante$i"];
			$ubicacionFila=$_POST["ubicacion_fila$i"];
			if($lote==""){
				$lote=0;
			}
			$fechaVencimiento=$_POST["fechaVenc$i"];

			$fechaVencimiento=UltimoDiaMes($fechaVencimiento);

			$precioUnitario=$precioBruto/$cantidad;
			
			$costo=$precioUnitario;
						
			
			$consulta="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
			precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto, cod_ubicacionestante, cod_ubicacionfila) 
			values($codigo,'$cod_material',$cantidad,$cantidad,'$lote','$fechaVencimiento',$precioUnitario,$precioUnitario,$costo,$costo,$costo,$costo,$ubicacionEstante,$ubicacionFila)";
			//echo "bbb:$consulta";
			$sql_inserta2 = mysql_query($consulta);
			
			$sqlMargen="select p.margen_precio from material_apoyo m, proveedores_lineas p
				where m.cod_linea_proveedor=p.cod_linea_proveedor and m.codigo_material='$cod_material'";
			$respMargen=mysql_query($sqlMargen);
			$numFilasMargen=mysql_num_rows($respMargen);
			$porcentajeMargen=0;
			if($numFilasMargen>0){
				$porcentajeMargen=mysql_result($respMargen,0,0);			
			}		
			$precioItem=$costo+($costo*($porcentajeMargen/100));
		
			
			if($banderaPrecioUpd==1){
				//SACAMOS EL ULTIMO PRECIO REGISTRADO
				$sqlPrecioActual="select precio from precios where codigo_material='$cod_material' and cod_precio=1";
				$respPrecioActual=mysql_query($sqlPrecioActual);
				$numFilasPrecios=mysql_num_rows($respPrecioActual);
				$precioActual=0;
				if($numFilasPrecios>0){
					$precioActual=mysql_result($respPrecioActual,0,0);
				}
				
				//echo "precio +margen: ".$precioItem." precio actual: ".$precioActual;
				//SI NO EXISTE EL PRECIO LO INSERTA CASO CONTRARIO VERIFICA QUE EL PRECIO DEL INGRESO SEA MAYOR AL ACTUAL PARA HACER EL UPDATE
				if($numFilasPrecios==0){
					$sqlPrecios="insert into precios (codigo_material, cod_precio, precio) values('$cod_material','1','$precioItem')";
					$respPrecios=mysql_query($sqlPrecios);
				}else{
					if($precioItem>$precioActual){
						$sqlPrecios="update precios set precio='$precioItem' where codigo_material='$cod_material' and cod_precio=1";
						$respPrecios=mysql_query($sqlPrecios);
					}
				}				
			}
			
			$aa=recalculaCostos($cod_material, $global_almacen);
			
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

?>
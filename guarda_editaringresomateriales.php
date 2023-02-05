<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

//HABILITAMOS LA BANDERA DE VENCIDOS PARA ACTUALIZAR EL PRECIO
$banderaPrecioUpd=obtenerValorConfiguracion($enlaceCon,7);
$banderaUpdPreciosSucursales=obtenerValorConfiguracion($enlaceCon,49);


$codIngreso=$_POST["codIngreso"];
$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=$_POST['nota_entrega'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$codSalida=$_POST['codSalida'];
$cantidad_material=$_POST['cantidad_material'];
$fecha_real=date("Y-m-d");

$consulta="update ingreso_almacenes set cod_tipoingreso='$tipo_ingreso', nro_factura_proveedor='$nro_factura', 
		observaciones='$observaciones' where cod_ingreso_almacen='$codIngreso'";
$sql_inserta = mysqli_query($enlaceCon, $consulta);

$sqlDel="delete from ingreso_detalle_almacenes where cod_ingreso_almacen='$codIngreso'";
$respDel=mysqli_query($enlaceCon, $sqlDel);

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
			values($codIngreso,'$cod_material',$cantidad,$cantidad,'$lote','$fechaVencimiento',$precioUnitario,$precioUnitario,$costo,$costo,$costo,$costo,'$ubicacionEstante','$ubicacionFila')";
			
			echo "bbb:$consulta";
			
			$sql_inserta2 = mysqli_query($enlaceCon,$consulta);
			
			
			$precioItem=$_POST["preciocliente$i"];
			
			//ARMAMOS EL ARRAY CON LOS PRECIOS
			$arrayPreciosModificar=[];
			$sqlSucursales="select cod_ciudad, descripcion from ciudades ";
			if($banderaUpdPreciosSucursales==0){
				$sqlSucursales=$sqlSucursales." where cod_ciudad='$codSucursalIngreso'";
			}
			echo $sqlSucursales;
			$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
			while($datSucursales=mysqli_fetch_array($respSucursales)){
				$codCiudadPrecio=$datSucursales[0];
				$precioProductoModificar=$precioItem;
				$arrayPreciosModificar[$codCiudadPrecio]=$precioProductoModificar;
			}

			
			/*SOLO CUANDO ESTAN ACTIVADOS LOS CAMBIOS DE PRECIO Y EL TIPO DE INGRESO ES POR LABORATORIO*/
			if($banderaPrecioUpd>0 && $tipo_ingreso==1000){
				//SACAMOS EL ULTIMO PRECIO REGISTRADO
				$sqlPrecioActual="select precio from precios where codigo_material='$cod_material' and cod_precio=1 and cod_ciudad='$codSucursalIngreso'";
				$respPrecioActual=mysqli_query($enlaceCon,$sqlPrecioActual);
				$numFilasPrecios=mysqli_num_rows($respPrecioActual);
				$precioActual=0;
				if($numFilasPrecios>0){
					$datPrecioActual = mysqli_fetch_array($respPrecioActual);
					$precioActual=$datPrecioActual[0];
				}
								
				//SI NO EXISTE EL PRECIO LO INSERTA CASO CONTRARIO VERIFICA QUE EL PRECIO DEL INGRESO SEA MAYOR AL ACTUAL PARA HACER EL UPDATE
				if($banderaPrecioUpd==1){
					if($precioItem!=$precioActual){
						$respModificarPrecios=actualizarPrecios($enlaceCon,$cod_material,$arrayPreciosModificar);
					}
				}
				if($banderaPrecioUpd==2){
					if($precioItem>$precioActual){
						$respModificarPrecios=actualizarPrecios($enlaceCon,$cod_material,$arrayPreciosModificar);
					}
				}
			}
			$aa=recalculaCostos($enlaceCon,$cod_material, $global_almacen);			
		}
	}
	
	//var_dump($arrayPreciosModificar);

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
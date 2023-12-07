<html>
<body>
<table align='center' class="texto">
<tr>
<th><input type='checkbox' id='selecTodo'  onchange="marcarDesmarcar(form1,this)" ></th><th>Codigo</th><th style='background-color: $colorFV; text-align: center;'>FV</th><th>Producto</th><th>Linea</th><th>Principio Activo</th><th>Accion Terapeutica</th><th>Stock</th><th>Precio</th></tr>
<?php
require("conexionmysqli2.inc");
require("funciones.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$codigoMat=0;
$nomAccion="";
$nomPrincipio="";

$codigoCiudadGlobal=$_COOKIE["global_agencia"];

if(isset($_GET['codigoMat'])){
	$codigoMat=$_GET['codigoMat'];
}
if(isset($_GET['nomAccion'])){
	$nomAccion=$_GET['nomAccion'];
}
if(isset($_GET['nomPrincipio'])){
	$nomPrincipio=$_GET['nomPrincipio'];	
}
$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];

if($itemsNoUtilizar==""){
	$itemsNoUtilizar=0;
}

$tipoSalida=$_GET['tipoSalida'];

$fechaActual=date("Y-m-d");

$indexFila=0;

//SACAMOS LA CONFIGURACION PARA LA SALIDA POR VENCIMIENTO
$tipoSalidaVencimiento=obtenerValorConfiguracion($enlaceCon,5);
//Bandera para mostrar la Fecha de Vencimiento en la Factura o no
$banderaMostrarFV=obtenerValorConfiguracion($enlaceCon,20);
//Bandera para buscar desde el nombre de producto tambien el principio activo
$banderaBuscarPA=obtenerValorConfiguracion($enlaceCon,22);
//Bandera para mostrar el Codigo con Costo de Compra
$banderaCodigoCostoCompra=obtenerValorConfiguracion($enlaceCon,26);
//Bandera para mostrar 1 Decimal o 2 Decimales en el precio
$bandera1DecimalPrecioVenta=obtenerValorConfiguracion($enlaceCon,27);
// Obtenemos control de fecha
$numeroMesesControlVencimiento = obtenerValorConfiguracion($enlaceCon, 28);

	$sql="select m.codigo_material, m.descripcion_material,
	(select concat(p.nombre_proveedor,'-',pl.nombre_linea_proveedor)as nombre_proveedor
	from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor), m.principio_activo, m.accion_terapeutica, m.bandera_venta_unidades, m.cantidad_presentacion
	from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($codigoMat!=""){
		$sql=$sql. " and codigo_material='$codigoMat'";
	}
	if($nombreItem!="" && $banderaBuscarPA==1){
		$sql=$sql. " and (descripcion_material like '%$nombreItem%' or principio_activo like '%$nombreItem%')";
	}elseif($nombreItem!="" && $banderaBuscarPA!=1){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}

	/*if($tipoSalidaVencimiento==$tipoSalida){
		$sql=$sql. " and m.codigo_material in (select id.cod_material from ingreso_almacenes i, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$globalAlmacen' and i.ingreso_anulado=0 
		and id.fecha_vencimiento<'$fechaActual') ";
	}*/
	if((int)$codTipo>0){
    	if(isset($_GET["codProv"])){
          $sql=$sql." and m.cod_linea_proveedor in (SELECT cod_linea_proveedor from proveedores_lineas where cod_proveedor=".$_GET["codProv"].")";
    	}else{
    	  $sql=$sql." and m.cod_linea_proveedor=".$codTipo."";	
    	}        
    }
	if($nomAccion!=""){
		$sql=$sql. " and accion_terapeutica like '%$nomAccion%'";
	}
	if($nomPrincipio!=""){
		$sql=$sql. " and principio_activo like '%$nomPrincipio%'";
	}
	$sql=$sql." order by 2";
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		$cont=0;
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$linea=$dat[2];
			$principioActivo=$dat[3];
			$accionTerapeutica=$dat[4];
			$ventaSoloCajas=$dat[5];
			$cantidadPresentacion=$dat[6];
			
			$nombre=addslashes($nombre);
			$linea=addslashes($linea);
			
			$stockProducto=0;

			/*if($tipoSalida==$tipoSalidaVencimiento){
				$stockProducto=stockProductoVencido($enlaceCon,$globalAlmacen, $codigo);
			}else{
				$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);
			}*/
			$stockProducto=stockProducto($enlaceCon,$globalAlmacen, $codigo);

			// Stock Producto COLOR
			$stockColor = ($stockProducto <= $cantidadPresentacion) ? 'yellow' : 'transparent';
								
			$datosProd=$codigo."|".$nombre."|".$linea."|".$stockProducto."|".$stockColor;
		

			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and 
					cod_ciudad='$codigoCiudadGlobal'";
					
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			$precioProducto=$registro[0];
			if($precioProducto=="")
			{   $precioProducto=0;
			}
			if($bandera1DecimalPrecioVenta==1){
				$precioProducto=round($precioProducto,1);
			}else{
				$precioProducto=redondear2($precioProducto);
			}
			$mostrarFila=1;
			if(isset($_GET["stock"])){
				 if($_GET["stock"]==1&&$stockProducto<=0){
                    $mostrarFila=0;
				 }  	              
			}

			/*Mostrar la Fecha de Vencimiento*/
			$colorFV = 'white';
			$txtFechaVencimiento="-";
			if($banderaMostrarFV==1){
				$txtFechaVencimiento=obtenerFechaVencimiento($enlaceCon, $globalAlmacen, $codigo);
				//$txtFechaVencimiento="<span class='textogranderojo'><small>$txtFechaVencimiento</small></span>";
				$txtFechaVencimiento="<small><b>$txtFechaVencimiento</b></small>";
			}
			/*Fin Fecha de Vencimiento*/
			
			/* Se obtiene la diferencia de meses con la fecha actual */
			$fechaVencimiento = obtenerFechaVencimiento($enlaceCon, $globalAlmacen, $codigo);
			
			if($fechaVencimiento!=""){
				list($mes, $anio) = explode("/", $fechaVencimiento);
				$hoy = date('m/Y');
				list($mesHoy, $anioHoy) = explode("/", $hoy);
				$mesesDiferencia = (($anio - $anioHoy) * 12) + ($mes - $mesHoy);

				$controlVencimientoArray 	   = json_decode($numeroMesesControlVencimiento, true);
				usort($controlVencimientoArray, function($a, $b) {
					return $a['meses'] <=> $b['meses'];
				});
				$colorFV = '';
				foreach ($controlVencimientoArray as $item) {
					if ($mesesDiferencia <= $item['meses']) {
						$colorFV = $item['color'];
						break;
					} else {
						$colorFV = 'white';
					}
				}				
			}

			/* Fin diferencia de fecha */

			/**  Codigo Costo Compra***/
			$txtCodigoCostoCompra="";
			if($banderaCodigoCostoCompra==1){
				$sqlCostoCompra="SELECT concat(FORMAT(id.costo_almacen,1),'0',FORMAT((id.costo_almacen*1.25),1)) from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
					i.ingreso_anulado=0 and i.cod_tipoingreso in (999,1000) and id.cod_material='$codigo' order by i.cod_ingreso_almacen desc limit 0,1";
				$respCostoCompra=mysqli_query($enlaceCon,$sqlCostoCompra);
				if($datCostoCompra=mysqli_fetch_array($respCostoCompra)){
					$txtCodigoCostoCompra=$datCostoCompra[0];
				}
				//$txtCodigoCostoCompra=str_replace(".", "", $txtCodigoCostoCompra);
			}
			/*****Fin Bandera Costo Compra*****/
			if($mostrarFila==1){
				$indexFila++;

			  	if($stockProducto>0){
					$stockProductoFormat="<b class='textograndenegro' style='color:#C70039'>".$stockProducto."</b>";
			  	}else{
			  		$stockProductoFormat=$stockProducto;
			  	}
				echo "<tr><td><input type='checkbox' id='idchk$cont' name='idchk$cont' value='$datosProd' onchange='ver(this)' ></td>
					<td>$codigo</td>
					<td style='background-color: $colorFV; text-align: center;'>
						<b>$fechaVencimiento</b>
					</td>
					<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre - $linea ($codigo)-$txtCodigoCostoCompra ####$txtFechaVencimiento####$cantidadPresentacion####$ventaSoloCajas####$precioProducto \",$stockProducto)'>$nombre</a></div></td>
				<td>$linea</td>
				<td><small>$principioActivo</small></td>
				<td><small>$accionTerapeutica</small></td>
				<td style='background-color: $stockColor;'>$stockProductoFormat</td>
				<td>$precioProducto</td>
				</tr>";
				$cont++;
			}
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>
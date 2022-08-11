<?php
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
$start_time = microtime(true);
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");
require("funcion_nombres.php");
require("funciones_inventarios.php");

$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

$idLineaProveedor=$_POST["id_linea_proveedor"];
$nombreLineaProveedorX=nombreLineaProveedor($enlaceCon, $idLineaProveedor);

$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=1007;
$tipoDoc=3;
$almacenDestino=0;
$almacenOrigen=$global_almacen;

$cod_tipopreciogeneral=0;
$cod_tipoVenta2=1;
$cod_tipodelivery=0;
$monto_bs=0;
$monto_usd=0;
$tipo_cambio=0;
$tipoVenta=0;
$observaciones="Salida por Ajuste de Inventario por Linea. Linea $nombreLineaProveedorX";
$totalVenta=0; 
$descuentoVenta=0; 
$totalFinal=0; 
$totalEfectivo=0;    
$totalCambio=0;  
$complemento=0;  
$totalFinalRedondeado=0;

$fecha=date("Y-m-d");
$hora=date("H:i:s");

$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];
$created_by=$usuarioVendedor;
$contador = 0;

do {
    $anio=date("Y");
    $created_at=date("Y-m-d H:i:s");
    $sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
    $resp=mysqli_query($enlaceCon,$sql);
    // $codigo=mysqli_result($resp,0,0);
    $datCodSalida=mysqli_fetch_array($resp);
    $codigo=$datCodSalida[0];

    $vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDoc);
    $nro_correlativo=$vectorNroCorrelativo[0];
    $cod_dosificacion=0;

    
    $sql_inserta="INSERT INTO salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
    cod_tipo_doc, fecha, hora_salida, territorio_destino, almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada, 
    cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago)
    values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
    '$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta')";
    $sql_inserta=mysqli_query($enlaceCon,$sql_inserta);
    $contador++;
} while ($sql_inserta<>1 && $contador <= 100);

if($sql_inserta==1){
    $montoTotalVentaDetalle=0;
    $i=1;
    foreach($_POST as $nombre_campo => $valor){ 
        $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
        $cadenaBuscar='producto';
        $posicion = strpos($nombre_campo, $cadenaBuscar);
        if ($posicion === false) {
        }else{
            list($nombreCampoX, $codProductoX)=explode("|",$nombre_campo);  
            $codigoProductoX=$codProductoX;
            $cantidadProductoX=$_POST["producto|".$codProductoX];
            $stockProductoX=$_POST["stock|".$codProductoX];
            $cantidadInsertX=$stockProductoX-$cantidadProductoX;
            $precioUnitario=0;
            $descuentoProducto=0;
            $montoMaterial=0;
            if($cantidadInsertX>0){
                //echo "PRODUCTO: ".$codProductoX." STOCK: ".$stockProductoX." CANT INSERT: ".$cantidadInsertX;
                if($banderaValidacionStock==1){
                $respuesta=descontar_inventarios($enlaceCon,$codigo, $almacenOrigen,$codigoProductoX,$cantidadInsertX,$precioUnitario,$descuentoProducto,$montoMaterial,$i);
                }else{
                    $respuesta=insertar_detalleSalidaVenta($enlaceCon,$codigo, $almacenOrigen,$codigoProductoX,$cantidadInsertX,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
                }
                if($respuesta!=1){
                    echo "<script>
                        alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
                    </script>";
                }
                $i++;
            }
        }
    }
    echo "<h2>Se ajustaron correctamente las Salidas!</h2>";  
}else{
    echo "<h2>Ocurrio un error en la transaccion. Contacte con el administrador del sistema.</h2>";
}






/*ESTA PARTE ES PARA LOS INGRESOS*/
$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat = mysqli_fetch_array($resp);
$codigo=$dat[0];

$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat = mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];

$hora_sistema = date("H:i:s");
$tipo_ingreso=1002;
$nota_entrega=0;
$nro_factura=0;
$observaciones="Ingreso por ajuste de Linea. Linea $nombreLineaProveedorX";
$proveedor=0;

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");
$fecha_real=date("Y-m-d");

$consulta="INSERT into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date) 
values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones','0','$nota_entrega','$nro_correlativo',0,0,0,$nro_factura,0,0,'$proveedor','$createdBy','0','$createdDate','')";
$sql_inserta = mysqli_query($enlaceCon,$consulta);

if($sql_inserta==1){
    $i=1;
    foreach($_POST as $nombre_campo => $valor){ 
        $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
        $cadenaBuscar='producto';
        $posicion = strpos($nombre_campo, $cadenaBuscar);
        if ($posicion === false) {
        }else{
            list($nombreCampoX, $codProductoX)=explode("|",$nombre_campo);  
            $codigoProductoX=$codProductoX;
            $cantidadProductoX=$_POST["producto|".$codProductoX];
            $stockProductoX=$_POST["stock|".$codProductoX];
            $cantidadInsertX=$stockProductoX-$cantidadProductoX;
            $precioUnitario=0;
            $descuentoProducto=0;
            $montoMaterial=0;
            if($cantidadInsertX<0){
                //echo "PRODUCTO: ".$codProductoX." STOCK: ".$stockProductoX." CANT INSERT: ".$cantidadInsertX;
                $cantidadInsertX=$cantidadInsertX*(-1);
                $lote="LA01";
                $fechaVencimiento="2024-08-30";
                $precioUnitario=0;
                $costo=0;
                $ubicacionEstante=0;
                $ubicacionFila=0;
                $consulta="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
                precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto, cod_ubicacionestante, cod_ubicacionfila) 
                values($codigo,'$codigoProductoX',$cantidadInsertX,$cantidadInsertX,'$lote','$fechaVencimiento',$precioUnitario,$precioUnitario,$costo,$costo,$costo,$costo,'$ubicacionEstante','$ubicacionFila')";
                $respuesta = mysqli_query($enlaceCon,$consulta);
            
                if($respuesta!=1){
                    echo "<h2>Ocurrio un error en la transaccion. Contacte con el administrador del sistema.</h2>";
                }
                $i++;
            }
        }
    }    
    echo "<h2>Se ajustaron correctamente los Ingresos!</h2>";  
}

?>




<?php

    include "conexionmysqli.inc";
    require_once('funciones.php');
    
require('funcion_nombres.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php'); 
$cod_ciudad=$_COOKIE["global_agencia"];

if(isset($_GET["codigo_salida"])){
    $codigoVenta=$_GET["codigo_salida"];
}else{
    $codigoVenta=$codigoVenta;
}

//OBTENEMOS EL LOGO Y EL NOMBRE DEL SISTEMA
$logoEnvioEmail=obtenerValorConfiguracion($enlaceCon,13);
$nombreSistemaEmail=obtenerValorConfiguracion($enlaceCon,12);

$sqlConf="select id, valor from configuracion_facturas where id=1 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nombreTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=10 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nombreTxt2=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=2 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$sucursalTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=3 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$direccionTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=4 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$telefonoTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=5 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$ciudadTxt=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=6 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt1=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from siat_leyendas where id=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt2=mysqli_result($respConf,0,1);


$sqlConf="select id, valor from configuracion_facturas where id=9 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nitTxt=mysqli_result($respConf,0,1);


$sqlDatosFactura="select '' as nro_autorizacion, '', '' as codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.siat_fechaemision, '%d/%m/%Y') from salida_almacenes f
    where f.cod_salida_almacenes=$codigoVenta";
    
//echo $sqlDatosFactura;
$respDatosFactura=mysqli_query($enlaceCon,$sqlDatosFactura);
$nroAutorizacion=mysqli_result($respDatosFactura,0,0);
$fechaLimiteEmision=mysqli_result($respDatosFactura,0,1);
$codigoControl=mysqli_result($respDatosFactura,0,2);
$nitCliente=mysqli_result($respDatosFactura,0,3);
$razonSocialCliente=mysqli_result($respDatosFactura,0,4);
$razonSocialCliente=strtoupper($razonSocialCliente);
$fechaFactura=mysqli_result($respDatosFactura,0,5);


$cod_funcionario=$_COOKIE["global_usuario"];
//datos documento
$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, 'cliente', s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf,s.siat_complemento,(SELECT nombre_tipopago from tipos_pago where cod_tipopago=s.cod_tipopago) as nombre_pago,s.siat_fechaemision,s.siat_codigotipoemision,s.siat_codigoPuntoVenta,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,(SELECT siat_unidadProducto from ciudades where cod_ciudad in (select cod_ciudad from almacenes where cod_almacen=s.cod_almacen)) as unidad_medida
        from `salida_almacenes` s, `tipos_docs` t, `clientes` c
        where s.`cod_salida_almacenes`='$codigoVenta' and s.cod_tipo_doc=t.codigo";
        //echo $sqlDatosVenta;
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
$siat_complemento="";
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
    $cuf=$datDatosVenta['siat_cuf'];
    $fechaVenta=$datDatosVenta[0];
    $nombreTipoDoc=$datDatosVenta[1];
    $nombreCliente=$datDatosVenta[2];
    $nroDocVenta=$datDatosVenta[3];
    $descuentoVenta=$datDatosVenta[4];
    $descuentoVenta=redondear2($descuentoVenta);
    $horaFactura=$datDatosVenta[5];
    $montoTotal2=$datDatosVenta['monto_total'];
    $montoFinal2=$datDatosVenta['monto_final'];
    $montoEfectivo2=$datDatosVenta['monto_efectivo'];
    $montoCambio2=$datDatosVenta['monto_cambio'];
    $montoTotal2=redondear2($montoTotal2);
    $montoFinal2=redondear2($montoFinal2);

    $montoEfectivo2=redondear2($montoEfectivo2);
    $montoCambio2=redondear2($montoCambio2);

    $descuentoCabecera=$datDatosVenta['descuento'];
    $cod_funcionario=$datDatosVenta['cod_chofer'];
    $tipoPago=$datDatosVenta['cod_tipopago'];
    $tipoDoc=$datDatosVenta['nombre'];
    $codTipoDoc=$datDatosVenta['cod_tipo_doc'];

    $fecha_salida=$datDatosVenta['fecha'];
    $hora_salida=$datDatosVenta['hora_salida'];
    $cod_ciudad_salida=$datDatosVenta['cod_ciudad'];
    $cod_cliente=$datDatosVenta['cod_cliente'];

    $siat_complemento=$datDatosVenta['siat_complemento'];

    $siat_codigotipoemision=$datDatosVenta['siat_codigotipoemision'];
    $siat_codigopuntoventa=$datDatosVenta['siat_codigoPuntoVenta'];

    $unidad_medida=$datDatosVenta['unidad_medida'];

    $nombrePago=$datDatosVenta['nombre_pago'];
    $txt3=$datDatosVenta['leyenda'];
    $fechaFactura=date("d/m/Y H:i:s",strtotime($datDatosVenta['siat_fechaemision']));
    // $nombrePago="EFECTIVO";
    // if($tipoPago!=1){
    //     $nombrePago="TARJETA/OTROS";
    // }
}

if($siat_codigotipoemision==2){
    $sqlConf="select id, valor from siat_leyendas where id=3";
}else{
    $sqlConf="select id, valor from siat_leyendas where id=2";
}
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txtLeyendaFin=mysqli_result($respConf,0,1);


ob_start();
?>    
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <title>Factura <?=$nroDocVenta?></title>
</head>
<style type="text/css">
    
    :root {
    --body-bg: rgb(204, 204, 204);
    --white: #ffffff;
    --darkWhite: #ccc;
    --black: #000000;
    --dark: #615c60;
    --themeColor: #0FBDA5;
    --pageShadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
}

body {
    background-color: var(--body-bg);
    font-family: Sans-serif !important;
}

.page {
    background: var(--white);
    display: block;
    margin: 0 auto;
    position: relative;
    box-shadow: var(--pageShadow);
}

.page[size="A4"] {
    width: 18cm;
}

.bb {
    border-bottom: 3px solid var(--darkWhite);
}

/* Top Section */
.top-content {
    padding-bottom: 15px;
}

.logo img {
    height: 60px;
}

.top-left p {
    margin: 0;
}

.top-left .graphic-path {
    height: 40px;
    position: relative;
}

.top-left .graphic-path::before {
    content: "";
    height: 20px;
    background-color: var(--dark);
    position: absolute;
    display: inline-block;
    left: 15px;    
    right: 0;    
    top: -15px;
    z-index: 2;
}

.top-left .graphic-path::after {
    content: "";
    height: 22px;
    width: 17px;
    background: var(--black);
    position: absolute;    
    top: -13px;
    left: 6px;
    transform: rotate(45deg);
}

.top-left .graphic-path p {
    color: var(--white);
    height: 40px;
    left: 0;
    right: -50px;
    text-transform: uppercase;
    background-color: var(--themeColor);
    font: 26px;
    z-index: 3;
    position: absolute;
    padding-left: 10px;
}

/* User Store Section */
.store-user {
    padding-bottom: 25px;
}

.store-user p {
    margin: 0;
    font-weight: 600;
}

.store-user .address {
    font-weight: 400;
}

.store-user h2 {
    color: var(--themeColor);
    font-family: 'Rajdhani', sans-serif;
    font-size: 26px;
}

.extra-info p span {
    font-weight: 400;
}

/* Product Section */
thead {
    color: var(--white);
    background: var(--themeColor);
}

.table td,
.table th {
    text-align: center;
    vertical-align: middle;
}

tr th:first-child,
tr td:first-child {
    text-align: left;
}

.media img {
    height: 60px;
    width: 60px;
}

.media p {
    font-weight: 400;
    margin: 0;
}

.media p.title {
    font-weight: 200;
}

/* Balance Info Section */
.balance-info .table td,
.balance-info .table th {
    padding: 0;
    border: 0;
}

.balance-info tr td:first-child {
    font-weight: 600;
}

tfoot {
    border-top: 2px solid var(--darkWhite);
}

tfoot td {
    font-weight: 600;
}

/* Cart BG */
.cart-bg {
    height: 200px;
    bottom: 70px;
    left: -0px;
    opacity: 0.3;
    position: absolute;
}

/* Footer Section */
footer {
    text-align: center;
    position: absolute;
    bottom: 30px;
    left: 75px;
    /*top: 100px !important;*/
}

footer hr {
    margin-bottom: -22px;
    border-top: 3px solid var(--darkWhite);
}

footer a {
    color: var(--themeColor);
}

footer p {
    padding: 6px;
    border: 3px solid var(--darkWhite);
    background-color: var(--white);
    display: inline-block;
}
.productos{
    border-collapse:collapse;
}
.productos tr th,.productos tr td{    
    border: 1px solid #000;
}


</style>
<body>
    <div class="my-5 page" size="A4">
        <div class="p-5">
            <section class="top-content bb d-flex justify-content-between">
                
            <table>
                <tr><td><div class="logo">
                    <img src="<?=__DIR__?>/imagenes/<?=$logoEnvioEmail;?>" alt="" class="img-fluid" style="width: 200px;">
                </div></td>
                    <td><div class="top-left">
                    <div class="position-relative">
                        <div style="font-size: 15px;width:100%;background:#14AF91;color:#fff;padding: 5px;"><p>FACTURA <br><small><small>(Con Derecho a Crédito Fiscal)</small></small></p></div>                        
                        <p style="width:300px !important;hyphens: auto;word-wrap: break-word;word-break: break-word;font-size: 10px;">Código Autorización: <span><?=$cuf?></span></p><p style='font-size: 14px;'>Factura No. <span><?=$nroDocVenta?></span></p>
                    </div>
                </div></td>
                </tr>
            </table>
                
            </section>
            <section class="store-user mt-5">
                <div class="col-10">
                    <div class="row bb pb-3">
                        <table style="width: 100%;font-size: 12px;">
                            <tr><td width="40%"><div class="col-7">
                            <p><?=$nombreTxt?></p>
                            <h4 style="color:#14AF91"><?=$sucursalTxt?></h4>
                            <div class="txn mt-2">Punto de Venta: <?=$siat_codigopuntoventa?></div>
                            <div class="txn mt-2">NIT: <?=$nitTxt?></div>
                            <p class="address"> <?=$direccionTxt?></p>
                            <div class="txn mt-2">Tel: <?=$telefonoTxt?></div>
                            <div class="txn mt-2">San Jose Chiquitos</div>
                            </div></td><td width="60%" valign="top"><div class="col-5">
                            <p>Nombre/Razón Social</p>
                            <h4 style="color:#14AF91"><?=$razonSocialCliente?></h4>
                            <!-- <p class="address"><?=$nombreCliente?></p> -->
                            <p class="address">NIT/CI/CEX: <?=$nitCliente." ".$siat_complemento?></p>
                            <p class="address">Cod. Cliente: <?=$cod_cliente?></p>
                            
                            <!-- <p class="address">Tel:</p> -->
                        </div></td></tr>
                        </table>
                        
                        
                    </div>
                    <div class="row extra-info pt-3" style="font-size: 12px;">
                        <div class="col-7">
                            <p>Tipo de Pago: <span><?=$nombrePago?></span></p>
                            
                            
                        </div>
                        <div class="col-5">
                            <p>Fecha de Factura: <span><?=$fechaFactura?></span></p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="product-area mt-4">
                <table class="table table-hover productos" style="width: 100%;font-size: 10px !important;">
                    <thead>
                        <tr style="background: #4CD1AC;font-size: 9px !important">
                            <th width="13%">Codigo<br>Producto/Servicio</th>                            
                            <th width="40%">DESCRIPCIÓN</th>
                            <th width="13%">Unidad Medida</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>                                                    
                            <th>Descuento</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
        sum(s.`descuento_unitario`), sum(s.`monto_unitario`) from `salida_detalle_almacenes` s, `material_apoyo` m where 
        m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta 
        group by s.cod_material
        order by s.orden_detalle";
$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

$yyy=65;

$montoTotal=0;$descuentoVentaProd=0;
while($datDetalle=mysqli_fetch_array($respDetalle)){
    $codInterno=$datDetalle[0];
    $cantUnit=$datDetalle[1];
    $nombreMat=$datDetalle[2];
    $precioUnit=$datDetalle[3];
    $descUnit=$datDetalle[4];
    //$montoUnit=$datDetalle[5];
    $montoUnit=($cantUnit*$precioUnit)-$descUnit;
    
    //recalculamos el precio unitario para mostrar en la factura.
    //$precioUnitFactura=$montoUnit/$cantUnit;
    $precioUnitFactura=($cantUnit*$precioUnit)/$cantUnit;
    $cantUnit=redondear2($cantUnit);
    $precioUnit=redondear2($precioUnit);
    $montoUnit=redondear2($montoUnit);
    
    $precioUnitFactura=redondear2($precioUnitFactura);
    // $precioUnitFactura=number_format($precioUnitFactura,2);

    // - $descUnit
    $descUnit=redondear2($descUnit);  
    // $descUnit=number_format($descUnit,2);  
    $descuentoVentaProd+=$descUnit;
    $montoUnitProd=($cantUnit*$precioUnit);

    $montoUnitProdDesc=$montoUnitProd-$descUnit;
    $montoUnitProdDesc=redondear2($montoUnitProdDesc);
    // $montoUnitProdDesc=number_format($montoUnitProdDesc,2);

    $montoUnitProd=redondear2($montoUnitProd);


    // $dir_imagen_producto = $datDetalle['dir_imagen'];
    // if($dir_imagen_producto==""){
    //       $dir_imagen_producto="imagenes/imagen_prod.jpg";
    // }


    ?>
    <tr>
        <td style="text-align: left;"><?=$codInterno?></td>
        <td style="text-align: left;">
            <div class="media">
                <!-- <img class="mr-3 img-fluid" src="<?=__DIR__?>/<?=$dir_imagen_producto?>" alt="Pd"> -->
                <div class="media-body">
                    <p class="mt-0 title" style="font-size: 10px !important;"><small><?=$nombreMat?></small></p>                                        
                </div>
            </div>
        </td>
        <td style="font-size: 11px !important;"><small><?=$unidad_medida?></small></td>
        <td style="text-align: right;"><?=$cantUnit?></td>
        <td style="text-align: right;"><?=number_format($precioUnitFactura,2)?></td>                                                        
        <td style="text-align: right;"><?=number_format($descUnit,2)?></td>
        <td style="text-align: right;"><?=number_format($montoUnitProdDesc,2)?></td>
    </tr>

    <?php
    //$montoTotal=$montoTotal+$montoUnitProd; 
    $montoTotal=$montoTotal+$montoUnitProdDesc; 
    $yyy=$yyy+6;
}

// echo $montoTotal;
$descuentoVenta=number_format($descuentoVenta,2,'.','');
//$montoFinal=$montoTotal-$descuentoVenta-$descuentoVentaProd;
$montoFinal=$montoTotal-$descuentoVenta;
//$montoTotal=number_format($montoTotal,1,'.','')."0";
$montoFinal=number_format($montoFinal,2,'.','');



$arrayDecimal=explode('.', $montoFinal);
if(count($arrayDecimal)>1){
    list($montoEntero, $montoDecimal) = explode('.', $montoFinal);
}else{
    list($montoEntero,$montoDecimal)=array($montoFinal,0);
}

if($montoDecimal==""){
    $montoDecimal="00";
}
$txtMonto=NumeroALetras::convertir($montoEntero);

?>



<?php
$cadenaQR=$nitTxt."|".$nroDocVenta."|".$nroAutorizacion."|".$fechaVenta."|".$montoTotal."|".$montoFinal."|".$codigoControl."|".$nitCliente."|0|0|0|".($descuentoVentaProd+$descuentoVenta);
$codeContents = $cadenaQR; 

$fechahora=date("dmy.His");
$fileName="qrs/".$fechahora.$nroDocVenta.".png"; 
    
QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 3);

?>
<!-- <img src="<?=$fileName?>" style="margin: 0px;padding: 0;"> -->
<?php

//CAMPAÑAS APLICADAS
?>
                      
                    </tbody>
                </table>
            </section>

            <section class="balance-info">
                <div class="row">
                    <table style="width: 100%;font-size: 12px;">
                        <tr>
                            <td width="65%"><div class="col-8">
                        <!-- <p class="m-0 font-weight-bold"> Son: </p> -->
                        <p>Son: <?="$txtMonto"." ".$montoDecimal."/100 Bolivianos"?></p>
                    </div></td>
                            <td width="35%"><br><div class="col-4">
                        <table class="table border-0 table-hover" style="width: 100%;font-size: 12px;">
                            <tr>
                                <td style="text-align: left;font-weight: none;">SUBTOTAL Bs:</td>
                                <td style="text-align: right;"><?=number_format($montoTotal,2)?></td>
                            </tr>

                            <tr>
                                <td style="text-align: left;font-weight: none;">DESCUENTO Bs:</td>
                                <td style="text-align: right;"><?=number_format($descuentoVenta,2)?></td>
                            </tr>
                            <tfoot>
                                <tr>
                                    <td style="text-align: left;">TOTAL Bs:</td>
                                    <td style="text-align: right;"><?=number_format($montoFinal,2)?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">MONTO A PAGAR Bs:</td>
                                    <td style="text-align: right;"><?=number_format($montoFinal,2)?></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">IMPORTE BASE CRÉDITO FISCAL:</td>
                                    <td style="text-align: right;"><?=number_format($montoFinal,2)?></td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Signature -->
                        <div class="col-12">
                                                      
                        </div>
                    </div></td>
                        </tr>
                    </table>
                    
                    
                </div>
                <div class="row">
                    <div class="col-8">
                    </div>
                    <div class="col-4">
                        <?php

             $sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
$respDir=mysqli_query($enlaceCon,$sqlDir);
$urlDir=mysqli_result($respDir,0,0);
           
$cadenaQR=$urlDir."/consulta/QR?nit=$nitTxt&cuf=$cuf&numero=$nroDocVenta&t=2";
$codeContents = $cadenaQR; 

$fechahora=date("dmy.His");
$fileName=__DIR__."/qrs/".$fechahora.$nroDocVenta.".png"; 
    
QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 3);
//$txt3=iconv('utf-8', 'windows-1252', $txt3); 
?>
                    </div>
                </div> 
            </section>
            <footer>
                <table border="0">
                    <td><img src="<?=$fileName?>" style="margin: 0px;padding: 0;width: 120px;"></td>
                    <td align="center" class="table table-hover productos" style="font-size: 11px !important;">
                            <?=$txt2?><br> 
                            <?=$txt3?><br>
                            <?=$txtLeyendaFin?>                       
                    </td>
                </table>
            </footer>
        </div>
    </div>

</body></html>
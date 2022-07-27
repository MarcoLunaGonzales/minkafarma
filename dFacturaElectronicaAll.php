<?php
    include "conexionmysqli.inc";
    require('funciones.php');
require('funcion_nombres.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php'); 
$cod_ciudad=$_COOKIE["global_agencia"];
$codigoVenta=$_GET["codigo_salida"];
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

$sqlConf="select id, valor from configuracion_facturas where id=7 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt2=mysqli_result($respConf,0,1);

$sqlConf="select id, valor from configuracion_facturas where id=8 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$txt3=mysqli_result($respConf,0,1);


$sqlConf="select id, valor from configuracion_facturas where id=9 and cod_ciudad='$cod_ciudad'";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$nitTxt=mysqli_result($respConf,0,1);


// $sqlDatosFactura="select d.nro_autorizacion, DATE_FORMAT(d.fecha_limite_emision, '%d/%m/%Y'), f.codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.fecha, '%d/%m/%Y') from facturas_venta f, dosificaciones d
//     where f.cod_dosificacion=d.cod_dosificacion and f.cod_venta=$codigoVenta";

$sqlDatosFactura="select '','', f.codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.fecha, '%d/%m/%Y') from facturas_venta f     where  f.cod_venta=$codigoVenta";

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
$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, c.`nombre_cliente`, s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf
        from `salida_almacenes` s, `tipos_docs` t, `clientes` c
        where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_cliente`=c.`cod_cliente` and
        s.`cod_tipo_doc`=t.`codigo`";
        //echo $sqlDatosVenta;
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);

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

    $nombrePago="EFECTIVO";
    if($tipoPago!=1){
        $nombrePago="TARJETA/OTROS";
    }
}
?>    
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/factura.css">
    

    <title>Factura</title>
</head>

<body>
    <div class="my-5 page" size="A4">
        <div class="p-5">
            <section class="top-content bb d-flex justify-content-between">
                <div class="logo">
                    <img src="imagenes/tufarma.png" alt="" class="img-fluid">
                </div>
                <div class="top-left">
                    <div class="graphic-path">
                        <p>Factura</p>
                    </div>
                    <div class="position-relative">
                        <p style="width:300px !important;hyphens: auto;word-wrap: break-word;word-break: break-word;">Código Autorización: <span><?=$cuf?></span></p><p>Factura No. <span><?=$nroDocVenta?></span></p>
                    </div>
                </div>
            </section>

            <section class="store-user mt-5">
                <div class="col-10">
                    <div class="row bb pb-3">
                        <div class="col-7">
                            <p>Sistema Comercial</p>
                            <h2><?=$sucursalTxt?></h2>
                            <p class="address"> <?=$direccionTxt?></p>
                            <div class="txn mt-2">Tel: <?=$telefonoTxt?></div>
                        </div>
                        <div class="col-5">
                            <p>Cliente,</p>
                            <h2><?=$razonSocialCliente?></h2>
                            <p class="address"><?=$nombreCliente?></p>
                            <p class="address">NIT: <?=$nitCliente?></p>
                            
                            <!-- <p class="address">Tel:</p> -->
                        </div>
                    </div>
                    <div class="row extra-info pt-3">
                        <div class="col-7">
                            <p>Tipo de Pago: <span><?=$nombrePago?></span></p>
                            <?php 
                            if($tipoPago!=1){
                                ?><!-- <p>Order Number: <span></span></p> --><?php
                            }else{
                                ?><p>Monto Cancelado: <span><?=$montoEfectivo2?></span></p><?php
                            }
                            ?>
                            
                        </div>
                        <div class="col-5">
                            <p>Fecha de Factura: <span><?=$fechaFactura?></span></p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="product-area mt-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>Descripcion Producto</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                            <td>Descuento</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody>
<?php
$sqlDetalle="select m.codigo_material, sum(s.`cantidad_unitaria`), m.`descripcion_material`, s.`precio_unitario`, 
        sum(s.`descuento_unitario`), sum(s.`monto_unitario`),m.dir_imagen from `salida_detalle_almacenes` s, `material_apoyo` m where 
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

    // - $descUnit
    $descUnit=redondear2($descUnit);    
    $descuentoVentaProd+=$descUnit;
    $montoUnitProd=($cantUnit*$precioUnit);
    $montoUnitProd=redondear2($montoUnitProd);


    $dir_imagen_producto = $datDetalle['dir_imagen'];
    if($dir_imagen_producto==""){
          $dir_imagen_producto="imagenes/imagen_prod.jpg";
    }


    ?>
    <tr>
                            <td>
                                <div class="media">
                                    <img class="mr-3 img-fluid" src="<?=$dir_imagen_producto?>" alt="Pd">
                                    <div class="media-body">
                                        <p class="mt-0 title"><?=$nombreMat?></p>
                                        <?=$codInterno?>
                                    </div>
                                </div>
                            </td>
                            <td><?=$precioUnitFactura?></td>
                            <td><?=$cantUnit?></td>
                            <td><?=$descUnit?></td>
                            <td><?=$montoUnitProd?></td>
                        </tr>

    <?php
    $montoTotal=$montoTotal+$montoUnitProd; 
     
    $yyy=$yyy+6;
}

$descuentoVenta=number_format($descuentoVenta,1,'.','')."0";
$montoFinal=$montoTotal-$descuentoVenta-$descuentoVentaProd;
//$montoTotal=number_format($montoTotal,1,'.','')."0";
$montoFinal=number_format($montoFinal,1,'.','')."0";






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
    
QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 4);

?>
<!-- <img src="<?=$fileName?>" style="margin: 0px;padding: 0;"> -->
<?php

$sqlGlosa="select cod_tipopreciogeneral from `salida_almacenes` s where s.`cod_salida_almacenes`=$codigoVenta";
$respGlosa=mysqli_query($enlaceCon,$sqlGlosa);
$codigoPrecio=mysqli_result($respGlosa,0,0);
$txtGlosaDescuento="";
$sql1="SELECT glosa_factura from tipos_preciogeneral where codigo=$codigoPrecio and glosa_estado=1";
$resp1=mysqli_query($enlaceCon,$sql1);
while($filaDesc=mysqli_fetch_array($resp1)){    
        $txtGlosaDescuento=iconv('utf-8', 'windows-1252', $filaDesc[0]);        
}


//CAMPAÑAS APLICADAS
?>
                      
                    </tbody>
                </table>
            </section>

            <section class="balance-info">
                <div class="row">
                    <div class="col-8">
                        <p class="m-0 font-weight-bold"> Son: </p>
                        <p><?="$txtMonto"." ".$montoDecimal."/100 Bolivianos"?></p>
                    </div>
                    <div class="col-4">
                        <table class="table border-0 table-hover">
                            <tr>
                                <td>Sub Total:</td>
                                <td><?=$montoTotal?></td>
                            </tr>
                            <tr>
                                <td>Descuento P:</td>
                                <td><?=$descuentoVentaProd?></td>
                            </tr>
                            <tr>
                                <td>Descuento V:</td>
                                <td><?=$descuentoVenta?></td>
                            </tr>
                            <tfoot>
                                <tr>
                                    <td>Total:</td>
                                    <td><?=$montoFinal?></td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Signature -->
                        <div class="col-12">
                            <!-- <img src="signature.png" class="img-fluid" alt=""> -->
                            <?php
                            if($txtGlosaDescuento!=""){
                                   ?><p class="text-center m-0"><?=$txtGlosaDescuento?></p><?php
                            }
                            ?>                            
                        </div>
                    </div>
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
$fileName="qrs/".$fechahora.$nroDocVenta.".png"; 
    
QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 4);

//$txt3=iconv('utf-8', 'windows-1252', $txt3); 
?>
<img src="<?=$fileName?>" style="margin: 0px;padding: 0;">

                    </div>
                </div>

                <p class="m-0 text-center" style="font-size: 12px;"><?=$txt2?></p> 
                <p class="m-0 text-center" style="font-size: 12px;"><b><?=$txt3?></b></p>                
            </section>
                <br>
                <br>
                <br>
                <br>
                <br>
            <!-- Cart BG -->
            <img src="imagenes/cart.jpg" class="img-fluid cart-bg" alt="">            
            <footer>
                <hr>
                <br>
                

                <!-- <p class="m-0 text-center">
                    Descargar la factura en formato XML - <a href="www.farmaciasbolivia.com/facturas_online?f=<?=$nroDocVenta?>" target="_blank"> www.farmaciasbolivia.com/facturas_online?f=<?=$nroDocVenta?></a>
                </p> -->
                <p class="m-0 text-center">
                    Visitanos en <a href="http://www.farmaciasbolivia.com.bo/" target="_blank"> www.farmaciasbolivia.com.bo</a>
                </p>

                <div class="social pt-3">
                    <span class="pr-2">
                        <i class="fas fa-mobile-alt"></i>
                        <span style="font-size: 12px !important;">+591 70013999</span>
                    </span>
                    <span class="pr-2">
                        <i class="fas fa-envelope"></i>
                        <span style="font-size: 12px !important;">cobofar@farmaciasbolivia.com.bo</span>
                    </span>
                    <span class="pr-2">
                        <i class="fab fa-facebook-f"></i>
                        <span style="font-size: 12px !important;">/Farmacias Bolivia S.A.</span>
                    </span>
<!--                     <span class="pr-2">
                        <i class="fab fa-twitter"></i>
                        <span>@Farmaciasbolivia</span>
                    </span> -->
                    <span class="pr-2">
                        <i class="fab fa-chrome"></i>
                        <span style="font-size: 12px !important;">www.farmaciasbolivia.com.bo</span>
                    </span>
                </div>
            </footer>
        </div>
    </div>

</body></html>
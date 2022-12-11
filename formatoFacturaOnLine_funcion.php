
<?php
function formatofacturaSIAT($codigoVenta){
$home=1;
ob_start();

include "conexionmysqli.inc";
require_once('funciones.php');
require('funcion_nombres.php');
require('NumeroALetras.php');
include('phpqrcode/qrlib.php'); 


// $cod_ciudad=$_COOKIE["global_agencia"];

$sql="SELECT a.cod_ciudad from salida_almacenes s join almacenes a on s.cod_almacen=a.cod_almacen where s.cod_salida_almacenes='$codigoVenta'";
// echo $sql;
$respq=mysqli_query($enlaceCon,$sql);
$cod_ciudad=mysqli_result($respq,0,0);

//OBTENEMOS EL LOGO Y EL NOMBRE DEL SISTEMA
$logoEnvioEmail=obtenerValorConfiguracion($enlaceCon,13);
$nombreSistemaEmail=obtenerValorConfiguracion($enlaceCon,12);

$sqlConf="select id, valor from configuracion_facturas where id=1 and cod_ciudad='$cod_ciudad'";
// echo $sqlConf;
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


$sqlDatosFactura="select '' as nro_autorizacion, '', '' as codigo_control, f.nit, f.razon_social, DATE_FORMAT(f.siat_fechaemision, '%d/%m/%Y'),f.siat_nombreEstudiante,f.siat_periodoFacturado 
from salida_almacenes f
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

$nombreEstudiante=mysqli_result($respDatosFactura,0,6);
$periodoFacturado=mysqli_result($respDatosFactura,0,7);



$cod_funcionario=$_COOKIE["global_usuario"];
//datos documento
$sqlDatosVenta="select DATE_FORMAT(s.fecha, '%d/%m/%Y'), t.`nombre`, 'cliente', s.`nro_correlativo`, s.descuento, s.hora_salida,s.monto_total,s.monto_final,s.monto_efectivo,s.monto_cambio,s.cod_chofer,s.cod_tipopago,s.cod_tipo_doc,s.fecha,(SELECT cod_ciudad from almacenes where cod_almacen=s.cod_almacen)as cod_ciudad,s.cod_cliente,s.siat_cuf,s.siat_complemento,(SELECT nombre_tipopago from tipos_pago where cod_tipopago=s.cod_tipopago) as nombre_pago,s.siat_fechaemision,s.siat_codigotipoemision,s.siat_codigoPuntoVenta,(SELECT descripcionLeyenda from siat_sincronizarlistaleyendasfactura where codigo=s.siat_cod_leyenda) as leyenda,(SELECT siat_unidadProducto from ciudades where cod_ciudad in (select cod_ciudad from almacenes where cod_almacen=s.cod_almacen)) as unidad_medida
        from `salida_almacenes` s, `tipos_docs` t, `clientes` c
        where s.`cod_salida_almacenes`='$codigoVenta' and s.cod_tipo_doc=t.codigo";
        // echo $sqlDatosVenta;
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

<html>
    <head>
        <!-- CSS Files -->
        <!-- <link rel="icon" type="image/png" href="../assets/img/favicon.png"> -->
        <!-- <link href="assets/libraries/plantillaPDFFactura.css" rel="stylesheet"/> -->
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   </head>
   <style type="text/css">
       
body{font-family:Arial, sans-serif; font-size: 10px;}
@page { margin-top: 15px; margin-left: 60px; margin-right: 40px; margin-bottom: 15px;}


header { position: fixed; top: -125px; left: 0px; right: 0px; height: 70px;}
footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 50px; }

.imagen-logo-izq{
  position: absolute;
  /*float: right;*/
  padding-left: 50px;
  padding-top: -25px;
  left: 0px;
  width:70px;
  height:70px;
}
.imagen-logo-der{
  position: absolute;
  /*float: right;*/
  padding-left: 50px;
  padding-top: -25px;
  left: 550px;
  top: 20px;
  width:70px;
  height:70px;
}
.imagen-logo-izq_2{
  /*position: absolute;*/
  /*float: right;*/
  /*padding-left: 50px;
  padding-top: -25px;*/
  left: 0px;
  width:70px;
  height:70px;
}
#header_titulo_texto{text-align: center; font-size: 16px; font-weight: bold;}
#header_titulo_texto_grande{text-align: center; font-size: 22px; font-weight: bold;}
#header_titulo_texto_des{text-align: left; font-size: 12px; font-weight: bold; border-top: 1px solid #000;}
#header_titulo_texto_inf{text-align: center; font-size: 11px;padding-top: 20px;}
#header_titulo_texto_inf_2{text-align: left; font-size: 11px;padding-left:80px;}
#header_titulo_texto_inf_3{text-align: center; font-size: 11px;padding-top:20px;}

#header_titulo_texto_inf_der{text-align: right; font-size: 11px;padding-left:80px;}



#info_izq{
  
  /*float: right;*/
  padding-left: 50px;
  padding-top: 15px;
  
}


#info_izq2{
  
  /*float: right;*/
  padding-left: 40px;
  padding-top: 15px;
  
}



.table-inf{
width: 100%;  
}



.table{
width: 100%;  
border-collapse: collapse;
}
.table .fila-primary td{
   padding: 5px;
    border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px;
}
.table .fila-totales td{
   padding: 5px;
    border-bottom: 0px;
    border-right: 0px;
    border-top: 1px solid black;
    border-left: 0px;
}
.table tr td{
   
    border: 1px solid black;
}
.td-border-none{
  border: none !important;
}
.table .table-title{
 font-size: 12px;
}

.table2{
width: 100%;  
border-collapse: collapse;
}
.table2 .fila-primary td{
   padding: 2px;
    border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px;
}
.table2 .fila-totales td{
   padding: 2px;
    border-bottom: 0px;
    border-right: 0px;
    border-top: 1px solid black;
    border-left: 0px;
}
.table2 tr td{
   padding: 2px;
    border: 1px solid black;
}
.td-border-none{
  border: none !important;
}
.table2 .table-title{
 font-size: 12px;
}

.table3{
width: 100%;  
border-collapse: none;
}
.table3 .fila-primary td{
   padding: 2px;
    border-top: 0px;
    border-right: 0px;
    border-bottom: 0px solid black;
    border-left: 0px;
}
.table3 .fila-totales td{
   padding: 2px;
    border-bottom: 0px;
    border-right: 0px;
    border-top: 1px solid black;
    border-left: 0px;
}
.table3 tr td{
   padding: 2px;
    border: 0px solid black;
}
.table3 .table-title{
 font-size: 12px;
}



.pt-1{
  margin-top: 10px;
}
.pt-2{
  margin-top: 20px;
}
.pt-3{
  margin-top: 30px;
}
.pt-4{
  margin-top: 40px;
}

.bold{
  font-weight: bold;
}
.text-right{
text-align: right;  
}
.text-center{
  text-align: center;
}
.text-left{
text-align: left;  
}














.sbody{font-family:  sans-serif; font-size: 10px;}
.header2 { position: fixed; top: -10px; left: 0px; right: 0px; height: 70px;}
.headDes { position: fixed; top: 30px; left: 0px; right: 40px; height: 70px;}
#header_logo{background: #ffd; width: 150px; text-align: center;}
#logo{width: 80%;height: 105px;}
.logo-pie{width: 60%;height: 50px;}
#header_texto{text-align: center; background: #ffd; width: 450px;}
#header_logos{background: #ffd; text-align: center; width: 150px;}
#logo1, #logo2{width: 50px;}

#footer_texto{text-align: left; font-size: 10px;color:black;}

#texto_main{margin-top: 25px;font-size: 13px; text-align: justify;padding-left: 70px;padding-right: 90px;}

#table_info{width: 100%;}


#nombres{text-align: left; font-size: 13px; padding-left: 3em;}
.final{text-align: left; font-size: 9px;padding-top: 180px;}
.tablas{font-size: 8px;}
#nombres2{text-align: left; font-size: 13px; padding-left: 2em;}
.justificar{text-align: justify;}

#header_titulo_dos{text-align: justify; font-size: 13px;padding-left: 120px;padding-top: 20px;padding-right: 90px;}

.text-derecha{
  font-size: 8px;
  position: relative;
  right: 0;
  top:-20px;
}
.bg-pluma{
  background-color:#a5a5a5;
}
.fondillo{
  background-color:#303f9f;
  color:#ffffff;
}
.fechassol{
  border: 1px solid #000;
}
.firma{
margin: auto;
width: 80%;
}
.firma2{
margin: auto;
width: 100%;
}
.ref{
  text-align: center;
}
.cont{
padding-right: 35px;
padding-left: 80px; 
}
.cont_des{
  padding-top: 40px;
padding-right: 40px;
padding-left: 40px; 
}
.contInforme{
padding-right: 20px;
padding-left: 80px; 
}
.cont-borde{
  padding: 2px;
  border: 1px solid #000;
}
.cont-borde-in{
  border: 1px solid #000;
}
.cont-cuerpo{
  padding-left: 50px;
  padding-right: 50px;
  padding-top: 20px;
  padding-bottom: 20px;
}
.cont-cuerpo-2{
  padding-left: 25px;
  padding-right: 25px;
  padding-top: 2px;
  padding-bottom: 2px;
}
.imagen-logo{
  position: absolute;
  /*float: right;*/
  right: 0px;
  width:100px;
  height:50px;
}


.table-inf-reem{
  margin: auto;
width: 90%; 
border-collapse: collapse;
}
.table-infPar{
  margin: auto;
width: 40%; 
border-collapse: collapse;
}

.derecha{
text-align: right;  
}
.center-txt{
  text-align: center;
}
.text-muted{
  text-align: left;
  font-weight: bold;
  color: #1a5276 ;
}
.informetecnico{
  padding-bottom: 6px;
}

.fila-primary{
  background-color:#a93226;
  color: white;
}
.fila-secondary{
  background-color: #16a085;
  color: white;
}
.fila-totales{
  background-color:#eaeded;
}
.table-infPar .fila-primary td{
   padding: 5px;
    border-top: 0px;
    border-right: 0px;
    border-bottom: 1px solid black;
    border-left: 0px;
}
.table-infPar .fila-totales td{
   padding: 5px;
    border-bottom: 0px;
    border-right: 0px;
    border-top: 1px solid black;
    border-left: 0px;
}
.table-infPar tr td{
   padding: 5px;
    border: 1px solid #5d6d7e;
}

.justi{
  width: 80%;
  margin: auto;
}

.tabla_p {
   width: 100%;
}
.tabla_p td{
   text-align: right;
   vertical-align: top;
   border: 1px solid #000;
   border-collapse: collapse;
   padding: 0.3em;
   caption-side: bottom;
}
.hr {
border-bottom: 1px solid #000;
}
       
   </style>

<body>
<div  style="height: 49.4%">
        <table  style="width: 100%;">
            <tr>
                <td align="center" width="45%"><br><br>
                    </small></small>
                </td>
                
                <td >
                    <div style="width:100%;text-align: left;font-size: 14px"><p><b>FACTURA</b><br><small><small>(Con Derecho a Crédito Fiscal)</small></small></p></div><br>
                    <table style="width: 100%;border: hidden;text-align: left;">
                        <tr align="left">
                          <td width="40%" ><b>
                              NIT : <br>
                              FACTURA N° : <br>
                              CÓD. AUTORIZACIÓN : </b>
                          </td>
                          <td class="text-left">
                              <?=$nitTxt?><br>
                              <?=$nroDocVenta?><br>
                          </td>
                        </tr>
                        <tr><td colspan="2"><?=$cuf?></td></tr>
                        <tr><td colspan="2">
                            <b>FECHA FACTURA : </b> <?=$fechaFactura?><br>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table">
            <tr >
                <td class="td-border-none text-left" width="15%" ><b>Nombre/Razón Social : </b></td>
                <td class="td-border-none" width="43%"><?=$razonSocialCliente?></td>
                <td class="td-border-none text-right" width="15%"><b>NIT/CI/CEX:</b></td>
                <td class="td-border-none">&nbsp;&nbsp;&nbsp;<?=$nitCliente." ".$siat_complemento?></td>
            </tr>
            <tr >
              <td class="td-border-none text-left" width="25%" ><b>Nombre Estudiante : </b></td>
              <td class="td-border-none" ><?=$nombreEstudiante?></td>
              <td class="td-border-none text-right" ><b>Cod. Cliente :</b></td>
              <td class="td-border-none">&nbsp;&nbsp;&nbsp;<?=$cod_cliente?></td>
            </tr>
            <tr >
              <td class="td-border-none text-left" width="25%" ></td>
              <td class="td-border-none" ></td>
              <td class="td-border-none text-right" ><b>Periodo Facturado :</b></td>
              <td class="td-border-none">&nbsp;&nbsp;&nbsp;<?=$periodoFacturado?></td>
            </tr>
        </table>
        <table class="table2">
            <tr>
                <td width="8%" class="td-border-none text-center">Codigo<br>Servicio</td>
                <td width="40%" class="td-border-none text-center">DESCRIPCIÓN</td>
                <td width="8%" class="td-border-none text-center">Unidad Medida</td>
                <td width="8%" class="td-border-none text-center">Cantidad</td>
                <td class="td-border-none text-center">Precio Unitario</td>
                <td class="td-border-none text-center">Descuento</td>
                <td class="td-border-none text-center">Subtotal</td>
            </tr>
            <?php
            $suma_total=0;
            ?>
            
            <tr ><td style="border: hidden;"></td><td style="border: hidden;"></td><td style="border: hidden;"></td><td style="border: hidden;"></td><td style="border: hidden;"></td><td style="border: hidden;"></td><td style="border: hidden;"></td></tr>
            <?php

                $contador_items=0;                    
                $cantidad_por_defecto=5;//cantidad de items por defect

                $sqlDetalle="SELECT m.codigo_material, s.orden_detalle,m.descripcion_material,s.observaciones,s.precio_unitario,sum(s.cantidad_unitaria) as cantidad_unitario,
                sum(s.descuento_unitario) as descuento_unitario, sum(s.monto_unitario) as monto_unitario
                from salida_detalle_almacenes s, material_apoyo m 
                where m.codigo_material=s.cod_material and s.cod_salida_almacen=$codigoVenta
                group by m.codigo_material, s.orden_detalle,m.descripcion_material, s.observaciones,s.precio_unitario
                order by s.orden_detalle;";
                $respDetalle=mysqli_query($enlaceCon,$sqlDetalle);

                $yyy=65;
                $montoTotal=0;$descuentoVentaProd=0;
                while($datDetalle=mysqli_fetch_array($respDetalle)){                        
                    $observaciones=$datDetalle['observaciones'];
                    if($datDetalle['observaciones']==null){
                        $observaciones="";
                    }
                    $codInterno=$datDetalle['codigo_material'];
                    $cantUnit=$datDetalle['cantidad_unitario'];
                    $nombreMat=$datDetalle['descripcion_material']." ".$observaciones;;
                    $precioUnit=$datDetalle['precio_unitario'];
                    $descUnit=$datDetalle['descuento_unitario'];
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

                    ?>
                    <tr>
                        <td class="text-center" valign="top" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;"><?=$codInterno?></td>
                        <td class="text-left" valign="top" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;">
                            <?=$nombreMat;?>
                        </td>
                        <td class="text-center" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;"><small><?=$unidad_medida?></small></td>
                        <td class="text-center" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;"><?=$cantUnit?></td>
                        <td class="text-right" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;"><?=number_format($precioUnitFactura,2)?></td>
                        <td class="text-right" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;"><?=number_format($descUnit,2)?></td>
                        <td class="text-right" style="padding-top: 0px;padding-bottom: 0px; border: hidden; font-size: 8px;"><?=number_format($montoUnitProdDesc,2)?></td>
                    </tr>
                    
                    <?php $contador_items++;
                }
                
                for($i=$contador_items;$i<$cantidad_por_defecto;$i++){ ?>
                    <tr>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;">&nbsp;</td>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;"></td>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;"></td>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;"></td>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;"></td>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;"></td>
                        <td style="padding-top: 0px;padding-bottom: 0px; border: hidden;"></td>
                    </tr>
                <?php 
                }
                $montoTotal=$montoTotal+$montoUnitProdDesc; 
                $yyy=$yyy+6;

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
            
             $sqlDir="select valor_configuracion from configuraciones where id_configuracion=46";
            $respDir=mysqli_query($enlaceCon,$sqlDir);
            $urlDir=mysqli_result($respDir,0,0);
                       
            $cadenaQR=$urlDir."/consulta/QR?nit=$nitTxt&cuf=$cuf&numero=$nroDocVenta&t=2";
            $codeContents = $cadenaQR; 

            $fechahora=date("dmy.His");
            $fileName="qrs/".$fechahora.$nroDocVenta.".png"; 
                
            QRcode::png($codeContents, $fileName,QR_ECLEVEL_L, 3);

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
            } ?>
            <tr>
                <td rowspan="2" align="center" style="margin: 0px;border: hidden;">
                    <img src="<?=$fileName?>" style="margin: 0px;padding: 0;width: 120px;">
                </td>
                <td  colspan="6" style="border: hidden;">
                    <table class="table">
                        <tr ><td style="padding: 0px;margin: 0px;border: hidden;" valign="top">
                            <?php
                        $entero=floor(round($importe,2));
                        $decimal=$importe-$entero;
                        $centavos=round($decimal*100);
                        if($centavos<10){
                            $centavos="0".$centavos;
                        }?>
                        <span class="bold table-title" valign="bottom"><small>Son: <?="$txtMonto"." ".$montoDecimal."/100 Bolivianos"?></small></span>
                        </td>
                            <td align="right" style="border: hidden;" valign="bottom">
                                <table class="table" style="font-size: 9px;" >
                                    <tr>
                                        <td align="right" style="border: hidden;" valign="bottom">SUBTOTAL Bs:</td>
                                        <td align="right" style="border: hidden;" valign="bottom"><?=number_format($montoTotal,2)?></td>
                                    </tr>

                                    <tr>
                                        <td align="right" style="border-left: hidden;border-bottom: hidden; border-top: hidden;border-right: hidden;" valign="bottom">DESCUENTO Bs:</td>
                                        <td align="right" style="border-left: hidden;border-bottom: hidden; border-top: hidden;border-right: hidden;" valign="bottom"><?=number_format($descuentoVenta,2)?></td>
                                    </tr>
                                    <tfoot>
                                        <tr>
                                            <td align="right" style="border: hidden;" valign="bottom">TOTAL Bs:</td>
                                            <td align="right" style="border: hidden;" valign="bottom"><?=number_format($montoFinal,2)?></td>
                                        </tr>
                                        <tr>
                                            <td align="right" style="border: hidden;" valign="bottom"><b>MONTO A PAGAR Bs:</b></td>
                                            <td align="right" style="border: hidden;" valign="bottom"><?=number_format($montoFinal,2)?></td>
                                        </tr>
                                        <tr>
                                            <td align="right" style="border: hidden;" valign="bottom"><b>IMPORTE BASE CRÉDITO FISCAL:</b></td>
                                            <td align="right" style="border: hidden;" valign="bottom"><?=number_format($montoFinal,2)?></td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </td>
                        <tr >
                    </table >
                </td>
            </tr>
            
            <tr><td colspan="6" style="border:hidden;" valign="bottom"><span style="padding: 0px;margin: 0px;"><small><small>Forma de Pago: <?=$nombrePago?></small></small></span></td></tr>
            
        </table>
        <table class="table3" >
            <tr align="center"><td>&quot;<?=$txt2?>&quot;<br>&quot;<?=$txt3?>&quot;<br>&quot;<?=$txtLeyendaFin?>&quot;</td></tr>
        </table>
    </div>    

</body>
</html>

<?php

$html = ob_get_clean();

return $html;

}


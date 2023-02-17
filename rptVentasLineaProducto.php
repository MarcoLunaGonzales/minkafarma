<html>
<head>
  <meta charset="utf-8" />
</head>
<body>
<?php
set_time_limit(0);
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');
$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$codTipoPago=$_GET['codTipoPago'];
$codSubGrupo=$_GET['codSubGrupo'];
$rpt_formato=(int)$_GET['rpt_formato'];
//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;



 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$rpt_territorio=$_GET['codTipoTerritorio'];
$almacenes=obtenerAlmacenesDeCiudadString($rpt_territorio);
$fecha_reporte=date("d/m/Y");

$nombre_territorio=obtenerNombreSucursalAgrupado($rpt_territorio);
$nombre_territorio=str_replace(",",", ", $nombre_territorio);
?><style type="text/css"> 
        thead tr th { 
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }
    
        .table-responsive { 
            height:200px;
            overflow:scroll;
        }
    </style>
<table style='margin-top:-90 !important' align='center' class='textotit' width='70%'><tr><td align='center'>Ventas x Distribuidor y Linea
  <br> De: <?=$fecha_ini?> A: <?=$fecha_fin?>
  <br>Fecha Reporte: <?=$fecha_reporte?></tr></table>
  <center><div style='width:70%;text-align:center;'><b>Sucursales:</b><br><small><?=$nombre_territorio?></small></div></center>
<?php

setlocale(LC_ALL, 'es_ES');
$tiempoInicio = strtotime($fecha_iniconsulta);//obtener tiempo de inicio
$tiempoFin = strtotime(date("Y-m-t", strtotime($fecha_finconsulta)).""); //obtener el tiempo final pero al ultimo día, para que muestre todos los meses
?>
<br><center><table align='center' class='texto' width='70%' id='ventasLinea'>
  <thead>
<tr><th width="5%">N.</th><th><small>Proveedor</small></th><th><small>Línea</small></th>
<?php
if($rpt_formato==2){
  ?>
   <th><small>Producto</small></th>
  <?php
}
$cantidadMes=0;
while($tiempoInicio <= $tiempoFin){
  $fechaActual = date("Y-m-d", $tiempoInicio);
  ?><th><small><?=strftime('%b %Y', strtotime($fechaActual))?></small></th><?php
  $tiempoInicio += strtotime("+1 month","$fechaActual");
  $cantidadMes++;
}
?>
<th>Totales</th>
</tr>
</thead>
<tbody>
<?php
if($rpt_formato==1){
  $sqlSucursal="select 0 as cod_material,'' as descripcion_material,l.cod_linea_proveedor, l.nombre_linea_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=l.cod_proveedor) as nombre_proveedor from proveedores_lineas l where l.cod_linea_proveedor in ($codSubGrupo) and l.estado=1 order by l.nombre_linea_proveedor";
}else{
  $sqlSucursal="select m.codigo_material, m.descripcion_material,m.cod_linea_proveedor,(SELECT nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor=m.cod_linea_proveedor) as nombre_linea_proveedor,(SELECT nombre_proveedor from proveedores where cod_proveedor=(SELECT cod_proveedor from proveedores_lineas where cod_linea_proveedor=m.cod_linea_proveedor)) as nombre_proveedor from material_apoyo m where m.cod_linea_proveedor in ($codSubGrupo) and m.estado=1 order by m.descripcion_material";
}
$respSucursal=mysqli_query($enlaceCon,$sqlSucursal);
//echo $sqlSucursal;
$index=0;
while($datosSuc=mysqli_fetch_array($respSucursal)){ 
  $totalesHorizontal=0;
  $index++;
  $nombreLinea=$datosSuc[3];
  $nombreProveedor=$datosSuc[4];
  ?><tr><th><?=$index?></th><th><?=$nombreProveedor?></th><th><?=$nombreLinea?></th><?php
  if($rpt_formato==2){
   $codigoSubGrupo=$datosSuc[0];
   ?><th><?=$datosSuc[1];?></th><?php
  }else{
   $codigoSubGrupo=obtenerMaterialesStringDeLinea($datosSuc[2]);
  }

  $tiempoInicio2 = strtotime($fecha_iniconsulta);
  $cantidadMes2=0;
  while($tiempoInicio2 <= $tiempoFin){
    //obtener rangos del mes
    $dateInicio = date("Y-m", $tiempoInicio2)."-01";
    $dateFin = date("Y-m-t", $tiempoInicio2);
    //para listar desde el dia escogido en el primer y ultimo mes
    if($cantidadMes==0){
      $dateInicio=date('Y-m-d', strtotime($fecha_iniconsulta));
    }
    if($cantidadMes2==$cantidadMes){
      $dateFin=date('Y-m-d', strtotime($fecha_finconsulta));
    }
    $montoVenta=obtenerMontoVentasGeneradasLineaProducto($dateInicio,$dateFin,$almacenes,$codTipoPago,$codigoSubGrupo,$rpt_formato);
    $totalesHorizontal+=number_format($montoVenta,2,'.','');
    if($montoVenta>0){//if($dateInicio==date("Y-m")."-01"){
      ?><td><small><?=number_format($montoVenta,2,'.',',')?></small></td><?php
    }else{
      ?><td class='text-muted'><small><?=number_format($montoVenta,2,'.',',')?></small></td><?php
    }   
    // para sumar mes
    $fechaActual = date("Y-m-d", $tiempoInicio2);   
    $tiempoInicio2 += (float)strtotime("+1 month","$fechaActual");
  }
  ?><th><?=number_format($totalesHorizontal,2,'.',',')?></th>
 </tr>
  <?php
}
?>
</tbody><tfoot><tr></tr></tfoot></table></center></br>
<?php include("imprimirInc.php");
if($rpt_formato==1){
  ?>
  <script type="text/javascript">
  totalesTablaVertical('ventasLinea',3,1);
 </script>
 <?php 
}else{
  ?>
  <script type="text/javascript">
  totalesTablaVertical('ventasLinea',4,1);
 </script>
 <?php
}
?>

</body></html>
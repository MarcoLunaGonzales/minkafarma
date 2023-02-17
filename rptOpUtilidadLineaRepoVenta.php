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
$codSubGrupo=$_GET['codSubGrupo'];
$rpt_formato=$_GET['rpt_formato'];
$rpt_tipo=$_GET['rpt_tipo'];
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


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
<table style='margin-top:-90 !important' align='center' class='textotit' width='70%'><tr><td align='center'>Ranking Ventas x Distribuidor
  <br> De: <?=$fecha_ini?> A: <?=$fecha_fin?>
  <br>Fecha Reporte: <?=$fecha_reporte?></tr></table>
  <center><div style='width:70%;text-align:center;'><b>Sucursales:</b><br><small><?=$nombre_territorio?></small></div></center>
<?php
$descOrden="";
if($rpt_formato==1){
  $descOrden="desc";
}

$selectQuery="p.nombre_proveedor,pl.nombre_linea_proveedor,";
$agruparQuery=" GROUP BY pl.cod_linea_proveedor ORDER BY 3";
$colspanLinea="3";
if($rpt_tipo==1){
  $selectQuery="p.nombre_proveedor,";
  $agruparQuery=" GROUP BY p.cod_proveedor ORDER BY 2";
  $colspanLinea="2";
}

/*SACAMOS LOS TOTALES*/
$sqlTotales="SELECT SUM((ROUND(sd.cantidad_unitaria,2) * ROUND(sd.precio_unitario,2))-ROUND(sd.descuento_unitario,2)) as monto_venta
FROM salida_detalle_almacenes sd join salida_almacenes s on s.cod_salida_almacenes=sd.cod_salida_almacen 
join almacenes a on a.cod_almacen=s.cod_almacen
where s.cod_tiposalida=1001 and s.salida_anulada=0 and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' and s.cod_almacen in ($almacenes)";
$respTotales=mysqli_query($enlaceCon,$sqlTotales);
$montoTotalVentas=mysqli_result($respTotales,0,0);
//echo $montoTotalVentas;

//ROUND(sd.cantidad_unitaria,2) as cantidad,ROUND(sd.precio_unitario,2) as precio,ROUND(sd.descuento_unitario,2) as descuento, no va en el query
$sql="SELECT $selectQuery
  SUM((ROUND(sd.cantidad_unitaria,2) * ROUND(sd.precio_unitario,2))-ROUND(sd.descuento_unitario,2)) as monto_venta
FROM salida_detalle_almacenes sd join salida_almacenes s on s.cod_salida_almacenes=sd.cod_salida_almacen 
join material_apoyo m on m.codigo_material=sd.cod_material
JOIN proveedores_lineas pl on pl.cod_linea_proveedor=m.cod_linea_proveedor
join proveedores p on p.cod_proveedor=pl.cod_proveedor
join almacenes a on a.cod_almacen=s.cod_almacen
where s.cod_tiposalida=1001 and s.salida_anulada=0 and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' and p.cod_proveedor in ($codSubGrupo) and s.cod_almacen in ($almacenes)
$agruparQuery $descOrden limit 500;";

$resp=mysqli_query($enlaceCon,$sql);
?>
<br><center><table align='center' class='texto' width='70%' id='ventasLinea'>
  <thead>
<tr>
  <th width="5%">N.</th>
  <th>Proveedor</th>
  <?php if($rpt_tipo!=1){
    ?><th>Línea</th><?php  
  }?>
  
  <th>Monto Venta (Bs.)</th>
  <th>Participacion (%)</th>
  <th>Participacion Acumulada(%)</th>
</tr>
</thead>
<tbody>
<?php
$index=1;
$totalVenta=0;

//PARA GRAFICAR
$datosEstadisticos=[];

$totalParticipacion=0;
while($data=mysqli_fetch_array($resp)){
  $totalVenta+=number_format($data['monto_venta'],2,'.','');

  $participacionLinea=($data['monto_venta']/$montoTotalVentas)*100;

  $totalParticipacion+=$participacionLinea;

   //para graficar 
   $datosEstadisticos[$index-1]= array("titulo"=>$data['nombre_proveedor'],"valor"=>number_format($data['monto_venta'],2,'.',''));
  ?><tr>
    <td><?=$index?></td>
    <td><?=$data['nombre_proveedor']?></td>
    <?php if($rpt_tipo!=1){
      ?><td><?=$data['nombre_linea_proveedor']?></td><?php  
      $datosEstadisticos[$index-1]= array("titulo"=>$data['nombre_linea_proveedor'],"valor"=>number_format($data['monto_venta'],2,'.',''));
    }?>    
    <td><?=number_format($data['monto_venta'],2,'.',',')?></td>
    <td><?=number_format($participacionLinea,2,'.',',')?></td>
    <td><?=number_format($totalParticipacion,2,'.',',')?></td>
  </tr><?php
  $index++;
} 

?>
</tbody><tfoot>
  <tr>
    <th colspan="<?=$colspanLinea?>">TOTALES</th>    
    <th><?=number_format($totalVenta,2,'.',',')?></th>
    <th>-</th>
    <th>-</th>
  </tr>
</tfoot></table></center></br>


    <?php    
    //include "graficos_estadisticos.php";
     ?>    
  
</body></html>
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
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


// $rpt_territorio=$_GET['codTipoTerritorio'];
// $almacenes=obtenerAlmacenesDeCiudadString($rpt_territorio);
$fecha_reporte=date("d/m/Y");

// $nombre_territorio=obtenerNombreSucursalAgrupado($rpt_territorio);
// $nombre_territorio=str_replace(",",", ", $nombre_territorio);
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
<table style='margin-top:-90 !important' align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Recetas Registradas
  <br> De: <?=$fecha_ini?> A: <?=$fecha_fin?>
  <br>Fecha Reporte: <?=$fecha_reporte?></tr></table>
<?php
$descOrden="";
if($rpt_formato==1){
  $descOrden="desc";
}
//ROUND(sd.cantidad_unitaria,2) as cantidad,ROUND(sd.precio_unitario,2) as precio,ROUND(sd.descuento_unitario,2) as descuento, no va en el query
$sql="SELECT s.cod_salida_almacenes, 
            s.fecha, rs.cod_medico, 
            CONCAT(med.apellidos,' ',med.nombres) as medico, 
            med.cod_especialidad, 
            m.codigo_material, 
            m.descripcion_material, 
            sd.cantidad_unitaria
        FROM salida_almacenes s, recetas_salidas rs, salida_detalle_almacenes sd, medicos med, material_apoyo m
        WHERE s.cod_salida_almacenes = sd.cod_salida_almacen 
        AND rs.cod_salida_almacen = s.cod_salida_almacenes 
        AND s.salida_anulada = 0 
        AND rs.cod_medico > 0 
        AND sd.estado_receta = 1 
        AND rs.cod_medico = med.codigo 
        AND sd.cod_material = m.codigo_material 
        AND sd.estado_receta=1
        AND s.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
        ORDER BY s.fecha ASC";

// echo $sql;

//join clientes c on c.cod_cliente=s.cod_cliente
//and p.cod_cliente in ($codSubGrupo)
$resp=mysqli_query($enlaceCon,$sql);
?>
<br><center><table align='center' class='texto' width='70%' id='ventasLinea'>
  <thead>
<tr>
  <th width="5%">N.</th>
  <th>Fecha</th>
  <th>Médico</th>
  <th>Material</th>
  <th>Cantidad</th>
</tr>
</thead>
<tbody>
<?php
    $index=1;
    while($data=mysqli_fetch_array($resp)){
?>
    <tr>
        <td><?=$index?></td>
        <td><?=$data['fecha']?></td>
        <td><?=$data['medico']?></td>
        <td><?=$data['descripcion_material']?></td>
        <td><?=number_format($data['cantidad_unitaria'],2,'.',',')?></td>
    </tr>
<?php
    $index++;
    } 

?>
</tbody>
</table></center></br>
</body></html>
<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli2.inc');
require('funcion_nombres.php');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

?>
<script type="text/javascript">
	function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

 function mostrarStockProducto(codigo,nombre){
 	var indice = 1;
 	var codalm=$("#cod_almacen").val();
 	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codigo+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			$("#stock_producto"+indice).html(ajax.responseText);
			$("#nombre_producto").val(nombre);
	        $("#modalStockProducto").modal("show");
		}
	}
	ajax.send(null);	 		
  }	
</script>
<?php
$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$codPersonal=$_GET['codPersonal'];

$globalAlmacen=$_COOKIE["global_almacen"];
//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;//cambia_formatofecha($fecha_ini);
$fecha_finconsulta=$fecha_fin;//cambia_formatofecha($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon, $rpt_territorio);

echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Ventas x Documento
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_salida_almacenes,s.cod_chofer
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and s.cod_chofer in ($codPersonal) ";

$sql.=" order by s.fecha, s.nro_correlativo";

$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Personal</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Monto</th>
<th>
	<table width='100%'>
	<tr>
		<th width='50%'>Item</th>
		<th width='25%'>Cantidad</th>
		<th width='25%'>Monto</th>
	</tr>
	</table>
</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$codSalida=$datos[7];
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	$nombrePersonal=nombreVisitador($enlaceCon, $datos['cod_chofer']);
	$totalVenta=$totalVenta+$montoVenta;
	
	$sqlX="select m.`codigo_material`, concat(m.`descripcion_material`,' ',IFNULL(sd.observaciones,'')), 
	sum(sd.monto_unitario)montoVenta, sum(sd.cantidad_unitaria), sd.orden_detalle,sum(sd.descuento_unitario)montoDescuento
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') and 
	s.cod_salida_almacenes='$codSalida'
	group by m.`codigo_material`, sd.orden_detalle order by 5 desc";
	
	$respX=mysqli_query($enlaceCon,$sqlX);

	$tablaDetalle="<table width='100%'>";
	
	$totalVentaX=0;
	while($datosX=mysqli_fetch_array($respX)){	
		$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVenta=$datosX[2]-$datosX['montoDescuento'];
		$cantidad=$datosX[3];		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
		$totalVentaX=$totalVentaX+$montoVenta;

		$nombreItemSin = preg_replace("/[^a-zA-Z0-9]+/", "", $nombreItem);
		$tablaDetalle.="<tr>		
		<td><a href='#' style='font-size:14px' onclick='mostrarStockProducto(\"$codItem\",\"$nombreItemSin\");return false;'>($codItem) $nombreItem</a></td>
		<td>$cantidadFormat</td>
		<td>$montoPtr</td>		
		</tr>";
	}
	$totalPtr=number_format($totalVentaX,2,".",",");
	$tablaDetalle.="<tr>
		<td>&nbsp;</td>
		<th>Total:</th>
		<th>$totalPtr</th>
	<tr></table>";

	
	echo "<tr>
	<td>$nombrePersonal</td>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$datosDoc</td>
	<td>$montoVentaFormat</td>
	<td>$tablaDetalle</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<th>Total Reporte</th>
	<th>$totalVentaFormat</th>
</tr>";
echo "</table></br>";


include("imprimirInc.php");
?>
<input type="hidden" id="cod_almacen" value="<?=$globalAlmacen?>">

<!-- small modal -->
<!--div class="modal fade modal-primary" id="modalStockProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content card">
               <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon" style="background: #96079D;color:#fff;">
                    <i class="material-icons">inventory_2</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Stock Producto <small id="titulo_tarjeta"></small></h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
<div class="row">
	<div class="col-sm-12">
                <div class="row">
                  <label class="col-sm-3 col-form-label">Producto</label>
                  <div class="col-sm-9">
                    <div class="form-group">                      
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="nombre_producto" name="nombre_producto" readonly value=""/>
                    </div>
                  </div>
                </div> 
                <div class="row">
                  <label class="col-sm-3 col-form-label">Stock Sucursal</label>
                  <div class="col-sm-9">
                    <div class="form-group" id="stock_producto1">                      
                      <input class="form-control" type="number" style="background: #A5F9EA;" id="stock_producto" name="stock_producto" readonly value=""/>
                    </div>
                  </div>
                </div>                
                <br><br>
       </div>
</div>                      
                </div>
      </div>  
    </div>
  </div-->
<!--    end small modal -->
<?php
$estilosVenta=1;
require('estilos_reportes_almacencentral.php');
require('conexionmysqli.inc');
require('function_formatofecha.php');
require('function_comparafechas.php');
require('funciones.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
<style type="text/css">
	.bg-plomoclaro{
		background: #D8DCDB;
	}
	.bg-secundario{
		background: #F7DAC8;
	}
	.bg-verde-claro{
		background: #B0F7D0;
	}
	.textomini th,.titulos th{
	  background:#3AF3C1 !important
	}
</style>
<?php
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_territorio=mysqli_query($enlaceCon,$sql_nombre_territorio);
	$dat_territorio=mysqli_fetch_array($resp_territorio);
	$nombre_territorio=$dat_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$dat_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$dat_almacen[0];

	$consultaPrecio="select p.`precio` from precios p where p.`codigo_material`='$rpt_item' and p.cod_precio=1";
//	echo $consultaPrecio;
  $respProd=mysqli_query($enlaceCon,$consultaPrecio);
  $precioProd=mysqli_fetch_array($respProd);
  $precioVenta=$precioProd[0];
  if($precioVenta==""){   
  	$precioVenta=0;
  }
  $precioVenta=number_format($precioVenta,2,'.','');



	if($tipo_item==1)
	{	$nombre_tipoitem="Muestra Médica";
		$sql_item="select descripcion, presentacion from muestras_medicas where codigo='$rpt_item'";
	}
	else
	{	$nombre_tipoitem="Material de Apoyo";
		$sql_item="select descripcion_material,'' as pres from material_apoyo where codigo_material='$rpt_item'";
	}
	$resp_item=mysqli_query($enlaceCon,$sql_item);
	$dat_item=mysqli_fetch_array($resp_item);
	$nombre_item="$dat_item[0] $dat_item[1]";
	echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Kardex de Existencia Fisica<br>Territorio: 
	<strong>$nombre_territorio</strong> Almacen: <strong>$nombre_almacen</strong><br> Fecha inicio: <strong>$fecha_ini</strong> Fecha final: 
	<strong>$fecha_fin</strong><br>Item: <strong>($rpt_item) $nombre_item</strong><br>$txt_reporte</th></tr></table>";

	//desde esta parte viene el reporte en si
	$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
	$fecha_finconsulta=cambia_formatofecha($fecha_fin);
	//aqui sacamos las existencias a una fecha
	$sql="select sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha<'$fecha_iniconsulta'";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	$dat_existencias_afecha=mysqli_fetch_array($resp);
	$cantidad_ingresada_afecha=$dat_existencias_afecha[0];
	$sql_salidas_afecha="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha<'$fecha_iniconsulta'";
	$resp_salidas_afecha=mysqli_query($enlaceCon,$sql_salidas_afecha);
	$dat_salidas_afecha=mysqli_fetch_array($resp_salidas_afecha);
	$cantidad_sacada_afecha=$dat_salidas_afecha[0];
	$cantidad_inicial_kardex=$cantidad_ingresada_afecha-$cantidad_sacada_afecha;


	//aqui sacamos las existencias a una fecha
	$sql="select sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha<'$fecha_iniconsulta'";
//	echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	$dat_existencias_afecha=mysqli_fetch_array($resp);
	$cantidad_ingresada_afechaCaja=$dat_existencias_afecha[0];
	$sql_salidas_afecha="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha<'$fecha_iniconsulta'";
	$resp_salidas_afecha=mysqli_query($enlaceCon,$sql_salidas_afecha);
	$dat_salidas_afecha=mysqli_fetch_array($resp_salidas_afecha);
	$cantidad_sacada_afechaCaja=$dat_salidas_afecha[0];
	$cantidad_inicial_kardexCaja=$cantidad_ingresada_afechaCaja-$cantidad_sacada_afechaCaja;
	echo "<br><table class='texto' align='center'><tr class='titulos'><th>Existencia a fecha inicio reporte: Cajas $cantidad_inicial_kardexCaja, Sueltas $cantidad_inicial_kardex</th></tr></table>";
	
	
	echo "<center><br><table class='texto' cellspacing='0' width='100%'>";
	echo "<tr class='textomini'><th>Proceso</th><th>Tipo</th><th>Nro. Ingreso/Salida</th><th>Fecha</th><th>Factura</th><th>Observaciones<br>/ Razón Social</th><th>Entrada Cajas</th><th>Salida Cajas</th><th>Saldo Cajas</th><th class='bg-plomoclaro'>Entrada Sueltas</th><th class='bg-plomoclaro'>Salida Sueltas</th><th class='bg-plomoclaro'>Saldo Sueltas</th><th>Precio Venta</th><th>Tipo Ingreso/Salida</th><th>Sucursal<br>Origen/Destino</th><th>Nro<br>Ingreso/Salida<br>Destino/Origen</th><th>Responsable</th></tr>";
	$sql_fechas_ingresos="select distinct(i.fecha) from ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' order by i.fecha";
	$resp_fechas_ingresos=mysqli_query($enlaceCon,$sql_fechas_ingresos);
	$i=1;
	while($dat_fechas_ingresos=mysqli_fetch_array($resp_fechas_ingresos))
	{	$vector_fechas_ingresos[$i]=$dat_fechas_ingresos[0];
		$i++;
	}
	$sql_fechas_salidas="select distinct(s.fecha) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' order by s.fecha";
	
	$resp_fechas_salidas=mysqli_query($enlaceCon,$sql_fechas_salidas);
	$j=1;
	while($dat_fechas_salidas=mysqli_fetch_array($resp_fechas_salidas))
	{	$vector_fechas_salidas[$j]=$dat_fechas_salidas[0];
		$j++;
	}
	$i=$i-1;
	$j=$j-1;
	$ii=1;
	$jj=1;
	$zz=1;
	if(!isset($vector_final_fechas)){
	  $vector_final_fechas=[];	
	}
	while($ii<=$i and $jj<=$j)
	{	$fecha_ingresos=$vector_fechas_ingresos[$ii];
		$fecha_salidas=$vector_fechas_salidas[$jj];
		if(compara_fechas($fecha_ingresos,$fecha_salidas)<0)
		{	$vector_final_fechas[$zz]=$fecha_ingresos;
			$ii++;
		}
		if(compara_fechas($fecha_ingresos,$fecha_salidas)==0)
		{	$vector_final_fechas[$zz]=$fecha_ingresos;
			$ii++;
			$jj++;
		}
		if(compara_fechas($fecha_ingresos,$fecha_salidas)>0)
		{	$vector_final_fechas[$zz]=$fecha_salidas;
			$jj++;
		}

		$zz++;
	}
	if($ii==$i+1)
	{	for($kk=$jj;$kk<=$j;$kk++)
		{	$vector_final_fechas[$zz]=$vector_fechas_salidas[$kk];
			$zz++;
		}
	}
	if($jj==$j+1)
	{	for($kk=$ii;$kk<=$i;$kk++)
		{	$vector_final_fechas[$zz]=$vector_fechas_ingresos[$kk];
			$zz++;
		}
	}
	$cantidad_kardex=$cantidad_inicial_kardex;
	$cantidad_kardexCaja=$cantidad_inicial_kardexCaja;
	$suma_ingresosCaja=0;
	$suma_salidasCaja=0;
	$suma_ingresos=0;
	$suma_salidas=0;
	for($indice=1;$indice<=$zz;$indice++)
	{	
        if(isset($vector_final_fechas[$indice])){
          $fecha_consulta=$vector_final_fechas[$indice];
          $fecha_consulta_format=strftime('%d/%m/%Y',strtotime($fecha_consulta));
        }else{
          $fecha_consulta="";
          $fecha_consulta_format="";
        }

		//hacemos la consulta para ingresos        
		$sql_ingresos="select i.nro_correlativo, id.cantidad_unitaria, i.observaciones, ti.nombre_tipoingreso,id.cantidad_unitaria,i.created_by,i.cod_ingreso_almacen,(SELECT cod_almacen FROM salida_almacenes where cod_salida_almacenes=i.cod_salida_almacen)almacen_origen,i.nro_factura_proveedor,(SELECT nro_correlativo FROM salida_almacenes where cod_salida_almacenes=i.cod_salida_almacen)nro_origen,i.nota_entrega, i.nro_factura_proveedor as nro_factura, i.hora_ingreso
		from ingreso_almacenes i, ingreso_detalle_almacenes id, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
		i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha='$fecha_consulta'";
		
		//echo $sql_ingresos;
		
		$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
		while($dat_ingresos=mysqli_fetch_array($resp_ingresos))
		{	$nro_ingreso=$dat_ingresos[0];
			$cantidad_ingreso=$dat_ingresos[1];
			$obs_ingreso=$dat_ingresos[2];
			$nombre_ingreso=$dat_ingresos[3];
			$nro_facturaProveedor=$dat_ingresos['nro_factura'];
			$nro_origenSalida=$dat_ingresos['nro_origen'];
			$cod_ingreso_al=$dat_ingresos['cod_ingreso_almacen'];
			$horaIngreso=$dat_ingresos['hora_ingreso'];

			$suma_ingresos=$suma_ingresos+$cantidad_ingreso;
			$cantidad_kardex=$cantidad_kardex+$cantidad_ingreso;            
			$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$dat_ingresos['created_by']."'";
	        $respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	        $nombre_responsable=mysqli_result($respResponsable,0,0);

			$cantidad_ingresoCaja=$dat_ingresos[4];
			$suma_ingresosCaja=$suma_ingresosCaja+$cantidad_ingresoCaja;
			$cantidad_kardexCaja=$cantidad_kardexCaja+$cantidad_ingresoCaja;

			$cantidad_ingresoCajaF=formatNumberInt($cantidad_ingresoCaja);
			$cantidad_kardexCajaF=formatNumberInt($cantidad_kardexCaja);
			$cantidad_ingresoF=formatNumberInt($cantidad_ingreso);
			$cantidad_kardexF=formatNumberInt($cantidad_kardex);

			$territorio_origen=$dat_ingresos['almacen_origen'];
			$sql_nombre_territorio_origen="select nombre_almacen from almacenes where cod_almacen='$territorio_origen'";
				$resp_nombre_territorio_origen=mysqli_query($enlaceCon,$sql_nombre_territorio_origen);
				$dat_nombre_territorio_origen=mysqli_fetch_array($resp_nombre_territorio_origen);
				$nombre_territorio_origen=$dat_nombre_territorio_origen[0];


			if($nro_facturaProveedor==0){
				$nro_facturaProveedor="";
			}	

			if($nro_origenSalida==0){
					$nro_origenSalida="";
			}else{
					$nro_origenSalida="T-".$nro_origenSalida;
			}	

		  $nro_ingreso="I-".$nro_ingreso;		
		  if(!($nro_origenSalida==0||$nro_origenSalida=="")){
		  	$nro_origenSalida="T-".$nro_origenSalida;	
		  }

		  if($nro_origenSalida==''){
		  	if($dat_ingresos['nota_entrega']>0){
		  		$nro_origenSalida="K-".$dat_ingresos['nota_entrega'];	
		  	}		  	
		  }



		  
			echo "<tr><td class='bg-plomoclaro'><b>$cod_ingreso_al</b></td><td class='bg-plomoclaro'>Ingreso</td><td align='left' class='bg-plomoclaro'>$nro_ingreso</td><td align='center' class='bg-plomoclaro'>$fecha_consulta_format<br>$horaIngreso</td>
			<td class='bg-plomoclaro'>$nro_facturaProveedor</td>
			<td>&nbsp;$obs_ingreso</td>
			<td align='right'>$cantidad_ingresoCajaF</td>
			<td align='right'>0</td>
			<td align='right'class='bg-verde-claro'>$cantidad_kardexCajaF</td>
			<td align='right'class=''>$cantidad_ingresoF</td>
			<td align='right'class=''>0</td>
			<td align='right'class='bg-verde-claro'>$cantidad_kardexF</td>
			<td align='right'class='bg-secundario'>$precioVenta</td>
			<td align='left'>$nombre_ingreso</td><td>$nombre_territorio_origen</td><td>$nro_origenSalida</td>
			<td align='left' class='bg-plomoclaro'>$nombre_responsable</td></tr>";
		}
		//hacemos la consulta para salidas
		$sql_salidas="select s.nro_correlativo, sd.cantidad_unitaria, ts.nombre_tiposalida, s.observaciones, s.territorio_destino, s.cod_salida_almacenes,sd.cantidad_unitaria,s.created_by,s.cod_tipo_doc,s.razon_social,s.almacen_destino, s.cod_chofer, s.hora_salida
		from salida_almacenes s, salida_detalle_almacenes sd, tipos_salida ts
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
		s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha='$fecha_consulta'";
		$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
		while($dat_salidas=mysqli_fetch_array($resp_salidas))
		{	$nro_salida=$dat_salidas[0];
			$cantidad_salida=$dat_salidas[1];
			$nombre_salida=$dat_salidas[2];
			$obs_salida=$dat_salidas[3];
			$cod_salida=$dat_salidas[5];
			$nro_facturaProveedor=$nro_salida;
      $tipoDocumento=$dat_salidas["cod_tipo_doc"];
      $razon_social=$dat_salidas['razon_social'];            
			$territorio_destino=$dat_salidas['almacen_destino'];
			$codPersonalSalida=$dat_salidas['cod_chofer'];
			$horaSalida=$dat_salidas['hora_salida'];

          $sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1, 1),'.') from funcionarios where codigo_funcionario='".$codPersonalSalida."'";
	        $respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	        $nombre_responsable=mysqli_result($respResponsable,0,0);

			$cantidad_salidaCaja=$dat_salidas[6];
			$suma_salidasCaja=$suma_salidasCaja+$cantidad_salidaCaja;
			$cantidad_kardexCaja=$cantidad_kardexCaja-$cantidad_salidaCaja;

				$sql_nombre_territorio_destino="select nombre_almacen from almacenes where cod_almacen='$territorio_destino'";
				$resp_nombre_territorio_destino=mysqli_query($enlaceCon,$sql_nombre_territorio_destino);
				$dat_nombre_territorio_destino=mysqli_fetch_array($resp_nombre_territorio_destino);
				$nombre_territorio_destino=$dat_nombre_territorio_destino[0];


				$sql_nro_destino_ingreso="select nro_correlativo from ingreso_almacenes where cod_salida_almacen='$cod_salida'";
				$resp_nro_destino_ingreso=mysqli_query($enlaceCon,$sql_nro_destino_ingreso);
				$dat_nro_destino_ingreso=mysqli_fetch_array($resp_nro_destino_ingreso);
				$nro_ingreso_destino=$dat_nro_destino_ingreso[0];


			$cantidad_kardex=$cantidad_kardex-$cantidad_salida;
			$suma_salidas=$suma_salidas+$cantidad_salida;

			if($tipoDocumento==1){
              $nro_salida="F-".$nro_salida;
              $obs_salida=$razon_social;
			}else{
				if($tipoDocumento==4){
                  $nro_salida="M-".$nro_salida;
                  $obs_salida=$razon_social;
			    }else{
			       if($tipoDocumento==3){
                     $nro_salida="T-".$nro_salida;
                     $nro_facturaProveedor="";
			       }else{
			    					 $nro_salida="NR-".$nro_salida;
                     //$nro_facturaProveedor="";
                     $obs_salida=$razon_social;
			       }	
			    }
			}

			$cantidad_salidaCajaF=formatNumberInt($cantidad_salidaCaja);
			$cantidad_kardexCajaF=formatNumberInt($cantidad_kardexCaja);
			$cantidad_salidaF=formatNumberInt($cantidad_salida);
			$cantidad_kardexF=formatNumberInt($cantidad_kardex);
			
			if(!($nro_ingreso_destino==0||$nro_ingreso_destino=="")){
		  	$nro_ingreso_destino="I-".$nro_ingreso_destino;	
		  }
			echo "<tr><td class='bg-plomoclaro'><b>$cod_salida</b></td><td class='bg-plomoclaro'>Salida</td><td align='left' class='bg-plomoclaro'>$nro_salida</td><td align='center' class='bg-plomoclaro'>$fecha_consulta_format<br>$horaSalida</td>
			   <td class='bg-plomoclaro'>$nro_facturaProveedor</td>
			   <td>&nbsp;$obs_salida</td>
			   <td align='right'>0</td>
			   <td align='right'>$cantidad_salidaCajaF</td>
			   <td align='right'class='bg-verde-claro'>$cantidad_kardexCajaF</td>
			   <td align='right'class=''>0</td>
			   <td align='right'class=''>$cantidad_salidaF</td>			   
			   <td align='right'class='bg-verde-claro'>$cantidad_kardexF</td>
			   <td align='right'class='bg-secundario'>$precioVenta</td>
			   <td align='left'>$nombre_salida</td>
			   <td align='left'>$nombre_territorio_destino</td>
			   <td align='left'>$nro_ingreso_destino</td><td align='left' class='bg-plomoclaro'>$nombre_responsable</td></tr>";
		}
	}
	$suma_saldo_finalCaja=$suma_ingresosCaja-$suma_salidasCaja+$cantidad_inicial_kardexCaja;
	$suma_saldo_final=$suma_ingresos-$suma_salidas+$cantidad_inicial_kardex;
	echo "<tr class='titulos'><th colspan='6'>Sumas</th>
	<th align='right' style='text-align:right'>$suma_ingresosCaja</td>
	<th align='right' style='text-align:right'>$suma_salidasCaja</td>
	<th align='right' style='text-align:right'>$suma_saldo_finalCaja</td>
	<th align='right'class='bg-plomoclaro' style='text-align:right'>$suma_ingresos</td>
	<th align='right'class='bg-plomoclaro' style='text-align:right'>$suma_salidas</td>
	<th align='right'class='bg-plomoclaro' style='text-align:right'>$suma_saldo_final</td>
	<th align='left'>&nbsp;</th><th align='left'>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	echo "</table></center><br>";
	
	include("imprimirInc.php");
?>

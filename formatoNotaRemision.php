<?php
require('fpdf.php');
require('conexion.inc');
require('funciones.php');

$codigoVenta=$_GET["codVenta"];

//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysql_query($sqlNro);
$nroItems=mysql_result($respNro,0,0);

$tamanoLargo=70+($nroItems*3)-3;

//$pdf=new FPDF('P','mm',array(76,$tamanoLargo));
$pdf=new FPDF('P','mm',array(76,140));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',10);

$y=0;
$incremento=3;

$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
$respEmp=mysql_query($sqlEmp);

$nombreEmpresa=mysql_result($respEmp,0,1);

list($nombre1, $nombre2) = split("&", $nombreEmpresa);

$nitEmpresa=mysql_result($respEmp,0,2);
$direccionEmpresa=mysql_result($respEmp,0,3);
$ciudadEmpresa=mysql_result($respEmp,0,4);

		
$sqlDatosVenta="select concat(s.fecha,' ',s.hora_salida) as fecha, t.`nombre`, 
(select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente) as nombreCliente, 
s.`nro_correlativo`, s.razon_social, s.observaciones
		from `salida_almacenes` s, `tipos_docs` t
		where s.`cod_salida_almacenes`='$codigoVenta'  and
		s.`cod_tipo_doc`=t.`codigo`";
$respDatosVenta=mysql_query($sqlDatosVenta);
while($datDatosVenta=mysql_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta[1];
	$nombreCliente=$datDatosVenta[2];
	$nroDocVenta=$datDatosVenta[3];
	$razonSocial=$datDatosVenta[4];
	$obsVenta=$datDatosVenta[5];
}


$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,$nombre1,0,0,"C");
$pdf->SetXY(0,$y+6);		$pdf->Cell(0,0,$nombre2,0,0,"C");

$pdf->SetXY(0,$y+9);		$pdf->Cell(0,0,"$nombreTipoDoc Nro. $nroDocVenta", 0,0,"C");
$pdf->SetXY(0,$y+12);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");


$pdf->SetXY(0,$y+15);		$pdf->Cell(0,0,"FECHA: $fechaVenta",0,0,"C");
$pdf->SetXY(0,$y+18);		$pdf->Cell(0,0,"Sr(es): $nombreCliente",0,0,"C");
$pdf->SetXY(0,$y+21);		$pdf->Cell(0,0,"R.S.: $razonSocial",0,0,"C");
$pdf->SetXY(0,$y+24);		$pdf->Cell(0,0,"Obs.: $obsVenta",0,0,"C");

$pdf->SetXY(0,$y+27);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(5,$y+30);		$pdf->Cell(0,0,"Cant.");
$pdf->SetXY(15,$y+30);		$pdf->Cell(0,0,"ITEM");
$pdf->SetXY(53,$y+30);		$pdf->Cell(0,0,"Importe");
$pdf->SetXY(0,$y+33);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$sqlDetalle="select m.`orden_grupo`, s.`cantidad_unitaria`, m.`descripcion_material`, s.`precio_unitario`, 
		s.`descuento_unitario`, s.`monto_unitario`, m.abreviatura, ss.descuento 
		from `salida_detalle_almacenes` s, `material_apoyo` m , salida_almacenes ss
		where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta and s.cod_salida_almacen=ss.cod_salida_almacenes order by m.descripcion_material";
$respDetalle=mysql_query($sqlDetalle);

$yyy=36;

$montoTotal=0;
while($datDetalle=mysql_fetch_array($respDetalle)){
	$codInterno=$datDetalle[0];
	$cantUnit=$datDetalle[1];
	$cantUnit=redondear2($cantUnit);
	$nombreMat=$datDetalle[2];
	$precioUnit=$datDetalle[3];
	$precioUnit=redondear2($precioUnit);
	$descUnit=$datDetalle[4];
	$montoUnit=$datDetalle[5];
	$montoUnit=redondear2($montoUnit);
	$abrevMat=$datDetalle[6];
	$descuentoNota=$datDetalle[7];
	$descuentoNota=redondear2($descuentoNota);
	$cadMaterial="";
	if($abrevMat==""){
		$cadMaterial=$nombreMat;
	}else{
		$cadMaterial=$abrevMat;
	}
	
	$pdf->SetXY(7,$y+$yyy);		$pdf->Cell(0,0,"$cantUnit");
	$pdf->SetXY(13,$y+$yyy);		$pdf->Cell(20,0,"$cadMaterial",0,0);
	$pdf->SetXY(59,$y+$yyy);		$pdf->Cell(0,0,"$montoUnit");
	$montoTotal=$montoTotal+$montoUnit;
	
	$yyy=$yyy+4;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;


$pdf->SetXY(37,$y+$yyy);		$pdf->Cell(0,0,"Total Venta:  $montoTotal",0,0);
$pdf->SetXY(40,$y+$yyy+4);		$pdf->Cell(0,0,"Descuento:  $descuentoNota",0,0);
$totalFinal=$montoTotal-$descuentoNota;
$pdf->SetXY(37,$y+$yyy+8);		$pdf->Cell(0,0,"Total Final:  $totalFinal",0,0);

$pdf->Output();
?>
<?php
require('fpdf.php');
require('conexion.inc');
require('funciones.php');

$codigoVenta=$_GET["codVenta"];

//consulta cuantos items tiene el detalle
$sqlNro="select count(*) from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoVenta";
$respNro=mysql_query($sqlNro);
$nroItems=mysql_result($respNro,0,0);

$tamanoLargo=200+($nroItems*3)-3;

$pdf=new FPDF('P','mm',array(76,$tamanoLargo));
$pdf->SetMargins(0,0,0);
$pdf->AddPage(); 
$pdf->SetFont('Arial','',8);

$y=0;
$incremento=3;

$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
$respEmp=mysql_query($sqlEmp);

$nombreEmpresa=mysql_result($respEmp,0,1);
$nitEmpresa=mysql_result($respEmp,0,2);
$direccionEmpresa=mysql_result($respEmp,0,3);
$ciudadEmpresa=mysql_result($respEmp,0,4);

//datos documento
$sqlDatosVenta="select s.fecha, t.`nombre`, c.`nombre_cliente`, s.`nro_correlativo`
		from `salida_almacenes` s, `tipos_docs` t, `clientes` c 
		where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_cliente`=c.`cod_cliente` and
		s.`cod_tipo_doc`=t.`codigo`";
$respDatosVenta=mysql_query($sqlDatosVenta);
while($datDatosVenta=mysql_fetch_array($respDatosVenta)){
	$fechaVenta=$datDatosVenta[0];
	$nombreTipoDoc=$datDatosVenta[1];
	$nombreCliente=$datDatosVenta[2];
	$nroDocVenta=$datDatosVenta[3];
}


$pdf->SetXY(0,$y+3);		$pdf->Cell(0,0,$nombreEmpresa,0,0,"C");
$pdf->SetXY(0,$y+6);		$pdf->Cell(0,0,"SUCURSAL 1",0,0,"C");
$pdf->SetXY(0,$y+9);		$pdf->Cell(0,0,$direccionEmpresa, 0,0,"C");
$pdf->SetXY(0,$y+12);		$pdf->Cell(0,0,"FACTURA", 0,0,"C");
$pdf->SetXY(0,$y+15);		$pdf->Cell(0,0,"$ciudadEmpresa",0,0,"C");
$pdf->SetXY(0,$y+18);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");
$pdf->SetXY(0,$y+21);		$pdf->Cell(0,0,"NIT: $nitEmpresa", 0,0,"C");
$pdf->SetXY(0,$y+24);		$pdf->Cell(0,0,"$nombreTipoDoc Nro. $nroDocVenta", 0,0,"C");
$pdf->SetXY(0,$y+27);		$pdf->Cell(0,0,"Autorizacion Nro. ------", 0,0,"C");

$pdf->SetXY(0,$y+30);		$pdf->Cell(0,0,"Venta al por menor de productos farmaceuticos, medicinales, cosmeticos y articulos de tabaco.", 0,0,"C");
$pdf->SetXY(0,$y+33);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------", 0,0,"C");


$pdf->SetXY(0,$y+36);		$pdf->Cell(0,0,"FECHA: $fechaVenta",0,0,"C");
$pdf->SetXY(0,$y+39);		$pdf->Cell(0,0,"Sr(es): $nombreCliente",0,0,"C");
$pdf->SetXY(0,$y+42);		$pdf->Cell(0,0,"NIT/CI:	0",0,0,"C");

$pdf->SetXY(0,$y+45);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");
$pdf->SetXY(2,$y+48);		$pdf->Cell(0,0,"ITEM");
$pdf->SetXY(43,$y+48);		$pdf->Cell(0,0,"Cant.");
$pdf->SetXY(53,$y+48);		$pdf->Cell(0,0,"Importe");
$pdf->SetXY(0,$y+52);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");


$sqlDetalle="select m.codigo_material, s.`cantidad_unitaria`, m.`descripcion_material`, s.`precio_unitario`, 
		s.`descuento_unitario`, s.`monto_unitario` from `salida_detalle_almacenes` s, `material_apoyo` m where 
		m.`codigo_material`=s.`cod_material` and s.`cod_salida_almacen`=$codigoVenta";
$respDetalle=mysql_query($sqlDetalle);

$yyy=55;

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
	
	$pdf->SetXY(1,$y+$yyy);		$pdf->Cell(0,0,"$nombreMat");
	$pdf->SetXY(45,$y+$yyy);		$pdf->Cell(0,0,"$cantUnit");
	$pdf->SetXY(55,$y+$yyy);		$pdf->Cell(0,0,"$montoUnit");
	$montoTotal=$montoTotal+$montoUnit;
	
	$yyy=$yyy+3;
}
$pdf->SetXY(0,$y+$yyy+2);		$pdf->Cell(0,0,"=================================================================================",0,0,"C");		
$yyy=$yyy+5;


$pdf->SetXY(37,$y+$yyy);		$pdf->Cell(0,0,"Total Venta:  $montoTotal",0,0);
$pdf->SetXY(40,$y+$yyy+4);		$pdf->Cell(0,0,"Descuento:  0",0,0);
$pdf->SetXY(37,$y+$yyy+8);		$pdf->Cell(0,0,"Total Final:  $montoTotal",0,0);

$pdf->SetXY(5,$y+$yyy+12);		$pdf->Cell(0,0,"-------------------------------------------------------------------------------",0,0,"C");
$pdf->SetXY(5,$y+$yyy+16);		$pdf->Cell(0,0,"CODIGO DE CONTROL: 00-00-00-00",0,0,"C");
$pdf->SetXY(5,$y+$yyy+20);		$pdf->Cell(0,0,"FECHA LIMITE DE EMISION: --/--/----",0,0,"C");
$pdf->SetXY(5,$y+$yyy+25);		$pdf->Cell(0,0,"Sr. Cliente una vez retirada la mercaderia",0,0,"C");
$pdf->SetXY(5,$y+$yyy+28);		$pdf->Cell(0,0,"no se aceptan devoluciones",0,0,"C");

$pdf->Image('imagenes/qrcode.png' , 25 ,$y+$yyy+35, 20, 20,'PNG');

$pdf->SetXY(5,$y+$yyy+60);		$pdf->Cell(0,0,"Esta factura contribuye al desarrollo del",0,0,"C");
$pdf->SetXY(5,$y+$yyy+63);		$pdf->Cell(0,0,"pais, el uso ilicito de esta sera sancionado",0,0,"C");
$pdf->SetXY(5,$y+$yyy+66);		$pdf->Cell(0,0,"de acuerdo a ley.",0,0,"C");


$pdf->Output();
?>
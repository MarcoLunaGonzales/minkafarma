<?php
require('fpdf.php');
require("conexion.inc");
require("funciones.php");


class PDF extends FPDF
{ 	
	
	function Header()
	{
		$codCobro=$_GET['codCobro'];
		$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
		$respEmp=mysql_query($sqlEmp);

		$nombreEmpresa=mysql_result($respEmp,0,1);
		$nitEmpresa=mysql_result($respEmp,0,2);
		$direccionEmpresa=mysql_result($respEmp,0,3);
		$ciudadEmpresa=mysql_result($respEmp,0,4);
	
		//datos documento				
		$sqlDatos="select c.`cod_cobro`, c.`fecha_cobro`,c.`observaciones`,c.`monto_cobro`,
		(select cl.`nombre_cliente` from clientes cl where c.`cod_cliente` = cl.`cod_cliente`), 
		c.nro_cobro, (select g.nombre_gestion from gestiones g where g.cod_gestion=c.cod_gestion) 
		from `cobros_cab` c order by c.`cod_cobro` desc";
		$respDatos=mysql_query($sqlDatos);
		while($datDatos=mysql_fetch_array($respDatos)){
			$fechaCobro=$datDatos[1];
			$obsNota=$datDatos[2];
			$montoCobro=$datDatos[3];
			$nombreCliente=$datDatos[4];			
			$nroCobro=$datDatos[5];
			$gestion=$datDatos[6];
		}
		
		$this->SetFont('Arial','',10);
		//$this->SetXY(10,10);		$this->Cell(0,0,$nombreEmpresa,0,0);
		$this->SetXY(80,10);		$this->Cell(0,0,"NOTA DE COBRANZA Nro.".$nroCobro,0,0);
		$this->SetXY(165,10);		$this->Cell(0,0,$fechaCobro,0,0);
		
		$this->SetXY(10,15);		$this->Cell(0,0,"Cliente: ".$nombreCliente,0,0);
		
		$this->SetXY(10,20);		$this->Cell(0,0,"Observaciones: ".$obsNota,0,0);
				
		$this->Line(5, 30, 210,30);
		
		$this->SetXY(20,33);		$this->Cell(0,0,"Nro Doc.",0,0);
		$this->SetXY(40,33);		$this->Cell(0,0,"Nro. Doc. Venta",0,0);
		$this->SetXY(80,33);		$this->Cell(0,0,"Razon Social",0,0);
		$this->SetXY(145,33);		$this->Cell(0,0,"Monto",0,0);
		
		$this->Line(5, 35, 210,35);
		
		$this->ln(10);
		
 
	}
	
	function Footer()
	{
		global $montoTotal;
		global $descuentoFinal;
		global $pesoTotal;
		global $pesoTotalqq;
		
		$this->Line(5, 100, 210,100);
		
		$this->SetY(-35);
		$this->SetX(90);		//$this->Cell(0,0,"Peso Total(Kg)",0,0);	
		$this->SetX(120);		//$this->Cell(0,0,$pesoTotal,0,0);
		
		$this->SetY(-30);

		$this->SetX(90);		//$this->Cell(0,0,"Peso Total(qq)",0,0);
		$this->SetX(120);		//$this->Cell(0,0,$pesoTotalqq,0,0);


		$this->SetY(-25);

		$this->SetY(-15);
		$this->SetX(30);	  $this->Cell(0,0,"Entregue Conforme",0,0);
		$this->SetX(150);	  $this->Cell(0,0,"Recibi Conforme",0,0);
		
		
		$this->SetY(-10);
		// Arial italic 8
		$this->SetFont('Arial','',10);
		// Número de página
		//$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}


$pdf=new PDF('L','mm',array(214,140));

//$pdf=new PDF('P','mm',array(140,214));
$pdf->AliasNbPages();
$pdf->AddPage();
	
$pdf->SetFont('Arial','',10);
			
//AQUI EMPEZAMOS CON EL DETALLE
$codCobro=$_GET['codCobro'];

$sql_detalle="select cd.`nro_doc`, cd.`monto_detalle`, td.`abreviatura`, s.`nro_correlativo`, s.`razon_social`
	from `cobros_cab` c, `cobros_detalle` cd, `salida_almacenes` s, `tipos_docs` td
	where c.`cod_cobro`=cd.`cod_cobro` and cd.`cod_venta`=s.`cod_salida_almacenes` and 
	c.`cod_cobro`='$codCobro' and td.`codigo`=s.`cod_tipo_doc`";		
	
$resp_detalle=mysql_query($sql_detalle);
$montoTotal=0;
$indice=1;
while($dat_detalle=mysql_fetch_array($resp_detalle))
{	$nroDoc=$dat_detalle[0];
	$montoDet=$dat_detalle[1];
	$nroVenta=$dat_detalle[2]."-".$dat_detalle[3];
	$razonSocial=$dat_detalle[4];
	
	$montoTotal=$montoTotal+$montoDet;
	$montoDet=redondear2($montoDet);
	
	
	$pdf->Cell(0,0,$indice,0,0);
	$pdf->SetX(25);
	$pdf->Cell(0,0,$nroDoc,0,0);
	$pdf->SetX(50);
	$pdf->Cell(0,0,$nroVenta,0,0);
	$pdf->SetX(80);
	$pdf->Cell(0,0,$razonSocial,0,0);
	$pdf->SetX(140);
	$pdf->Cell(0,0,$montoDet,0,0);
	
	$pdf->ln(4);
	$indice++;
}

//FIN DETALLE

$pdf->Output();


?>
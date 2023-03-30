<?php
// require("conexionmysqli.php");
require('assets/fpdf/fpdf.php');
// include 'assets/php-barcode-master/barcode.php';

$pdf = new FPDF($orientation='P',$unit='mm', 'A4');
$pdf->AddPage();

// Establecemos el tamaño de las columnas y la altura de las celdas
$col_width = $pdf->GetPageWidth()/2;

// Definimos las variables con los datos de ejemplo
$nombre_producto = 'Mezcladora lava platos extralabable xxxxxx 35090';
$codigo_general = 'ABCD-5678';
$precio = 'P:8250';

// Definimos los márgenes y las medidas de los cards
$margen_x = 10;
$margen_y = 10;
$card_width = 50;
$card_height = 30;
$radio_borde = 3;

// Dibujamos la primera card
$textypos = 5;
$pdf->RoundedRect($margen_x, $margen_y, $card_width, $card_height, $radio_borde);
// Dibujamos la segunda card
$pdf->RoundedRect($margen_x + $card_width + $margen_x, $margen_y, $card_width, $card_height, $radio_borde);


// margen_adicional
$ma = 10;

// TEXTO PRIMERA COLUMNA
/*****************************************************************************/
$textypos = 5;
$pdf->SetFont('Arial','B',9);
$pdf->setY(12);$pdf->setX(0 + $ma);
$pdf->multiCell(55, 3, utf8_decode($nombre_producto), 0, 'B', false);
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$y = $pdf->getY();
$pdf->setY($y + 8);

$code = "abcdef";
// barcode($code.'.png', $code, 20, 'horizontal', 'code128', true);
// barcode($code.'.png', $code,'70','horizontal','code128',true,1);		
// $pdf->Image($code.'.png',10,$y,50,0,'PNG');

$pdf->Cell(115, 20,$pdf->Image('barcode.php?text=0123jsgbjksebhgjkbh456789&size=40&codetype=Code39&print=true' , 15, $pdf->GetY(), 20, 20,'PNG', ''), 1, 0, 'R');

$pdf->Cell(15, $textypos,utf8_decode($codigo_general));
$pdf->Ln();

$pdf->SetFont('Arial','',10);
$pdf->Cell(15, $textypos,utf8_decode($precio));
$pdf->Ln();
/*****************************************************************************/
// TEXTO SEGUNDA COLUMNA
/*****************************************************************************/
$pos2 = 60;
$pdf->SetFont('Arial','B',9);
$pdf->setY(12);$pdf->setX(0 + $ma + $pos2);
$pdf->multiCell(55, 3, utf8_decode($nombre_producto), 0, 'B', false);
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$y = $pdf->getY();
$pdf->setY($y + 8);
$pdf->setX(0 + $ma + $pos2);
$pdf->Cell(15, $textypos,utf8_decode($codigo_general));
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$pdf->setX(0 + $ma + $pos2);
$pdf->Cell(15, $textypos,utf8_decode($precio));
$pdf->Ln();
/*****************************************************************************/


// $textypos = 5;
// $pdf->setY(12);$pdf->setX(0 + $ma);
// $pdf->Cell(5,$textypos,utf8_decode($nombre_producto));

// $pdf->Output('F', 'mi_pdf_con_codigo_de_barras.pdf'); // Guardar el PDF en el servidor con el nombre "mi_pdf_con_codigo_de_barras.pdf"
$pdf->Output('I', 'mi_pdf_con_codigo_de_barras.pdf'); // Mostrar el PDF en el navegador
?>
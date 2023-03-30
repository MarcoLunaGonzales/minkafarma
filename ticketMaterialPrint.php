<?php
require('assets/fpdf/fpdf.php');

/*****************************************************************************/
// Definimos tamaño de HOJA
$orientation = 'P';
$unit        = 'mm';
// $hoja        = 'letter';
$hoja        = array(135,150); // (ancho, alto)

// Tipo de letra
$tipo_letra     = 'Arial';
$estilo         = 'B';
$tamanio_letra  = 9;
// Definimos los márgenes y las medidas de los cards
$margen_x    = 5;
$margen_y    = 5;
$card_width  = 60;
$card_height = 30;
$radio_borde = 3;
$interlineado = 0;
/*****************************************************************************/

$pdf = new FPDF($orientation,$unit, $hoja);
$pdf->AddPage();

// Configuración de Texto
$pdf->SetFont($tipo_letra, $estilo, $tamanio_letra);

// Definimos las variables con los datos de ejemplo
$nombre_producto = $_GET['nombre'];
$codigo_general  = $_GET['codigo'];
$precio          = $_GET['precio'];

// Dibujamos la primera card
$pdf->RoundedRect($margen_x, $margen_y, $card_width, $card_height, $radio_borde);
// Dibujamos la segunda card
$pdf->RoundedRect($margen_x + $card_width + $margen_x, $margen_y, $card_width, $card_height, $radio_borde);


// ****************
// Valor iniciarl
$pdf->setY($margen_y+2);




$margen_seg_y = $pdf->getY();

// TEXTO PRIMERA COLUMNA
/*****************************************************************************/
$pdf->setX($margen_x);
$pdf->multiCell($card_width - 2, 3, utf8_decode($nombre_producto), 0, 'C', false);
$pdf->Ln();

$y = $pdf->getY();
$pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_x+10, $y, $card_width-20, ($card_height/2));
$pdf->Ln();

$pdf->setY($card_height-2);
$pdf->setX($margen_x+1);
$pdf->multiCell($card_width - 5, 3, utf8_decode("P: ".$precio), 0, 'B', false);
$pdf->Ln();
/*****************************************************************************/
// TEXTO SEGUNDA COLUMNA
/*****************************************************************************/
$margen_seg_x = $margen_x + $card_width + $margen_x;
$pdf->setY($margen_seg_y);
$pdf->setX($margen_seg_x);
$pdf->multiCell($card_width - 2, 3, utf8_decode($nombre_producto), 0, 'C', false);
$pdf->Ln();

$y = $pdf->getY();
$pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_seg_x+10, $y, $card_width-20, ($card_height/2));
$pdf->Ln();


$pdf->setY($card_height-2);
$pdf->setX($margen_seg_x+1);
$pdf->multiCell($card_width - 5, 3, utf8_decode("P: ".$precio), 0, 'B', false);
$pdf->Ln();

/*****************************************************************************/

// $pdf->Output('F', 'mi_pdf_con_codigo_de_barras.pdf'); // Guardar el PDF en el servidor con el nombre "mi_pdf_con_codigo_de_barras.pdf"
$pdf->Output('I', 'mi_pdf_con_codigo_de_barras.pdf'); // Mostrar el PDF en el navegador
?>
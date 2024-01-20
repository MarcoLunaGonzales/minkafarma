<?php
require "../funciones_siat.php";
$facturaImpuestos=generarFacturaVentaImpuestos('5357483');

print_r($facturaImpuestos);
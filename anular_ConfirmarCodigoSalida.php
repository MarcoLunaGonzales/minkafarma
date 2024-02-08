<?php

// Obtener el código de la solicitud GET
$codigo = $_GET["codigo"];

// Obtener la fecha y hora actual
$dia = date("d");
$mes = date("m");
$ano = date("Y");
$hh = date("H");
$mm = date("i");

// Generar el código de confirmación sumando el código recibido con la fecha y hora actual
$codigoGenerado = $codigo + $dia + $mes + $ano + $hh + $mm;

// Devolver el código generado como respuesta
echo $codigoGenerado;

?>

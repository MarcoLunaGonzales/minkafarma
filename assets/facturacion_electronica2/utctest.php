<?php
date_default_timezone_set("America/Monterrey");

$anio=date("Y");
$mes=date("m");
$dia=date("d");
$hora=date("H");
$minuto=date("i");
$segundo=date("s");
$fechaEnvio=$anio."-".$mes."-".$dia."T".$hora.":".$minuto.":".$segundo.".000";
echo $fechaEnvio;
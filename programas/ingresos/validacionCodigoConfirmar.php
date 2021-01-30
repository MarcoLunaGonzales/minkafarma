<?php

require("../../funciones.php");

$codigo = $_GET["codigo"];
$clave = $_GET["clave"];

//LLAMAMOS A LA FUNCION PARA LA CLAVE
$claveGenerada=generarCodigoAprobacion($codigo);

//
//comparacion final
if($clave==$claveGenerada)
   {
    echo "OK";
   }
else
   {//echo "ERROR_"."_$clave"."_$claveGenerada"."_";
    echo "ERROR";
   }

?>

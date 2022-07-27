<?php

$codigo = $_GET["codigo"];
$clave = $_GET["clave"];

// //
// $nroDigitos = strlen("".$codigo);
// $nroDigitos--;//total digitos
// //
// $cadAux = strrev($codigo);
// $ultimoCar="".$cadAux[0];//ultimo digito
// //
// $cadAux = "".$codigo;
// $primerCar="".$cadAux[0];//primer digito
// //
// $acumulador=0;
// $cadAux="".$codigo;//echo "_$cadAux<br>";
// for($i=0;$i<=$nroDigitos;$i++)
//    {$acumulador+=$cadAux[$i];//echo "_$cadAux[$i]-----$i";
//    }
// $acumulador=$acumulador+100;//suma de digitos mas 100
// //
// //clave generada
// $claveGenerada="".$nroDigitos.$ultimoCar.$primerCar.$acumulador;
//
//comparacion final
if($clave==$codigo)
   {
    echo 1;
   }
else
   {//echo "ERROR_"."_$clave"."_$claveGenerada"."_";
    echo 2;
   }

?>

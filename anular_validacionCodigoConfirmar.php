<?php
// Obtener el código y la clave de la solicitud GET
$codigo = $_GET["codigo"];
$clave = $_GET["clave"];

// Calcular la longitud del código
$nroDigitos = strlen($codigo) - 1;

// Obtener el último dígito del código
$ultimoCar = $codigo[$nroDigitos];

// Obtener el primer dígito del código
$primerCar = $codigo[0];

// Inicializar el acumulador
$acumulador = 0;

// Calcular la suma de los dígitos del código
for ($i = 0; $i <= $nroDigitos; $i++) {
    $acumulador += $codigo[$i];
}

// Sumar 100 al acumulador
$acumulador += 100;

// Generar la clave final
$claveGenerada = $nroDigitos . $ultimoCar . $primerCar . $acumulador;

// Comparar la clave recibida con la clave generada
if ($clave == $claveGenerada) {
    // Si la clave es correcta, devolver un JSON con el estado "OK"
    echo json_encode(array("status" => "OK"));
} else {
    // Si la clave es incorrecta, devolver un JSON con el estado "ERROR"
    echo json_encode(array("status" => "ERROR"));
}
?>

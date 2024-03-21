<?php

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

echo "hola";

$enlaceCon=mysqli_connect("localhost","root","4868422Marco","elykat");

if (mysqli_connect_errno()){
	echo "Error en la conexión: " . mysqli_connect_error();
}else{
  echo "<br>conectado";
}

//mysqli_set_charset($enlaceCon,"utf8");

var_dump($enlaceCon);

?>
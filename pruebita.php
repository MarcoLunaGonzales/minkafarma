<?php

$fecha="2011-12-01";

echo $fecha."<br>";

$fecha= date("Y-m-d", strtotime("$fecha + 31 days")); 

echo $fecha;

?>
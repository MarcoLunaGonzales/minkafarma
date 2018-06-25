<?php
require('conexion.inc');
$codigo=$_GET['parrilla'];
$id=$_GET['id'];
$cantidad=$_GET['cantidad'];

echo "<input type='text' size='2' class='texto' onBlur='guardaAjaxCambiaCantidadMaterial(this, $codigo_parrilla, $prioridad, $id);' value='$cantidad'>";
?>
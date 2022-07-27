<?php
require "conexionmysqli2.inc";
$existeSucursal=0;
$fecha=$_GET['fecha'];
$anio=date("Y",strtotime($fecha));

$ciudad=$_COOKIE['global_agencia'];

$cuis="";
$cons="SELECT cuis FROM siat_cuis where cod_gestion='$anio' and estado=1 and cod_ciudad='$ciudad' limit 1;";
$rs=mysqli_query($enlaceCon,$cons);
while($reg=mysqli_fetch_array($rs)){
  $cuis = $reg["cuis"];
}


$cufd="";
$cons="SELECT cufd FROM siat_cufd where fecha='$fecha' and estado=1 and cod_ciudad='$ciudad' and cuis='$cuis' limit 1;";
$rs=mysqli_query($enlaceCon,$cons);
while($reg=mysqli_fetch_array($rs)){
  $cufd = $reg["cufd"];
  $existeSucursal=1;
}

// $cuis="";
// $cons="SELECT cuis FROM siat_cuis where cod_gestion=YEAR('$fecha') and estado=1 and cod_ciudad='$ciudad' limit 1;";
// $rs=mysqli_query($enlaceCon,$cons);
// while($reg=mysqli_fetch_array($rs)){
//   $cuis = $reg["cuis"];
//   $existeSucursal=1;
// }
echo $existeSucursal;

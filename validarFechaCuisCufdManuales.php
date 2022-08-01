<?php
require "conexionmysqli2.inc";
$existeSucursal=0;
$fecha=$_GET['fecha'];
$hora=$_GET['hora'];

$anio=date("Y",strtotime($fecha));

$ciudad=$_COOKIE['global_agencia'];

$cuis="";
$created_at="";
$cons="SELECT cuis FROM siat_cuis where cod_gestion='$anio' and estado=1 and cod_ciudad='$ciudad' limit 1;";
$rs=mysqli_query($enlaceCon,$cons);
while($reg=mysqli_fetch_array($rs)){
  $cuis = $reg["cuis"];
}
// existe en hora
$cons="SELECT  cufd from siat_cufd where cod_ciudad='$ciudad' and cuis='$cuis' and  created_at between '$fecha 00:00:00' and '$fecha $hora:00' order by created_at desc limit 1";
// echo $cons;//and '$fecha 23:59:59'
$rs=mysqli_query($enlaceCon,$cons);
while($reg=mysqli_fetch_array($rs)){
  $existeSucursal=1;  
}

if($existeSucursal==0){
	//existe en fecha
	$created_at="";
	$cons="SELECT created_at FROM siat_cufd where fecha>='$fecha' and cod_ciudad='$ciudad' and cuis='$cuis' limit 1;";
	// echo $cons;
	$rs=mysqli_query($enlaceCon,$cons);
	while($reg=mysqli_fetch_array($rs)){
	  $created_at = $reg["created_at"];
	  $existeSucursal=2;
	}
}
// $cuis="";
// $cons="SELECT cuis FROM siat_cuis where cod_gestion=YEAR('$fecha') and estado=1 and cod_ciudad='$ciudad' limit 1;";
// $rs=mysqli_query($enlaceCon,$cons);
// while($reg=mysqli_fetch_array($rs)){
//   $cuis = $reg["cuis"];
//   $existeSucursal=1;
// }
echo $existeSucursal.",".$created_at;

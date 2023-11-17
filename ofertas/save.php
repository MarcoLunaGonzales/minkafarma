<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");

$user=0;
if(isset($_COOKIE['global_usuario'])){
  $user=$_COOKIE['global_usuario'];
}

$fecha_ini=$fecha_ini;
$fecha_fin=$fecha_fin;
$hora_ini=$hora_ini;
$hora_fin=$hora_fin;
$fecha_hora_ini=$fecha_ini." ".$hora_ini;
$fecha_hora_fin=$fecha_fin." ".$hora_fin;

$stockLimitado=$_POST["stock_limitado"];
$nivel_descuento=$_POST["nivel_descuento"];

$sql="SELECT IFNULL(max(codigo)+1,1) FROM $table";
$resp=mysqli_query($enlaceCon,$sql);
$codigo=mysqli_result($resp,0,0);

$sql="insert into $table (codigo,nombre, abreviatura,desde,hasta, estado,por_linea,cod_funcionario,oferta_stock_limitado) values($codigo,'$nombre','$abreviatura','$fecha_hora_ini','$fecha_hora_fin','1','$nivel_descuento','$user','$stockLimitado')";
//echo $sql;
$sql_inserta=mysqli_query($enlaceCon,$sql);

/* Guardando Sucursales */
$sqlDetalle=mysqli_query($enlaceCon,"DELETE FROM tipos_precio_ciudad WHERE cod_tipoprecio='$codigo'");
$sqlSucursales="SELECT d.cod_ciudad, d.nombre_ciudad from ciudades d where d.cod_estadoreferencial=1 order by 1";
$respSucursales=mysqli_query($enlaceCon, $sqlSucursales);
while($datSucursales=mysqli_fetch_array($respSucursales)){
		$codigoSucursalX=$datSucursales[0];	
 		$sql_upd=mysqli_query($enlaceCon,"INSERT INTO tipos_precio_ciudad values($codigo,$codigoSucursalX)");
}

/* Guardando Dias */
$sqlDetalle=mysqli_query($enlaceCon,"DELETE FROM tipos_precio_dias WHERE cod_tipoprecio='$codigo'");
$sqlDias="SELECT d.codigo, d.abreviatura from dias d where d.estado=1 order by 1";
$respDias=mysqli_query($enlaceCon, $sqlDias);
while($datDias=mysqli_fetch_array($respDias)){
		$codigoDiasX=$datDias[0];	
 		$sql_upd=mysqli_query($enlaceCon,"INSERT INTO tipos_precio_dias values($codigo,$codigoDiasX)");
}


	
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='$urlList2';
			</script>";

?>
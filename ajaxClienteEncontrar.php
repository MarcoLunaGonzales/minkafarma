<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
$cliente=$_GET['cliente'];

$nombre_cliente="";
$paterno="";
$nit_cliente="";
$ci_cliente="";
$dir_cliente="";
$telf1_cliente="";
$email_cliente="";
$cod_area_empresa="";
$nombre_factura="";
$cod_tipo_precio="";
$cod_tipo_edad="";
$cod_genero="";

$sql="select cod_cliente,nombre_cliente,paterno,nit_cliente,ci_cliente,dir_cliente,telf1_cliente,email_cliente,cod_area_empresa,nombre_factura,cod_tipo_precio,cod_tipo_edad,cod_genero 
from clientes 
	where cod_cliente='$cliente'";
	//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$cod_cliente=$dat[0];
	$nombre_cliente=$dat[1];
	$paterno=$dat[2];
	$nit_cliente=$dat[3];
	$ci_cliente=$dat[4];
	$dir_cliente=$dat[5];
	$telf1_cliente=$dat[6];
	$email_cliente=$dat[7];
	$cod_area_empresa=$dat[8];
	$nombre_factura=$dat[9];
	$cod_tipo_precio=$dat[10];
	$cod_tipo_edad=$dat[11];
	$cod_genero=$dat[12];
}

echo "0#####".$nombre_cliente."#####".$paterno."#####".$ci_cliente."#####".$nit_cliente."#####".$dir_cliente."#####".$telf1_cliente."#####".$email_cliente."#####".$cod_area_empresa."#####".$nombre_factura."#####".$cod_tipo_edad."#####".$cod_genero;

?>

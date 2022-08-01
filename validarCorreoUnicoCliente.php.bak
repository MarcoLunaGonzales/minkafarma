<?php
require "conexionmysqli2.inc";
$existeSucursal=1;
$cliente=$_GET['cliente'];
$nit=trim($_GET['nit']);
$correo=trim($_GET['correo']);

$sqlCliente="";
if($cliente>0){
	$sqlCliente=" and cod_cliente!='$cliente' ";
}
$sql="SELECT cod_cliente FROM clientes where email_cliente='$correo' $sqlCliente;"; //nit_cliente='$nit' and 
$rs=mysqli_query($enlaceCon,$sql);
while($reg=mysqli_fetch_array($rs)){
  $existeSucursal=0;
}
echo $existeSucursal;

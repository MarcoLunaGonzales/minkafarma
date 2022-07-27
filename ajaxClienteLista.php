<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
$nitCliente=$_GET['nitCliente'];

$sql="select f.cod_cliente,CONCAT(f.nombre_cliente,' ',f.paterno,' (CI:',f.ci_cliente,')')nombre,'' as tipo from clientes f 
	where f.nit_cliente='$nitCliente' order by f.cod_cliente desc";
$resp=mysqli_query($enlaceCon,$sql);

//,(SELECT siat_codigotipodocumentoidentidad FROM salida_almacenes where cod_cliente=f.cod_cliente and siat_codigotipodocumentoidentidad=5 and salida_anulada=0 order by fecha desc limit 1) as tipo

$cod_cliente=146;// varios
$htmlCliente="";
$index=0;
$tipo=1;
while($dat=mysqli_fetch_array($resp)){

	$cod_cliente=$dat[0];
	$nombre_item=$dat[1];
	$tipo=$dat[2];	
	$index++;
	$htmlCliente.="<option value='$cod_cliente' selected>CLI-$index $nombre_item</option>";
}
if($cod_cliente==146){
	$htmlCliente='<option value="146" selected>NO REGISTRADO</option>';
}
echo $cod_cliente."####".$htmlCliente."####".$tipo;
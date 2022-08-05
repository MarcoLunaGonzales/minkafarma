<?php
//$estilosVenta=1;
require("conexionmysqli2.inc");
$cliente=$_GET["cliente"];
$nit=$_GET["nit"];

$sql="select f.cod_cliente,CONCAT(f.nombre_cliente,' ',f.paterno,' (CI:',f.ci_cliente,')')nombre from clientes f 
	where f.nit_cliente='$nit' order by f.cod_cliente desc";
$resp=mysqli_query($enlaceCon,$sql);

$cod_cliente=146;// varios
$htmlCliente="";
$index=0;
while($dat=mysqli_fetch_array($resp)){

	$cod_cliente=$dat[0];
	$nombre_item=$dat[1];	
	$index++;
	if($cod_cliente==$cliente){
		$htmlCliente.="<option value='$cod_cliente' selected>CLI-$index $nombre_item</option>";
	}else{
		$htmlCliente.="<option value='$cod_cliente'>CLI-$index $nombre_item</option>";
	}
}
if($cod_cliente==146){
	$htmlCliente='<option value="146" selected>NO REGISTRADO</option>';
}
echo $htmlCliente;

<?php
$estilosVenta=1;
require('conexionmysqli2.inc');
$ci=$_GET['ci'];
$nombre=$_GET['nombre'];
$paterno=$_GET['paterno'];

$sql_item="SELECT cod_cliente,CONCAT(nombre_cliente,' ',paterno,' (',nit_cliente,')')nombre FROM clientes where nit_cliente>0 ";

if($ci>0){
   $sql_item.=" and (nit_cliente='$ci' or ci_cliente='$ci') ";
}else{
	if($nombre!=""){
	  $sql_item.=" and nombre_cliente like '%$nombre%' ";		
	}else{
			if($nombre!=""){
	  			 $sql_item.=" and paterno like '%$paterno%' ";		
			}else{			
 	 			 $sql_item.=" and cod_cliente<-45465 ";			//para que no liste nada
			}
	}   
}
$sql_item.=" order by 2";
//echo $sql_item;
	$resp=mysqli_query($enlaceCon,$sql_item);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		$nombre_item=$dat[1];
		echo "<option value='$codigo_item' selected>$nombre_item</option>";
	}
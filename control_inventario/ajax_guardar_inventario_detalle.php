<?php
$estilosVenta=1;
require("../conexionmysqli2.inc");
require("configModule.php");

$cantidad_registrada=$_GET["cantidad_registrada"];
$codigo=$_GET["codigo"];
$observacion=$_GET["observacion"];
$sql="UPDATE $tableDetalle set cantidad_registrada='$cantidad_registrada',revisado=1,observacion='$observacion' where codigo=$codigo";
$resp=mysqli_query($enlaceCon,$sql); 

if($resp==1){
	echo "#####1";
}else{
	echo "#####0";
}

  

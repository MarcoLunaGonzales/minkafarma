<?php
require("../conexionmysqli.inc");
error_reporting(0);
require("../estilos2.inc");
require("configModule.php");

$codigo=$_POST['codigo'];
$cod_tipoprecio=$_POST['tipo'];
$sql_upd=mysqli_query($enlaceCon,"DELETE FROM tipos_precio_dias WHERE cod_tipoprecio=$cod_tipoprecio");
for ($i=0; $i < count($codigo); $i++) { 
	if($codigo[$i]>0){
	   $dia=$codigo[$i];
       $sql_upd=mysqli_query($enlaceCon,"INSERT INTO tipos_precio_dias values($cod_tipoprecio,$dia)");
	}
}

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlList2';
			</script>";
?>
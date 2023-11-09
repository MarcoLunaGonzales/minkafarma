<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");

$codigo=$_GET['codigo_registro'];
$estado=$_GET['estado'];
$obs=$_GET['obs'];
$fechaActual=date("Y-m-d H:i:s");
$sql="UPDATE inventarios_sucursal SET estado_inventario=$estado,glosa_inventario='$obs' where codigo=$codigo";
if((int)$estado==4){
	$sql="UPDATE inventarios_sucursal SET estado_inventario=$estado,fecha_iniciorevision='$fechaActual',glosa_inventario='$obs' where codigo=$codigo";
}
if((int)$estado==3){
	$sql="UPDATE inventarios_sucursal SET estado_inventario=$estado,fecha_finrevision='$fechaActual',glosa_inventario='$obs' where codigo=$codigo";
}


$sql_upd=mysqli_query($enlaceCon,$sql);
if($sql_upd==1){
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlList2';
			</script>";
}else{
    echo "<script language='Javascript'>
			alert('Ocurrio un error al procesar los datos.');
			location.href='$urlList2';
			</script>";
}

?>
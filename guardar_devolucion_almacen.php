<?php
require("conexion.inc");
require("estilos_almacen.inc");
$numero2=count($_POST);
$tags2=array_keys($_POST);
$valores2=array_values($_POST);
//sacamos el codigo maximo
$respMax=mysql_query("select codigo_devolucion from devoluciones_ciclo order by codigo_devolucion desc");
$datMax=mysql_fetch_array($respMax);
$filasMax=mysql_num_rows($respMax);
if($filasMax==0){
	$codigoDevolucion=1;
}
else{
	$codigoDevolucion=$datMax[0]+1;
}

echo $codigoDevolucion;

$sqlInsertCab="insert into devoluciones_ciclo values('$codigoDevolucion','$global_gestion','$ciclo_global','$global_visitador',1,1,0)";
$respInsertCab=mysql_query($sqlInsertCab);

for($i=0;$i<=$numero2-1;$i++){
	$var_aux=$tags2[$i];
	$vector_aux=split("-",$var_aux);
	$codigoMaterial=$vector_aux[0];
	$cantidadTeorica=$vector_aux[1];
	$cantidadDevolucion=$valores2[$i];
	$sqlInsertDet="insert into devoluciones_ciclodetalle values('$codigoDevolucion','$codigoMaterial','$cantidadTeorica','$cantidadDevolucion')";
	$respInsertDet=mysql_query($sqlInsertDet);
}
echo "<script language='JavaScript'>
	alert('Los datos se registraron satisfactoriamente');
	location.href='navegador_devolucion_visitador.php';
</script>";

?>
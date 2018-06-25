<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$sql="select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' and grupo_ingreso='2' order by cod_ingreso_almacen desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$nro_correlativo=1;
}
else
{	$nro_correlativo=$dat[0];
	$nro_correlativo++;
}
$hora_sistema=date("H:i:s");
$fecha_real=date("Y-m-d");
$nombreVisitador=$_POST['nombre_visitador'];
$sql_inserta=mysql_query("insert into ingreso_almacenes 
values($codigo,$global_almacen,1001,'$fecha_real','$hora_sistema','Por devolucion de ciclo. Visitador: $nombreVisitador',2,0,'0','$nro_correlativo',0)");

$numero2=count($_POST);
$tags2=array_keys($_POST);
$valores2=array_values($_POST);
$cod_devolucion=$_POST['cod_devolucion'];
$cod_ciclo=$_POST['cod_ciclo'];
$cod_gestion=$_POST['cod_gestion'];
$cod_visitador=$_POST['cod_visitador'];


for($i=0;$i<=$numero2-6;$i++)
{	$cod_material=$tags2[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$valores2[$i];
	//sacamos lotes y vencimientos
	$sqlLote="select i.`nro_lote`, i.`fecha_vencimiento` from `salida_detalle_visitador` sv, 
		`salida_detalle_almacenes` sd, `salida_detalle_ingreso` si, `ingreso_detalle_almacenes` i 
		where sv.`cod_salida_almacen` = sd.`cod_salida_almacen` and sd.`cod_salida_almacen`=si.`cod_salida_almacen` and  
		sd.`cod_material`=si.`material` and si.`cod_ingreso_almacen`=i.`cod_ingreso_almacen` and 
		sv.`codigo_ciclo` = $cod_ciclo and sv.`codigo_gestion` = $cod_gestion and sd.`cod_material`='$cod_material' and 
		sv.`codigo_funcionario` in ($cod_visitador)";
	$respLote=mysql_query($sqlLote);
	$nroLote=mysql_result($respLote,0,0);
	$fechaVenci=mysql_result($respLote,0,1);
	//sacar lotes y vencimientos
	 
	$sql_inserta2="insert into ingreso_detalle_almacenes values($codigo,'$cod_material','$nroLote','$fechaVenci',$cantidad,$cantidad)";
	$resp_inserta2=mysql_query($sql_inserta2);
	
	$sqlUpd="update devoluciones_ciclodetalle set cantidad_ing_almacen='$cantidad'
		where codigo_devolucion=$cod_devolucion and codigo_material='$cod_material'";
	$respUpd=mysql_query($sqlUpd);

}
$sql_actualiza="update devoluciones_ciclo set estado_devolucion=2 where codigo_devolucion=$cod_devolucion";
$resp_actualiza=mysql_query($sql_actualiza);
//echo $sql_actualiza;
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_devolucion_almacen.php';
			</script>";
?>
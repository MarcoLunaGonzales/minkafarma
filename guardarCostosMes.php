<?php
require("conexion.inc");
require("estilos.inc");

$codMes=$_POST['codMes'];
$codAnio=$_POST['codAnio'];
$codAlmacen=$_POST['codAlmacen'];

$codMesAux=$codMes+1;
$codAnioAux=$codAnio;
if($codMesAux==13){
	$codMesAux=1;
	$codAnioAux=$codAnioAux+1;
}

$fecha="$codAnioAux-$codMesAux-01";

$sql="select codigo_material from material_apoyo";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codigoMat=$dat[0];
	$costoUnit=$_POST["$codigoMat"];
	
	$sqlDel="delete from costo_promedio_mes where cod_material='$codigoMat' and mes='$codMes' and anio='$codAnio'";
	$respDel=mysql_query($sqlDel);
	
	//sacamos las existencias del item en el almacen
	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<'$fecha' and i.cod_almacen='$codAlmacen'
			and id.cod_material='$codigoMat' and i.ingreso_anulado=0";
	$resp_ingresos=mysql_query($sql_ingresos);
	$dat_ingresos=mysql_fetch_array($resp_ingresos);
	$cant_ingresos=$dat_ingresos[0];
	$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<'$fecha' and s.cod_almacen='$codAlmacen'
			and sd.cod_material='$codigoMat' and s.salida_anulada=0";
	$resp_salidas=mysql_query($sql_salidas);
	$dat_salidas=mysql_fetch_array($resp_salidas);
	$cant_salidas=$dat_salidas[0];
	$cantExistencia=$cant_ingresos-$cant_salidas;
	//fin sacar existencias
	$valorUnitario=$costoUnit*$cantExistencia;
	
	$sqlInsert="insert into costo_promedio_mes values('$codAlmacen', '$codAnio', '$codMes', '$codigoMat', '$cantExistencia', '$costoUnit', '$valorUnitario')";
	$respInsert=mysql_query($sqlInsert);
		
}
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegadorCostosMesAlmacen.php';
			</script>";


?>
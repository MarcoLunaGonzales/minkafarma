<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$fecha_finrevision=$fecha_fin." 23:59:59";
//$fecha_reporte=date("Y-m-d");
$cod_ciudad=$_COOKIE['global_agencia'];
$codAlmacen=$_COOKIE['global_almacen'];
// $cod_funcionario=$_COOKIE['global_usuario'];
$cod_funcionario=$_POST['rpt_funcionario'];
$sql="SELECT IFNULL(max(codigo)+1,1) FROM $table";
$resp=mysqli_query($enlaceCon,$sql);
$codigo=mysqli_result($resp,0,0);


$sql="insert into $table (codigo,nombre, fecha_reporte,cod_ciudad,cod_funcionario, glosa_inventario,estado_inventario,cod_estadoreferencial) values($codigo,'$nombre','$fecha_finrevision','$cod_ciudad','$cod_funcionario','$glosa_inventario','1','1')";
$sql_inserta=mysqli_query($enlaceCon,$sql);
if($sql_inserta==1){
	$sqlInsertDetalleInv="";
	$stringLineas=implode(",",$rpt_subcategoria);
	$sqlLineas="SELECT codigo_material FROM material_apoyo WHERE cod_linea_proveedor in ($stringLineas)";
    $resp=mysqli_query($enlaceCon,$sqlLineas);
    while($dat=mysqli_fetch_array($resp))
	{
		$codigo_material=$dat[0];
		$stock=stockProducto($enlaceCon, $codAlmacen, $codigo_material);
		$sqlInsertDetalleInv.="($codigo,'$codigo_material','$stock','0','','0','$fecha_finrevision'),"; 
        
	}
	$sqlInsertDetalleInv = substr_replace($sqlInsertDetalleInv, '', -1, 1);
    $sqlInsertDet="INSERT INTO $tableDetalle (cod_inventariosucursal,cod_material,cantidad,cantidad_registrada,observacion,revisado,fecha_saldo) VALUES ".$sqlInsertDetalleInv.";";	
    $sqlDetalle_inserta=mysqli_query($enlaceCon,$sqlInsertDet);
}
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='$urlList2';
			</script>";

?>
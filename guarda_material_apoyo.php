<?php
require("conexion.inc");
require("estilos.inc");
$sql="select codigo_material from material_apoyo order by codigo_material desc";
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

//AQUI VERIFICAMOS EL ORDEN INGRESADO PARA REARMAR EL MISMO
$sqlVeri="select * from material_apoyo where orden_grupo=$codOrdenGrupo and cod_tipo_material=$tipo_material";
$respVeri=mysql_query($sqlVeri);
$numFilas=mysql_num_rows($respVeri);
if($numFilas>=1){
	$sqlUpd="select codigo_material, orden_grupo from material_apoyo where cod_tipo_material=$tipo_material and 
			orden_grupo>=$codOrdenGrupo order by orden_grupo";
	$respUpd=mysql_query($sqlUpd);
	
	$ordenGrupoNew=$codOrdenGrupo+1;
	
	while($datUpd=mysql_fetch_array($respUpd)){
		$codMaterial=$datUpd[0];
		
		$sqlActUpd="update material_apoyo set orden_grupo=$ordenGrupoNew where codigo_material=$codMaterial";
		$respActUpd=mysql_query($sqlActUpd);
		
		$ordenGrupoNew++;
	}
}
//FIN ORDEN
$sql_inserta="insert into material_apoyo values($codigo,'$material','Activo','$tipo_material','$peso','$codOrdenGrupo','$abreviatura','$item_metraje','$nro_metros')";
$resp_inserta=mysql_query($sql_inserta);
echo $sql_inserta;
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php';
			</script>";
?>
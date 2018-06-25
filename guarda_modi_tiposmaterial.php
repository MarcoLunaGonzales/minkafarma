<?php
require("conexion.inc");
require("estilos.inc");
$sql_upd=mysql_query("update tipos_material set nombre_tipomaterial='$tipo_material', obs_tipomaterial='$obs_tipo_material' where cod_tipomaterial='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_tiposmaterial.php';
			</script>";
?>
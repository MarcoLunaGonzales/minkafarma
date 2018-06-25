<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update materiales set cod_tipomaterial='$tipo_material', nombre_material='$nombre_material',
		 			cod_producto='$producto', cod_forma='$forma_farmaceutica', presentacion='$presentacion'
 			   		where cod_material='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_materiales.php';
			</script>";
?>
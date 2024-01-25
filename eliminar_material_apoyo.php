<?php

	require("conexionmysqli2.inc");
	require("funciones.php");
	//require('estilos_inicio_adm.inc');
	$datos=$_GET["datos"];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		// VERIFICACIÓN DE STOCK
		if(!verificaStockSucursales($vector[$i])){

			$sql="update material_apoyo set estado=0 where codigo_material=$vector[$i]";
			// echo $sql;
			$resp=mysqli_query($enlaceCon,$sql);
			if ($resp) {
				// Mensaje de éxito
				echo "<script language='Javascript'>
						alert('El producto fue eliminado correctamente.');
						location.href = 'navegador_material.php';
					  </script>";
			} else {
				// Mensaje de error en caso de fallo en la consulta
				echo "<script language='Javascript'>
						alert('Ocurrió un error al intentar eliminar el producto.');
						location.href = 'navegador_material.php';
					  </script>";
			}
		}else{
			echo "<script language='Javascript'>
			alert('No se eliminó el producto porque aún cuenta con stock en alguna de las sucursales.');
			location.href='navegador_material.php';
			</script>";
		}
	}


?>
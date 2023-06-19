<?php
require("conexionmysqli2.inc");
$codProveedor=$_GET['codProveedor'];

	echo "<select name='rpt_item' class='texto'>";
	
	$sql_item="select m.codigo_material, m.descripcion_material from material_apoyo m, proveedores_lineas pl, proveedores p
		where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor and p.cod_proveedor='$codProveedor';";
	
	$resp=mysqli_query($enlaceCon, $sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		$nombre_item=$dat[1];
		if($rpt_item==$codigo_item)
		{	echo "<option value='$codigo_item' selected>$nombre_item</option>";
		}
		else
		{	echo "<option value='$codigo_item'>$nombre_item</option>";
		}
	}
	echo "</select>";

?>

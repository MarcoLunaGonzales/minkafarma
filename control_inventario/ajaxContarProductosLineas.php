<?php
require("../conexionmysqli2.inc");
require("configModule.php");
$lineas=implode(",",$_GET["lineas"]);
$sql_material="SELECT count(*)cantidad FROM material_apoyo where cod_linea_proveedor in ($lineas) and estado=1; ";
$resp_material=mysqli_query($enlaceCon,$sql_material);
$dat_material=mysqli_fetch_array($resp_material);
$cantidadProductos=$dat_material[0];
echo $cantidadProductos;
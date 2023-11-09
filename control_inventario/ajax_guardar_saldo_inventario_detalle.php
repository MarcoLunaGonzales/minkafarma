<?php
$estilosVenta=1;
require("../conexionmysqli2.inc");
require("configModule.php");
require("../funciones.php");

$fechaActual=$_GET["fecha"];
$codigo=$_GET["codigo"];

$sql_material="SELECT cod_material FROM inventarios_sucursal_detalle where codigo='$codigo';";
$resp_material=mysqli_query($enlaceCon,$sql_material);
$dat_material=mysqli_fetch_array($resp_material);
$codigoMaterial=$dat_material[0];
$codAlmacen=$_COOKIE['global_almacen'];
$stock=stockProductoFechas($codAlmacen, $codigoMaterial,$fechaActual);
$sql="UPDATE $tableDetalle set cantidad='$stock',fecha_saldo='$fechaActual 23:59:59' where codigo=$codigo";
$resp=mysqli_query($enlaceCon,$sql);
echo $stock;
<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
$categoria=$_GET["categoria"];
$sql="SELECT cod_linea_proveedor,nombre_linea_proveedor from proveedores_lineas where estado=1 and cod_proveedor=$categoria order by 2";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp))
{ $codigo_cat=$dat[0];
  $nombre_cat=$dat[1];
  echo "<option value='$codigo_cat'>$nombre_cat</option>";
}

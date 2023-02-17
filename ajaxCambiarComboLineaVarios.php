<?php
$estilosVenta=1;
require("conexionmysqli2.inc");
$categoria=implode(",",json_decode($_GET["categoria"]));
$sql="SELECT cod_linea_proveedor,nombre_linea_proveedor from proveedores_lineas where estado=1 and cod_proveedor in ($categoria) order by cod_proveedor,2";
echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp))
{ $codigo_cat=$dat[0];
  $nombre_cat=$dat[1];
  echo "<option value='$codigo_cat'>$nombre_cat</option>"; 
}

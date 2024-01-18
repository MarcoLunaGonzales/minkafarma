<?php
require "../funciones_siat.php";

require "../../conexionmysqli.inc";

$ciudad=$_GET['cod_ciudad'];
$codEntidad=$_GET['codEntidad'];
$sql="select cod_impuestos,descripcion from ciudades where cod_ciudad='$ciudad' and cod_entidad='$codEntidad'";
// echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];
$descripcion=$dat[1];

// echo $ciudad.".".$cod_impuestos.".".$descripcion;
abrirPuntoVenta($ciudad,$cod_impuestos,5,$descripcion,$codEntidad);
?>
<script type="text/javascript">window.location.href='index.php'</script>

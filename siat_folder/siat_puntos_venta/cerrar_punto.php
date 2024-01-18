<?php
require "../funciones_siat.php";

require "../../conexionmysqli.inc";

$ciudad=$_GET['cod_ciudad'];
$codEntidad=$_GET['codEntidad'];
$sql="select cod_impuestos from ciudades where cod_ciudad='$ciudad' and cod_entidad='$codEntidad'";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];

cerrarPuntoVenta($ciudad,$cod_impuestos,$codEntidad);
?>
<script type="text/javascript">window.location.href='index.php'</script>

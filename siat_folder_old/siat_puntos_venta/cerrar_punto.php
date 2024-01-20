<?php
require "../funciones_siat.php";

require "../../conexionmysqli.inc";

$ciudad=$_GET['cod_ciudad'];
$sql="select cod_impuestos from ciudades where cod_ciudad='$ciudad'";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];

cerrarPuntoVenta($ciudad,$cod_impuestos);
?>
<script type="text/javascript">window.location.href='index.php'</script>

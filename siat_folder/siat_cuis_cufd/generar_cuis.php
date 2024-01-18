<?php
require "../funciones_siat.php";

require "../../conexionmysqli.php";
$ciudad=$_GET['cod_ciudad'];
$cod_entidad=$_GET['cod_entidad'];
$sql="select c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta from ciudades c where c.cod_ciudad='$ciudad' and cod_entidad=$cod_entidad";

$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];
$codigoPuntoVenta=$dat[1];
generarCuis($ciudad,$cod_impuestos,$codigoPuntoVenta,$cod_entidad);
// exit;
?>

<script type="text/javascript">window.location.href='index.php'</script>

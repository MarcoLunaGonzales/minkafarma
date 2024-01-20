<?php
require "../funciones_siat.php";

require "../../conexionmysqli.inc";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ciudad=$_GET['cod_ciudad'];
$cod_entidad=$_GET['cod_entidad'];
$sql="select c.cod_impuestos,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta from ciudades c where c.cod_ciudad='$ciudad' and cod_entidad=$cod_entidad";
// echo $sql."<br><br><br>";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_impuestos=$dat[0];
$codigoPuntoVenta=$dat[1];
$cuis=obtenerCuis_vigente_BD($ciudad,$cod_entidad);
deshabilitarCufd($ciudad,$cuis,date('Y-m-d'),$cod_entidad);

generarCufd($ciudad,$cod_impuestos,$codigoPuntoVenta,$cod_entidad);
// exit;
?>
<script type="text/javascript">window.location.href='index.php'</script>

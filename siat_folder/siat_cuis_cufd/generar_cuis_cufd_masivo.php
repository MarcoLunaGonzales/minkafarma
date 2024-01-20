<?php
require "../funciones_siat.php";

require "../../conexionmysqli.inc";
$cod_entidad=$_GET['cod_entidad'];
$sql="SELECT c.cod_ciudad,(SELECT codigoPuntoVenta from siat_puntoventa where cod_ciudad=c.cod_ciudad)as codigoPuntoVenta,c.cod_impuestos  from ciudades c where c.cod_impuestos>=0 AND c.cod_ciudad>1 having codigoPuntoVenta>0 and cod_entidad='$cod_entidad' order by c.cod_ciudad;";
//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){	
	$ciudad=$dat[0];
	$codigoPuntoVenta=$dat[1];
	$cod_impuestos=$dat[2];

	//echo $ciudad."-OK";
	generarCuis($ciudad,$cod_impuestos,$codigoPuntoVenta,$cod_entidad);
	generarCufd($ciudad,$cod_impuestos,$codigoPuntoVenta,$cod_entidad);
}
?>
<script type="text/javascript">window.location.href='index.php'</script>

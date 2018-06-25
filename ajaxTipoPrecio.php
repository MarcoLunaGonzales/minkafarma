<?php
require("conexion.inc");
$codCliente=$_GET['codCliente'];

$sql="select t.codigo, t.nombre from `tipos_precio` t 
	where t.`codigo` in (select c.`cod_tipo_precio` from clientes c where c.`cod_cliente`='$codCliente')";
$resp=mysql_query($sql);
$numFilas=mysql_num_rows($resp);
$tipoPrecioCliente=-1;
if($numFilas>0){
	$tipoPrecioCliente=mysql_result($resp,0,0);
}

$sql1="select codigo, nombre from tipos_precio order by 2";
$resp1=mysql_query($sql1);
echo "<select name='tipoPrecio' class='texto' id='tipoPrecio'>";
while($dat=mysql_fetch_array($resp1)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	if($tipoPrecioCliente==$codigo){
		echo "<option value='$codigo' selected>$nombre</option>";
	}else{
		echo "<option value='$codigo'>$nombre</option>";
	}
}
echo "</select>";

?>

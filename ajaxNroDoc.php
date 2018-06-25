<?php
require("conexion.inc");
$codTipoDoc=$_GET['codTipoDoc'];

$sql="select max(nro_correlativo)+1 from salida_almacenes where cod_tipo_doc=$codTipoDoc";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
		$codigo=$dat[0];
		if($codigo==null){
			if($codTipoDoc==1){
				echo "<input type='text' name='nroCorrelativoFactura' value='1' size='2' id='nroCorrelativoFactura'>";
			}else{
				echo "1";
			}
		}else{
			if($codTipoDoc==1){
				echo "<input type='text' name='nroCorrelativoFactura' value='$codigo' size='2' id='nroCorrelativoFactura'>";
			}else{
				echo "$codigo";
			}
			
		}
		
	}


?>

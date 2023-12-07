<script>
        <link href="../stilos.css" rel='stylesheet' type='text/css'>
</script>
<?php
require("../conexionmysqli.inc");
require("../funciones.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$fecha=$_POST["fecha"];
$fecha=formateaFechaVista($fecha);
$hora=date("H:i:s");

$cliente=$_POST["cliente"];
$nroFilas=$_POST["nroFilas"];
$observaciones=$_POST["observaciones"];




$sql="SELECT cod_cobro FROM cobros_cab ORDER BY cod_cobro DESC";
//echo $sql;
$resp=mysqli_query($enlaceCon, $sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
	$codigo++;
}

$sqlNro="select IFNULL(max(nro_cobro)+1,1) from cobros_cab";
$resp=mysqli_query($enlaceCon, $sqlNro);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
$nroCobranza=$dat[0];




$montoTotal=0;

echo "monto=0";
$globalGestion=1;

for($i=1;$i<=$nroFilas;$i++)
{   		
	
	echo $i." iii";

	$codVenta=$_POST["codCobro$i"];	
	$montoPago=$_POST["montoPago$i"];
	$nroDoc=$_POST["nroDoc$i"];
	$tipoPago=$_POST["tipoPago$i"];
	
	$montoTotal=$montoTotal+$montoPago;
	if($montoPago>0){
		$sql_inserta="INSERT INTO `cobros_detalle` (`cod_cobro`,`cod_venta`,`monto_detalle`,`nro_doc`, cod_tipopago) 
			VALUE ('$codigo','$codVenta','$montoPago','$nroDoc', '$tipoPago')";
		//echo $sql_inserta;
		$sql_inserta=mysqli_query($enlaceCon, $sql_inserta);
	}
	
	//actualizamos la tabla ordenes de compra
	$sqlUpd="update salida_almacenes set monto_cancelado=monto_cancelado+$montoPago where cod_salida_almacenes='$codVenta'";
	$respUpd=mysqli_query($enlaceCon, $sqlUpd);

	//echo $i;
}

$sqlInsertC="INSERT INTO `cobros_cab` (`cod_cobro`,`fecha_cobro`,`monto_cobro`,`observaciones`,`cod_cliente`,`cod_estado`,`cod_gestion`,`nro_cobro`) 
	VALUE ('$codigo','$fecha','$montoTotal','$observaciones','$cliente','1','$globalGestion','$nroCobranza')";

//echo $sqlInsertC;

$respInsertC=mysqli_query($enlaceCon, $sqlInsertC);

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegadorCobranzas.php';";
echo "</script>";
?>




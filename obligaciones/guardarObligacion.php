<script>
        <link href="../../stilos.css" rel='stylesheet' type='text/css'>
</script>
<?php
require("../conexionmysqli2.inc");
require("../funciones.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$fecha	= $_POST["fecha"];
$fecha	= formateaFechaVista($fecha);
$hora	= date("H:i:s");

$proveedor 	   = $_POST["proveedor"];
$nroFilas 	   = $_POST["nroFilas"];
$observaciones = $_POST["observaciones"];

$sql="SELECT cod_pago FROM pagos_proveedor_cab ORDER BY cod_pago DESC";
// echo $sql;
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

$sqlNro="SELECT max(nro_pago)+1 from pagos_proveedor_cab where cod_gestion in (SELECT cod_gestion from gestiones where estado=1)";
$nroPago=obtenerCodigo($enlaceCon, $sqlNro);

$montoTotal=0;

// echo "monto=0";
$globalGestion=1;

for($i=1;$i<=$nroFilas;$i++)
{   		
	// echo $i." iii";
	$codIngresoAlmacen=$_POST["codIngresoAlmacen$i"];	
	$montoPago=$_POST["montoPago$i"];
	$nroDoc=$_POST["nroDoc$i"];
	
	$montoTotal=$montoTotal+$montoPago;
	if($montoPago>0){
		$sql_inserta="INSERT INTO `pagos_proveedor_detalle` (`cod_pago`,`cod_ingreso_almacen`,`monto_detalle`,`nro_doc`) 
			VALUE ('$codigo','$codIngresoAlmacen','$montoPago','$nroDoc')";
		// echo $sql_inserta;
		$sql_inserta=mysqli_query($enlaceCon, $sql_inserta);
	}
	
	//actualizamos la tabla Ingreso
	$sqlUpd="UPDATE ingreso_almacenes set monto_cancelado = monto_cancelado+$montoPago where cod_ingreso_almacen='$codIngresoAlmacen'";
	$respUpd=mysqli_query($enlaceCon, $sqlUpd);
	//echo $i;
}
$sqlInsertC="INSERT INTO `pagos_proveedor_cab` (`cod_pago`,`fecha`,`monto_pago`,`observaciones`,`cod_proveedor`,`cod_estado`,`cod_gestion`,`nro_pago`) 
	VALUE ('$codigo','$fecha','$montoTotal','$observaciones','$proveedor','1','$globalGestion','$nroPago')";

// echo $sqlInsertC;

$respInsertC=mysqli_query($enlaceCon, $sqlInsertC);
echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegadorObligaciones.php';";
echo "</script>";
?>




<html>

<?php
require_once "conexionmysqlipdf.inc";
require_once "funciones.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');

$codVenta=$_GET["codVenta"];
$tipoDoc=$_GET["tipodoc"];


/*SACAMOS EL TIPO DE IMPRESION PDF O HTML*/
$tipoImpresion=obtenerValorConfiguracion($enlaceCon,48);
$tipoVentaCaja=obtenerValorConfiguracion($enlaceCon,17);

/**************  Si es 0 Vemos el PDF -> 1 HTML **********************/
if($tipoImpresion==0 && $tipoVentaCaja==0){
	if($tipoDoc==1){
		$url="formatoFactura.php?codVenta=$codVenta";
	}else{
		$url="formatoNotaRemision.php?codVenta=$codVenta";
	}
	?>
	<script>
	window.open('<?=$url;?>','newwindow');
	window.open('registrar_salidaventas.php','_self');
	</script>
	<?php
}elseif($tipoImpresion==1 && $tipoVentaCaja==0) {
	if($tipoDoc==1){
		$url="formatoFacturaOnline.php?codVenta=$codVenta";
	}else{
		$url="formatoNotaRemisionOnline.php?codVenta=$codVenta";
	}
	?>
	<script>
		location.href='<?=$url;?>';
	</script>
	<?php
}elseif ($tipoVentaCaja==1) {
	?>
	<script>
		location.href="formatoTicketVentaCaja.php?codVenta=<?=$codVenta;?>";
	</script>
	<?php
}

?>

</html>
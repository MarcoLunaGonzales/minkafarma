<html>

<?php
$codVenta=$_GET["codVenta"];
$tipoDoc=$_GET["tipodoc"];

if($tipoDoc==1){
	$url="formatoFactura.php?codVenta=$codVenta";
}else{
	$url="formatoNotaRemision.php?codVenta=$codVenta";
}
?>

<script>
	console.log('entrando 1');
	window.open('<?=$url;?>','newwindow');

</script>	


<script>
	console.log('entrando 2');
	window.open('registrar_salidaventas.php','_self');
</script>	


</html>
<?php
if(isset($_GET['codVenta'])){
	$codSalida=$_GET['codVenta'];
}else{
	$codSalida=$codigoVenta;
}


require "conexionmysqli2.inc";
require_once "siat_folder/funciones_siat.php";  
$facturaImpuestos=generarXMLFacturaVentaImpuestos($codSalida);

$sqlDatosVenta="select s.siat_cuf
        from `salida_almacenes` s
        where s.`cod_salida_almacenes`='$codSalida'";
$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
$cuf="";
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
    $cuf=$datDatosVenta['siat_cuf'];

}
$nombreFile="siat_folder/Siat/temp/Facturas-XML/$cuf.xml";
unlink($nombreFile);	
$archivo = fopen($nombreFile,'a');    
fputs($archivo,$facturaImpuestos);
fclose($archivo);

if(isset($sw_correo)){
	
}else{
	if(!isset($_GET["email"])){
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=\"$cuf.xml\"");
		readfile($nombreFile);	
		unlink($nombreFile);
	}else{
		echo $cuf.".xml";
	}	
}






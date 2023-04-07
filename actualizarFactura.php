<?php
require_once 'conexionmysqli.inc';

$cod_venta_edit 	= $_GET['cod_venta_edit'];
$edit_cod_vendedor 	= $_GET['edit_cod_vendedor'];
$edit_cod_tipopago 	= $_GET['edit_cod_tipopago'];

$sqlUpd="UPDATE salida_almacenes 
		SET cod_chofer = '$edit_cod_vendedor',
		cod_tipopago = '$edit_cod_tipopago' 
		WHERE cod_salida_almacenes = '$cod_venta_edit'";
$respUpd = mysqli_query($enlaceCon, $sqlUpd);

echo $respUpd; // Ejecutar QUERY

?>




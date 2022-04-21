<?php

require("../../conexionmysqli.php");

$nombreLinea = $_POST["nombreLinea"];
$abreviatura = $_POST["abreviatura"];
$contacto1 = $_POST["contacto1"];
$contacto2 = $_POST["contacto2"];
$codProveedor=$_POST["codProveedor"];
$codLinea=$_POST["codLinea"];
$codProcedencia=$_POST["cod_procedencia"];
$margen=$_POST["margen"];


$consulta="
update proveedores_lineas set nombre_linea_proveedor='$nombreLinea', abreviatura_linea_proveedor='$abreviatura', 
contacto1='$contacto1', contacto2='$contacto2', cod_procedencia='$codProcedencia', margen_precio='$margen'
where cod_linea_proveedor='$codLinea' and cod_proveedor='$codProveedor'";

$resp=mysqli_query($enlaceCon,$consulta);

if($resp) {
    echo "<script>
		alert('Se edito correctamente.');
		location.href='navegadorLineasDistribuidores.php?codProveedor=$codProveedor';
		</script>";
} else {
    //echo "$consulta";
    echo "<script>
		alert('Error al editar el registro.');
		location.href='navegadorLineasDistribuidores.php?codProveedor=$codProveedor';
		</script>";
}

?>

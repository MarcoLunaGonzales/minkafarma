<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");

$tipoGasto=$_POST["tipo_gasto"];
$fechaGasto=$_POST["fecha"];
$nombreGasto=$_POST["nombre_gasto"];
$montoGasto=$_POST["monto_gasto"];

$globalCiudad=$_COOKIE["global_agencia"];


$sql="SELECT cod_gasto FROM gastos ORDER BY cod_gasto DESC";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
	$codigo++;
}

$sql_inserta="INSERT INTO gastos (cod_gasto, descripcion_gasto, cod_tipogasto, estado, fecha_gasto, monto, cod_ciudad)
		values ('$codigo', '$nombreGasto', '$tipoGasto', '1', '$fechaGasto', '$montoGasto','$globalCiudad')";
$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);

echo "<script type='text/javascript' language='javascript'>";
echo "    alert('Los datos fueron insertados correctamente.');";
echo "    location.href='navegador_gastos.php';";
echo "</script>";

?>




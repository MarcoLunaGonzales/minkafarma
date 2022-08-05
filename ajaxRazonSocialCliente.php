<?php
$estilosVenta=1;
require("conexionmysqli.php");
$cliente=$_GET['cliente'];

$nombre="";

$sql="select f.nombre_factura from clientes f 
	where f.cod_cliente='$cliente'";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$nombre=$dat[0];
}

if($cliente==146){
	$nombre="SN";
}
echo "<input type='text' value='$nombre' class='form-control' name='razonSocial' id='razonSocial' required style='text-transform:uppercase;'  onchange='ajaxNitCliente(this.form);' onkeyup='javascript:this.value=this.value.toUpperCase();' placeholder='Ingrese la razon social' pattern='[A-Za-z0-9Ññ.& ]+'>";

?>

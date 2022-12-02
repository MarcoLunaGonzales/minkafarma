<meta charset="utf-8">
<?php

require("conexionmysqli.inc");
require("estilos.inc");
require("funcion_nombres.php");

$rpt_ciudad=$_GET["c"];
$user=$_GET["p"];
$sqlInsert="UPDATE funcionarios SET cod_ciudad='$rpt_ciudad' where codigo_funcionario='$user' ";
$respInsert=mysqli_query($enlaceCon,$sqlInsert);	
echo "<script language='Javascript'>
		swal({
    title: 'Correcto!',
    text: 'Se configuró la sucursal predeterminada',
    type: 'success'
}).then(function() {
    window.location = 'asignarSucursalPersonal.php?p=$user&c=$rpt_ciudad';
});
			</script>";	
?>
<meta charset="utf-8">
<?php

require("conexionmysqli.inc");
require("estilos.inc");
require("funcion_nombres.php");

$rpt_ciudad=$_GET["c"];
$user=$_GET["p"];
$sqlInsert="DELETE FROM funcionarios_agencias where codigo_funcionario='$user' and  cod_ciudad='$rpt_ciudad'";
$respInsert=mysqli_query($enlaceCon,$sqlInsert);	
echo "<script language='Javascript'>
		swal({
    title: 'Correcto!',
    text: 'Se elimino el registro',
    type: 'success'
}).then(function() {
    window.location = 'asignarSucursalPersonal.php?p=$user&c=$rpt_ciudad';
});
			</script>";	
?>
<meta charset="utf-8">
<?php

require("conexionmysqli.inc");
require("estilos.inc");
require("funcion_nombres.php");

$rpt_ciudad=$_POST["rpt_ciudad"];
$user=$_POST["rpt_personal"];
$fechaActual=date("Y-m-d H:i:s");

$sql="select codigo_funcionario from funcionarios_agencias where codigo_funcionario='$user' and cod_ciudad='$rpt_ciudad'";
$resp = mysqli_query($enlaceCon,$sql);
$numFilas=mysqli_result($resp,0,0);
if($numFilas>0){
	echo "<script language='Javascript'>
	swal({
    title: 'Informativo!',
    text: 'La persona ya tiene asignada la sucursal.',
    type: 'warning'
}).then(function() {
    window.location = 'asignarSucursalPersonal.php?p=$user&c=$rpt_ciudad';
});
			</script>";	
}else{
	$sqlInsert="insert into funcionarios_agencias (codigo_funcionario, cod_ciudad) values 
		($user, $rpt_ciudad)";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);	
	echo "<script language='Javascript'>
		swal({
    title: 'Correcto!',
    text: 'Se asigno a la sucursal',
    type: 'success'
}).then(function() {
    window.location = 'asignarSucursalPersonal.php?p=$user&c=$rpt_ciudad';
});
			</script>";	
}
?>
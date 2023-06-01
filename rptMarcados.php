<?php
require("conexionmysqli2.inc");
require("estilos.inc");

$fechaInicio=$_POST['exafinicial'];
$fechaFinal=$_POST['exaffinal'];


echo "<h1>Reporte Marcados de Personal</h1>";

$sqlPersonal="select distinct(f.codigo_funcionario), concat(f.paterno,' ',f.materno,' ',f.nombres) from marcados_personal m, funcionarios f
where f.codigo_funcionario=m.cod_funcionario and 
m.fecha_marcado BETWEEN '$fechaInicio' and '$fechaFinal'";
$respPersonal=mysqli_query($enlaceCon, $sqlPersonal);

echo "<center><table class='texto'>";

while($datPersonal=mysqli_fetch_array($respPersonal)){
	$codPersonal=$datPersonal[0];
	$nombrePersonal=$datPersonal[1];
	echo "<tr><th colspan='8'>$nombrePersonal<th></tr>";
	echo "<tr><td>Fecha</td><td>Marcado1</td><td>Marcado2</td><td>Marcado3</td><td>Marcado4</td><td>Marcado5</td><td>Marcado6</td><td>Horas trabajadas</td></tr>";

	$fechaPivot=$fechaInicio;
	$hora1="";
	$hora2="";
	$hora3="";
	$hora4="";
	$hora5="";
	$hora6="";
	
	while($fechaPivot<=$fechaFinal){
		$sqlMarcados="select fecha_marcado from marcados_personal where fecha_marcado between '$fechaPivot 00:00:00' and '$fechaPivot 23:59:59' 
		and cod_funcionario='$codPersonal'";
		$respMarcados=mysqli_query($enlaceCon, $sqlMarcados);
		echo "<tr><td>$fechaPivot</td>";
		$contador=1;
		
		while($datMarcados=mysqli_fetch_array($respMarcados)){
			$horaMarcado=$datMarcados[0];
			list($fechaX, $horaX)=explode(' ',$horaMarcado);
			$horaX=substr($horaX,0,-3);
			echo "<td>$horaX</td>";
			if($contador==1){
				$hora1=$horaX;
			}
			if($contador==2){
				$hora2=$horaX;
			}
			if($contador==3){
				$hora3=$horaX;
			}
			if($contador==4){
				$hora4=$horaX;
			}
			if($contador==5){
				$hora5=$horaX;
			}
			if($contador==6){
				$hora6=$horaX;
			}
			$contador++;
		}
		for($ii=$contador; $ii<=6; $ii++){
			echo "<td>-</td>";
		}
		$totalSegundos=0;
		if($hora1!="" && $hora2!=""){
			$horaDT1=new DateTime($hora1);
			$horaDT2=new DateTime($hora2);			
			$totalSegundos = abs($horaDT1->getTimestamp() - $horaDT2->getTimestamp());
		}
		if($hora3!="" && $hora4!=""){
			$horaDT1=new DateTime($hora3);
			$horaDT2=new DateTime($hora4);			
			$totalSegundos = $totalSegundos+abs($horaDT1->getTimestamp() - $horaDT2->getTimestamp());
		}
		if($hora5!="" && $hora6!=""){
			$horaDT1=new DateTime($hora5);
			$horaDT2=new DateTime($hora6);			
			$totalSegundos = $totalSegundos+abs($horaDT1->getTimestamp() - $horaDT2->getTimestamp());
		}
		if($totalSegundos==0){
			echo "<td>-</td></tr>";
		}else{
			echo "<td>".gmdate("H:i", $totalSegundos)."</td></tr>";
		}

		$fechaPivot=date('Y-m-d',strtotime($fechaPivot.'+1 day'));
	
		unset($hora1);
		unset($hora2);
		unset($hora3);
		unset($hora4);
		unset($hora5);
		unset($hora6);		
	}
}
echo "</table></center>";
echo "</form>";
?>
<?php
require("conexionmysqli2.inc");

$order_by=$_GET["order_by"];
$cod_medico=$_GET["cod_medico"];


$sql="(
SELECT m.codigo,TRIM(CONCAT(IFNULL(m.nombres,''),' ',IFNULL(m.apellidos,''))) nombres_medico,m.matricula,(SELECT nombre from especialidades where codigo=m.cod_especialidad)especialidad from medicos m where m.codigo='$cod_medico'
) UNION (";
$sql.="SELECT m.codigo,TRIM(CONCAT(IFNULL(m.nombres,''),' ',IFNULL(m.apellidos,''))) nombres_medico,m.matricula,(SELECT nombre from especialidades where codigo=m.cod_especialidad)especialidad from medicos m where m.estado=1 ";

// if(isset($_GET["nom_medico"])){
//    $sql.=" and m.nombres like '%".$_GET["nom_medico"]."%' ";
// }
if(isset($_GET["app_medico"])){
   $sql.=" and CONCAT(IFNULL(m.nombres,''),' ',IFNULL(m.apellidos,'')) like '%".$_GET["app_medico"]."%' ";
}


if(isset($_GET["espe"])&&$_GET["espe"]>0){
   $sql.=" and cod_especialidad='".$_GET["espe"]."' ";
}

/*if($cod_medico>0){
   $sql.=" or codigo='$cod_medico'";
}*/
if($order_by=="codigo"){
   $sql.=") order by $order_by desc limit 100 ";   
}else{
   $sql.=") order by $order_by limit 100 ";
}

//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
$filaSeleccionado="";
$filas="";

while($dat=mysqli_fetch_array($resp)){
	$codigo = $dat["codigo"];
   	$nombres = $dat["nombres_medico"];
    $matricula = $dat["matricula"];
    $especialidad = $dat["especialidad"];
    if($codigo==$cod_medico){
    	$filaSeleccionado.='<tr class="bg-warning"><td align="left" id="medico_lista0" style="text-align:left;padding-left:20px"><b>'.$nombres.'</b></td><td>'.$especialidad.'</td><td><a href="#" onclick="asignarMedicoVenta(\'0\')" title="Quitar" class="btn btn-success btn-fab btn-sm"><i class="material-icons">verified</i></a></td></tr>';
    }else{
    	$filas.='<tr><td align="left" id="medico_lista'.$codigo.'" style="text-align:left;padding-left:20px">'.$nombres.'</td><td>'.$especialidad.'</td><td><a href="#" onclick="asignarMedicoVenta(\''.$codigo.'\')" title="Seleccionar" class="btn btn-danger btn-fab btn-sm"><i class="material-icons">check_circle</i></a></td></tr>';
    }
}
// Limpiar el búfer de salida
ob_clean();

echo $filaSeleccionado.$filas;
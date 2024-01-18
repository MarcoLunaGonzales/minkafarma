<?php
require("conexionmysqli2.inc");
$nom_doctor=$_GET["nom_doctor"];
$ape_doctor=$_GET["ape_doctor"];
$dir_doctor=$_GET["dir_doctor"];
$mat_doctor=$_GET["mat_doctor"];
$n_ins_doctor=$_GET["n_ins_doctor"];
$ins_doctor=$_GET["ins_doctor"];
$esp_doctor=$_GET["esp_doctor"];
$esp_doctor2=$_GET["esp_doctor2"];

/*if((int)$ins_doctor==-2){
   $sql="SELECT IFNULL(max(codigo)+1,1) from instituciones";
   $resp=mysqli_query($enlaceCon,$sql);			
   $ins_doctor=mysqli_result($resp,0,0);
   $sql="INSERT INTO instituciones (codigo,nombre,abreviatura,estado) VALUES('$ins_doctor','$n_ins_doctor','$n_ins_doctor',1)";
   mysqli_query($enlaceCon,$sql);
}*/
$sql="INSERT INTO medicos(nombres,apellidos,direccion,matricula,cod_institucion,cod_especialidad,cod_especialidad2,estado,institucion) VALUES('$nom_doctor','$ape_doctor','$dir_doctor','$mat_doctor','$ins_doctor','$esp_doctor','$esp_doctor2',1,'$n_ins_doctor')";
$resp=mysqli_query($enlaceCon,$sql);

echo $resp;
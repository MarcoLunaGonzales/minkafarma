<?php
require("conexionmysqli.php");
require("estilos.inc");
$sql_inserta=mysqli_query($enlaceCon,"insert into usuarios_sistema values($codigo_funcionario,'$contrasena')");
//esta parte saca el nombre del funcionario
$sql_nombre_fun="select paterno, materno, nombres from funcionarios where codigo_funcionario='$codigo_funcionario'";
$resp_nombre_fun=mysqli_query($enlaceCon,$sql_nombre_fun);
$dat_nombre_fun=mysqli_fetch_array($resp_nombre_fun);
$nombre_funcionario="$dat_nombre_fun[0] $dat_nombre_fun[1] $dat_nombre_fun[2]";
//esta parte envia el mail al usuario
$sql_mail="select email from funcionarios where codigo_funcionario=$codigo_funcionario";
$resp_mail=mysqli_query($enlaceCon,$sql_mail);
$dat_mail=mysqli_fetch_array($resp_mail);
$mail_funcionario=$dat_mail[0];

echo "<script language='Javascript'>
			alert('La Alta se realizo correctamente.');
			location.href='navegador_funcionarios.php?cod_ciudad=$cod_territorio';
			</script>";
?>

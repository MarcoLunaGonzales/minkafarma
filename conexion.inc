<?php

//header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/La_Paz');

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

$conexion=mysql_connect("localhost","root","4868422Marco");
$bd=mysql_select_db("carmelita");
<<<<<<< HEAD
//$conexion=mysql_connect("www.minkasoftware.com","minkauserbd","4868422Marco$");
//$bd=mysql_select_db("minkafarmacia");

=======
>>>>>>> 185a9a426d541d2dc50660e67cbe9ccb2bfee8e4

$sqlX="SET NAMES 'utf8'";
mysql_query($sqlX);
?>

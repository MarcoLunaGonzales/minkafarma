<?php

 // error_reporting(E_ALL);
 // ini_set('display_errors', '1');

setcookie("global_usuario", "100");
    
$nombreUser=$_COOKIE['global_usuario'];

//echo "usuario: ".$nombreUser;

header("location:www.minkasoftware.com");

?>
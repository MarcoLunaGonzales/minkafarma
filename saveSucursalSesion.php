<?php
$estilosVenta=1; //para no ejecutar las librerias js css
require("funciones.php");
require("conexionmysqli2.inc");

$cod_ciudad=$_POST['cod_ciudad'];

   $sql="SELECT cod_almacen FROM almacenes where cod_ciudad='$cod_ciudad'";
   $resp=mysqli_query($enlaceCon,$sql);
   //echo $sql;
   $codigo_funcionario=$_COOKIE["global_usuario"];
   //$sqlFun="UPDATE funcionarios SET cod_ciudad='$cod_ciudad' where codigo_funcionario='$codigo_funcionario'";
   //mysqli_query($enlaceCon,$sqlFun);
   while($dat=mysqli_fetch_array($resp)){
      $codigo=$dat[0];
   }
   setcookie("global_agencia",$cod_ciudad);
   setcookie("global_almacen",$codigo);
   if(isset($_POST["url"])){
   	$url=$_POST["url"];
    ?>
   <script type="text/javascript">window.location.href='<?=$url?>';</script>
   <?php
   }else{
   ?>
   <script type="text/javascript">parent.window.location.href=window.parent.location;</script>
   <?php	
   }



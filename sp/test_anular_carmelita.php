<?php 
    require_once("../conexionmysqli.inc");
    require_once("../estilos_almacenes.inc");
    require_once("../siat_folder/funciones_siat.php");
    require_once("../enviar_correo/php/send-email_anulacion.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');  

$codSucursal=0;
$codigoSucursal=0;
$codigoPuntoVenta=2;
$cod_entidad=1;

$sql="select s.cod_salida_almacenes, s.siat_cuis, sc.cufd, s.siat_cuf from salida_almacenes s, siat_cufd sc 
where s.siat_codigocufd=sc.codigo and s.salida_anulada=0 and s.cod_salida_almacenes in 
(41802,41825,41839,41842,41887,41907,41954,41974,41987,42022,42071,42129,42346,42377,42587,42588,42589,42699,42701,42736,42788,42887)";

/**/

echo $sql;

$resp=mysqli_query($enlaceCon, $sql);
while($dat=mysqli_fetch_array($resp)){
  echo "entra";
  
  $codVenta=$dat[0];
  $cuis=$dat[1];
  $cufd=$dat[2];
  $cuf=$dat[3];
  
  echo $codVenta." ".$cuis." ".$cufd." ".$cuf."<br>";
  $respEvento=anulacionFactura_siat($codigoPuntoVenta,$codigoSucursal,$cuis,$cufd,$cuf);
  
  echo implode(" ",$respEvento);
  
  $mensaje=$respEvento[1];
  if($respEvento[0]==1){
    echo "anulado ".$codVenta." ".$mensaje;
  }

}

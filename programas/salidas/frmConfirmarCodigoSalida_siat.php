<?php
$estilosVenta=1;
require("../../conexionmysqli.inc");
require("../../funciones.php");
require("../../funcion_nombres.php");

$codigo=$_GET["codigo"];
// $cod_sucursal=$_GET["cod_sucursal"];
//
//echo "ddd:$codigo<br>";
$sqlFecha="select DAY(s.fecha), MONTH(s.fecha), YEAR(s.fecha), HOUR(s.hora_salida), MINUTE(s.hora_salida),s.cod_chofer,(select c.email_cliente from clientes c where c.cod_cliente=s.cod_cliente)as correo_destino
from salida_almacenes s where s.cod_salida_almacenes='$codigo'";
// echo $sqlFecha;
$respFecha=mysqli_query($enlaceCon,$sqlFecha);
$datConf=mysqli_fetch_array($respFecha);
$dia=$datConf[0];
$mes=$datConf[1];
$ano=$datConf[2];
$hh=$datConf[3];
$mm=$datConf[4];

$chofer=$datConf[5];
$correo_destino=$datConf[6];
// $dia=mysqli_result($respFecha,0,0);
// $mes=mysqli_result($respFecha,0,1);
// $ano=mysqli_result($respFecha,0,2);
// $hh=mysqli_result($respFecha,0,3);
// $mm=mysqli_result($respFecha,0,4);
// $chofer=mysqli_result($respFecha,0,5);
// $correo_destino=mysqli_result($respFecha,0,6);

$nombreCajero=nombreFuncionarioReal($enlaceCon,$chofer);
//generamos el codigo de confirmacion
$codigoGenerado=$codigo+$dia+$mes+$ano+$hh+$mm;
//

//SACAMOS LA VARIABLE PARA ENVIAR EL CORREO O NO SI ES 2 ENVIAMOS CORREO PARA APROBACION
// $banderaCorreo=obtenerValorConfiguracion(8);

// if($banderaCorreo==2){
// 	$codigoSalida=$codigo;
// 	$codigoGeneradoX=$codigoGenerado;
// 	include("../../sendEmailAprobAnulacionSalida.php");
// }
$fechaAnulacion=date("Y-m-d");

?>
<div>
<center>
  <table class="table table-sm table-condensed" cellspacing="0" >
    <tr><th colspan="3">Introduzca el codigo de confirmación</th></tr>
    <tr><td class="bg-danger text-white">Cajero (a):</td><td colspan="2"><input type="text" id="cajero" value="<?=$nombreCajero?>" readonly class="form-control"></td><input type="hidden" name="rpt_personal" id="rpt_personal" value="<?=$chofer?>"></tr>
    <tr><td class="bg-danger text-white">Codigo:</td><td colspan="2"><input type="text" id="idtxtcodigo" value="<?php echo "$codigoGenerado";?>" readonly class="form-control"></td></tr>
    <tr><td class="bg-danger text-white">Clave:</td><td colspan="2"><input type="password" id="idtxtclave" value="" class="form-control" style="background: #A5F9EA;" autocomplete="off"></td></tr>
    <tr><td class="bg-danger text-white">Fecha:</td><td colspan="2"><input type="date" id="idtxtfecha" value="<?=$fechaAnulacion?>" class="form-control" readonly></td></tr>
    
   
  <tr><td class="bg-info text-white">Enviar Correo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="enviar_correo" name="enviar_correo" type='checkbox' style="background: #white;"></td>
    <td colspan="2"><input type="text" name="correo_destino" id="correo_destino" value="<?=$correo_destino?>" class="form-control" style="background: #white;"></td>
  </tr>
  </table>  
  </center>  
</div>
<script type="text/javascript">$(".selectpicker").selectpicker();</script>
<?php

?>

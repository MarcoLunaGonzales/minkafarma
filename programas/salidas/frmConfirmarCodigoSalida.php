<?php
require("../../conexion.inc");
require("../../funciones.php");

$codigo=$_GET["codigo"];
//
//echo "ddd:$codigo<br>";
$sqlFecha="select DAY(s.fecha), MONTH(s.fecha), YEAR(s.fecha), HOUR(s.hora_salida), MINUTE(s.hora_salida) 
from salida_almacenes s where s.cod_salida_almacenes='$codigo'";
$respFecha=mysql_query($sqlFecha);
$dia=mysql_result($respFecha,0,0);
$mes=mysql_result($respFecha,0,1);
$ano=mysql_result($respFecha,0,2);
$hh=mysql_result($respFecha,0,3);
$mm=mysql_result($respFecha,0,4);

//generamos el codigo de confirmacion
$codigoGenerado=$codigo+$dia+$mes+$ano+$hh+$mm;
//

//SACAMOS LA VARIABLE PARA ENVIAR EL CORREO O NO SI ES 2 ENVIAMOS CORREO PARA APROBACION
$banderaCorreo=obtenerValorConfiguracion(8);

if($banderaCorreo==2){
	$codigoSalida=$codigo;
	$codigoGeneradoX=$codigoGenerado;
	include("../../sendEmailAprobAnulacionSalida.php");
}

?>
<center>
    <div id='pnlfrmcodigoconfirmacion'>
        <br>
        <table class="texto" border="1" cellspacing="0" >
            <tr><td colspan="2">Introduzca el codigo de confirmacion</td></tr>
            <tr><td>Codigo:</td><td><input type="text" id="idtxtcodigo" value="<?php echo "$codigoGenerado";?>" readonly ></td></tr>
            <tr><td>Clave:</td><td><input type="text" id="idtxtclave" value="" ></td></tr>
        </table>
        <br>
    </div>
</center>
<?php

?>


<?php

$codigo=$_GET["codigo"];
//
//echo "ddd:$codigo<br>";
//
$dia=date("d");
$mes=date("m");
$ano=date("Y");
$hh=date("H");
$mm=date("i");

//generamos el codigo de confirmacion
$codigoGenerado=$codigo+$dia+$mes+$ano+$hh+$mm;
//$codigoGenerado=1234;
//
//echo "1:$dia<br>";
//echo "2:$mes<br>";
//echo "3:$ano<br>";
//echo "4:$hh<br>";
//echo "5:$mm<br>";
//

?>
<center>
    <div id='pnlfrmcodigoconfirmacion'>
        <br>
        <table class="texto" border="1" cellspacing="0" >
            <tr><td colspan="2">Introdusca codigo de confirmacion</td></tr>
            <tr><td>Codigo:</td><td><input type="text" id="idtxtcodigo" value="<?php echo "$codigoGenerado";?>" readonly ></td></tr>
            <tr><td>Clave:</td><td><input type="text" id="idtxtclave" value="" ></td></tr>
        </table>
        <br>
    </div>
</center>
<?php

?>

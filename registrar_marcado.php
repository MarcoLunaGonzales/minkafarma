<script language='JavaScript'>
  function cargaInicio(f){
  	f.clave_marcado.focus();
  }
</script>
<body onload="cargaInicio(form1);">
<?php
require("conexionmysqli2.inc");
require("estilos.inc");

echo "<form name='form1' action='guarda_marcado.php' method='post'>";
echo "<h1>Registrar Marcado de Personal</h1>";

// Se obtiene IP y Navegador
echo "<input type='hidden' id='ipAddress' name='ipAddress'>
    <input type='hidden' id='userAgent' name='userAgent'>";

echo "<center><table class='texto' width='50%'>";
echo "<tr><th>Introducir la clave del sistema para realizar el marcado</th></tr>";
echo "<tr><td align='center'><input type='password' value='' name='clave_marcado' id='clave_marcado' size='50' required></td>";
echo "</table></center>";

echo "<div class='divBotones'><input type='submit' class='boton' value='Guardar Marcado' onClick='validar(this.form)'>
</div>";

$sql="select m.fecha_marcado, 
(select concat(f.paterno,' ', f.nombres) from funcionarios f where f.codigo_funcionario=m.cod_funcionario) 
from marcados_personal m order by m.fecha_marcado desc limit 0,10";
$resp=mysqli_query($enlaceCon, $sql);
echo "<center><table class='texto'>";
echo "<tr><th colspan='2'>Ultimos Marcados del personal</th></tr>";
echo "<tr><th>Fecha/Hora Marcado</th><th>Personal</th></tr>";
while($dat=mysqli_fetch_array($resp)){
	$fechaHora=$dat[0];
	$nombresPersonal=$dat[1];
	
	echo "<tr><td>$fechaHora</td><td>$nombresPersonal</td></tr>";
}
echo "</table></center>";

echo "</form>";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Obtener la dirección IP del dispositivo
    function getIPAddress(callback) {
      $.getJSON('https://api64.ipify.org?format=json', function(data) {
        var ipAddress = data.ip;
        console.log('IP Adress:'+ipAddress);
        callback(ipAddress);
      });
    }
    // Obtener el Navegador
    var userAgent = navigator.userAgent;
    getIPAddress(function(ip) {
      document.getElementById('ipAddress').value = ip;
    });
    document.getElementById('userAgent').value = userAgent;
    console.log('user agent:' + userAgent);
</script>
</body>
<html>
        <script type='text/javascript' language='javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxCargarDeudas(){
	var contenedor;
	contenedor = document.getElementById('divDetalle');

	var codCliente = document.getElementById('cliente').value;

	ajax=nuevoAjax();

	ajax.open("GET", "ajaxCargarDeudas.php?codCliente="+codCliente,true);

	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}else{
			contenedor.innerHTML = "Cargando...";
		}
	}
	ajax.send(null)
}


function validar(f)
{   
	var codCliente=document.getElementById("cliente").value;
	var numRegistros=document.getElementById("nroFilas").value;
	var monto;
	var nroDoc;
	if(codCliente==0){
		alert("Debe seleccionar un Cliente");
	}else{
		if(numRegistros>0){
			for(var i=1; i<=numRegistros; i++){
				monto=parseFloat(document.getElementById("montoPago"+i).value);
				nroDoc=parseFloat(document.getElementById("nroDoc"+i).value);
				//if(monto==0 || nroDoc==0 || monto=="NaN" || nroDoc=="NaN"){
					//alert("Monto de Pago, Nro. Doc. no pueden estar vacios. Fila: "+i);
					//return(false);
				//}
				f.submit();
			}
		}
		
	}	
}

function solonumeros(e)
{
	var key;
	if(window.event) {// IE
		key = e.keyCode;
	}else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	}
	if (key < 46 || key > 57) 
	{
	  return false;
	}
	return true;
}



	</script>
<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funcion_nombres.php");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$globalCiudad=$_COOKIE["global_agencia"];
$nombreTerritorio=nombreTerritorio($enlaceCon, $globalCiudad);


?>

<body>
<form action='guardar_gasto.php' method='post' name='form1'>
<h3 align="center">Registrar Gasto<br><?=$nombreTerritorio;?></h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Tipo</th><th>Fecha</th><th>Descripcion</th><th>Monto</th></tr>
<?php
$sql1="select cod_tipogasto, nombre_tipogasto from tipos_gasto where estado=1 order by 2";
$resp1=mysqli_query($enlaceCon,$sql1);
?>
<tr>
<td align='center'>
<select name='tipo_gasto' id='tipo_gasto' class='selectpicker' data-style='btn btn-success' required>
<?php
while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
?>
	<option value="<?php echo $codigo; ?>"><?php echo $nombre; ?></option>
<?php	
}
$fecha=date("Y-m-d");
?>
</select>
</td>
<td>
<input type='date' class='texto' max='<?php echo $fecha; ?>' value='<?php echo $fecha; ?>' id='fecha' name='fecha' required>
</td>
<td>
<input type='text' class='texto' value="" id='nombre_gasto' size='60' name='nombre_gasto' required>
</td>

<td>
<input type='number' class='texto' value="" id='monto_gasto' name='monto_gasto' step='0.1' required>
</td>

</tr>
</table>

<?php
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_gastos.php\"'>
</div>";
?>


</form>
</body>
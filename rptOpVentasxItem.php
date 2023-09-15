<?php

require_once "conexionmysqli.php";
require_once "estilos_almacenes.inc";

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

?>	
<script language='JavaScript'>
function actualizarDatosPersonal(){
    var parametros={"codigo":0};
     $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxCalcularDatosPersonal.php",
        data: parametros,   
        success:  function (resp) { 
          //alert(resp);
          document.getElementById("rpt_personal").innerHTML=resp;
          $("#rpt_personal").selectpicker('refresh');      
        }
    });
 }
</script>


<?php


$fecha_rptdefault=date("Y-m-d");
echo "<h1>Ranking de Ventas x Item</h1>";

echo"<form method='post' action='rptVentasxItem.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td>
	<select name='rpt_territorio' class='selectpicker' data-style='btn btn-success' required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Personal</th><td>
		<select name='rpt_personal[]' id='rpt_personal' multiple class='selectpicker' data-live-search='true' data-size='6' data-style='btn btn-success' data-actions-box='true'>";
		$sql="SELECT codigo_funcionario,CONCAT(paterno,' ',materno,' ',nombres)personal FROM funcionarios where estado=1 order by paterno,materno,nombres ";

	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_funcionario=$dat[0];
		$nombre_funcionario=$dat[1];
		if($globalFuncionario==$codigo_funcionario){
		   echo "<option value='$codigo_funcionario' selected>$nombre_funcionario</option>";	
		}else{
		  echo "<option value='$codigo_funcionario' selected>$nombre_funcionario</option>";				
		}
	}
	echo "</select><a href='#' class='btn btn-deffault btn-fab btn-sm'><i class='material-icons' onclick='actualizarDatosPersonal();return false;' title='Listar Todo el Personal (Incluidos Retirados)'>refresh</i></a></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini' required>";
    		echo" </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin' required>";
    		echo" </TD>";
	echo "</tr>";

	echo "<tr><th align='left'>Ordenar por:</th><td><select name='rpt_ordenar' class='selectpicker' data-style='btn btn-warning' required>";
	echo "<option value='0'>Nombre de Producto</option>";
	echo "<option value='1'>Monto</option>";
	echo "<option value='2'>Cantidad</option>";
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Ver:</th><td><select name='rpt_ver' class='selectpicker' data-style='btn btn-info' required>";
	echo "<option value='1'>Reporte Normal</option>";
	echo "<option value='2'>Reporte con Existencias</option>";
	echo "</select></td></tr>";


	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>
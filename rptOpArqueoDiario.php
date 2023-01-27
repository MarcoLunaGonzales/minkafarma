<script language='JavaScript'>
function envia_formulario(f, variableAdmin)
{	var fecha_ini;
	var fecha_fin;
	var hora_ini;
	var hora_fin;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	//var rpt_funcionario=$("#rpt_funcionario").val();
	var rpt_funcionario=f.rpt_funcionario.value;

	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	hora_ini=f.exahorainicial.value;
	hora_fin=f.exahorafinal.value;
	window.open('rptArqueoDiarioPDF.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&hora_ini='+hora_ini+'&hora_fin='+hora_fin+'&variableAdmin='+variableAdmin+'&rpt_funcionario='+rpt_funcionario,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_formulario2(f, variableAdmin)
{	var fecha_ini;
	var fecha_fin;
	var hora_ini;
	var hora_fin;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_funcionario;
	rpt_funcionario=f.rpt_funcionario.value;

	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	hora_ini=f.exahorainicial.value;
	hora_fin=f.exahorafinal.value;
	window.open('rptArqueoDiarioPDFSm.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&hora_ini='+hora_ini+'&hora_fin='+hora_fin+'&variableAdmin='+variableAdmin+'&rpt_funcionario='+rpt_funcionario,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_formulario3(f, variableAdmin)
{	var fecha_ini;
	var fecha_fin;
	var hora_ini;
	var hora_fin;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_funcionario;
	rpt_funcionario=f.rpt_funcionario.value;

	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	hora_ini=f.exahorainicial.value;
	hora_fin=f.exahorafinal.value;
	window.open('rptArqueoDiarioPDFSmCompleto.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&hora_ini='+hora_ini+'&hora_fin='+hora_fin+'&variableAdmin='+variableAdmin+'&rpt_funcionario='+rpt_funcionario,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
function envia_formulario4(f, variableAdmin)
{	var fecha_ini;
	var fecha_fin;
	var hora_ini;
	var hora_fin;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_funcionario;
	rpt_funcionario=f.rpt_funcionario.value;

	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	hora_ini=f.exahorainicial.value;
	hora_fin=f.exahorafinal.value;
	window.open('rptFacturasOffline.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&hora_ini='+hora_ini+'&hora_fin='+hora_fin+'&variableAdmin='+variableAdmin+'&rpt_funcionario='+rpt_funcionario,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}

function actualizarDatosPersonal(){
    var parametros={"codigo":0};
     $.ajax({
        type: "GET",
        dataType: 'html',
        url: "depositos/ajaxCalcularDatosPersonal.php",
        data: parametros,   
        success:  function (resp) { 
          //alert(resp);
          document.getElementById("rpt_funcionario").innerHTML=resp;
          $("#rpt_funcionario").selectpicker('refresh');      
        }
    });
 }
</script>
<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}

$fecha_rptinidefault=date("Y")."-".date("m")."-01";
//$hora_rptinidefault=date("H:i");
$hora_rptinidefault="06:00";
$hora_rptfindefault="23:59";
$fecha_rptdefault=date("Y-m-d");
$globalCiudad=$_COOKIE['global_agencia'];
$globalUser=$_COOKIE['global_usuario'];
echo "<h1>Reporte Arqueo Diario de Caja</h1><br>";
echo"<form method='post' action='rptArqueoDiarioPDF.php'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='selectpicker form-control'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($codigo_ciudad==$globalCiudad){
			echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";			
		}else{
			echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Personal</th><td><select name='rpt_funcionario' id='rpt_funcionario' class='selectpicker form-control col-sm-11' data-live-search='true' data-size='6'>";
	$sql="SELECT codigo_funcionario,CONCAT(paterno,' ',materno,' ',nombres)personal FROM funcionarios where estado=1 order by paterno,materno,nombres"; 
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$cod_funcionario=$dat[0];
		$nombre_fun=$dat[1];
		if($cod_funcionario==$globalUser){
			echo "<option value='$cod_funcionario' selected>$nombre_fun</option>";			
		}else{
			echo "<option value='$cod_funcionario'>$nombre_fun</option>";
		}
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha Inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'><INPUT  type='time' class='texto' value='$hora_rptinidefault' id='exahorainicial' size='10' name='exahorainicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha Fin:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'><INPUT  type='time' class='texto' value='$hora_rptfindefault' id='exahorafinal' size='10' name='exahorafinal'>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo"\n </table><br>";
	echo "<center>
		<input type='button' class='boton' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form,$variableAdmin)' class='btn btn-info'>
		
		<!--button onClick='envia_formulario4(this.form,$variableAdmin);return false;' class='btn btn-primary'><i class='material-icons'>print</i> Reporte de Facturas OFF LINE</button--><br>
		
	</center><br>";
	echo"</form>";
	echo "</div>";
?>

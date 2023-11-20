<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

?>
<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver;
	rpt_territorio=f.rpt_territorio.value;
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	rptOrden=f.rpt_orden.value;

	var codPersonal=new Array();
	var j=0;
	for(var i=0;i<=f.rpt_personal.options.length-1;i++)
	{	if(f.rpt_personal.options[i].selected)
		{	codPersonal[j]=f.rpt_personal.options[i].value;
			j++;
		}
	}

	window.open('rptVentasGeneral.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&codPersonal='+codPersonal+''+'&rptOrden='+rptOrden+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
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

$globalCiudad=$_COOKIE["global_agencia"];

if(isset($_POST["rpt_territorio"])){
	$rpt_territorio=$_POST["rpt_territorio"];
}else{
	$rpt_territorio=$_COOKIE["global_agencia"];
}

$globalFuncionario=$_COOKIE["global_usuario"];
$fecha_rptdefault=date("Y-m-d");

echo "<h1>Reporte Ventas x Documento e Item</h1>";
echo"<form method='post' action=''>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Sucursal</th><td><select name='rpt_territorio' class='selectpicker' data-live-search='true' data-size='6'>";
		$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($globalCiudad==$codigo_ciudad){
		   echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";	
		}else{
		   //if(isset($_GET["admin"])){
			  echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";	
		   //}			
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Personal</th><td><select name='rpt_personal' id='rpt_personal' multiple class='selectpicker' data-live-search='true' data-size='6' data-actions-box='true'>";
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
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exafinicial' size='10' name='exafinicial'>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='date' class='texto' value='$fecha_rptdefault' id='exaffinal' size='10' name='exaffinal'>";
    		echo"  </TD>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Orden de Reporte</th><td><select name='rpt_orden' class='selectpicker' data-live-search='true' data-size='6'>";
	echo "<option value='1'>Ascendente</option>";	
	echo "<option value='2'>Descendente</option>";	
	echo "</select></td></tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>
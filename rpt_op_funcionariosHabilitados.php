<script language='JavaScript'>
function envia_formulario(f)
{	var j,i;
	var rpt_territorio=new Array();
	j=0;
	for(i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rpt_funcionariosHabilitados.php?rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	return(true);
}
</script>
<?php
require("conexion.inc");
require("estilos_administracion.inc");
echo "<center><table class='textotit'><tr><th>Funcionarios Habilitados</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto'  size='12' multiple>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
?>
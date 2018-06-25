<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.baja.value=='')
		{	alert('El campo Baja esta vacio.');
			f.baja.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
$sql=mysql_query("select codigo_motivo, tipo_motivo, descripcion_motivo from motivos_baja where codigo_motivo=$codigo");
$dat=mysql_fetch_array($sql);
$codMotivo=$dat[0];
$tipoMotivo=$dat[1];
$motivo=$dat[2];

echo "<form action='guarda_editarTipoBaja.php' method='post'>";

echo "<center><table border='0' class='textotit'><tr><th>Editar Tipo de Baja</th></tr></table></center><br>";


echo "<input type='hidden' name='codigo' value='$codMotivo'>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Descripcion</th><th>Tipo</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='baja' onKeyUp='javascript:this.value=this.value.toUpperCase();' value='$motivo'></td>";

if($tipoMotivo==1){
	echo "<td><select name='tipoBaja' class='texto'>
			<option value='1' selected>Baja de Dias</option>
			<option value='2'>Baja de Medicos</option>
	</select></td>
	</tr>";
}
if($tipoMotivo==2){
	echo "<td><select name='tipoBaja' class='texto'>
			<option value='1'>Baja de Dias</option>
			<option value='2' selected>Baja de Medicos</option>
	</select></td>
	</tr>";
}
echo "</table><br>";

echo"\n<table align='center'><tr><td><a href='navegador_cargos.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>
<script language='Javascript'>
	function validar(f)
	{
		if(f.material.value=='')
		{	alert('El campo Nombre esta vacio.');
			f.material.focus();
			return(false);
		}
		if (isNaN(f.peso.value) || f.peso.value == "") {
			alert("El peso no es un numero valido.");
			f.peso.focus();
			return(false);
		}
		if(f.codOrdenGrupo.value==""){
			alert('El campo Codigo Interno esta vacio.');
			f.codOrdenGrupo.focus();
			return(false);
		}
		
		f.submit();
	}

</script>


<?php
require("conexion.inc");
require('estilos.inc');
echo "<form action='guarda_material_apoyo.php' method='post' name='form1'>";

echo "<table border='0' align='center' class='textotit'><tr><th>Adicionar Material</th></tr></table><br>";
echo "<table border='1' align='center' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Nombre Material</th>";
echo "<td align='left'><input type='text' class='texto' name='material' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
echo "<tr><th align='left'>Tipo de Material</th>";

$sql1="select * from tipos_material order by nombre_tipomaterial";
$resp1=mysql_query($sql1);
echo "<td align='left'><select name='tipo_material' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipomaterial=$dat1[0];
	$nombre_tipomaterial=$dat1[1];
	echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Peso(Kg)</th><td><input type='text' class='texto' name='peso'></td></tr>";
echo "<tr><th align='left'>Abreviatura</th><td><input type='text' class='texto' name='abreviatura'></td></tr>";
echo "<tr><th align='left'>Codigo x Grupo</th><td><input type='text' class='texto' name='codOrdenGrupo'></td></tr>";

echo "<tr><th align='left'>Manejo por Metros</th>";
echo "<td align='left'><select name='item_metraje' class='texto'>";
echo "<option value='0'>No</option>";
echo "<option value='1'>Si</option>";
echo "</select></td>";
echo "</tr>";

echo "<tr><th align='left'>Nro. Metros</th><td><input type='text' class='texto' name='nro_metros'></td></tr>";

echo "</table><br>";

echo"\n<table align='center'><tr><td><a href='navegador_material.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>



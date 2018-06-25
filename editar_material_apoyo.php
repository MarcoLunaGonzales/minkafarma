<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.material.value=='')
		{	alert('El campo Material de Apoyo esta vacio.');
			f.material.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require('estilos_administracion.inc');
$sql=mysql_query("select descripcion_material, estado, cod_tipo_material, peso, orden_grupo, abreviatura, item_metraje,  nro_metros 
from material_apoyo where codigo_material=$cod_material");
$dat=mysql_fetch_array($sql);
$material=$dat[0];
$estado=$dat[1];
$cod_tipo_material=$dat[2];
$peso=$dat[3];
$ordenGrupo=$dat[4];
$abreviatura=$dat[5];
$itemMetraje=$dat[6];
$nroMetros=$dat[7];

echo "<form action='guarda_modi_material_apoyo.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Material</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Material</th>";
echo "<input type='hidden' name='codigo' value='$cod_material'>";
echo "<td align='left'><input type='text' class='texto' name='material' value='$material' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Tipo de Material</th>";
$sql1="select * from tipos_material order by nombre_tipomaterial";
$resp1=mysql_query($sql1);
echo "<td align='left'><select name='tipo_material' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipomaterial=$dat1[0];
	$nombre_tipomaterial=$dat1[1];
	if($cod_tipomaterial==$cod_tipo_material)
	{	echo "<option value='$cod_tipomaterial' selected>$nombre_tipomaterial</option>";
	}
	else
	{	echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Estado</th>";
echo "<td align='left'><select name='estado' class='texto'>";
	if($estado=='Activo')
	{
	 	echo "<option value='Activo' selected>Activo</option><option value='Retirado'>Retirado</option></select>";
	}
	if($estado=='Retirado')
	{
	  echo "<option value='Activo'>Activo</option><option value='Retirado' selected>Retirado</option></select>";
	}
echo "</td></tr>";
echo "<tr>
<th align='left'>Peso</th>
<td><input type='text' name='peso' value='$peso'></td>
</tr>";
echo "<tr>
<th align='left'>Abreviatura</th>
<td><input type='text' name='abreviatura' value='$abreviatura'></td>
</tr>";
echo "<tr>
<th align='left'>Orden Grupo</th>
<td><input type='text' name='codOrdenGrupo' value='$ordenGrupo'></td>
</tr>";

if($itemMetraje==1){
echo "<tr>
<th align='left'>Manejo por Metros</th>
<td><select name='item_metraje' class='texto'>
<option value='0'>No</option>
<option value='1' selected>Si</option>
</select></td>
</tr>";
}else{
echo "<tr>
<th align='left'>Manejo por Metros</th>
<td><select name='item_metraje' class='texto'>
<option value='0' selected>No</option>
<option value='1'>Si</option>
</select></td>
</tr>";
}

echo "<tr>
<th align='left'>Nro. Metros</th>
<td><input type='text' name='nro_metros' value='$nroMetros'></td>
</tr>";

echo "<tr>
<th align='left'>Orden Grupo</th>
<td><input type='text' name='codOrdenGrupo' value='$ordenGrupo'></td>
</tr>";


echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_material.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>
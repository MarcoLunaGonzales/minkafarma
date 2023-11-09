<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");

$sql="select d.cod_ciudad,(SELECT cod_ciudad from tipos_precio_ciudad where cod_tipoprecio=$codigo_registro and cod_ciudad=d.cod_ciudad) from ciudades d where d.cod_estadoreferencial=1 order by 1";
$resp=mysqli_query($enlaceCon,$sql);
?>
<script type="text/javascript">
  function seleccionar_todo(){
   for (i=0;i<document.f1.elements.length;i++)
      if(document.f1.elements[i].type == "checkbox")
         document.f1.elements[i].checked=1
   }
  function deseleccionar_todo(){
   for (i=0;i<document.f1.elements.length;i++)
      if(document.f1.elements[i].type == "checkbox")
         document.f1.elements[i].checked=0
   } 
</script>
<?php
echo "<form action='$urlSaveEditCiudad' method='post' name='f1'>";

echo "<h1>Modificar Sucursales del Precio</h1>";
echo "<input type='hidden' value='$codigo_registro' name='tipo' id='tipo'>";

	echo "<center><table class='table table-sm table-bordered'>";
	echo "<tr class='bg-info text-white font-weight-bold'>
	<th width='10%'><div class='btn-group'><a href='#' class='btn btn-sm btn-warning' onClick='seleccionar_todo()'>T</a><a href='#' onClick='deseleccionar_todo()' class='btn btn-sm btn-default'>N</a></div></th>
	<th>Sucursal</th>
	</tr>";
	$index=0;
	while($dat=mysqli_fetch_array($resp))
	{
		$index++;		 
		$ciudades=obtenerNombreCiudad($dat[0]);
		$checked="";
		if($dat[1]>0){
         $checked="checked";
		}
		echo "<tr>
		<td><input type='checkbox' name='codigo[]' value='$dat[0]' $checked></td>
		<td>$ciudades</td>
		</tr>";
	}
	echo "</table></center><br>";




echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
</div>";

echo "</form>";
?>
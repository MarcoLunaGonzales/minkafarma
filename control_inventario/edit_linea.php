<?php
require("../conexionmysqli.inc");
require("../estilos2.inc");
require("configModule.php");
require("../funciones.php");
$sql="select d.cod_linea_proveedor,(SELECT cod_linea_proveedor from tipos_precio_lineas where cod_tipoprecio=$codigo_registro and cod_linea_proveedor=d.cod_linea_proveedor),(SELECT nombre_proveedor from proveedores where cod_proveedor=d.cod_proveedor),d.nombre_linea_proveedor from proveedores_lineas d where d.estado=1 order by 3,4 ";
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
echo "<form action='$urlSaveEditLinea' method='post' name='f1'>";

echo "<h1>Modificar Líneas del Precio</h1>";
echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
</div>";
echo "<input type='hidden' value='$codigo_registro' name='tipo' id='tipo'>";

	echo "<center><table class='table table-sm table-bordered'>";
	echo "<tr class='bg-info text-white font-weight-bold'>
	<th width='10%'><div class='btn-group'><a href='#' class='btn btn-sm btn-warning' onClick='seleccionar_todo()'>T</a><a href='#' onClick='deseleccionar_todo()' class='btn btn-sm btn-default'>N</a></div></th>
	<th>Proveedor</th>
	<th>Línea</th>
	</tr>";
	$index=0;
	while($dat=mysqli_fetch_array($resp))
	{
		$index++;		 
		$lineas=$dat[3];
		$proveedor=$dat[2];
		$checked="";
		if($dat[1]>0){
         $checked="checked";
		}
		echo "<tr>
		<td><input type='checkbox' name='codigo[]' value='$dat[0]' $checked></td>
		<td>$proveedor</td>
		<td>$lineas</td>
		</tr>";
	}
	echo "</table></center><br>";

echo "<div class=''>
<input type='submit' class='btn btn-primary' value='Guardar'>
<input type='button' class='btn btn-danger' value='Cancelar' onClick='location.href=\"$urlList2\"'>
</div>";

echo "</form>";
?>
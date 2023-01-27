<?php
require("../../conexionmysqli.php");
require("../../estilos_almacenes.inc");
require("../../funcion_nombres.php");
echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

?>
<script language='Javascript'>
		function enviar_nav(codProveedor)
		{	location.href='registrarLineaDistribuidor.php?codProveedor='+codProveedor;
		}
		
		function editar_nav(f, codProveedor)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro.');
				}
				else
				{
					location.href='editarLineaDistribuidor.php?codigo_registro='+j_cod_registro+'&codProveedor='+codProveedor;
				}
			}
		}
		
		function eliminar_nav(f, codProveedor)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos una Linea para eliminar.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminarLineaDistribuidor.php?codProveedor='+codProveedor+'&datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>
<form>
<?php


$codProveedor=$_GET['codProveedor'];
$nombreProveedor=nombreProveedor($enlaceCon,$codProveedor,$enlaceCon);

$globalAdmin=$_COOKIE["global_admin_cargo"];

echo "<h2>Lineas de Distribuidor <br> $nombreProveedor</h2>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onClick='enviar_nav($codProveedor);'>
<input class='boton' type='button' value='Editar' onClick='editar_nav(this.form, $codProveedor);'>
<input class='boton2' type='button' value='Eliminar' onClick='eliminar_nav(this.form, $codProveedor)'>
<input class='boton2' type='button' value='Cancelar' onClick='location.href=(\"inicioProveedores.php\");'>
</div>";

echo "<center>";
echo "<table class='texto'>";
echo "<tr>";
echo "<th>&nbsp;</th><th>Codigo</th><th>Linea</th><th>Abreviatura</th><th>Procedencia</th><th>Margen de precio</th><th>Contacto 1</th><th>Contacto 2</th>";
echo "<th>Ver Productos</th><th>Editar Precios</th><th>Ajustar Stocks</th><th>Ajustar Stocks</th></tr>";
$consulta="select p.cod_linea_proveedor, p.nombre_linea_proveedor, p.abreviatura_linea_proveedor, p.contacto1, 
	p.contacto2, (select t.nombre_procedencia from tipos_procedencia t where t.cod_procedencia=p.cod_procedencia), 
	margen_precio
	from proveedores_lineas p where p.cod_proveedor=$codProveedor and estado=1 order by p.nombre_linea_proveedor";


$rs=mysqli_query($enlaceCon,$consulta);

$cont=0;
while($reg=mysqli_fetch_array($rs)){
	$cont++;
    $codLinea = $reg["cod_linea_proveedor"];
    $nombreLinea = $reg["nombre_linea_proveedor"];
    $contacto1 = $reg["contacto1"];
    $contacto2 = $reg["contacto2"];
    $abreviatura = $reg["abreviatura_linea_proveedor"];
	$procedencia=$reg[5];
	$margenPrecio=$reg[6];
    echo "<tr>";
    echo "<td><input type='checkbox' id='$codLinea' value='$codLinea' ></td>
    <td>$codLinea</td>
    <td>$nombreLinea</td>
    <td>$abreviatura</td>
	<td>$procedencia</td><td>$margenPrecio</td>
	<td>$contacto1</td><td>$contacto2</td>";
	if($globalAdmin==1){
		echo "<td><a href='../../detalleMaterialLineas.php?linea=$codLinea' target='_BLANK'><img src='../../imagenes/detalle.png' width='40'></a></td>;
			<td><a href='../../navegador_precios2.php?orden=3&linea=$codLinea' target='_BLANK'><img src='../../imagenes/detalle.png' width='40'></a></td>
			<td><a href='../../navegadorpreciosstocks.php?vista=1&orden=3&linea=$codLinea' target='_BLANK'><img src='../../imagenes/ruteroaprobado.png' width='40' title='Ver Todos los Productos'></a></td>
			<td><a href='../../navegadorpreciosstocks.php?vista=2&orden=3&linea=$codLinea' target='_BLANK'><img src='../../imagenes/edit.png' width='35' title='Ver Solo Productos con Stock'></a></td>";
	}
    echo "</tr>";
   }
echo "</table>";
echo "</center>";

echo "<div class='divBotones'><input class='boton' type='button' value='Adicionar' onClick='enviar_nav($codProveedor);'>
<input class='boton' type='button' value='Editar' onClick='editar_nav(this.form, $codProveedor);'>
<input class='boton2' type='button' value='Eliminar' onClick='eliminar_nav(this.form, $codProveedor)'>
<input class='boton2' type='button' value='Cancelar' onClick='location.href=(\"inicioProveedores.php\");'>
</div>";


?>
</form>
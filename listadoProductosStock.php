<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">

<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<script>

    $(document).ready(function() {
        $('#myTable').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            fixedHeader: {
              header: true,
              footer: true
            }
        } );
    } );
	
</script>

<?php
	require("conexion.inc");
	require('estilos.inc');
	require('funciones.php');
	
	echo "<h1>Listado de Productos Registrados</h1>";

	$globalAlmacen=$_COOKIE["global_almacen"];
	
	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		(select e.nombre_empaque from empaques e where e.cod_empaque=m.cod_empaque), 
		(select f.nombre_forma_far from formas_farmaceuticas f where f.cod_forma_far=m.cod_forma_far), 
		(select pl.nombre_linea_proveedor from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)linea,
		(select t.nombre_tipoventa from tipos_venta t where t.cod_tipoventa=m.cod_tipoventa), m.cantidad_presentacion, m.principio_activo 
		from material_apoyo m
		where m.estado='1' order by linea, m.descripcion_material";

	$resp=mysql_query($sql);

	echo "<center><table class='texto' id='myTable'>";
	echo "<thead>";
	echo "<tr><th>Indice</th><th>Linea</th><th>Nombre Producto</th><th>Empaque</th>
		<th>Cant.Presentacion</th><th>Precio</th><th>Stock</th><th>Ubicacion</th><th>Forma Farmaceutica</th><th>Principio Activo</th>
		<th>Accion Terapeutica</th></tr>";
	echo "</thead>";
	
	echo "<tbody>";

	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreProd=$dat[1];
		$estado=$dat[2];
		$empaque=$dat[3];
		$formaFar=$dat[4];
		$nombreLinea=$dat[5];
		$tipoVenta=$dat[6];
		$cantPresentacion=$dat[7];
		$principioActivo=$dat[8];
		
		$precioProducto=precioProducto($codigo);
		$precioF=formatNumberDec($precioProducto);
		$stockProducto=stockProducto($globalAlmacen, $codigo);
		$ubicacionProducto=ubicacionProducto($globalAlmacen, $codigo);
		
		$txtAccionTerapeutica="";
		$sqlAccion="select a.nombre_accionterapeutica from acciones_terapeuticas a, material_accionterapeutica m
			where m.cod_accionterapeutica=a.cod_accionterapeutica and 
			m.codigo_material='$codigo'";
		$respAccion=mysql_query($sqlAccion);
		while($datAccion=mysql_fetch_array($respAccion)){
			$nombreAccionTerX=$datAccion[0];
			$txtAccionTerapeutica=$txtAccionTerapeutica." - ".$nombreAccionTerX;
		}
		
		echo "<tr><td align='center'>$indice_tabla</td>
		<td>$nombreLinea</td>
		<td><div class='textomedianorojo'>$nombreProd</div></td>
		<td>$empaque</td>
		<td align='center'>$cantPresentacion</td>
		<td align='right'><div class='textomedianorojo'>$precioF</div></td>
		<td>$stockProducto</td>
		<td>$ubicacionProducto</td>
		<td>$formaFar</td>
		<td>$principioActivo</td><td>$txtAccionTerapeutica</td>
		
		</tr>";
		$indice_tabla++;
	}

	echo "</tbody>";
	echo "</table></center><br>";
?>

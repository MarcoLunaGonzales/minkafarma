<?php
ini_set('post_max_size','100M');
	require("conexionmysqli.php");
	require("estilos.inc");
	require("funciones.php");
	require("funcion_nombres.php");
	// DATOS
	$cod_ciudad 	 = $_COOKIE['global_agencia'];
	$globalAlmacen 	 = $_COOKIE['global_almacen'];
	$codProveedor	 = $_GET['codProveedor'];
	// TIpo de selección de items 
	// 0 = TODO
	// 1 = STOCK
	$tipo_seleccion  = $_GET['tipo'];
	$titulo_tipo 	 = $tipo_seleccion == 0 ? 'TODO' : 'STOCK' ;
	$nombreProveedor = nombreProveedor($enlaceCon,$codProveedor,$enlaceCon);
?>
<!-- GLOBAL - Codigo de Ciudad -->
<input type="hidden" id="cod_ciudad" value="<?=$cod_ciudad;?>">
<center>
	<h3>Incremento de Precios (<?= $titulo_tipo; ?>) - Proveedor [<?= $nombreProveedor; ?>]</h3>
	<label for="incremento" class="text-danger"><b>% Porcentaje de Incremento:</b></label>
	<input type="text" id="incremento" name="incremento" value="0">
	<button type="button" class="btn btn-warning" id="aplicar_incremento">Aplicar</button>
</center>

<center>
	<table class="texto" id="main">
		<thead>
			<tr>
				<th>Nro.</th>
				<th>Linea</th>
				<th>Material</th>
				<th>Cantidad Presentacion</th>
				<th>Precio Actual(Bs.)</th>
				<th>Stock</th>
				<th class="text-center">Precio Nuevo(Bs.)</th>
				<th class="text-center">Porcentaje de Incremento(%)</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$nro = 1;
				$sql = "SELECT m.codigo_material, m.descripcion_material, m.cantidad_presentacion, pl.nombre_linea_proveedor 
						FROM material_apoyo m, proveedores_lineas pl
						WHERE pl.cod_proveedor='$codProveedor' AND pl.cod_linea_proveedor=m.cod_linea_proveedor 
						ORDER BY pl.nombre_linea_proveedor, m.descripcion_material";
				$resp=mysqli_query($enlaceCon,$sql);
				while($data=mysqli_fetch_array($resp))
				{							
					$codigo_material		= $data[0];
					$descripcion_material	= $data[1];
					$cantidad_presentacion	= $data[2];
					$nombre_linea_proveedor	= $data[3];
					$stock_producto			= stockProducto($enlaceCon,$globalAlmacen, $codigo_material);
					/**
					 * Se obtiene precio de los materiales
					 */
					$sqlPrecio = "SELECT p.precio 
								FROM precios p 
								WHERE p.cod_precio = 1 
								AND p.cod_ciudad = '$cod_ciudad'
								AND p.codigo_material = '$codigo_material'";
					$respPrecio = mysqli_query($enlaceCon,$sqlPrecio);
					$numFilas   = mysqli_num_rows($respPrecio);
					$precio1	= 0;
					$precio1	= redondear2($precio1);
					if($numFilas == 1){
						$datPrecio  = mysqli_fetch_array($respPrecio);
						$precio1	= $datPrecio[0];
						$precio1	= redondear2($precio1);
					}
					if($tipo_seleccion == 0 || ($tipo_seleccion == 1 && $stock_producto > 0)){
			?>
			<tr>
				<td><?= $nro; ?></td>
				<td><?= $nombre_linea_proveedor; ?></td>
				<td><?= $descripcion_material; ?></td>
				<td><?= $cantidad_presentacion; ?></td>
				<!-- Precio Actual -->
				<td><b><?= $precio1; ?></b></td>
				<!-- Stock de Producto -->
				<td align="center">
					<?= $stock_producto; ?>
					<!-- CODIGO MATERIAL -->
					<input type="hidden" value="<?=$codigo_material;?>" id="cod_material<?=$codigo_material;?>">
					<!-- PRECIO ACTUAL -->
					<input type="hidden" value="<?=$precio1;?>" id="precio_actual<?=$codigo_material;?>">
				</td>
				<td align="center">
					<input type="text" value="0" id="precio_nuevo<?=$codigo_material;?>" class="precio_nuevo_cambio" data-codigo_material="<?=$codigo_material;?>">
				</td>
				<td align="center">
					<input type="text" value="0" id="precio_incremento<?=$codigo_material;?>" readonly="">
				</td>
			</tr>
			<?php
					}
					$nro++;
				} 	
			?>
		</tbody>
	</table>
	<center>
		<button class="btn btn-primary" id="form_actualizar">Actualizar todo</button>
	</center>
</center>
<!-- SCRIPT -->
<script>
$(document).ready(function() {
    /**
	 * PROCESO DE INCREMENTO DE PRECIO EN PORCENTAJE
	 **/
    $("#aplicar_incremento").on('click',function() {
        // Obtener el valor del campo #incremento
        var porcentajeIncremento = parseFloat($('#incremento').val());
        
        // Verificar si el valor es NaN (no es un número) o está vacío
        if (isNaN(porcentajeIncremento) || porcentajeIncremento === "") {
            porcentajeIncremento = 0; // Asignar cero como valor predeterminado
        }
        
        // Recorrer cada fila de la tabla #main
        $("#main tbody tr").each(function() {
            // Obtener los elementos necesarios de la fila actual
			// Debe tomar en cuenta la fila 5 (<!-- Stock de Producto -->)
            var precioActual 		  = parseFloat($(this).find("td:nth-child(5)").text());
            var precioNuevoInput 	  = $(this).find("input[id^='precio_nuevo']");
            var precioIncrementoInput = $(this).find("input[id^='precio_incremento']");
            
            // Calcular el nuevo precio y el porcentaje de incremento
            var nuevoPrecio = precioActual + (precioActual * (porcentajeIncremento / 100));
            // Precio diferencia del Actual con el Nuevo en porcentaje(%)
            if (precioActual !== 0) {
            	incremento = ((nuevoPrecio - precioActual) / precioActual) * 100;
			} else {
				incremento = 0; // o cualquier otro valor predeterminado que desees
			}
			// Actualizar los valores en los campos correspondientes
            precioNuevoInput.val(nuevoPrecio.toFixed(2));
            precioIncrementoInput.val(incremento.toFixed(2));
        });
    });
	/**
	 * ACTUALIZACIÓN DE PRECIOS
	 */
    $("#form_actualizar").click(function() {
        // Crear un array para almacenar los datos de las filas
        var datos = [];
        // TABLA BODY
        $("#main tbody tr").each(function() {
            // Obtener los elementos necesarios de la fila actual
            var codigoMaterial = $(this).find("input[id^='cod_material']").val();
            var precioNuevo    = parseFloat($(this).find("input[id^='precio_nuevo']").val());
            var cod_ciudad 	   = $('#cod_ciudad').val();
            // JSON - Lista de Materiales
            var fila = {
                cod_material: codigoMaterial,
                precio_nuevo: precioNuevo,
				cod_ciudad: cod_ciudad
            };
            datos.push(fila);
        });
		// Realizar la solicitud AJAX con jQuery
		$.ajax({
			url: "ajaxGuardaPrecioIncremento.php",
			type: "POST",
			dataType: "json",
			data: {
				// items: JSON.stringify(datos)
				items: datos
			},
			success: function(data) {
				// La solicitud se ha completado correctamente
				// var data = JSON.parse(response);
				// console.log(data)
				if (data.status) {
					alert(data.message);
					// Recargar la página después de 5 segundos
					setTimeout(function() {
						location.reload();
					}, 3000);
				} else {
					alert(data.message);
				}
			},
			error: function(xhr, status, error) {
				// Manejar el error en caso de que ocurra
				console.log('Error de registro');
			}
		});
    })
    /**
     * Modificación de precio Nuevo Manualmente
     **/
    $(".precio_nuevo_cambio").keyup(function(){
    	let precio_nuevo = $(this).val() !== '' ? parseFloat($(this).val()) : 0;
    	let codigo_material = $(this).data('codigo_material');
    	let precio_actual   = $('#precio_actual' + codigo_material).val();
    	let incremento 		= 0;

        if (precio_actual == '0' && precio_nuevo >= 0) {
        	incremento = 100;
		} else if (precio_actual !== 0) {
        	incremento = ((precio_nuevo - precio_actual) / precio_actual) * 100;
		} else {
			incremento = 0; // o cualquier otro valor predeterminado que desees
		}
    	$('#precio_incremento' + codigo_material).val(incremento.toFixed(2));
    });
});
</script>
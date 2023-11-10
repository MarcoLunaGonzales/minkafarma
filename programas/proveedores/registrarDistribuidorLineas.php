<script language='Javascript'>
	function guardar(f){	
		if(f.nombreLinea.value==""){
			alert("El nombre no puede estar vacio.");
			return(false);
		}
		f.submit();
	}
</script>
<?php
require("../../conexionmysqli.php");

require("../../estilos_almacenes.inc");

require("../../funcion_nombres.php");
echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

$codProveedor=$_GET['codProveedor'];
$nombreProveedor=nombreProveedor($enlaceCon,$codProveedor);

?>
<form action="guardaDistribuidorLinea.php" method="post">
    <input type="hidden" name="codDistribuidor" id="codDistribuidor" value="<?php echo $codProveedor?>">
    <center>
        <br/>
        <h1>Asociar Linea<br>Distribuidor - <?php echo $nombreProveedor;?></h1>
        <table class="texto">
            <tr>
                <th width="50%">Proveedor</th>
                <th width="50%">Lineas de Proveedor</th>
            </tr>
            <tr>
                <td>
                    <select name='cod_proveedor' id='cod_proveedor' class='selectpicker form-control' data-live-search="true" required data-style="btn btn-rose">
                        <option value=''>Seleccionar proveedor</option>
                        <?php
                            $consulta="SELECT p.cod_proveedor, p.nombre_proveedor
                                        FROM proveedores p
                                        WHERE estado = 1
                                        ORDER BY p.nombre_proveedor";
                            $rs=mysqli_query($enlaceCon,$consulta);
                            while($reg=mysqli_fetch_array($rs)){
                        ?>
                            <option value="<?= $reg["cod_proveedor"]; ?>"><?= $reg["nombre_proveedor"]; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="selectpicker form-control" name="cod_linea_proveedor[]" id="cod_linea_proveedor" multiple data-style="btn btn-success" data-actions-box="true" data-live-search="true" data-size="6" required>
                    </select>
                </td>
            </tr>
        </table>
    </center>
    <div class="divBotones">
        <input class="boton" type="submit" value="Guardar"/>
        <input class="boton2" type="button" value="Cancelar" onclick="location.href='navegadorListaDistribuidorLineas.php?codProveedor=<?php echo $codProveedor;?>'" />
    </div>
</form>

<script>
    /**
     * Obtiene la lista de Lineas de Proveedores, 
     * en base al proveedor seleccionado
     */
    $('#cod_proveedor').on('change', function(){
        let codProveedor = $(this).val();
        $.ajax({
            url: 'obtieneLineasProveedor.php',
            type: 'POST',
            dataType: 'json',
            data: {
                cod_proveedor: codProveedor
            },
            success: function (data) {
                console.log(data);
                var select = $('#cod_linea_proveedor');
                select.empty();
                $.each(data, function (index, linea) {
                    select.append($('<option>', {
                        value: linea.cod_linea_proveedor,
                        text: linea.nombre_linea_proveedor
                    }));
                });
                select.selectpicker('refresh');
            },
            error: function () {
                console.log('Error al cargar las líneas de proveedores.');
            }
        });
    });
</script>